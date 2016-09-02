<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

use Auth;
use Storage;

// Models
use Wawjob\Project;
use Wawjob\ProjectApplication;

class JobController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all jobs.
   *
   * @param  Request $request
   * @return Response
   */
  public function search(Request $request)
  {
    $dates = parseDateRange($request->input('date_range'));

    $jobs = Project::orderBy("created_at", "desc")
        ->with('skills');

    // Date Range
    if ($dates) {
      $jobs = $jobs->whereBetween('created_at', $dates);
    }

    $jobs = $jobs->select('projects.*');

    // Client Name (the user who posted the job)
    $client_name = $request->input("client_name");
    if ($client_name) {
      self::$tmp['client_name'] = $client_name;

      $jobs->leftJoin('user_contacts', 'projects.client_id', '=', 'user_contacts.user_id')
        ->where(function($query) {
          $query->where('first_name', 'like', '%'.self::$tmp['client_name'].'%')
          ->orWhere('last_name', 'like', '%'.self::$tmp['client_name'].'%');
        });
    }

    // Client username (the user who posted the job)
    $username = $request->input("un");
    if ($username) {

      $jobs->leftJoin('users', 'projects.client_id', '=', 'users.id')
        ->where('users.username', '=', $username);
    }

    // Subject | Content keyword
    $ckeyword = $request->input("ckeyword");
    if ( $ckeyword ) {
      self::$tmp['ckeyword'] = $ckeyword;
      $jobs = $jobs->where(function($query) {
        $query->where('subject', 'like', '%'.self::$tmp['ckeyword'].'%')
        ->orWhere('desc', 'like', '%'.self::$tmp['ckeyword'].'%');
      });
    }

    // Public | Private
    $is_public = $request->input("is_public");
    if ( isset($is_public) && $is_public !== '' ) {
      $jobs = $jobs->where('is_public', '=', $is_public);
    }

    // Open | Closed
    $is_open = $request->input("is_open");
    if ( isset($is_open) && $is_open !== '' ) {
      $jobs = $jobs->where('status', '=', $is_open);
    }

    $jobs = $jobs->paginate($this->per_page);

    foreach($jobs as $job) {
      $job->ago = ago($job->created_at);
      $job->strPrice = "$".$job->price;

      // Summary (with trailing ...more)
      if (strlen($job->desc) > Project::SUMMARY_MAX_LENGTH) {
        $job->summary = substr($job->desc, 0, Project::SUMMARY_MAX_LENGTH);
        $job->summaryMore = substr($job->desc, Project::SUMMARY_MAX_LENGTH);
      } else {
        $job->summary = $job->desc;
        $job->summaryMore = "";
      }

      // Get number of {applicants, interview, hired}
      $job->num = [
        'applicant' => $job->allProposalsCount(),
        'interview' => $job->messagedApplicationsCount(),
        'hired' => $job->contracts_hired_count()
      ];
    }

    $request->flashOnly(['date_range', 'client_name', 'ckeyword', 'is_public', 'is_open']);

    // Get option vars
    $options = [
      'is_public' => Project::getOptions('is_public'),
      'is_open' => Project::getOptions('is_open')
    ];    

    return view('pages.admin.job.list', [
      'page' => 'job.list',
      'css' => 'job.list',
      'component_css' => [
        'assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3',
      ],
      'jobs' => $jobs,
      'options' => $options
    ]);
  }

  public function ajaxAction(Request $request)
  {
    if ( !$request->ajax() ) {
      return false;
    }

    $cmd = $request->input("cmd");

    if ($cmd == "cancel" || $cmd == "reopen") {
      $jid = intval($request->input("id"));
      $jobs = Project::where("id", $jid)->get();
      if ( !$jobs ) {
        return response()->json([
          'success' => false,
          'msg' => 'Invalid job given.',
        ]);
      }

      $job = $jobs[0];

      $subject = $job->subject;
      if (strlen($subject) > 40) {
        $subject = substr($subject, 0, 40) . "...";
      }

      if ($cmd == "cancel") {
        $isOpenNew = Project::STATUS_CLOSED;
        $successMsg = '"'.$subject.'" has been canceled.';
        $reason = "Cancelled by admin.";
      } else {
        $isOpenNew = Project::STATUS_OPEN;
        $successMsg = '"'.$subject.'" has been opened.';
        $reason = "";
      }

      $ret = Project::where("id", $jid)->update([
        "status" => $isOpenNew,
        "reason" => $reason
      ]);

      # @todo - send_mail()

      return response()->json([
        'success' => true,
        'msg' => $successMsg,
        'isOpenNew' => strtolower(Project::is_open_to_string($isOpenNew))
      ]);
    }

    return response()->json([
      'success' => false,
      'msg' => "Invalid command given."
    ]);
  }
}