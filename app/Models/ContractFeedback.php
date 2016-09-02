<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class ContractFeedback extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'contract_feedbacks';

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $fillable = ['contract_id'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
}
