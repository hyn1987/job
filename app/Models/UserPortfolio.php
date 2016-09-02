<?php namespace Wawjob;

use DB;
use Illuminate\Database\Eloquent\Model;

class UserPortfolio extends Model {

	//
	/**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'user_portfolios';

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * Get the user's portfolios.
   * @return array
   */
  public static function getPortfolio($user_id)
  {
    try {
      
      $portfolios = self::where('user_id', $user_id)->get();
      return $portfolios;
    }
    catch(Exception $e) {
      return [];
    }
  }
  /**
   * Get the user portfolio's categories.
   * @return array
   */
  public static function getCategories($user_id)
  {
    try {
      
      $categories = DB::select("SELECT id, name FROM categories WHERE id IN (SELECT cat_id FROM user_portfolios WHERE user_id=?)", [$user_id]);
      return $categories;
    }
    catch(Exception $e) {
      return [];
    }
  }
}
