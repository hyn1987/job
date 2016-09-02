<?php namespace Wawjob;

use DB;

use Illuminate\Database\Eloquent\Model;

class HourlyLogMap extends Model {

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hourly_log_maps';

  protected $fillable = array('contract_id', 'date');

  /**
  * Returns total minutes of each contract for the week containing given date
  *
  * @author paulz
  * @created Mar 27, 2016
  *
  * @param array $cids: Array of contract IDs
  * @param string $when: this (week), last (week), all (the weeks), custom date (the week of this date)
  *
  * @return array [cid => week_mins]
  */
  public static function getWeekMinutes($cids, $when = 'this')
  {
    if (is_object($cids)) {
      $cids = $cids->toArray();
    } else if ( !is_array($cids) ) {
      $cids = [$cids];
    }

    $query = self::selectRaw('contract_id, SUM(mins) as week_mins')
      ->groupBy('contract_id');
    if (count($cids) == 1) {
      $query->where('contract_id', $cids[0]);
    } else {
      $query->whereIn('contract_id', $cids);
    }

    if ($when != 'all') {
      if ($when == 'this') {
        $date = 'now';
      } else if ($when == 'last') {
        $date = '-1 weeks';
      } else {
        $date = $when;
      }

      list($from, $to) = weekRange($date);
      $from = date("Y-m-d", strtotime($from));
      $to = date("Y-m-d", strtotime($to));

      $query->whereBetween('date', [$from, $to]);
    }

    $rows = $query->get()->keyBy('contract_id');

    $maps = [];
    foreach($cids as $cid) {
      if (isset($rows[$cid])) {
        $maps[$cid] = intval($rows[$cid]->week_mins);
      } else {
        $maps[$cid] = 0;
      }
    }

    return $maps;
  }

  /**
  * @author paulz
  * @created Mar 31, 2016
  * @param string $week: this | last
  * @return array of ids of contracts which have activity during this or last week
  */
  public static function getActiveHourlyContractIds($week = 'this')
  {
    if ($week == 'this') {
      $range = weekRange();
    } else if ($week == 'last') {
      $range = weekRange('-1 weeks');
    } else {
      return false;
    }

    list($from, $to) = $range;
    $from = date("Y-m-d", strtotime($from));
    $to = date("Y-m-d", strtotime($to));

    $cids = DB::table('hourly_log_maps')->whereBetween('date', [$from, $to])
      ->selectRaw('DISTINCT(contract_id) as cid')
      ->lists('cid');

    return $cids;
  }
}