<?php namespace Wawjob\Http\Controllers;

use DB;

use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Wawjob\User;
use Wawjob\Contract;
use Wawjob\HourlyLog;
use Wawjob\HourlyLogMap;
use Wawjob\Transaction;

class TestController extends Controller {

  public static function index()
  {
    //HourlyLog::generateMap(4, '2016-03-21', '2016-03-27');
    // Update contract_meter from hourly_log_maps for this week
    /*$cids = HourlyLogMap::getActiveHourlyContractIds('this');
    HourlyLog::updateContractMeter($cids, 'this');*/

    // Update contract_meter for last week
    /*$last_cids = HourlyLogMap::getActiveHourlyContractIds('last');
    HourlyLog::updateContractMeter($last_cids, 'last');
    */

    /*Transaction::pay([
      'cid' => 6,
      'amount' => 200,
      'type' => 'fixed'
    ]);*/

    /*Transaction::processPending([
      'ids' => [48, 49, 50]
    ]);*/

    $users = User::get();
    foreach($users as $user) {
      if ($user->id <= 6) {
        echo "User #$user->id = " . $user->totalScore() . "<br>";
      }
    }

    exit;
  }
}