<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

use Auth;

use Wawjob\User;
use Wawjob\Contract;
use Wawjob\HourlyLog;

use Wawjob\SimpleImage;

class WorkdiaryController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  public function view(Request $request, $cid)
  {
    if ( !$cid ) {
      return false;
    }

    // Work diary Date
    $wdate = $request->input("wdate");

    if ( !$wdate ) {
      $wdate = date("Y-m-d");
    }

    // Timezone
    $tz = $request->input('tz', '');

    // Show mode (grid | list)
    $mode = $request->input("mode");
    if ( !$mode ) {
      $mode = isset($_COOKIE['workdiary_mode']) ? $_COOKIE['workdiary_mode'] : 'grid';
    }

    // Get contract
    $c = Contract::find($cid);
    if ( !$c || !$c->isHourly() ) {
      abort(404);
    }

    // Get {buyer, contractor} information for timezone
    $timezones = [];

    $t = $c->buyer->contact->timezone;
    if ($t && $t->gmt_offset != 0) {
      $label = timezoneToString($t->gmt_offset) . ' (buyer)';
      $timezones[$label] = $t->name;
    }

    $t = $c->contractor->contact->timezone;
    if ($t && $t->gmt_offset != 0) {
      $label = timezoneToString($t->gmt_offset) . ' (contractor)';
      $timezones[$label] = $t->name;
    }

    $request->flash();

    // Get screenshots and meta
    $data = HourlyLog::getDiary($cid, $wdate, $tz);
    $info = $data[0];
    $diary = $data[1];

    // Calculate prev, next URL
    $dates = [
      'prev' => date("Y-m-d", strtotime($wdate) - 86400),
      'next' => date("Y-m-d", strtotime($wdate) + 86400),
      'today' => date("Y-m-d")
    ];

    foreach($dates as $k => $date) {
      $dates[$k] = $request->url() . "?wdate=$date&tz=$tz";
    }

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

    //dd(weekRange(2016, 1));
    //dd(weekNum('2016-01-04'));

    /*
    $path = getScreenshotPath(1, '201603200350', 'full');
    $path2 = getScreenshotPath(1, '201603200350', 'thumbnail_path');
    $img = new SimpleImage($path);
    $img->resizeToWidth(160);
    $img->save($path2, IMAGETYPE_JPEG);
    */

    return view('pages.admin.workdiary.view', [
      'page' => 'workdiary.view',
      'css' => 'workdiary.all',
      'component_css' => [
        'assets/plugins/bootstrap-datepicker/css/datepicker3',
      ],
      'meta' => $meta,
      'diary' => $diary,
      'options' => [
        'tz' => $timezones
      ]
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

        $html = view('pages.admin.workdiary.modal.act_table', [
            'act' => $act1,
            'class' => 'two-col'
          ])->render();

        $html .= view('pages.admin.workdiary.modal.act_table', [
            'act' => $act2,
            'class' => 'two-col'
          ])->render();
      } else {
        $html = view('pages.admin.workdiary.modal.act_table', [
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
}