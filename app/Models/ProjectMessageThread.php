<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class ProjectMessageThread extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'project_message_threads';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * Get the messages.
   *
   * @return mixed
   */
  public function messages()
  {
    return $this->hasMany('Wawjob\ProjectMessage', 'thread_id')->orderBy('created_at', 'asc');
  }

  /**
   * Get the application.
   *
   * @return mixed
   */
  public function application()
  {
    return $this->hasOne('Wawjob\ProjectApplication', 'application_id');
  }

  /**
   * Get the sender.
   *
   * @return mixed
   */
  public function sender()
  {
    return $this->hasOne('Wawjob\User', 'id', 'sender_id');
  }

  /**
   * Get the receiver.
   *
   * @return mixed
   */
  public function receiver()
  {
    return $this->hasOne('Wawjob\User', 'id', 'receiver_id');
  }
}