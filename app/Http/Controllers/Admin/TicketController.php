<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

use Auth;
use Storage;
use Config;

// Models
use Wawjob\Ticket;
use Wawjob\TicketComment;
use Wawjob\User;

class TicketController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all tickets.
   *
   * @return Response
   */
  public function search(Request $request)
  {
    $dates = parseDateRange($request->input('date_range'));

    $tickets = Ticket::orderBy("created_at", "desc");

    // Date Range
    if ($dates) {
      $tickets = $tickets->whereBetween('created_at', $dates);
    }

    $tickets = $tickets->select('tickets.*');

    // Username
    $username = $request->input("username");
    if ($username) {
      self::$tmp['username'] = $username;

      $tickets->leftJoin('user_contacts', 'tickets.user_id', '=', 'user_contacts.user_id')
        ->where(function($query) {
          $query->where('first_name', 'like', '%'.self::$tmp['username'].'%')
          ->orWhere('last_name', 'like', '%'.self::$tmp['username'].'%');
        });
    }

    // Subject | Content keyword
    $ckeyword = $request->input("ckeyword");
    if ( $ckeyword ) {
      self::$tmp['ckeyword'] = $ckeyword;
      $tickets = $tickets->where(function($query) {
        $query->where('subject', 'like', '%'.self::$tmp['ckeyword'].'%')
        ->orWhere('content', 'like', '%'.self::$tmp['ckeyword'].'%');
      });
    }

    // Status
    $status = $request->input("status");
    if ( isset($status) && $status !== '' ) {
      $tickets = $tickets->where('status', '=', $status);
    }

    // Filter by admin id
    if (!$this->is_super_admin) {
      $tickets = $tickets->where('admin_id', $this->auth_user->id);
    }

    // Pagination
    $tickets = $tickets->paginate($this->per_page);

    foreach($tickets as $ticket) {
      // get comment count for each ticket
      $ticket->numComments = $ticket->comments_count();

      // Notify | Normal | Dispute | Question | Suspension | Maintenance | ...
      $ticket->strType = Ticket::toString('type', $ticket->type);
    }

    // Get option vars
    $options = [
      'type' => Ticket::getOptions('type'),
      'status' => Ticket::getOptions('status'),
      'priority' => Ticket::getOptions('priority'),
      'admins' => User::getAdmins()
    ];

    $request->flashOnly(['date_range', 'username', 'ckeyword', 'status']);

    return view('pages.admin.ticket.list', [
      'page' => 'ticket.list',
      'css' => 'ticket.list',
      'component_css' => [
        'assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3',
      ],
      'options' => $options,
      'tickets' => $tickets,

      'j_trans'=> [
        'remove_comment' => trans('j_message.admin.ticket.remove_comment'), 
        'btn_delete' => trans('j_message.btn_delete'), 
      ]
    ]);
  }

  /**
  * @author paulz
  * @created Mar 7, 2015
  */
  protected function updateTicket(Request $request)
  {
    $id = intval($request->input("t_id"));
    if ( !$id ) {
      return response()->json([
        "success" => false,
        "msg" => "Invalid ticket ID given."
      ]);
    }

    $admin_id = intval($request->input("t_admin"));

    // If the admin is assigned and status is 'Open', should update status as 'Assigned'.
    $status = intval($request->input("t_status"));
    if ($admin_id && $status == Ticket::STATUS_OPEN) {
      $status = Ticket::STATUS_ASSIGNED;
    }

    $type = intval($request->input("t_type"));
    $priority = intval($request->input("t_priority"));

    $ticket = Ticket::find($id);
    if ( !$ticket ) {
      return response()->json([
        "success" => false,
        "msg" => "Failed to update the ticket."
      ]);
    }

    $ret = Ticket::where("id", $id)->update([
      "status" => $status,
      "admin_id" => $admin_id,
      "type" => $type,
      "priority" => $priority
    ]);

    if (!$ret) {
      return response()->json([
        "success" => false,
        "msg" => "Failed to update the ticket."
      ]);
    }

    $strType = Ticket::toString('type', $type);

    // Notify the user about the Ticket update
    $user = User::find($ticket->user_id);
    $fullname = $user->fullname();
    $to = $fullname . ' <'.$user->email.'>';
    $from = 'Wawjob <support@wawjob.com>';
    $subject = 'Ticket #' . $id.' has been updated.';
    $text = "Hello ".$fullname."!\n\nYour ticket #" . $id.' has been updated.';
    $html = '<html><body>' . $text . '</body></html>';
    send_mail($to, $from, '', $subject, $text, $html);

    return response()->json([
      'success' => true,
      'msg' => "Successfully updated the ticket #".$id,
      'data' => compact("id", "status", "admin_id", "type", "strType", "priority")
    ]);
  }

  /**
  * Adds a new comment to the thread, notify the ticket owner and responds with rendered HTML in JSON
  *
  * @author paulz
  * @created Mar 8, 2015
  */
  protected function replyTicket(Request $request)
  {
    // The user current logged in.
    $user = Auth::user();

    // 1. Adds a new comment to DB
    $t_id = $request->input("t_id");
    $t_msg = $request->input("t_msg");

    $tc = new TicketComment;
    $tc->ticket_id = $t_id;
    $tc->comment = $t_msg;
    $tc->commentor_id = $user->id;
    $tc->save();

    $tc_id = $tc->id;

    // Save uploaded file if exists
    if ( isset($_FILES["t_attach"]) ) {
      $dir = getTicketCommentUploadDir($t_id, $tc_id);

      createDir($dir);

      // Full path to the destination
      $path = $dir .  $_FILES["t_attach"]["name"];

      // Move file
      $ret = move_uploaded_file($_FILES["t_attach"]["tmp_name"], $path);
      if ( !$ret ) {
        return response()-json([
          'success' => false,
          'msg' => 'Failed to move uploaded file.'
        ]);
      }
    }

    // 2. Send email
    // send_mail();

    // 3. Respnod to ajax client side with rendered HTML
    $html = view('pages.admin.ticket.comment', [
      "comment" => $tc
    ])->render();

    return response()->json([
      'success' => true,
      'msg' => "Your reply message has been sent.",
      'tc_info' => [
        't_id' => $t_id,
        'tc_id' => $tc_id,
        'html' => $html
      ]
    ]);
  }

  /**
  * Get a ticket comment from Ticket Comment ID
  */
  protected function getComment(Request $request)
  {
    $tc_id = intval($request->input("tc_id"));
    if ( !$tc_id ) {
      return $this->failed("Invalid Ticket Comment ID given.");
    }

    $tc_info = TicketComment::find($tc_id, ['comment', 'commentor_id']);
    if ( !$tc_info ) {
      return $this->failed("Ticket Comment not found.");
    }

    return response()->json([
      'success' => true,
      'tc_info' => $tc_info
    ]);
  }

  protected function updateComment(Request $request)
  {
    $tc_id = intval($request->input("tc_id"));
    if ( !$tc_id ) {
      return $this->failed("Invalid Ticket Comment ID given.");
    }

    $t_comment = strip_tags($request->input("t_comment"));

    $tc = TicketComment::find($tc_id, ['id']);
    if ( !$tc ) {
      return $this->failed("Error: Ticket comment #".$tc_id." not found.");
    }

    TicketComment::where("id", $tc_id)->update([
      'comment' => $t_comment
    ]);

    return response()->json([
      'success' => true,
      'msg' => 'Successfully updated the comment.',
      'tc_info' => [
        'id' => $tc_id,
        'comment' => $t_comment
      ]
    ]);
  }

  protected function removeComment(Request $request)
  {
    $t_id = $request->input("t_id");
    $tc_id = $request->input("tc_id");
    TicketComment::where("id", $tc_id)->delete();

    $dir = getTicketCommentUploadDir($t_id, $tc_id);
    removeDir($dir);

    return response()->json([
      'success' => true,
      'msg' => "The comment #${tc_id} has been removed."
    ]);
  }

  /**
  * Main ajax handler
  *
  * @author paulz
  * @created Mar 7, 2015
  */
  public function ajaxAction(Request $request)
  {
    if ( !$request->ajax() ) {
      return false;
    }

    $cmd = $request->input("cmd");
    if (method_exists($this, $cmd)) {
      return $this->$cmd($request);
    }

    return response()->json([
      'success' => false,
      'msg' => "Invalid command given."
    ]);
  }
}