<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

use Config;
use App;

class UserNotification extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_notifications';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['notified_at', 'read_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
  
  /**
   * Read a notification.
   * @return bool
   */
  public static function read($id)
  {
    try {
      $now = date('Y-m-d H:i:s');
      return self::where(['id' => $id])
          ->update(['read_at' => $now]);
    }
    catch(Exception $e) {
      return false;
    }
  }

  /**
   * Delete notification(s).
   * @return bool
   */
  public function del($id, $is_notification = false)
  {
    try {
      if ($is_notification) {
        return self::where(['notification_id' => $id])
          ->delete();
      } else {
        return self::where(['id' => $id])
          ->delete();  
      }
      
    }
    catch(Exception $e) {
      return false;
    }
  }

  /**
   * Get the system notifications for current user.
   * @return mixed
   */
  public static function getSystem($user_id)
  {
    try {
      
      $system_notifications = self::leftJoin('notifications as n', 'n.id', '=', 'user_notifications.notification_id')
                                  ->where(['receiver_id' => $user_id, 'n.type' => 1])
                                  ->whereNull('read_at')
                                  ->where(function($query){
                                    $now = date('Y-m-d H:i:s');
                                    $query->orWhere('user_notifications.valid_date', Null)->orWhere('user_notifications.valid_date', '>', $now);
                                  })
                                  ->orderBy('notified_at', 'DESC')
                                  ->select('user_notifications.*', 'n.content')
                                  ->get();
      parse_notification($system_notifications, App::getLocale());//if we use the multi languages, change 'EN' with current language code
      return $system_notifications;
    }
    catch(Exception $e) {
      return [];
    }
  }

  /**
   * Get the unread notifications for current user.
   * @return mixed
   */
  public static function getUnread($user_id)
  {
    try {
      
      $unread_notifications = self::leftJoin('notifications as n', 'n.id', '=', 'user_notifications.notification_id')
                                  ->where(['receiver_id' => $user_id, 'n.type' => 0])
                                  ->whereNull('read_at')
                                  ->where(function($query){
                                    $now = date('Y-m-d H:i:s');
                                    $query->orWhere('user_notifications.valid_date', Null)->orWhere('user_notifications.valid_date', '>', $now);
                                  })
                                  ->orderBy('notified_at', 'DESC')
                                  ->select('user_notifications.*', 'n.content')
                                  ->get();
      parse_notification($unread_notifications, App::getLocale());//if we use the multi languages, change 'EN' with current language code
      return $unread_notifications;
    }
    catch(Exception $e) {
      return [];
    }
  }
  /**
   * Get the notifications for current user.
   * @return mixed
   */
  public static function getAll($user_id)
  {
    try {
      
      $per_page = Config::get('settings.frontend.per_page');
      $notification = self::leftJoin('notifications as n', 'n.id', '=', 'user_notifications.notification_id')
                              ->where(['receiver_id' => $user_id, 'n.type' => 0])
                              ->where(function($query){
                                $now = date('Y-m-d H:i:s');
                                $query->orWhere('user_notifications.valid_date', Null)->orWhere('user_notifications.valid_date', '>', $now);
                              })
                              ->orderBy('notified_at', 'desc')
                              ->select('user_notifications.*', 'n.content')
                              ->paginate($per_page);
      
      parse_notification($notification, App::getLocale());//if we use the multi languages, change 'EN' with current language code
      
      return $notification;
    }
    catch(Exception $e) {
      return [];
    }
  }

  /**
   * Add the notification for users.
   * @return mixed
   */
  public static function add($notification_id, $notification, $sender_id, $receiver_id, $valid_date = null)
  {
    try {
      $now = date('Y-m-d H:i:s');
      self::insert(['notification_id' => $notification_id, 'notification' => $notification, 'sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'notified_at' => $now, 'valid_date' => $valid_date]);
    }
    catch(Exception $e) {
      return false;
    }
    return true;
  }

  /**
   * Delete the old(one month ago) notifications by the cronjob
   * @return bool
   */
  public static function deleteByCron()
  {
    $now = date("Y-m-d H:i:s");
    $monthago = date('Y-m-d', strtotime('-1 month'));
    self::where('notified_at', "<", $monthago)
        ->delete();
    return true;
  }
}