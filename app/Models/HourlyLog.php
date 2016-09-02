<?php namespace Wawjob;

use Config;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Wawjob\Contract;
use Wawjob\ContractMeter;
use Wawjob\Cronjob;
use Wawjob\HourlyLogMap;

class HourlyLog extends Model {

  use SoftDeletes;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hourly_logs';

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['taken_at', 'deleted_at'];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = false;

  public static function getLogUnit()
  {
    return Config::get("settings.hourly_log_unit");
  }

  public static function getMaxSlot()
  {
    return 60 / self::getLogUnit();
  }

  /**
  * Retrieves segments info from screenshots of {contract, date}
  * We should highlight the beginning and the end of each contiguous segment
  *
  * The result of this method will be array of each hour
  * Each hour holds the following
  *  1. List of 6 screen at maximum (screenshot thumbnail URL, score, taken_at)
  *  2. the segments for comments (if hour goes over or memo changes, then it should be a new segment. The beginning and the end of each segment will have its green area rounded.)
  *
  * @author paulz
  * @created Mar 16, 2016
  * @param integer $cid:   Contract ID
  * @param string $wdate: Work Diary date
  * @param string $tz: Timezone string (see `timezones`.name)
  */
  public static function getDiary($cid, $wdate, $tz = '')
  {
    $logUnit = self::getLogUnit();
    $maxSlot = self::getMaxSlot();

    if ( !$cid || !$wdate ) {
      return false;
    }

    $query = DB::table('hourly_logs')
      ->where("contract_id", $cid)
      ->whereNull('deleted_at')
      ->orderBy("taken_at");

    $sel = "id, comment, score, active_window, is_manual, is_overlimit";

    if ( empty($tz) || $tz == 'Etc/UTC' || $tz == 'UTC') {
      $tz = '';
    }

    if ($tz) {
      $gmt_offset = Timezone::nameToOffset($tz);
      $str_offset = timezoneToString($gmt_offset, false);
      $sel .= ", taken_at as utc_taken_at, CONVERT_TZ(taken_at, '+00:00', '$str_offset') as taken_at";
      $query->havingRaw("DATE(taken_at) = '$wdate'");
    } else {
      $sel .= ", taken_at";
      $query->whereRaw("DATE(taken_at) = '$wdate'");
    }

    $query->selectRaw($sel);
    
    /* $items may have up to 24 * 6 = 144 screenshots as it is for one day */
    $slots = $query->get();

    // Calculate segments
    $res = [];
    $nAuto = 0;
    $nOverlimit = 0;
    $nManual = 0;
    $nTotal = 0;

    foreach ($slots as $k => $item) {
      if ( !$tz ) {
        $slots[$k]->utc_taken_at = $item->taken_at;
      }

      $dt = date_create($item->taken_at);
      $hr = intval(date_format($dt, "H"));
      $min = intval(date_format($dt, "i"));

      $slots[$k]->hour = $hr;
      $slots[$k]->minute = $min;

      // e.g: 9:00 am, 10:20 pm
      $hourLabel = $hr;
      if ($hr < 12) {
        $ampm = "am";

        if ($hr == 0) {
          $hourLabel = 12;
        }
      } else {
        $ampm = "pm";
        if ($hr > 12) {
          $hourLabel = $hr - 12;
        }
      }

      if ( !isset($res[$hr]) ) {
        $res[$hr] = [
          'label' => [
            'hour' => $hourLabel,
            'ampm' => $ampm,
          ],
          'seg' => [],
          'slots' => []
        ];

        for($si = 0; $si < $maxSlot; $si++) {
          $res[$hr]['slots'][$si] = [
            'is_empty' => true,
            'timeLabel' => sprintf("%d:%02d", $hourLabel, $logUnit * $si) . " $ampm"
          ];
        }
      }

      // Slot order: 0 ~ 5
      $minuteIndex = floor($item->minute / $logUnit);

      $slot = (array)$item;

      $slot["timeLabel"] = sprintf("%d:%02d", $hourLabel, $item->minute) . " $ampm";
      $slot["is_empty"] = false;

      $utc_dt = date_create($item->utc_taken_at);
      $utc_datetime = date_format($utc_dt, "YmdHi");

      if ( !$slot['is_manual'] ) {
        $slot["link"] = [
          "full" => resourceUrl('ss', $cid, $utc_datetime, 'full'),
          "thumbnail" => resourceUrl('ss', $cid, $utc_datetime, 'thumbnail')
        ];
      }

      $res[$hr]['slots'][$minuteIndex] = $slot;

      if ($slot['is_overlimit']) {
        $nOverlimit += 10;
      } else {
        if ($slot['is_manual']) {
          $nManual += 10;
        } else {
          $nAuto += 10;
        }
      }
    }

    $nTotal = $nAuto + $nOverlimit + $nManual;

    $info = [
      'total' => $nTotal,
      'auto' => $nAuto,
      'overlimit' => $nOverlimit,
      'manual' => $nManual
    ];

    foreach($res as $hr => $hourGroup) {
      $minuteIndex = 0;
      while ($minuteIndex < $maxSlot) {
        $slot = $hourGroup["slots"][$minuteIndex];

        if ( !isset($slot["comment"]) ) {
          $minuteIndex++;
          continue;
        }

        // Check if slot is not the last slot in this hour and has same content with the next one
        $currentComment = $slot["comment"];
        $isManual = $slot["is_manual"];
        $isOverlimit = $slot["is_overlimit"];
        $startIndex = $minuteIndex;
        $endIndex = $minuteIndex;
        if ($endIndex < $maxSlot - 1) {
          do {            
            $nextSlot = $hourGroup["slots"][$endIndex + 1];
            if (isset($nextSlot["comment"]) && $currentComment == $nextSlot["comment"] && $isManual == $nextSlot["is_manual"] && $isOverlimit == $nextSlot["is_overlimit"]) {
              $endIndex++;
            } else {
              break;
            }
          } while ($endIndex < $maxSlot - 1);
        }

        $res[$hr]["seg"][] = [
          'from' => $startIndex,
          'to' => $endIndex,
          'comment' => $currentComment,
          'is_manual' => $isManual,
          'is_overlimit' => $isOverlimit,
          'start' => true, // is fixed in the code lines below
          'end' => true    // is fixed in the code lines below
        ];

        $minuteIndex = $endIndex + 1;
      }
    }

    /*
    * For each hour, check if its first segment is continuity of previous hour's last segment (start = false) or its last segment is continued to next hour's first segment (end = false)
    */
    $k = 0;
    $hourCount = count($res);

    foreach($res as $hr => $hourGroup) {
      $thisHourSegs = $hourGroup['seg'];

      // Check first segment when this is not the first hour group of $res
      $thisHourStartSeg = $thisHourSegs[0];
      if ($k > 0 && isset($res[$hr - 1])) {
        $prevHourLastSeg = end($res[$hr - 1]['seg']);

        // If this segment ends at last slot
        if ($prevHourLastSeg['to'] == $maxSlot - 1) {
          // If comment is same, too
          if ($prevHourLastSeg['comment'] == $thisHourStartSeg['comment']) {
            // Then, mark $startSeg as the continuity of the previous hour's last segment
            $res[$hr]['seg'][0]['start'] = false;
          }
        } 
      }

      // Check last segment when this is not the last hour group of $res and this last segment ends at last slot (6th slot in this hour)
      $thisHourEndSeg = end($thisHourSegs);
      if ($k < $hourCount - 1 && isset($res[$hr + 1]) && $thisHourEndSeg['to'] == $maxSlot - 1) {
        $nextHourFirstSeg = $res[$hr + 1]['seg'][0];

        // If this segment starts at first slot
        if ($nextHourFirstSeg['from'] == 0) {
          // If comment is same, too
          if ($nextHourFirstSeg['comment'] == $thisHourEndSeg['comment']) {
            // Then, mark $startSeg as the continuity of the previous hour's last segment
            $thisHourSegCount = count($thisHourSegs);
            $res[$hr]['seg'][$thisHourSegCount - 1]['end'] = false;
          }
        } 
      }

      $k++;
    }

    return [$info, $res];
  }

