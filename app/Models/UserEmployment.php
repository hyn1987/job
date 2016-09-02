<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class UserEmployment extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_employments';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}