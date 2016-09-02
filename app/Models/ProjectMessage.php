<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class ProjectMessage extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'project_messages';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['created_at', 'received_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the sender.
   *
   * @return mixed
   */
  public function sender()
  {
    return $this->belongsTo('Wawjob\User', 'sender_id');
  }
}