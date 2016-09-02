<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

use Wawjob\UserNotification;
use Wawjob\User;
use Wawjob\Cronjob;

class Notification extends Model {

  /**
   * Notifications
   *
   * NOTE: freelancer, buyer, system
   * NOTE: define slug for freelancer, buyer, system
   * @var string
   */

//Account
const ACCOUNT_SUSPENDED = 'ACCOUNT_SUSPENDED';
const ACCOUNT_REACTIVATED = 'ACCOUNT_REACTIVATED';
const FINANCIAL_ACCOUNT_SUSPENDED = 'FINANCIAL_ACCOUNT_SUSPENDED';
const FINANCIAL_ACCOUNT_REACTIVATED = 'FINANCIAL_ACCOUNT_REACTIVATED';

//Application
const RECEIVED_OFFER = 'RECEIVED_JOB_OFFER';
const RECEIVED_INVITATION = 'RECEIVED_INVITATION';
const BUYER_JOB_CANCELED = 'BUYER_JOB_CANCELED';
const APPLICATION_DECLINED = 'APPLICATION_DECLINED';

//Payment
const PAY_BONUS = 'PAY_BONUS';
const PAY_FIXED = 'PAY_FIXED';
const REFUND = 'REFUND';
const TIMELOG_REVIEW = 'TIMELOG_REVIEW';
const USER_CHARGE = 'USER_CHARGE';
const USER_WITHDRAWAL = 'USER_WITHDRAWAL';
const BUYER_PAY_BONUS = 'BUYER_PAY_BONUS';
const BUYER_PAY_FIXED = 'BUYER_PAY_FIXED';
const BUYER_REFUND = 'BUYER_REFUND';

//Ticket
const TICKET_OPENED = 'TICKET_OPENED';
const TICKET_CLOSED = 'TICKET_CLOSED';
const TICKET_SOLVED = 'TICKET_SOLVED';

//Contract
const BUYER_CONTRACT_PAUSED = 'BUYER_CONTRACT_PAUSED';
const FREELANCER_CONTRACT_PAUSED = 'FREELANCER_CONTRACT_PAUSED';
const CONTRACT_STARTED = 'CONTRACT_STARTED';
const CONTRACT_CLOSED = 'CONTRACT_CLOSED';
const FREELANCER_ENABLED_CHANGE_FEEDBACK = 'FREELANCER_ENABLED_CHANGE_FEEDBACK';
const BUYER_CHANGED_FEEDBACK = 'BUYER_CHANGED_FEEDBACK';

//Message
const SEND_MESSAGE = 'SEND_MESSAGE';

  /**
   * Notification types
   *
   * @var string
   */
  const NOTIFICATION_TYPE_NORMAL = 0;
  const NOTIFICATION_TYPE_SYSTEM = 1;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'notifications';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the all notifications and decode content field.
   *
   * @return array
   */
  public static function getAll()
  {
    $notifications = self::orderBy('is_const')->orderBy('type', 'desc')->orderBy('slug')->get();

    foreach ($notifications as &$notification) {
      $notification->content = str_replace('"', '&quot;', json_decode($notification->content, true));
    }
    
    return $notifications;
  }

  /**
   * Get the all slug of notifications.
   *
   * @return array
   */
  public static function getAllSlugs()
  {
    $slugs = self::select("id", "slug")
                 ->orderBy('is_const')->orderBy('type', 'desc')->orderBy('slug')->get();
    return $slugs;
  }

  /**
   * Get the content of notification.
   *
   * @return array
   */
  public static function getContent($id)
  {
    $content = self::select("content", "slug")
                 ->where("id", $id)->first();
    return $content;
  }

  /**
   * Add the notification for users.
   * @return mixed
   */
  public static function getWithSlug($slug)
  {
    $notification = self::where("slug", "=", $slug)
                        ->first();
    return $notification;
  }
  /**
   * Add the notification for users.
   * @return mixed
   */
  public static function send($slug, $sender_id, $receiver_id, $params = [], $valid_date = null)
  {
    try {
      $now = date('Y-m-d H:i:s');
      $notification = self::where("slug", "=", $slug)
              ->first();
      $user = User::find($receiver_id);
      $user_lang = $user->getLocale();
      if (isset($params[$user_lang])) {
        $content = json_encode(array($user_lang => $params[$user_lang]));
      }else {
        $content = json_encode(array("EN"=>$params));//"EN" should be changed by the receiver's language code  
      }
      return UserNotification::add($notification->id, $content, $sender_id, $receiver_id, $valid_date);
    }
    catch(Exception $e) {
    }
    return false;
  }

  /**
   * Add the notification for users.
   * @return mixed
   */
  public static function saveModified($changes)
  {
    $result = [];
    try {
      foreach ($changes as $notification) {
        if (is_numeric($notification["id"])) {
          if (isset($notification['remove']) && $notification['remove'] = true) {
            self::where('id', $notification["id"])
                ->delete();
            UserNotification::del($notification["id"], true);
          } else {
            self::where('id', $notification["id"])
                ->update(['slug' => $notification['slug'], 
                          'content' => json_encode($notification['content']), 
                          'is_const' => $notification['is_const'], 
                          'type' => $notification['type']]
                        );  
          }
          
        } else {
          $result[$notification["id"]] = self::insertGetId(['slug' => $notification['slug'], 
                                                            'content' => json_encode($notification['content']), 
                                                            'is_const' => $notification['is_const'], 
                                                            'type' => $notification['type']]
                                                          );

        }
      }
      return $result;
    }
    catch(Exception $e) {
      return false;
    }
    return $result;
  }

  /**
   * Send the notification by the cronjob
   * @return bool
   */
  public static function sendByCron()
  {
    $is_run = true;

    while ($is_run) {
      $call_time = time();
      $cronjob = Cronjob::getFirstCronJob(Cronjob::TYPE_NOTIFICATION, Cronjob::STATUS_PAUSE);
      
      if (empty($cronjob))
      {
        $is_run = false;
        break;
      } else {
        Cronjob::updateStatus($cronjob->id, Cronjob::STATUS_PROCESS);
        $meta = json_decode($cronjob->meta, true);
        $notification_id = $meta['notification_id'];
        $notification = self::getContent($notification_id);
        $sender = $meta['sender'];
        $receivers = $meta['receivers'];
        $params = $meta['params'];
        $max_runtime = $cronjob->max_runtime;
        $valid_date = isset($meta['valid_date'])?$meta['valid_date']: "";
        
        foreach ($receivers as $key => $r) {
          $now = time();
          if ($valid_date != "" && $now > $valid_date) {
            break;
          }
          self::send($notification->slug, $sender, $r, $params, $valid_date);
          
          if ($max_runtime != 0 && ($now - $call_time) > $cronjob->max_runtime) {
            $meta['receivers'] = array_slice($receivers, $key + 1);
            Cronjob::saveCronJob(Cronjob::TYPE_NOTIFICATION, json_encode($meta), 7200, Cronjob::STATUS_PAUSE, $cronjob->id);
            $is_run = false;
            break;
          }

        }
        if ($is_run) {
          Cronjob::updateStatus($cronjob->id, Cronjob::STATUS_COMPLETE);
        }
        

      }
    }
  }
  
}