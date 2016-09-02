<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Auth;
use Storage;
use Config;
use Session;

// Models
use Wawjob\User;
use Wawjob\Project;
use Wawjob\Contract;
use Wawjob\HourlyLog;

use Wawjob\SimpleImage;

class WorkdiaryController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();    
  }

  public function view_first(Request $request)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->view_first_buyer($request);
    } elseif ($user->isFreelancer()) {
      return $this->view_first_freelancer($request);
    }
  }
  public function view(Request $request, $cid)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->view_buyer($request, $cid);
    } elseif ($user->isFreelancer()) {
      return $this->view_freelancer($request, $cid);
    }
  }
  protected function view_first_buyer(Request $request)
  {
    $user = Auth::user();
    $contract = Contract::where('buyer_id','=', $user->id)
                    ->where('status', '=', Project::STATUS_OPEN)
                    ->where('type', '=', Project::TYPE_HOURLY)
                    ->orderBy('started_at', 'asc')
                    ->first();
    if ($contract) {
      return $this->view_buyer($request, $contract->id);
    }
    return $this->view_buyer($request, 0);
  }

  protected function view_buyer(Request $request, $cid)
  {
    $user = Auth::user();

    // Work diary Date
    $wdate = $request->input("wdate");
    if ( !$wdate ) {
      $wdate = date("Y-m-d");
    }

    // Show mode (grid | list)
    $mode = $request->input("mode");
    if ( !$mode ) {
      $mode = isset($_COOKIE['workdiary_mode']) ? $_COOKIE['workdiary_mode'] : 'grid';
    }

    // Timezone
    $tz = $request->input('tz', '');    

    $diary = null;
    $meta  = array();

    // Get my timezone
    $timezones = [];

    $t = $user->contact->timezone;
    if ($t && $t->gmt_offset != 0) {
      $label = timezoneToString($t->gmt_offset);
      $timezones[$label] = $t->name;
    }

    // Calculate prev, next URL
    $dates = [
      'prev' => date("Y-m-d", strtotime($wdate) - 86400),
      'next' => date("Y-m-d", strtotime($wdate) + 86400),
      'today' => date("Y-m-d")
    ];

    foreach($dates as $k => $date) {
      $dates[$k] = $request->url() . "?wdate=$date&tz=$tz";
    }

    if ($cid) {
      // Get contract
      $c = Contract::find($cid);
      if ( !$c ) {
        abort(404);
      }

      $request->flash();

      // Get screenshots and meta
      $data = HourlyLog::getDiary($cid, $wdate, $tz);
      $info = $data[0];
      $diary = $data[1];

      // Data to pass to view
      $meta = [
        'mode' => $mode,
        'wdate' => $wdate,
        'maxSlot' => HourlyLog::getMaxSlot(),
        'tz' => $tz,

        'time' => [
          'total' => formatMinuteInterval($info['total']),
          'auto' => formatMinuteInterval($info['auto']),
          'manual' => formatMinuteInterval($info['manual']),
          'overlimit' => formatMinuteInterval($info['overlimit']),
        ],

        'dateUrls' => $dates,
      ];

    } else {
      $meta = [
        'mode' => $mode,
        'wdate' => $wdate,
        'maxSlot' => HourlyLog::getMaxSlot(),
        'tz' => $tz,
        
        'time' => [
          'total' => 0,
          'auto' => 0,
          'manual' => 0,
          'overlimit' => 0,
        ],

        'dateUrls' => $dates,
      ];
    }
    
    // Contract Selector
    $_contracts = Contract::where('buyer_id','=', $user->id)
                    ->whereIn('status', [Contract::STATUS_OPEN, Contract::STATUS_PAUSED])
                    ->where('type', '=', Project::TYPE_HOURLY)
                    ->orderBy('started_at', 'asc')
                    ->get();
    $_contracts = $_contracts->groupBy('project_id');
    $contracts = array();
    foreach ($_contracts as $c_project_id=>$p_contracts) {
      $contracts[$c_project_id] = array(
          'project'   => Project::find($c_project_id), 
          'contracts' => $p_contracts, 
        );
    }

    $contract = false;
    if ($cid) {
      $contract = Contract::findOrFail($cid);
    }

    return view('pages.buyer.workdiary.view', [
      'page' => 'buyer.workdiary.view',
      'meta' => $meta,
      'diary' => $diary,
      'options' => [
        'tz' => $timezones
      ], 
      'contracts' => $contracts,
      'contract' => $contract,
    ]);
  }

  public function ajaxAction(Request $request)
  {
    if ( !$request->ajax() ) {
      return false;
    }

    $cmd = $request->input("cmd");
    if ($cmd == "loadSlot") {
      $sid = $request->input("sid");
      $act = HourlyLog::getSlotInfo($sid);

      if ( !$act ) {
        return response()->json([
          'success' => false
        ]);
      }

      if (count($act) >= 10) {
        $col = ceil(count($act) / 2);

        $act1 = array_splice($act, 0, $col);
        $act2 = $act;

        $html = view('pages.buyer.workdiary.modal.act_table', [
            'act' => $act1,
            'class' => 'two-col'
          ])->render();

        $html .= view('pages.buyer.workdiary.modal.act_table', [
            'act' => $act2,
            'class' => 'two-col'
          ])->render();
      } else {
        $html = view('pages.buyer.workdiary.modal.act_table', [
            'act' => $act,
            'class' => 'one-col'
          ])->render();
      }

      return response()->json([
        'success' => true,
        'sid' => $sid,
        'html' => $html
      ]);
    }

    return false; 
  }

