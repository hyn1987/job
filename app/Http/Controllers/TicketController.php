<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Auth;
use Storage;
use Config;

// Models
use Wawjob\User;
use Wawjob\ProjectMessageThread;
use Wawjob\ProjectMessage;
use Wawjob\Ticket;
use Wawjob\TicketComment;

//DB
use DB;

class TicketController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Ticket Message list
   *
   * @param  Request $request
   * @return Response
   */
  public function all(Request $request)
  {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }
    
    if ($request->ajax()) {
      $ajaxType = $request->input('type');
      if ($ajaxType == "reply") {
        $userId = Auth::user()->id;
        $ticketId = $request->input('ticketId');

        $comment = new TicketComment;

        $comment->comment = $request->input('comment');
        $comment->ticket_id = $ticketId;
        $comment->commentor_id = $userId;
        
        try {
          $comment->save();

          if ( !empty($_FILES) ) {
            $dir = getTicketCommentUploadDir($comment->ticket_id, $comment->id);

            createDir($dir);

            // Full path to the destination
            $path = $dir .  $_FILES["attachFile"]["name"];

            // Move file
            $ret = move_uploaded_file($_FILES["attachFile"]["tmp_name"], $path);
            /*if ( !$ret ) {
              return response()->json([
                'success' => false,
                'msg' => 'Failed to move uploaded file.'
              ]);
            }  */    
          }

          //refresh the comment data..
          $commentCollection = TicketComment::
                                      where('ticket_id', '=', $ticketId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

          $strComments = '';

          foreach ($commentCollection as $key => $comment) {
            $strComments = $strComments . 
                            view('pages.ticket.comment', [
                                  "comment" => $comment
                              ])->render();
          }


        } catch (Exception $e) {
          return;
        };

        return response()->json([
                'success'     => true,
                'strComments' => $strComments,
                'ticketId'    => $ticketId,
              ]);

      } else if ( $ajaxType == "edit" ) {
        $userId = Auth::user()->id;
        $commentId = $request->input('commentId');
        $comment = $request->input('comment');

        try {
          $comm = TicketComment::find($commentId);
          
          $comm->comment = $comment;

          if ( !empty($_FILES) ) {
            $dir = getTicketCommentUploadDir($comm->ticket_id, $comm->id);

            createDir($dir);

            // Full path to the destination
            $path = $dir .  $_FILES["attachFile"]["name"];

            // Move file
            $ret = move_uploaded_file($_FILES["attachFile"]["tmp_name"], $path);
            /*if ( !$ret ) {
              return response()->json([
                'success' => false,
                'msg' => 'Failed to move uploaded file.'
              ]);
            }   */  
          }

        } catch (Exception $e) {
          return response()->json([
                'success'     => false,
                'comment' => $comm,
                'commentId'    => $commentId,
              ]);
        };

        return response()->json([
                'success'   => true,
                'comment'   => $comment,
                'commentId' => $commentId,
                'comm'      => $comm,
              ]);
      } else if ( $ajaxType == "close" ) {
        $userId = Auth::user()->id;
        $ticketId = $request->input('ticketId');

        $comment = new TicketComment;

        $comment->comment = $request->input('comment');
        $comment->ticket_id = $ticketId;
        $comment->commentor_id = $userId;
        
        $ticket = Ticket::where('id', '=', $ticketId)->first();
        $ticket->status = Ticket::STATUS_CLOSED;

        try {
          $comment->save();
          $ticket->save();

          //refresh the comment data..
          $commentCollection = TicketComment::
                                      where('ticket_id', '=', $ticketId)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

          $strComment = view('pages.ticket.comment', [
                                  "comment" => $comment
                              ])->render();

        } catch (Exception $e) {
          return;
        };

        return response()->json([
                'success'     => true,
                'strComment' => $strComment,
                'ticketId'    => $ticketId,
                'ticketStatus' => Ticket::toString('status', $ticket->status),
              ]);
      }

      return response()->json([
                'success'     => false,
              ]);
    } 

    //retrieve the ticket list
    $userId = Auth::user()->id;
    
    try {
      $ticketList = Ticket::where('user_id', '=', $userId)
                            ->orderBy('created_at', 'desc')
                            ->get();

      $optionTypeArry = Ticket::getOptions("type");
    } catch (Exception $e) {
      return;
    };

    return view('pages.ticket.list', [
        'page' => 'ticket.list',
        'ticketList' => $ticketList,
        'optionTypeArry' => $optionTypeArry,
    ]);
  }

  /**
   * Ticket create
   *
   * @param  Request $request
   * @return Response
   */
  public function create(Request $request)
  { 
    $userId = Auth::user()->id;

    $ticket = new Ticket;

    $ticket->subject = $request->input('subject');
    $ticket->content = $request->input('content');
    $ticket->type = $request->input('type');
    $ticket->user_id = $userId;
    
    try {
      $ticket->save();

      if ( !empty($_FILES) ) {
        $dir = getTicketUploadDir($ticket->id);

        createDir($dir);

        // Full path to the destination
        $path = $dir .  $_FILES["attachFile"]["name"];

        // Move file
        $ret = move_uploaded_file($_FILES["attachFile"]["tmp_name"], $path);
        /*if ( !$ret ) {
          return response()->json([
            'success' => false,
            'msg' => 'Failed to move uploaded file.'
          ]);
        }  */    
      }
    } catch (Exception $e) {      
      add_message('Found Exception', 'danger');
    };

    return redirect()->route('ticket.list');    
  }
  
}