  /**
  * Returns JSON info for screenshot 
  *
  * @author paulz
  * @created Mar 19, 2016
  * @param integer $id: `hourly_logs`.id
  * @param float $tz: Timezone offset
  */
  public static function getSlotInfo($id, $tz = '')
  {
    if ( !$id ) {
      return false; 
    }

    $row = self::find($id);
    if ( !$row->activity ) {
      error_log("[HourlyLog::getSlotInfo] Could not find activity data for screenshot #$id.");
      return false;
    }

    $utc_arr = json_decode($row->activity, true);
    if ( !$utc_arr ) {
      error_log("[HourlyLog::getSlotInfo] Failed to JSON decode for screenshot #$id.");
      return false;
    }

    // Convert timezone if $tz is not UTC
    // Assume $tz = +6 (UTC +06:00)
    if ($tz) {
      $gmt_offset = Timezone::nameToOffset($tz);

      foreach($utc_arr as $utc_tm => $act) {
        // 02:10 => [2, 10]
        list($utc_h, $utc_m) = explode(":", $utc_tm, 2);

        // [2, 10] => 130 mins
        $utc_mins = $utc_h * 60 + $utc_m;

        // 130 mins + 6 hours => 490 mins
        $mins = $utc_mins + $gmt_offset * 60;

        // If it goes to previous date or next date, then add or subtract one day to get valid hour:minute
        if ($mins < 0) {
          $mins += 1440;
        } else if ($mins > 1440) {
          $mins -= 1440;
        }

        // 490 => 08:10 (next date)
        $tm = formatMinuteInterval($mins, false);
        $arr[$tm] = $act;
      }
    } else {
      $arr = $utc_arr;
    }

    return $arr;
  }

