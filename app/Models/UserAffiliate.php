<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class UserAffiliate extends Model {

	 /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['created_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

}
