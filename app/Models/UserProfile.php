<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_profiles';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;
  

  /* User Profile :: Availability */
  const AV_MORE_THAN_30 = 0; # default value
  const AV_10_TO_30 = 1;
  const AV_NOT_AVAILABLE = 2;

  protected static $strAvailability;

  function __construct()
  {
    self::$strAvailability = array(
      self::AV_MORE_THAN_30  => trans('common.av_more_than_30'),
      self::AV_10_TO_30      => trans('common.av_10_to_30'),
      self::AV_NOT_AVAILABLE => trans('common.av_not_available'),
    );
  }

  public function toString()
  {
    return self::$strAvailability[$this->availability];
  }
}