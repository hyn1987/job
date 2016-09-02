<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'countries';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}