<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

use DB;
use Auth;
use Wawjob\ProjectMessageThread;
use Wawjob\ProjectMessage;
use Wawjob\User;

class ProjectApplication extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'project_applications';

  const STATUS_NORMAL = 0;
  const STATUS_ACTIVE = 1;
  const STATUS_INVITED = 2;
  const STATUS_CLIENT_DCLINED = 3;
  const STATUS_FREELANCER_DECLINED = 4;
  const STATUS_PROJECT_CANCELLED = 5;
  const STATUS_PROJECT_EXPIRED = 6;
  const STATUS_HIRED = 7;
  const STATUS_HIRING_CLOSED = 8;

  const STATUS_OFFER = 9;

  /**
   * Get the user.
   *
   * @return mixed
   */
  public function user()
  {
    return $this->hasOne('Wawjob\User', 'id', 'user_id');
  }

  /**
   * Get the project.
   *
   * @return mixed
   */
  public function project()
  {
    return $this->hasOne('Wawjob\Project', 'id', 'project_id');
  }

  /**
   * Get the Open Application using Project and User.
   * Open : Normal, Active, Invited, Offer, Hired
   * @return mixed
   */
  public static function getOpenApplication($project_id, $user_id)
  {
    try {
      $app = self::whereRaw('project_id=? AND user_id=? AND 
                            (status=? OR status=? OR status=? OR status=? OR status=?)', 
                            [$project_id, $user_id,
                            self::STATUS_NORMAL, self::STATUS_ACTIVE, 
                            self::STATUS_INVITED, self::STATUS_OFFER, self::STATUS_HIRED])
              ->orderBy('updated_at', 'DESC')
              ->first();

      return $app;
    }
    catch(Exception $e) {
      
    }
    return false;
  }


  /**
   * Get notification message for archived application.
   * @return mixed
   */
  public function getArchivedNotification() {
    $user = Auth::user();

    $msg = "";
    if      ($this->status == self::STATUS_CLIENT_DCLINED) {
      $msg = trans('proposal.declined_by_client'); //"Declined by client";
    }
    else if ($this->status == self::STATUS_FREELANCER_DECLINED) {
      $msg = trans('proposal.declined_by_freelancer'); //"Declined by freelancer";
    }
    else if ($this->status == self::STATUS_PROJECT_CANCELLED) {
      $msg = trans('proposal.job_cancelled'); //"Job cancelled";
    }
    else if ($this->status == self::STATUS_PROJECT_EXPIRED) {
      $msg = trans('proposal.job_expired'); //"Job expired";
    }
    else if ($this->status == self::STATUS_HIRED) {
      $msg = trans('common.hired'); //"Hired";
    }
    else if ($this->status == self::STATUS_HIRING_CLOSED) {
      $msg = trans('proposal.contract_closed'); //"Contract closed";
    }
    else if ($this->status == self::STATUS_OFFER) {
      $msg = trans('proposal.offer_sent'); //"Offer sent, already";
    }

    return $msg;
  }

  /**
   * Get MessageThread from Application
   * 
   * @return mixed
   */
  public function getMessageThread()
  {
    $thread = ProjectMessageThread::
                  where('application_id', $this->id)
                ->first();
    return $thread;
  }

  /**
   * Get Messages between Buyer and Contractor (Application)
   * 
   * @return mixed
   */
  public function getMessages($grouped=false) {
    if ($this->getMessageThread()) {
      $messages = $this->getMessageThread()->messages;
      if ($grouped) {
        $groupMessages = $messages->groupBy( function ($item, $key) {
          return substr($item->created_at, 0, 10);
        });
        return $groupMessages;
      }
      return $messages;
    }
    return array();
  }

  /**
   * Send Message trought application
   * from : User ID
   * @return mixed
   */
  public function sendMessage($msg, $from) {

    $thread = $this->getMessageThread();

    if (!$thread) {
      //New Message Thread
      $thread = new ProjectMessageThread();
      $thread->subject        = $this->project->subject;
      $thread->sender_id      = $this->project->client_id;
      $thread->receiver_id    = $this->user_id;
      $thread->application_id = $this->id;

      $thread->save();
    }

    //New Message
    $newMessage = new ProjectMessage();

    $newMessage->thread_id  = $thread->id;
    $newMessage->sender_id  = $from;
    $newMessage->message    = $msg;
    $newMessage->created_at = date('Y-m-d H:i:s');

    $newMessage->save();

    //Notification
    $to = '';
    if ($thread->sender_id == $from) {
      $to = $thread->receiver_id;
    } else {
      $to = $thread->sender_id;
    }

    $to_user = User::find($to);
    if ($to_user) {
      Notification::send(Notification::SEND_MESSAGE, 
                               SUPERADMIN_ID, 
                               $to,
                               ["sender_name" => $to_user->fullname()]);
    }


    // This is an only case where buyer send message to freelancer
    if ($this->status == self::STATUS_NORMAL) {
      $this->status = self::STATUS_ACTIVE;
      $this->save();
    }
  }
}