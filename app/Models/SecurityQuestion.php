<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'security_questions';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}