<?php namespace Wawjob;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model {

  /**
   * Get the user.
   *
   * @return mixed
   */
  public function user()
  {
    return $this->belongsTo('Wawjob\User', 'user_id');
  }

}