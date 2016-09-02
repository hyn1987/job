<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_contacts';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = array('user_id');

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the country record associated with the user contact.
   *
   * @return mixed
   */
  public function country()
  {
    return $this->hasOne('Wawjob\Country', 'charcode', 'country_code');
  }

  /**
   * Get the timezone record associated with the user contact.
   *
   * @return mixed
   */
  public function timezone()
  {
    return $this->hasOne('Wawjob\Timezone', 'id', 'timezone_id');
  }

  public function user()
  {
    return $this->belongsTo('Wawjob\User', 'user_id');
  }
}