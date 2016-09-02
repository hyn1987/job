<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_educations';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}