  /**
  * Add manual time
  *
  * @author paulz
  * @created Mar 23, 2016
  *
  * @param integer $cid: Contract ID
  * @param string $memo: Memo to be filled in the manual slots
  */
  public static function addManualSlots($cid, $opts)
  {
    $defaults = [
      'from' => '',
      'to' => '',
      'memo' => '',
      'tz' => ''
    ];

    $opts = array_merge($defaults, $opts);
    if ( !$opts['from'] || !$opts['to'] ) {
      error_log("[HourlyLog::addManualSlots()] Invalid time range given.");
      return false;
    }

    // Swap $from and $to when from > to
    if ($opts['from'] > $opts['to']) {
      $tmp = $opts['from'];
      $opts['from'] = $opts['to'];
      $opts['to'] = $tmp;
    }

    // Check whether this contract is hourly, and manual time is allowed for this contract
    $contract = Contract::find($cid);
    if ( !$contract ) {
      error_log("[HourlyLog::addManualSlots()] Contract #$cid is not found.");
      return false;
    }

    // Whether this contract is an hourly contract
    if ( !$contract->isHourly() ) {
      error_log("[HourlyLog::addManualSlots()] Contract #$cid is not an hourly contract.");
      return false;
    }

    // Whether it is allowed to add manual time
    if ( !$contract->is_allowed_manual_time ) {
      error_log("[HourlyLog::addManualSlots()] Contract #$cid is not allowed to add manual time. Please contact your client.");
      return false;
    }

    // Convert time range to UTC if timezone is given.
    $timezone = $opts['tz'];

    // Fix the time range to HH:m0 ~ HH:m9
    $from = convertTz($opts['from'], 'UTC', $timezone);
    // truncate last digit => 0
    $t = strtotime($from);
    $t -= $t % 10;
    $from = date("Y-m-d H:i:s", $t);
 
    $to = convertTz($opts['to'], 'UTC', $timezone);
    // round this range to 10 mins
    $t = strtotime($to);
    $t += 599 - $t % 600;
    $to_max = date("Y-m-d H:i:s", $t);

    // Remove deleted records in this range
    DB::table('hourly_logs')
      ->where('contract_id', $cid)
      ->whereBetween('taken_at', [$from, $to_max])
      ->whereNotNull('deleted_at')
      ->delete();

    // Find slots which are included in this time range
    $records = DB::table('hourly_logs')
      ->where('contract_id', $cid)
      ->whereBetween('taken_at', [$from, $to_max])
      ->select(['id', 'taken_at'])
      ->get();      

    $slots = [];
    foreach($records as $slot)
    {
      $t = strtotime($slot->taken_at);
      # 
      # Truncate last digit of minute to 0
      # 2016-03-23 02:38:42 => 2016-03-23 02:38:40
      $t -= $t % 600;
      $slots[$t] = $slot;
    }

    $newSlots = [];
    $now = strtotime($from);
    $to_time = strtotime($to);
    while ($now <= $to_time) {
      // If this slot is already logged, skip
      if ( !isset($slots[$now]) ) {
        $taken_at = date("Y-m-d H:i:s", $now);
        $newSlots[] = [
          'contract_id' => $cid,
          'is_manual' => true,
          'taken_at' => $taken_at,
          'comment' => $opts['memo'],
        ];
      }
      
      // Move to next slot (10 mins)
      $now += 600;
    }

    if ( !self::insert($newSlots) ) {
      error_log("Failed to insert new records into hourly_logs.");
      return false;
    }

    // Re-check overlimit screenshots
    if ( !self::checkOverlimit($cid, $from) ) {
      error_log("Failed to check overlimit.");
      return false;
    }

    return true;
  }

