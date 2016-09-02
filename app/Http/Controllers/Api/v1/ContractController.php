<?php namespace Wawjob\Http\Controllers\Api\v1;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Api\v1\ApiController;

use Illuminate\Http\Request;

use Auth;

// Models
use Wawjob\HourlyLog;

class ContractController extends ApiController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Upload time log.
   *
   * @param  Request $request
   * @return JSON
   */
  public function timelog(Request $request)
  {
    $payload = $this->parseJWT($request->header('JWT'));
    $logs = $payload['logs'];

    $return = [];

    foreach ($logs as $time => $log) {
      $return[$time] = [];
      $time = intval($time);

      $contract_id = $log['contract'];
      $comment = $log['comment'];
      $active_window = $log['active_window'];
      $activities = $log['activities'];

      // Process screeenshot uploaded.
      $screenshot = $request->file('screenshot_' . $time);
      if ($screenshot && $screenshot->isValid()) {
        $upload_location = getScreenshotPath($contract_id, date('YmdHi', $time), 'array');
        $screenshot->move($upload_location['path'], $upload_location['filename']);
      } else {
        $return[$time]['error'] = 1;
        $return[$time]['msg'] = 'screenshot_' . $time . ' Screenshot is not valid.';
        continue;
      }

      // For Test

      $score = 0;
      foreach ($activities as $activity) {
        $clicks = intval($activity['k']) + intval($activity['m']);
        if ($clicks) {
          $score++;
        }
      }
      $score = round(10 * $score / count($activities));
      $is_overlimit = false; // Should be calculated

      // Record screenshot info.
      $formatted_time = date('Y-m-d H:i:s', $time);

      // Check if the screenshot is already taken and uploaded.
      if (HourlyLog::where('contract_id', $contract_id)->where('taken_at', $formatted_time)->count()) {
        $return[$time]['error'] = 2;
        $return[$time]['msg'] = 'screenshot_' . $time . ' Screenshot is already taken.';
        continue;
      }

      $hourlyLog = new HourlyLog;

      $hourlyLog->contract_id = $contract_id;
      $hourlyLog->comment = $comment;
      $hourlyLog->activity = json_encode($activities);
      $hourlyLog->score = $score;
      $hourlyLog->active_window = $active_window;
      $hourlyLog->is_manual = false;
      $hourlyLog->is_overlimit = $is_overlimit;
      $hourlyLog->taken_at = $formatted_time;

      if ($hourlyLog->save()) {
        $return[$time]['error'] = 0;

        /*$today = date("Y-m-d");
        list($monday, $sunday) = weekRange($today);
        HourlyLog::generateMap($contract_id, $today, $sunday);*/
      } else {
        $return[$time]['error'] = 3;
        $return[$time]['msg'] = 'Database Error to upload.';
      }
    }

    return response()->json([
      'status' => $return
    ]);
  }
}