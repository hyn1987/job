<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;
use Auth;

// Models
use Wawjob\User;
use Wawjob\Role;
use Wawjob\Notification;
use Wawjob\Cronjob;
use Wawjob\Language;

class NotificationController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all notifications.
   *
   * @return Response
   */
  public function all()
  {
    $notifications = Notification::getAll();
    $languages = Language::getAllCode();

    return view('pages.admin.system.notification.list', [
      'page' => 'notification.list',
      'css' => 'notification.list',
      'notifications' => $notifications,
      'languages' => $languages,

      'j_trans'=> [
        'remove_notification' => trans('j_message.admin.notification.remove_notification'), 
      ]
    ]);
  }

  /**
   * Save the changes.
   *
   * @return Response
   */
  public function save(Request $request)
  {
    $result = Notification::saveModified($request->input('changes'));
    return response()->json([
      'status' => is_array($result)?'success':'fail',
      'reflects' => $result,
    ]);
  }

  /**
   * Get the notification template.
   *
   * @return Response
   */
  public function get(Request $request, $id)
  {
    $content = Notification::getContent($id);
    $params = [];
    $pattern = '/@#[a-zA-Z0-9-_]+#/';
    $content_arr = json_decode($content->content, true);
    //$key: langcode, $val: message
    foreach ($content_arr as $key => $val) {
      $match = [];
      preg_match_all($pattern, $val, $match); 
      $params[$key] = isset($match[0]) ? $match[0] : $match;
    }
   

    return response()->json([
      'status' => $content == false ? 'fail' : 'success',
      'result' => $content,
      'params' => $params,
    ]);
  }

  /**
   * Send notification.
   *
   * @return Response
   */
  public function send(Request $request)
  {
    $bfRoles = Role::getBFRole();
    $bfRoleIds = [];
    foreach ($bfRoles as $r)
    {
      $bfRoleIds[] = $r->id;
    }
    $user_model = User::leftJoin('users_roles as r', 'users.id', '=', 'r.user_id');
    $user_model->whereIn('r.role_id', $bfRoleIds)
               ->select('users.id', 'username', 'email', 'status', 'r.role_id')
               ->orderBy('username');
    $users = $user_model->get();
    $slugs = Notification::getAllSlugs();
    return view('pages.admin.system.notification.send', [
      'page' => 'notification.send',
      'css' => 'notification.send',
      'userStatusList' => User::$userStatusList,
      'userTypeList' => $bfRoles,
      'users' => $users,
      'slugs' => $slugs,

      'j_trans'=> [
        'title_send_notification' => trans('j_message.admin.notification.title_send_notification'), 
        'title_add_cronjob' => trans('j_message.admin.notification.title_add_cronjob'), 
        'send_notification' => trans('j_message.admin.notification.send_notification'), 
        'add_cronjob' => trans('j_message.admin.notification.add_cronjob'), 
      ]
      
    ]);
  }
  /**
   * Multicast a notification to selected users.
   *
   * @return Response
   */
  public function multicast(Request $request)
  {
    $user = Auth::user();
    $slug = $request->input('slug');
    $users = explode(",", $request->input('users'));
    $mode = $request->input('mode');
    $params = $request->input('param');
    $valid_date = $request->input('valid_date');
    if ($valid_date == "") {
      $valid_date = null;
    }
    if ($mode == "0") {
      foreach ($users as $receiver_id) {
        if (is_numeric($receiver_id)) {
          Notification::send($slug, $user->id, $receiver_id, $params, $valid_date);    
        }
      }
      
      return response()->json([
        'msg' => 'The notification was sent successfully',
        'status' => 'success',
      ]);  
    } else {
      $notification = Notification::getWithSlug($slug);
      $meta = [];
      $meta['notification_id'] = $notification->id;
      $meta['sender'] = $user->id;
      $meta['receivers'] = $users;
      $meta['params'] = $params;
      $meta['valid_date'] = $valid_date;
      Cronjob::saveCronJob(Cronjob::TYPE_NOTIFICATION, json_encode($meta), 7200, Cronjob::STATUS_READY);
      return response()->json([
        'msg' => 'The notification was saved in CronJob Queue.',
        'status' => 'success',
      ]);
    }
  }
}