  /**
  * Re-checks overlimit screenshots for the week of given date
  * All the datetime used in this method is UTC time.
  *
  * @author paulz
  * @created Mar 23, 2016
  */
  public static function checkOverlimit($cid, $date)
  {
    if ( !$cid || !$date ) {
      return false;
    }

    // Get hourly limit of this contract
    $contract = Contract::find($cid);
    if ( !$contract ) {
      error_log("[HourlyLog::checkOverlimit()] Contract #$cid is not found.");
      return false;
    }

    if ( !$contract->isHourly() ) {
      error_log("[HourlyLog::checkOverlimit()] Contract #$cid is not an hourly contract.");
      return false;
    }

    // Weekly limit of this contract
    $limit = $contract->limit;
    $nSlot = $limit * self::getMaxSlot(); 

    // Calculate week date range of given date
    list($from, $to) = weekRange($date);

    // Count slots of this week
    $count = self::where("contract_id", $cid)
      ->whereBetween('taken_at', [$from, $to])
      ->count();

    // Mark slots within limit
    $query = self::where("contract_id", $cid)
      ->whereBetween('taken_at', [$from, $to])
      ->orderBy('taken_at');

    if ($nSlot > 0) {
      $query->take($nSlot);
    }

    $query->update([
      'is_overlimit' => 0
    ]);

    // Mark slots over limit
    if ($nSlot > 0 && $count > $nSlot) {
      $query = self::where("contract_id", $cid)
        ->whereBetween('taken_at', [$from, $to])
        ->orderBy('taken_at', 'desc')
        ->take($count - $nSlot)
        ->update(['is_overlimit' => 1]);
    }

    return true;
  }


  /**
  * Requested by Ri Chol Min
  *
  * Deletes records from `hourly_logs`
  *
  * @author paulz
  * @created Mar 22, 2016
  *
  * @param array $sid: array of `hourly_logs`.id
  */
  public static function deleteSlot($sid)
  {
    // 1. Remove screenshot images
    /*
    $rows = self::whereIn('id', $sid)
      ->select(['contract_id', 'taken_at'])
      ->get();

    foreach($rows as $row) {
      $cid = $row->contract_id;
      $datetime = date("YmdHi", strtotime($row->taken_at));
      $path = getScreenshotPath($cid, $datetime, 'full');
      if (file_exists($path)) {
        unlink($path);
      }

      $path = getScreenshotPath($cid, $datetime, 'thumbnail_path');
      if (file_exists($path)) {
        unlink($path);
      }
    }
    */

    $row = self::whereIn('id', $sid)
      ->select(['contract_id', 'taken_at'])
      ->first();

    if ( !$row ) {
      return false;
    }

    // 2. Remove DB records
    if ( !self::whereIn('id', $sid)->delete() ) {
      return false;
    }

    $cid = $row['contract_id'];
    $date = $row['taken_at']->format("Y-m-d");

    // 3. Update is_overlimit
    // Re-check overlimit screenshots
    if ( !self::checkOverlimit($row['contract_id'], $row['taken_at']) ) {
      error_log("[HourlyLog::deleteSlot()] self::checkOverlimit() returned FALSE.");
      return false;
    }

    return true;
  }

  /**
  * Requested by Ri Chol Min
  *
  * Update memo for records of `hourly_logs`
  *
  * @author paulz
  * @created Mar 22, 2016
  *
  * @param array $sid: array of `hourly_logs`.id
  * @param string $memo: New work diary memo to update to
  */
  public static function updateMemo($sid, $memo)
  {
    if ( empty($sid) ) {
      error_log("[HourlyLog::updateMemo()] Invalid screenshot IDs given.");
      return false;
    }

    return DB::table('hourly_logs')
      ->whereIn('id', $sid)
      ->update([
        'comment' => $memo
      ]);
  }


