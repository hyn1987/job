<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class ContractMeter extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'contract_meters';

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $fillable = [];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}
