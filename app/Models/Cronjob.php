<?php namespace Wawjob;

# @note: Methods starting with 'cr' means cron job method

use DB;
use Illuminate\Database\Eloquent\Model;

use Wawjob\HourlyLog;

class Cronjob extends Model {

	/**
   * Cronjob
   *
   * NOTE: freelancer, buyer, system
   * NOTE: define cronjob type for freelancer, buyer, system
   * @var string
   */
	const TYPE_NOTIFICATION = 0;
	const TYPE_HOURLY_LOG_MAP = 1; // hourly_log_maps

  /**
  * save(create or update) CronJob Data
  *
  * @author brice
  * @created Mar 30, 2016
  *
  * NOTE: define cronjob status
  */
  const STATUS_READY = 0;
  const STATUS_PAUSE = 1;
  const STATUS_PROCESS = 2;
  const STATUS_COMPLETE = 3;

  // HLM = hourly_log_map
  const CRONJOB_HLM = 2;

	/**
  * save(create or update) CronJob Data
  *
  * @author brice
  * @created Mar 30, 2016
  *
  * @param int $type: Cronjob Type
  * @param string $meta: json encode data for cronjob process.
  * @param int $max_runtime: max running time for cronjob.
  * @param int $status: cronjob status (0: ready, 1: pause, 2: process, 3: complete)
  */
	public static function saveCronJob($type, $meta, $max_runtime, $status, $id = null)
  {
    try {
      if ($id != null) {
        self::where('id', $id)
            ->update(['type' => $type, 'meta' => $meta, 'max_runtime' => $max_runtime, 'status' => $status]);
        return $id;
      } else {
        return self::insertGetId(['type' => $type, 'meta' => $meta, 'max_runtime' => $max_runtime, 'status' => $status]);
      }
    }
    catch(Exception $e) {
      return false;
    }
  }

  /**
  * get CronJob Data
  *
  * @author brice
  * @created Mar 30, 2016
  *
  * @param int $type: Cronjob Type
  * @param int $status: cronjob status (0: ready, 1: pause, 2: process, 3: complete)
  */
  public static function getFirstCronJob($type, $status)
  {
    return self::where('type', $type)
                ->where('status', '<=', $status)
                ->first();
  }
  /**
  * update CronJob Status
  *
  * @author brice
  * @created Mar 30, 2016
  *
  * @param int $id: Cronjob id
  * @param int $status: cronjob status (0: ready, 1: pause, 2: process, 3: complete)
  */
  public static function updateStatus($id, $status)
  {
    return self::where('id', $id)
                ->update(['status' => $status]);
  }

  /**
  * Returns started_at for the cron job which was successful last time.
  */
  public static function readLastStartedAt($type)
  {
    $started_at = '';

    if ($type == 'hourly_log_map') {
      $type = self::TYPE_HOURLY_LOG_MAP;
    }

    $cr = self::where('type', $type)->first();
    if (!$cr) {
      return false;
    }

    if ($cr->meta) {
      $meta = json_decode($cr->meta, true);

      $started_at = $meta['last_started_at'];
    }

    return $started_at;
  }

  /**
  * Returns started_at for the cron job which was successful last time.
  */
  public static function saveLastStartedAt($type, $started_at)
  {
    if ($type == 'hourly_log_map') {
      $type = self::TYPE_HOURLY_LOG_MAP;
    }

    $cr = self::where('type', $type)->first();
    if (!$cr) {
      return false;
    }

    if ($cr->meta) {
      $meta = json_decode($cr->meta, true);
    } else {
      $meta = [];
    }

    $meta['last_started_at'] = $started_at;
    $jmeta = json_encode($meta);
    
    return self::where('type', $type)
      ->take(1)
      ->update(['meta' => $jmeta]);
  }


  // Re-generate hourly_log_maps for the contracts that have changes after last_started_at of HLM generation
  public static function crHourlyLogMap()
  {
    $last_started_at = self::readLastStartedAt('hourly_log_map');

    // Start this cron job
    self::updateStatus(self::CRONJOB_HLM, self::STATUS_PROCESS);

    $query = DB::table('hourly_logs')->selectRaw('DISTINCT(contract_id)');

    if ($last_started_at) {
      $query->whereRaw("(taken_at >= '$last_started_at' OR updated_at >= '$last_started_at' OR deleted_at >= '$last_started_at')");
    }

    $cids = $query->lists('contract_id');

    if ($cids) {
      try {
        foreach($cids as $cid) {
          HourlyLog::generateMap($cid, '-1 days', 'now');
        }

        HourlyLog::updateContractMeter($cids, 'this');
      } catch (Exception $e) {
        return false;
      }
    }

    // Save last_started_at
    self::saveLastStartedAt('hourly_log_map', date("Y-m-d H:i:s"));

    // Complete this cron job
    self::updateStatus(self::CRONJOB_HLM, self::STATUS_COMPLETE);
  }
}