  /**
  * Re-generate hourly log map for the dates between $from and $to. If total_mins is 0, then remove record from hourly_log_maps
  *
  * And update contract_meters, user_profiles
  *
  * @author paulz
  * @created Mar 26, 2016
  *
  * @param integer $cid: Contract ID
  * @param string $from: Date from
  * @param string $to:   Date to
  */
  public static function generateMap($cid, $from, $to = '')
  {
    if ( !$cid || !$from ) {
      return false;
    }

    if ( !$to ) {
      $to = $from;
    }

    $from_at = date("Y-m-d 00:00:00", strtotime($from));
    $to_at = date("Y-m-d 23:59:59", strtotime($to));

    $query = self::where('contract_id', $cid)
      ->whereBetween('taken_at', [$from_at, $to_at])
      ->where('is_overlimit', 0)
      ->orderBy('taken_at');

    $rows = $query->get();
    $n = $rows->count();

    // Fill this with all the weekdays
    $daily = [];
    $d = $from;
    while ($d <= $to) {
      $daily[$d] = [
        'mins' => 0,
        'act' => 0
      ];

      $d = date_add(date_create($d), date_interval_create_from_date_string('1 days'));

      $d = date_format($d, "Y-m-d");
    }

    $act = false;
    $cur_date = '';
    $daily_min = 0;
    $is_manual = false;

    foreach($rows as $i => $row) {
      $row_date = $row->taken_at->format("Y-m-d");
      if ($cur_date != $row_date) {
        if ($cur_date && $act) {
          $daily[$cur_date] = [
            'mins' => $daily_min,
            'act' => json_encode($act)
          ];
        }

        $cur_date = $row_date;
        $comment = '';
        $act = [];
        $k = -1;
        $daily_min = 0;
      }

      if ($row->comment != $comment || $row->is_manual != $is_manual) {
        $k++;
        $comment = $row->comment;
        $is_manual = $row->is_manual;

        $act[$k] = [
          'c' => $comment, // work diary memo
          'm' => 10,  // minutes 
        ];

        if ($is_manual) {
          $act[$k]['is_m'] = 1; // is_manual
        };
      } else {
        $act[$k]['m'] += 10;
      }

      $daily_min += 10;
    }

    // Last one
    $daily[$cur_date] = [
      'mins' => $daily_min,
      'act' => json_encode($act)
    ];

    /*echo "cid = $cid\n";
    print_r($daily);*/

    // Delete existing rows
    $del_dates = [];
    foreach($daily as $date => $row) {
      if ($row['mins'] == 0) {
        $del_dates[] = $date;
      } else {
        // Insert or update
        $map = HourlyLogMap::firstOrNew([
          'contract_id' => $cid,
          'date' => $date
        ]);

        $map->mins = $row['mins'];
        $map->act = $row['act'];

        $map->save();
      }
    }

    HourlyLogMap::where('contract_id', $cid)
      ->whereIn('date', $del_dates)
      ->delete();

    return true;
  }

  /**
  * Update contract_meters for given contracts
  *
  * @author paulz
  * @created Mar 31, 2016
  *
  * @param array $cids: Array of contract IDs
  * @param string $week: this | last
  */
  public static function updateContractMeter($cids, $week = 'this')
  {
    if ( !$cids ) {
      return false;
    }

    if ( $week != 'this' && $week != 'last' ) {
      return false;
    }

    if ($week == 'this') {
      $query = Contract::select(['id', 'price']);
      if (is_array($cids)) {
        $query->whereIn('id', $cids);
      } else {
        $query->where('id', $cids);
      }

      $cs = $query->get()->keyBy('id');

      $maps = HourlyLogMap::getWeekMinutes($cids, 'this');

      /*echo "\n--- 111 --- \n";
      print_r($maps);*/

      foreach($cids as $cid) {
        $week_mins = $maps[$cid];

        $c = $cs[$cid];

        if ( !$c->meter ) {
          $c->meter = new ContractMeter;
          $c->meter->id = $c->id;
        }
        
        $c->meter->this_mins = $week_mins;
        $c->meter->this_amount = $c->buyerPrice($week_mins);
        $c->meter->total_mins = $c->meter->last_total_mins + $week_mins;
        $c->meter->total_amount = $c->meter->last_total_amount + $c->meter->this_amount;
        $c->meter->save();
      }
    } else if ($week == 'last') {
      $str_cids = implode(',', $cids);

      $nAffected = DB::update("UPDATE `contract_meters` SET last_total_mins = total_mins, last_total_amount = total_amount WHERE `id` IN ($str_cids)");

      error_log("[HourlyLog::updateContractMeter] updated last total for $nAffected rows.");
    }

    return true;
  }

}