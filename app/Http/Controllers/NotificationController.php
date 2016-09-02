<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Auth;
use Storage;
use Config;

// Models
use Wawjob\User;
use Wawjob\Notification;
use Wawjob\UserNotification;

//DB
use DB;

class NotificationController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Retrieve Notification list
   * @author Brice
   * @since March 23, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function all(Request $request)
  {
    $user = Auth::user();
    //Notification::send(Notification::NOTIFICATION_FREELANCER_RECEIVED_INVITATION, 1, 8, ["job_title" => "test"]);
    //Notification::send(Notification::NOTIFICATION_SYSTEM_UNDER_MAINTANANCE, 1, 8);
    $notification_list = UserNotification::getAll($user->id);
    return view('pages.notification.list', [
          'page'              => 'notification.list',
          'notification_list' =>  $notification_list,
        ]);
  }
  /**
   * Read a notification
   *
   * @author Brice
   * @since March 22, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function read(Request $request, $id)
  {
    try {
      $app = new UserNotification();
      $result = $app->read($id);
      return response()->json([
        'status' => $result == false ? 'fail' : 'success',
        'notification_id' => $id,
      ]);
    }
    catch(ModelNotFoundException $e) {
      // Not found Job
      return response()->json([
        'status' => 'success',
        'notification_id' => $id,
      ]);
    }
  }
  /**
   * Delete a notification
   *
   * @author Brice
   * @since March 23, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function del(Request $request, $id)
  {
    try {
      $app = new UserNotification();
      $result = $app->del($id);
      return response()->json([
        'status' => $result == false ? 'fail' : 'success',
      ]);
    }
    catch(ModelNotFoundException $e) {
      // Not found Job
      return response()->json([
        'status' => 'success',
      ]);
    }
  }
}