<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserToken extends Model {

  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_tokens';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}