/*
*   @Auth Ri Chol Min
*   @Date 03/21/2016
*/
  protected function view_first_freelancer(Request $request)
  {
    $user = Auth::user();
    $contract = Contract::where('contractor_id','=', $user->id)
                    ->where('status', '=', Project::STATUS_OPEN)
                    ->where('type', '=', Project::TYPE_HOURLY)
                    ->orderBy('started_at', 'asc')
                    ->first();
    if ($contract) {
      return $this->view_freelancer($request, $contract->id);
    }

    return $this->view_freelancer($request, 0);
  }

  protected function view_freelancer(Request $request, $cid)
  {
    $user = Auth::user();

    // Work diary Date
    $wdate = $request->input("wdate");
    if ( !$wdate ) {
      $wdate = date("Y-m-d");
    }

    // Show mode (grid | list)
    $mode = $request->input("mode");
    if ( !$mode ) {
      $mode = isset($_COOKIE['workdiary_mode']) ? $_COOKIE['workdiary_mode'] : 'grid';
    }

    // Timezone
    $tz = $request->input('tz', '');    

    $diary = null;
    $meta  = array();

    // Get my timezone (contractor)
    $timezones = [];

    $t = $user->contact->timezone;
    if ($t && $t->gmt_offset != 0) {
      $label = timezoneToString($t->gmt_offset);
      $timezones[$label] = $t->name;
    }

    // Calculate prev, next URL
    $dates = [
      'prev' => date("Y-m-d", strtotime($wdate) - 86400),
      'next' => date("Y-m-d", strtotime($wdate) + 86400),
      'today' => date("Y-m-d")
    ];

    foreach($dates as $k => $date) {
      $dates[$k] = $request->url() . "?wdate=$date&tz=$tz";
    }

    if ($cid) {
      // Get contract
      $c = Contract::find($cid);
      if ( !$c ) {
        abort(404);
      }

      $request->flash();

      // Get screenshots and meta
      $data = HourlyLog::getDiary($cid, $wdate, $tz);
      $info = $data[0];
      $diary = $data[1];

      // Data to pass to view
      $meta = [
        'mode' => $mode,
        'wdate' => $wdate,
        'tz' => $tz,
        'maxSlot' => HourlyLog::getMaxSlot(),
        
        'time' => [
          'total' => formatMinuteInterval($info['total']),
          'auto' => formatMinuteInterval($info['auto']),
          'manual' => formatMinuteInterval($info['manual']),
          'overlimit' => formatMinuteInterval($info['overlimit']),
        ],

        'dateUrls' => $dates,
      ];

      /*
      $path = getScreenshotPath(1, '201603200350', 'full');
      $path2 = getScreenshotPath(1, '201603200350', 'thumbnail_path');
      $img = new SimpleImage($path);
      $img->resizeToWidth(160);
      $img->save($path2, IMAGETYPE_JPEG);
      */
    } else {
      $meta = [
        'mode' => $mode,
        'wdate' => $wdate,
        'tz' => $tz,
        'maxSlot' => HourlyLog::getMaxSlot(),
        
        'time' => [
          'total' => 0,
          'auto' => 0,
          'manual' => 0,
          'overlimit' => 0,
        ],

        'dateUrls' => $dates,
      ];
    }
    
    // Contract Selector
    $_contracts = Contract::where('contractor_id','=', $user->id)
                    ->whereIn('status', [Contract::STATUS_OPEN, Contract::STATUS_PAUSED])
                    ->where('type', '=', Project::TYPE_HOURLY)
                    ->orderBy('started_at', 'asc')
                    ->get();
    $_contracts = $_contracts->groupBy('project_id');
    $contracts = array();
    foreach ($_contracts as $c_project_id=>$p_contracts) {
      $contracts[$c_project_id] = array(
          'project'   => Project::find($c_project_id), 
          'contracts' => $p_contracts, 
        );
    }

    $contract = false;
    if ($cid) {
      $contract = Contract::findOrFail($cid);
    }

    list($from, $to) = weekRange();

    // Is this week?
    $is_this_week = ( $wdate >= $from && $wdate <= date("Y-m-d") );

    return view('pages.freelancer.workdiary.viewjob', [
      'page' => 'freelancer.workdiary.viewjob',
      'meta' => $meta,
      'diary' => $diary,
      'options' => [
        'tz' => $timezones
      ], 
      'contracts' => $contracts,
      'contract' => $contract,      
      'is_this_week' => $is_this_week, 

      'j_trans'=> [
        'delete_screenshot' => trans('j_message.freelancer.workdiary.delete_screenshot'), 
        'select_screenshot' => trans('j_message.freelancer.workdiary.select_screenshot'), 
      ]
    ]);
  }

  public function ajaxjobAction(Request $request)
  {
    if ( !$request->ajax() ) {
      return false;
    }

    $cmd = $request->input("cmd");
    switch ($cmd) {
    case "loadSlot":
      $sid = $request->input("sid");
      $tz = $request->input("tz");
      $act = HourlyLog::getSlotInfo($sid, $tz);

      if ( !$act ) {
        return response()->json([
          'success' => false
        ]);
      }

      if (count($act) >= 10) {
        $col = ceil(count($act) / 2);

        $act1 = array_splice($act, 0, $col);
        $act2 = $act;

        $html = view('pages.freelancer.workdiary.modal.act_table', [
            'act' => $act1,
            'class' => 'two-col'
          ])->render();

        $html .= view('pages.freelancer.workdiary.modal.act_table', [
            'act' => $act2,
            'class' => 'two-col'
          ])->render();
      } else {
        $html = view('pages.freelancer.workdiary.modal.act_table', [
            'act' => $act,
            'class' => 'one-col'
          ])->render();
      }

      return response()->json([
        'success' => true,
        'sid' => $sid,
        'html' => $html
      ]);

    case "deleteSlot":
      $sid = $request->input("sid"); # array
      if ( empty($sid) ) {
        return $this->failed("No screenshot IDs given.");
      }

      $cid = $request->input("cid");
      $date = $request->input("date");

      $n = count($sid);

      if ( !HourlyLog::deleteSlot($sid) ) {
        return $this->failed("Failed to delete screenshots.");
      }

      return response()->json([
        'success' => true,
        'msg' => "Successfully deleted $n " . str_plural("screenshot", $n)
      ]);
    
    case "editMemo":
      $cid = $request->input("cid"); // contract ID
      $date = $request->input("date"); // contract ID
      $sid = $request->input("sid"); // array
      $memo = $request->input("memo");

      if ( !HourlyLog::updateMemo($sid, $memo) ) {
        return $this->failed("Failed to update memo.");
      }

      HourlyLog::generateMap($cid, $date);

      return response()->json([
        'success' => true,
        'msg' => "Successfully updated memo."
      ]);

    case "addManual":
      // $starttime = $request->input("starttime");
      // $endtime = $request->input("endtime");
      // $memo = $request->input("memo");
      $vars = ['cid', 'date', 'from_hour', 'from_min', 'to_hour', 'to_min', 'memo', 'tz'];
      foreach($vars as $v) {
        ${$v} = $request->input($v);
      }

      $from_at = "$date ".sprintf("%02d:%02d:00", $from_hour, $from_min);
      $to_at = "$date ".sprintf("%02d:%02d:00", $to_hour, $to_min);

      $opts = [
        'from' => $from_at,
        'to' => $to_at,
        'tz' => $tz,
        'memo' => $memo
      ];

      if ( !HourlyLog::addManualSlots($cid, $opts) ) {
        return $this->failed("Failed to add manual time.");
      }

      // [test] Re-generate hourly_log_map
      // @note: this should be run at CRON job
      list($monday, $sunday) = weekRange($date);
      HourlyLog::generateMap($cid, $date, $sunday);

      return response()->json([
        'success' => true,
        'msg' => "Successfully added manual time."
      ]);

    default:
    }

    return false; 
  }
}