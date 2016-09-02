<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Auth;
use Storage;
use Config;
use Session;
use Exception;

// Models
use Wawjob\User;
use Wawjob\Role;
use Wawjob\Project;
use Wawjob\ProjectApplication;
use Wawjob\ProjectMessageThread;
use Wawjob\ProjectMessage;
use Wawjob\Contract;
use Wawjob\ContractMeter;
use Wawjob\HourlyLogMap;
use Wawjob\Notification;

class JobController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Create Job Page (job/create)
   *
   * @author nada
   * @since Jan 28, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */

  public function create(Request $request) {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }

    if ($request->isMethod('post')) {

      try {
        // Flash data to the session.
        $request->flashOnly('category', 
                            'title', 
                            'description', 
                            'job_type', 
                            'duration', 
                            'workload', 
                            'price', 
                            'cv_required', 
                            'contract_limit', 
                            'job_public');

        ///////////// Validation ////////////
        if ($request->input('job_type') == Project::TYPE_FIXED) {
          $price = priceRaw($request->input('price'));
          if ($price <= 0) {
            throw new Exception(trans('message.buyer.price.lt_zero'));
          }
        }

        $project = new Project;
        $project->client_id   = $user->id;
        $project->category_id = $project->category_id = $request->input('category');

        $project->subject   = $request->input('title');
        $project->desc      = strip_tags($request->input('description'));
        $project->type      = $request->input('job_type');
        if ($project->type == Project::TYPE_HOURLY) {
          $project->duration  = $request->input('duration');
          $project->workload  = $request->input('workload');
          $project->price     = 0;
        } else {
          $project->duration  = '';
          $project->workload  = '';
          $project->price     = priceRaw($request->input('price'));
        }
        

        $project->req_cv    = $request->input('cv_required')? $request->input('cv_required') : 0;
        $project->contract_limit = $request->input('contract_limit');

        $project->is_public = $request->input('job_public')? $request->input('job_public') : Project::STATUS_PRIVATE;
        $project->status    = 1;

        $project->save();
        add_message(trans('message.buyer.job.post.success_create', ['job_title'=>$project->subject]), 'success');

        return redirect()->route('job.my_jobs');
      } catch(Exception $e) {
        add_message($e->getMessage(), 'danger');
      }
    }

    return view('pages.buyer.job.create', [
        'page' => 'buyer.job.create',
        'error' => isset($error) ? $error : null,
      ]);
  }

  /**
   * All Jobs Page (job/all)
   *
   * @author nada
   * @since Mar 1, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function all_jobs(Request $request)
  {
      $user = Auth::user();
      
      $jobs = Project::where('client_id','=', $user->id)
                      ->orderBy('status', 'desc')
                      ->orderBy('id', 'desc')
                      ->paginate(10);
      $first = TRUE;

      foreach ($jobs as &$job) {

        if ($first) { 
          $job->is_first = TRUE;
          $first = FALSE; 
        }
      }

      return view('pages.buyer.job.all', [
        'page' => 'buyer.job.all',
        'jobs' => $jobs,
      ]);
  }

  /**
   * My Jobs Page (my-jobs)
   *
   * @author nada
   * @since Feb 18, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function my_jobs(Request $request)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->my_jobs_buyer($request);
    } elseif ($user->isFreelancer()) {
      return $this->my_jobs_freelancer($request);
    }
  }
  protected function my_jobs_buyer(Request $request)
  {
      $user = Auth::user();
      
      $jobs = array();
      $_jobs = Project::where('client_id','=', $user->id)
                      ->where('status', '=', Project::STATUS_OPEN)
                      ->orderBy('id', 'desc')
                      ->get();
      $first = TRUE;

      foreach ($_jobs as $_job) {
        $job = array(
          'job_id'      => $_job->id, 
          'title'       => $_job->subject, 
          'type'        => $_job->type_string(),
          'posted_ago'  => ago($_job->created_at), 
          'applicants'  => $_job->normalApplicationsCount(), 
          'messages'    => $_job->messagedApplicationsCount(), 
          'offers_hires'=> $_job->offerHiredContractsCount(), 
          'status'      => strtoupper($_job->status_string()), 
          '_object' => $_job, 
        );

        if ($first) { 
          $job['#first'] = TRUE;
          $first = FALSE; 
        }
        $jobs[] = $job;
      }

      // Get contracts from database
      $c_rows = Contract::where('buyer_id','=', $user->id)
                      ->whereIn('status', [Contract::STATUS_OPEN, Contract::STATUS_PAUSED])
                      ->orderBy('started_at', 'asc')
                      ->get()
                      ->keyBy('id');

      // Get total hours this week per each hourly contract
      $week_mins = HourlyLogMap::getWeekMinutes($c_rows->keys(), 'this');
      foreach($week_mins as $cid => $mins) {
        $c_rows[$cid]->this_week_mins = $mins;
      }

      // Group collection by project_id
      $c_rows = $c_rows->groupBy('project_id');

      // Pair each project and all the contracts of that
      $contracts = array();
      foreach ($c_rows as $pid => $p_contracts) {
        $contracts[$pid] = array(
          'project'   => Project::find($pid), 
          'contracts' => $p_contracts, 
        );
      }

      return view('pages.buyer.job.my_jobs', [
        'page' => 'buyer.job.my_jobs',
        'jobs' => $jobs,
        'contracts' => $contracts, 

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'app_declined' => trans('j_message.buyer.job.status.app_declined'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
  }

  /**
   * My All Jobs Page (my-jobs)
   *
   * @author Ri Chol Min
   * @since Mar 09, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  protected function my_jobs_freelancer(Request $request)
  {
    $user = Auth::user();

    try {

      $offers = Contract::where('contractor_id', $user->id)
        ->where('status', Contract::STATUS_OFFER)
        ->get();

      $hourly_jobs = Contract::where('contractor_id', $user->id)
        ->where('status', Contract::STATUS_OPEN)
        ->where('type', Project::TYPE_HOURLY)
        ->orderBy('created_at', 'desc')
        ->get()
        ->keyBy('id');

      // Get total hours this week per each hourly contract
      $week_minutes = HourlyLogMap::getWeekMinutes($hourly_jobs->keys());
      foreach($week_minutes as $cid => $min) {
        $hourly_jobs[$cid]->week_mins = $min;
      }

      $fixed_jobs = Contract::where('contractor_id', $user->id)
        ->where('status', Contract::STATUS_OPEN)
        ->where('type', Project::TYPE_FIXED)
        ->orderBy('created_at', 'desc')
        ->get();

      return view('pages.freelancer.contract.my_all_jobs', [
        'page'        => 'freelancer.contract.my_all_jobs',
        'offers'      => $offers,
        'hourly_jobs' => $hourly_jobs,
        'fixed_jobs'  => $fixed_jobs
      ]);

    } catch(ModelNotFoundException $e) {

    }
  }

  /**
   * View Job Page (job/{id})
   *
   * @author nada
   * @since Feb 23, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function view_job(Request $request, $job_id) {
    $user = Auth::user();

    if ($user->isBuyer()) {
      return $this->view_job_buyer($request, $job_id);
    }
    else if($user->isFreelancer()) {
      return $this->view_job_freelancer($request, $job_id);
    }
    else {
      return redirect()->route('user.login');
    }
  }
  protected function view_job_buyer(Request $request, $job_id)
  {
    $user = Auth::user();

    try {
      $job = Project::findOrFail($job_id);
      if (!$job->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $job_post_count = count(Project::where('client_id', $job->client_id)->get());

      return view('pages.buyer.job.view', [
        'page'          => 'buyer.job.view',
        'job'           => $job, 
        'job_post_count'=> $job_post_count, 
        'client'        => $user, 

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
    }
    catch(Exception $e) {
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * Edit Job Page (job/{id}/edit)
   *
   * @author nada
   * @since Feb 23, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function edit_job(Request $request, $job_id)
  {
    $user = Auth::user();

    if (!$user->isBuyer()) {
      return redirect()->route('user.login');
    }

    try {
      $project = Project::findOrFail($job_id);
      if (!$project->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      if ($request->isMethod('post')) {
        // Flash data to the session.
        $request->flashOnly('category', 
                            'title', 
                            'description', 
                            'job_type', 
                            'duration', 
                            'workload', 
                            'price', 
                            'cv_required', 
                            'contract_limit', 
                            'job_public');

        try {
          ///////////// Validation ////////////
          if ($request->input('job_type') == Project::TYPE_FIXED) {
            $price = priceRaw($request->input('price'));
            if ($price <= 0) {
              throw new Exception(trans('message.buyer.price.lt_zero'));
            }
          }

          $project->client_id   = $user->id;
          $project->category_id = $request->input('category');

          $project->subject   = $request->input('title');
          $project->desc      = strip_tags($request->input('description'));
          $project->type      = $request->input('job_type');
          if ($project->type == Project::TYPE_HOURLY) { //hourly
            $project->duration  = $request->input('duration');
            $project->workload  = $request->input('workload');
            $project->price     = 0;
          } else {
            $project->duration  = '';
            $project->workload  = '';
            $project->price     = priceRaw($request->input('price'));
          }
          
          $project->req_cv    = $request->input('cv_required')? $request->input('cv_required') : 0;
          $project->contract_limit = $request->input('contract_limit');

          $project->is_public = $request->input('job_public')? $request->input('job_public') : Project::STATUS_PRIVATE;
          $project->save();

          add_message('Job "'.$project->subject.'" updated, successfully.', 'success');
        } catch(Exception $e) {
          add_message($e->getMessage(), 'danger');
        }
      }
      return view('pages.buyer.job.edit', [
        'page' => 'buyer.job.edit',
        'job'  => $project, 

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
  }


  /**
   * View Applicants Page (job/{id}/applicants)
   *
   * @author nada
   * @since Feb 20, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function view_applicants(Request $request, $job_id)
  {
    $user = Auth::user();

    if (!$user->isBuyer()) {
      return redirect()->route('user.login');
    }

    try {
      $job = Project::findOrFail($job_id);
      if (!$job->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $applicants = $job->normalApplications(10);

      return view('pages.buyer.job.applicants', [
        'page'  => 'buyer.job.applicants', 
        'job'   => $job, 
        'applicants' => $applicants,

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'app_declined' => trans('j_message.buyer.job.status.app_declined'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * View Messaged Applicants Page (job/{id}/messaged-applicants)
   *
   * @author nada
   * @since Mar 12, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function messaged_applicants(Request $request, $job_id)
  {
    $user = Auth::user();

    try {
      $job = Project::findOrFail($job_id);
      if (!$job->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $applicants = $job->messagedApplications(10);

      return view('pages.buyer.job.messaged_applicants', [
        'page'  => 'buyer.job.messaged_applicants', 
        'job'   => $job, 
        'applicants' => $applicants,

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'app_declined' => trans('j_message.buyer.job.status.app_declined'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * View Archived Applicants Page (job/{id}/archived-applicants)
   *
   * @author nada
   * @since Mar 14, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function archived_applicants(Request $request, $job_id)
  {
    $user = Auth::user();

    try {
      $job = Project::findOrFail($job_id);
      if (!$job->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $applicants = $job->archivedApplications(10);

      return view('pages.buyer.job.archived_applicants', [
        'page'  => 'buyer.job.archived_applicants', 
        'job'   => $job, 
        'applicants' => $applicants,

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'app_declined' => trans('j_message.buyer.job.status.app_declined'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * View Archived Applicants Page (job/{id}/offer-hired-applicants)
   *
   * @author nada
   * @since Mar 14, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function offer_hired_applicants(Request $request, $job_id)
  {
    $user = Auth::user();

    try {
      $job = Project::findOrFail($job_id);
      if (!$job->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $contracts = $job->offerHiredContracts(10);

      return view('pages.buyer.job.offer_hired_applicants', [
        'page'  => 'buyer.job.offer_hired_applicants', 
        'job'   => $job, 
        'contracts' => $contracts,

        'j_trans'       => [
          'close_job' => trans('j_message.buyer.job.status.close_job'), 
          'change_public' => trans('j_message.buyer.job.status.change_public'), 
          'app_declined' => trans('j_message.buyer.job.status.app_declined'), 
          'status' => [
            'private' => trans('job.private'), 
            'public'  => trans('job.public'),
          ], 
        ]
      ]);
    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * View Applicants Page (job/application/{id})
   *
   * @author nada
   * @since Feb 20, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */

  public function application_detail(Request $request, $application_id) {
    $user = Auth::user();

    if ($user->isBuyer()) {
      return $this->application_detail_buyer($request, $application_id);
    }
    else if ($user->isFreelancer()) {
      return $this->application_detail_freelancer($request, $application_id);
    }
  }
  protected function application_detail_buyer(Request $request, $application_id) {
    $user = Auth::user();

    $app = true;
    $app_status_message = "";
    try {
      $app = ProjectApplication::findOrFail($application_id);
      $app_status_message = $app->getArchivedNotification();

      $job = $app->project;
      if (!$job || !$job->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $_action = $request->input('_action');
      //Send Message
      if ($request->isMethod('post')) {
        if ($_action == "send_message") {
          $app->sendMessage(strip_tags($request->input('message')), $user->id);
        }
      }

      $messages = $app->getMessages(true);

      return view('pages.buyer.job.application_detail', [
        'page'  => 'buyer.job.application_detail', 
        'application' => $app, 
        'job'   => $job,
        'messages' => $messages, 
        '_action'  => $_action, 
        '_archive_msg' => $app_status_message, 
      ]);

    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
    
  }

  /**
   * Search Job Skills (job/search_skills) [AJAX]
   *
   * @author nada
   * @since Feb 28, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function searchJobSkills(Request $request)
  {
    $_ajax = array(
      'status' => "success", 
      'skills' => array(), 
      'error'  => "",
    );

    $user = Auth::user();

    try {
      $_ajax['skills'] = 'drupal';
    }
    catch (Exception $e) {
      $_ajax['error']  = $e->getMessage();
      $_ajax['status'] = 'error';
    }

    if ($_ajax['status'] == 'success') {
      
    }

    return response()->json($_ajax);
  }


  /**
   * Change Job Status (job/{id}/change_status/{status}) [AJAX]
   *
   * @author nada
   * @since Feb 28, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function change_status(Request $request, $job_id, $status)
  {
    $_ajax = array(
      'status' => "success", 
      'error'  => "",
    );

    $user = Auth::user();

    try {
      $job = Project::findOrFail($job_id);

      if (!$job->checkIsAuthor($user->id)) {
        throw new ModelNotFoundException();
      }

      if ($status != null) {
        // Check if there is open contract When closing project
        if ($status == Project::STATUS_CLOSED) {
          $c_open_contracts = $job->contracts_hired_count();
          if ($c_open_contracts) {
            $_msg = trans('message.buyer.job.status.closed_unpossible', ['job_title'=>$job->subject]);
            if ($c_open_contracts > 1) {
              $_msg .= trans('message.buyer.job.contract.exist_plural', ['contract_count'=>$c_open_contracts]);
            } else {
              $_msg .= trans('message.buyer.job.contract.exist', ['contract_count'=>$c_open_contracts]);
            }

            $_ajax['status'] = '';
            throw new Exception($_msg);
          }

          // Close open applications
          Notification::send(Notification::BUYER_JOB_CANCELED, 
                             SUPERADMIN_ID, 
                             $user->id,
                             ["job_title" => $job->subject]);

          $job->closeAllOpenApplications(ProjectApplication::STATUS_PROJECT_CANCELLED);
        }

        $job->status = $status;
        $job->save();

      } else {
        $_ajax['status'] = "Status is null, Please contact to administrator";
      }
    }
    catch(ModelNotFoundException $e) {
      // Not found Job
      add_message(trans('message.buyer.job.status.non_exist', ['job_id'=>$job_id]), "error");
    }
    catch (Exception $e) {
      add_message($e->getMessage(), 'danger');
    }

    if ($_ajax['status'] == 'success') {
      $_msg = 'Job "'.$job->subject.'" is ';
      if ($status == Project::STATUS_OPEN) { $_msg .= 'opened.'; }
      else { $_msg .= 'closed.'; }

      add_message($_msg, "success");
    }

    return response()->json($_ajax);
  }

  /**
   * Change Job Status (job/{id}/change_public/{status}) [AJAX]
   *
   * @author nada
   * @since Feb 28, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function change_public(Request $request, $job_id, $is_public)
  {
    $_ajax = array(
      'status' => "success", 
      'error'  => "",
    );

    $user = Auth::user();
    if (!$user ||!$user->isBuyer() ) {
      $_ajax['status'] = "";
    } 
    else {
      try {
        $job = Project::findOrFail($job_id);

        if (!$job->checkIsAuthor($user->id)) {
          throw new ModelNotFoundException();
        }

        if ($is_public != null) {
          $job->is_public = $is_public;
          $job->save();
          // Success
        } else {
          $_ajax['status'] = "";
        }
      }
      catch(ModelNotFoundException $e) {
        // Not found Job
        $_ajax['status'] = "";
      }
    }

    if ($_ajax['status'] == 'success') {
      
    }

    return response()->json($_ajax);
  }

  /**
   * Change Application Status (job/application/{id}/change_status/{status}) [AJAX]
   *
   * @author nada
   * @since Mar 13, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function changeAppStatus_buyer(Request $request, $application_id, $status)
  {
    $_ajax = array(
      'status' => "success", 
      'error'  => "",
    );

    $user = Auth::user();
    if (!$user) {
      $_ajax['status'] = "error1";
    } 
    else {
      try {
        $app = ProjectApplication::findOrFail($application_id);
        $job = $app->project;
        if (!$job || !$job->checkIsAuthor($user->id)) {
          throw new Exception(trans("message.buyer.job.not_authorized"));
        }

        if ($status != null) {
          $app->status = $status;
          $app->save();
          // Success

          $cotractor = $app->user;
          add_message(trans('message.buyer.application.client_decline', ['contractor'=>$contractor->fullname()]), "success");
        } else {
          $_ajax['status'] = "error3";
        }
      }
      catch(ModelNotFoundException $e) {
        // Not found Job
        $_ajax['status'] = "";
      }
      catch(Exception $e) {
        $msg = $e->getMessage();
        if ($msg) {
          add_message($msg, 'danger');
        }
        
      }
    }

    return response()->json($_ajax);
  }

  /**
 * Invite (job/invite/{uid})
   *
   * @author nada
   * @since Mar 16, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function invite(Request $request, $user_id)
  {
    $user = Auth::user();

    try {
      $contractor = User::findOrFail($user_id);

      if ($request->isMethod('post')) {
        $job_id = $request->input('job');
        $job = Project::findOrFail($job_id);

        if (!$job || !$job->checkIsAuthor($user->id)) {
          throw new Exception(trans("message.buyer.job.not_authorized"));
        }

        $application = ProjectApplication::getOpenApplication($job_id, $user_id);
        if ($application) {
          /* TODO: invoke add_message in each case of project status */
        } else {
          $app = new ProjectApplication();
          $app->provenance  = ProjectApplication::STATUS_INVITED;    // Invite
           
          $app->project_id  = $job_id;
          $app->user_id     = $user_id;
          $app->type        = $job->type;
          $app->price       = 0;
          $app->cv          = "";
          $app->reason      = "";
          $app->status      = ProjectApplication::STATUS_INVITED;

          $app->save();

          // // Create MessageThread
          // $mt = new ProjectMessageThread();
          // $mt->subject        = $job->subject;
          // $mt->sender_id      = $user->id;  //  Buyer
          // $mt->receiver_id    = $user_id;   //  Freelancer
          // $mt->application_id = $app->id;

          // $mt->save();
          add_message(trans("message.buyer.job.invite.success", [
              'contractor_name' => $contractor->fullname(),
              'job_title' => $job->subject
            ]), 'success');
          
          Notification::send(Notification::RECEIVED_INVITATION, 
                             SUPERADMIN_ID,
                             $user_id, 
                             ["buyer_fullname" => $user->fullname(), 
                              "job_title"      => $job->subject]);

          return redirect()->route('job.my_jobs');
        }
      }

      $jobs = Project::where('client_id','=', $user->id)
                    ->where('status', '=', Project::STATUS_OPEN)
                    ->where('is_public', '=', Project::STATUS_PUBLIC)
                    ->orderBy('id', 'desc')
                    ->get();

      if ($contractor->isFreelancer()) {
        return view('pages.buyer.job.invite', [
          'page'  => 'buyer.job.invite',
          'jobs'  => $jobs, 
          'contractor' => $contractor,
        ]);
      }
    } catch(ModelNotFoundException $e) {
      // Not found user
    } catch(Exception $e) {
      $msg = $e->getMessage()." (Line: ".$e->getLine()." in ".$e->getFile().")";
      if ($msg) {
        add_message($msg, 'danger');
      }
    }

    return redirect()->route('job.my_jobs');

  }
  /**
   * Change Job Status (job/invite/job-info) [AJAX]
   *
   * @author nada
   * @since Mar 16, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function invite_job_info(Request $request) {
    $user = Auth::user();
    
    if ($request->ajax()) {
      $_ajax = array(
        'status' => "success", 
        'error'  => "",
      );

      $job_id = $request->input('job');
      $contractor_id = $request->input('contractor');

      try {
        $job = Project::findOrFail($job_id);
        $contractor = User::findOrFail($contractor_id);

        if (!$job || !$job->checkIsAuthor($user->id)) {
          throw new Exception(trans("message.buyer.job.not_authorized"));
        }

        $app = ProjectApplication::getOpenApplication($job_id, $contractor_id);

        $msg_status = "";
        if ($app) {
          if ($app->status == ProjectApplication::STATUS_NORMAL || $app->status == ProjectApplication::STATUS_ACTIVE) {
            $msg_status = trans('job.sb_applied_to_job', ['sb'=>$contractor->fullname()]) ." ". 
                          trans('job.check_sb_proposal', ['url'=> route('job.application_detail', ['id'=>$app->id]), 'sb'=>$contractor->fullname()]);
            $_ajax['disable_submit'] = "disable";
          }
          else if ($app->status == ProjectApplication::STATUS_INVITED) {
            $msg_status = trans('job.sent_invitation')." <a href='".route('job.application_detail', ['id'=>$app->id])."?_action=message'>".trans('job.click_here_send_message')."</a> ";
            $_ajax['disable_submit'] = "disable";
          }
          else if ($app->status == ProjectApplication::STATUS_OFFER) {
            $msg_status = trans('job.sent_offer');
            $_ajax['disable_submit'] = "disable";
          }
          else if ($app->status == ProjectApplication::STATUS_HIRED) {
            $msg_status = trans('job.hired_freelancer');
            $_ajax['disable_submit'] = "disable";
          }
        }

        $data = view('pages.buyer.job.section.invite_job_info', [
          'job' => $job, 
          'msg_status' => $msg_status
        ])->render();

        $_ajax['job_info'] = $data;
      }
      catch(ModelNotFoundException $e) {
        // Not found Job
        $_ajax['status'] = "error";
        $_ajax['error'] = "1";
      }
      catch(Exception $e) {
        $msg = $e->getMessage();
        if ($msg) {
          add_message($msg, 'danger');
        }
      }

      return response()->json($_ajax);
    }
  }

  /**
   * Make Offer (job/{id}/make-offer)
   *
   * @author nada
   * @since Mar 6, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
   public function make_offer(Request $request, $job_id, $user_id)
  {
    $validate = true;
    $user = Auth::user();
    try {
      $job = Project::findOrFail($job_id);
      $contractor = User::find($user_id);

      if (!$job || !$job->checkIsAuthor($user->id)) {
        throw new Exception(trans("message.buyer.job.not_authorized"));
      }

      if (!$contractor) {
        return redirect()->route('job.applicants', ['id'=>$job_id]);
      }

      if (!$contractor->isFreelancer()) {
        add_message(trans('message.buyer.job.make_offer.user_not_freelancer', ['contractor'=>$user->fullname()]), 'danger');
        return redirect()->route('job.applicants', ['id'=>$job_id]);
      }
      else if ($contractor->status != 1) {

      }

      // Validation
      if ($job->status != Project::STATUS_OPEN) {
        add_message(trans('message.buyer.job.make_offer.job_not_open', ['job_title'=>$job->subject]), 'danger');
        $validate = false;
      }
      else if ($job->is_public == Project::STATUS_PRIVATE) {
        add_message(trans('message.buyer.job.make_offer.job_is_private', ['job_title'=>$job->subject]), 'danger');
        $validate = false;
      }


      $request->flashOnly('hourly_rate', 
                          'fixed_price', 
                          'hourly_limit', 
                          'manual_log');

      // Check if offer uses application
      $applicant = ProjectApplication::getOpenApplication($job_id, $user_id);

      $use_application_data = false;
      if ($applicant) {
        if ($applicant->status==ProjectApplication::STATUS_NORMAL ||
            $applicant->status==ProjectApplication::STATUS_ACTIVE )
        {
          $use_application_data = true;
        }
        else if ($applicant->status==ProjectApplication::STATUS_OFFER) {
          add_message(trans('message.buyer.job.make_offer.existing_offer', ['contractor'=>$contractor->fullname()]), 'warning');
          /* TODO: Update Message Text. Adding Link to Application(Offer) Detail Page */
          $validate = false;
        }
        else if ($applicant->status==ProjectApplication::STATUS_HIRED) {
          add_message(trans('message.buyer.job.make_offer.hired', ['contractor'=>$contractor->fullname()]), 'warning');
          /* TODO: Update Message Text. Adding Link to Application(Offer) Detail Page */
          $validate = false;
        }
      }

      $hourly_limit = 40; // Default Full Time
      if ($request->input('hourly_limit')) {
        $hourly_limit = $request->input('hourly_limit');
      }

      //////////////////////////////////////////////
      $hourly_rate = 0;
      $fixed_price = 0;

      $job_type = $job->type;
      if ($job_type == Project::TYPE_HOURLY) {
        if ($use_application_data) {
          $hourly_rate = $applicant->price;
        } else {
          if ($contractor->profile) {
            $hourly_rate = $contractor->profile->rate;
          }
        }  
      }
      else {
        if ($use_application_data) {
          $fixed_price = $applicant->price;
        } else {
          $fixed_price = $job->price;
        }
      }

      if ($validate && $request->isMethod('post')) {
        $new_app = false;

        try {
          // New Application For Direct Offer
          // Change Status "Invited" to "Offer" for Application
          if (!$applicant || 
              ($applicant->status == ProjectApplication::STATUS_INVITED)  )
          {
            if (!$applicant) {
              $applicant = new ProjectApplication();
              $applicant->provenance  = ProjectApplication::STATUS_OFFER;    // OFFER
              $new_app = true;
            }
            $applicant->project_id  = $job_id;
            $applicant->user_id     = $user_id;
            $applicant->type        = $job->type;
            $applicant->price       = $job->type==Project::TYPE_HOURLY? $request->input('hourly_rate'):$request->input('fixed_price');
            $applicant->cv          = "";
            $applicant->reason      = "";
          }

          if ($applicant) {
            $applicant->status      = ProjectApplication::STATUS_OFFER;
            $applicant->save();

            // if ($new_app) {
            //   // Create MessageThread
            //   $mt = new ProjectMessageThread();
            //   $mt->subject        = $job->subject;
            //   $mt->sender_id      = $user->id;  //  Buyer
            //   $mt->receiver_id    = $user_id;   //  Freelancer
            //   $mt->application_id = $applicant->id;
  
            //   $mt->save();
            // }
          }

          // Contract for Offer
          $contract = new Contract;
          $contract->title          = $request->input('contract_title');
          $contract->buyer_id       = $user->id;
          $contract->contractor_id  = $contractor->id;
          $contract->project_id     = $job_id;
          $contract->application_id = $applicant ? $applicant->id:0;
          $contract->type           = $job->type;
          $contract->price          = $job->type==Project::TYPE_HOURLY ? $request->input('hourly_rate') : $request->input('fixed_price');
          $contract->limit          = $job->type==Project::TYPE_HOURLY ? $request->input('hourly_limit') : 0;
          $contract->is_allowed_manual_time = ($job->type==Project::TYPE_HOURLY && $request->input('manual_log'))? 1:0;
          $contract->status         = Contract::STATUS_OFFER;

          $contract->save();

          // Add contract_meter
          $contract->meter = new ContractMeter;
          $contract->meter->id = $contract->id;
          $contract->meter->save();

          add_message(trans('message.buyer.job.make_offer.success', 
                            ['job_title'=>$job->subject, 'contractor_name'=>$contractor->fullname()]), 
                      'success');
          
          Notification::send(Notification::RECEIVED_OFFER, 
                             SUPERADMIN_ID,
                             $user_id, 
                             ["buyer_fullname" => $user->fullname(), 
                              "job_title"      => $job->subject]);

          return redirect()->route('job.my_jobs');

        } catch(Exception $e) {
          $msg = $e->getMessage();
          if ($msg) {
            add_message($msg, 'danger');
          }
          return redirect()->route('job.my_jobs');
        }
      }

      return view('pages.buyer.job.make_offer', [
        'page'  => 'buyer.job.make_offer',
        'job_type' => $job_type, 
        'hourly_rate' => $hourly_rate,
        'fixed_price' => $fixed_price, 
        'hourly_limit'=> $hourly_limit, 
        'job'   => $job, 
        'contractor' => $contractor, 
        'validate' => $validate, 
      ]);
    }
    catch(ModelNotFoundException $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    } catch(Exception $e) {
      $msg = $e->getMessage();
      if ($msg) {
        add_message($msg, 'danger');
      }
      return redirect()->route('job.my_jobs');
    }

  }

  /**
   * View Detail Job Page (job/{id})
   *
   * @author Ri Chol Min
   * @since Feb 23, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  protected function view_job_freelancer(Request $request, $job_id)
  {
    $user = Auth::user();

    try {

      $job = Project::where('id', $job_id)->first();

      $job_post_count = count(Project::where('client_id', $job->client_id)->get());

      $client = User::where('id', $job->client_id)->first();

      if ($request->isMethod('post')) {
        return redirect()->route('job.apply', ['id'=>$job_id]);
      }

      if (!$user || !$user->isFreelancer()) {
        return view('pages.freelancer.job.job_detail', [
          'page'      => 'freelancer.job.job_detail', 
          'job'       => $job,
          'job_id'    => $job_id,
          'job_count' => $job_post_count,
          'client'    => $client
        ]);
      }
      if ( $job->openApplications(true, $user->id) > 0 ) {
        return view('pages.freelancer.job.job_detail', [
          'page'          => 'freelancer.job.job_detail', 
          'job'           => $job,
          'job_id'        => $job_id,
          'login'         => true,
          'job_count'     => $job_post_count,
          'client'        => $client,
          'bid'           => true
        ]);
      }else{
        return view('pages.freelancer.job.job_detail', [
          'page'      => 'freelancer.job.job_detail', 
          'job'       => $job,
          'job_id'    => $job_id,
          'login'     => true,
          'job_count' => $job_post_count,
          'client'    => $client,
          'bid'       => false
        ]);
      }    
      
    }
    catch(ModelNotFoundException $e) {
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * Apply Job Page (apply/{id})
   *
   * @author Ri Chol Min
   * @since Feb 27, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function job_apply(Request $request, $job_id)
  {
    $user = Auth::user();

    $youAlreadyApplied = ProjectApplication::where('user_id', $user->id)
      ->where('project_id', $job_id)
      ->whereNotNull('deleted_at')
      ->exists();

    if ($youAlreadyApplied) {
      return redirect()->route('job.view', ['id' => $job_id]);
    }

    try {

      $job = Project::find($job_id);

      if ($request->isMethod('post')) {
        $applicant = new ProjectApplication;
        $applicant->project_id    = $job_id;
        $applicant->user_id       = $user->id;
        $applicant->provenance    = 0;
        $applicant->type          = $job->type;
        $applicant->status        = '0';
        $applicant->price         = $request->input('billing_rate');        
        $applicant->cv            = strip_tags($request->input('coverletter'));

        $applicant->save();

        // // Create MessageThread
        // $mt = new ProjectMessageThread();
        // $mt->subject        = $job->subject;
        // $mt->sender_id      = $job->client_id;  //  Buyer
        // $mt->receiver_id    = $user->id;  //  Freelancer
        // $mt->application_id = $applicant->id;

        // $mt->save();

        add_message(trans('message.freelancer.job.apply.success_apply', ['job_title'=>$job->subject]), 'success');

        return redirect()->route('job.my_proposals');
      }

      return view('pages.freelancer.job.job_apply', [
        'page'  => 'freelancer.job.job_apply', 
        'job'   => $job,
        'job_id'=> $job_id
      ]);
      
    }
    catch(ModelNotFoundException $e) {
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * My Proposal Page (job/my-proposal)
   *
   * @author Ri Chol Min
   * @since Mar 03, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function my_proposals(Request $request)
  {
    $user = Auth::user();

    try {

      $active_jobs = ProjectApplication::where('user_id', $user->id)->where('status', ProjectApplication::STATUS_ACTIVE)->get();
      $invite_jobs = ProjectApplication::where('user_id', $user->id)->where('status', ProjectApplication::STATUS_INVITED)->get();
      $my_proposals = ProjectApplication::where('user_id', $user->id)->where('status', ProjectApplication::STATUS_NORMAL)->get();

      return view('pages.freelancer.job.my_proposals', [
        'page'  => 'freelancer.job.my_proposals', 
        'active_jobs'   => $active_jobs,
        'invite_jobs'   => $invite_jobs,
        'my_proposals'   => $my_proposals
      ]);
      
    }
    catch(ModelNotFoundException $e) {
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * My Archived Page (job/my-archived)
   *
   * @author Ri Chol Min
   * @since Mar 06, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function my_archived(Request $request)
  {
    $user = Auth::user();

    try {

      $archived_jobs = ProjectApplication::where('user_id', $user->id)->whereIn('status', [ProjectApplication::STATUS_CLIENT_DCLINED, ProjectApplication::STATUS_FREELANCER_DECLINED, ProjectApplication::STATUS_PROJECT_CANCELLED, ProjectApplication::STATUS_PROJECT_EXPIRED])->paginate(10);

      return view('pages.freelancer.job.my_archived', [
        'page'  => 'freelancer.job.my_archived',
        'archived_jobs' => $archived_jobs
      ]);
      
    }
    catch(ModelNotFoundException $e) {
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * My Applicant Page (job/application/{id})
   *
   * @author Ri Chol Min
   * @since Mar 07, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */

  protected function application_detail_freelancer(Request $request, $application_id)
  {
    try {
      $user   = Auth::user();
      $app    = ProjectApplication::findOrFail($application_id);
      $job    = $app->project;
      $job_id = $job->id;

      if ( count($app) > 0 ) {      

        if ($request->isMethod('post')) {
          if ($request->input('type') == 'T'){
            $app->price  = $request->input('rate');
            $app->save();
          }else if ($request->input('type') == 'M'){
            $app->sendMessage(strip_tags($request->input('message')), $user->id);
            $messages = $app->getMessages(true);
            return view('pages.freelancer.job.my_applicant', [
              'page'  => 'freelancer.job.my_applicant',
              'job'   => $job,
              'application' => $app,
              'job_id'  => $job_id,
              'application_id'  => $application_id,
              'messages'=> $messages,
              '_action' => 'send_message'
            ]);
          }else if ($request->input('type') == 'W'){
            $app->status  = ProjectApplication::STATUS_FREELANCER_DECLINED;
            $app->reason  = $request->input('reason');
            $app->save();
            return redirect()->route('job.my_proposals');
          }
        }

        $messages = $app->getMessages(true);

        return view('pages.freelancer.job.my_applicant', [
          'page'  => 'freelancer.job.my_applicant',
          'job'   => $job,
          'application' => $app,
          'job_id'  => $job_id,
          'application_id'  => $application_id,
          'messages'=> $messages,
          '_action' => 'message'
        ]);
      }else{
        return redirect()->route('job.view', ['id'=>$job_id]);
      }      
    }
    catch(ModelNotFoundException $e) {
      return redirect()->route('job.my_proposals');
    }
  }

  /**
   * Accept Offer Page (apply-offer)
   *
   * @author Ri Chol Min
   * @since Mar 16, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function apply_offer(Request $request, $contract_id)
  {
    $user = Auth::user();

    $contract = Contract::where('id', $contract_id)->first();
    $job = $contract->project;
    $app = ProjectApplication::findOrFail($contract->application_id);

    if ( $contract->status != Contract::STATUS_OFFER ){
      return redirect()->route('search.job');
    }

    if ($request->isMethod('post')) {
      if ( floatval($request->input('earning_rate')) > getEarningRate($contract->price) ){
        return view('pages.freelancer.job.apply_offer', [
          'page'        => 'freelancer.job.apply_offer',
          'job'         => $job,
          'contract'    => $contract,
          'contract_id' => $contract_id,
          'error'       => "You couldn't increase price.",
          'errorflag'   => 'error',
          'input_rate'  => formatCurrency($request->input('earning_rate'))
        ]);
      } else {
        if ( $request->input('message') != '' ){
            $app->sendMessage(strip_tags($request->input('message')), $user->id);          
          }
        if ( $request->input('_action') == 'accept' ){          
          $contract->status = Contract::STATUS_OPEN;
          $contract->started_at = date('Y-m-d H:i:s');
          $contract->price = getBuyerRate(priceRaw($request->input('earning_rate')));
          $contract->save();
        }else if( $request->input('_action') == 'reject' ){          
          $contract->status = Contract::STATUS_REJECTED;
          $contract->ended_at = date('Y-m-d H:i:s');
          $contract->save();          
        }

        add_message(trans('message.freelancer.job.offer.success_accept', ['job_title'=>$job->subject]), 'success');

        return redirect()->route('job.my_jobs');     
      }      
    }

    return view('pages.freelancer.job.apply_offer', [
      'page'        => 'freelancer.job.apply_offer',
      'job'         => $job,
      'contract'    => $contract,
      'contract_id' => $contract_id, 

      'j_trans'=> [
        'reject_offer' => trans('j_message.freelancer.job.reject_offer'), 
        'accept_offer' => trans('j_message.freelancer.job.accept_offer'), 
      ]
    ]);
  }

  /**
   * Accept Invite Page (accept-invite)
   *
   * @author Ri Chol Min
   * @since Mar 17, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function accept_invite(Request $request, $application_id)
  {
    $user = Auth::user();    
    
    $app = ProjectApplication::findOrFail($application_id);
    $job = $app->project;

    if ( $app->status != ProjectApplication::STATUS_INVITED ){
      return redirect()->route('search.job');
    }

    if ($request->isMethod('post')) {
      if ( $request->input('message') != '' ){
        $app->sendMessage(strip_tags($request->input('message')), $user->id);          
      }
      $app->status = ProjectApplication::STATUS_ACTIVE;
      $app->price = formatCurrency(floatval($request->input('earning_rate')) / 9 * 10);
      $app->save();

      add_message(trans('message.freelancer.job.invite.success_accept', ['job_title'=>$job->subject]), 'success');

      return redirect()->route('job.application_detail', ['id'=>$application_id]);    
    }

    return view('pages.freelancer.job.accept_invite', [
      'page'            => 'freelancer.job.accept_invite',
      'job'             => $job,
      'application'     => $app,
      'application_id'  => $application_id
    ]);
  }
}