<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Auth;
use Storage;
use Config;
use Session;
use DB;

// Models
use Wawjob\User;
use Wawjob\Role;
use Wawjob\Project;
use Wawjob\Contract;

use Wawjob\HourlyLog;
use Wawjob\HourlyLogMap;

use Wawjob\Transaction;

class ReportController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Weekly Summary Page
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function weekly_summary(Request $request)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->weekly_summary_buyer($request);
    } elseif ($user->isFreelancer()) {
      return $this->weekly_summary_freelancer($request);
    }
  }
  protected function weekly_summary_buyer(Request $request)
  {
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;
    $type        = 'all';

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));
      list($dates['from'], $dates['to']) = weekRange($dates['from']);

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }

      $type = $request->input('transaction_type');
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date("M j, Y", strtotime($dates['from']));
    $dates['to']   = date("M j, Y", strtotime($dates['to']));

    // Transactions Data
    $total      = array(
        'mins'    => 0, 
        'amount'  => 0, 
        'others'  => 0
      );

    $timesheets = array();
    $others     = array();

    $from = date_format(date_create($dates['from']), "Y-m-d");
    $to   = date_format(date_create($dates['to']), "Y-m-d");


    // Timesheet Section
    $qbuilder = DB::table('hourly_log_maps')
              ->leftJoin('contracts', 'contract_id', '=', 'contracts.id')
              ->where("contracts.buyer_id", "=", $user->id);

    $data = $qbuilder->where('date', '>=', $from)
                     ->where('date', '<=', $to)
                     ->select("date", "contract_id", "mins")
                     ->orderBy('date', 'ASC')
                     ->get();

    foreach ($data as $d) {
      $day = date_format(date_create($d->date), "N");
      $timesheets[$d->contract_id]['week'][$day] = $d;
    }

    foreach ($timesheets as $contract_id => &$c_ts) {
      $contract = Contract::find($contract_id);
      $c_ts['contract'] = $contract->contractor->fullname()." - ".$contract->title;
      $c_ts['mins'] = 0;
      foreach ($c_ts['week'] as $c) {

        $c_ts['mins'] += $c->mins;
      }
      $c_ts['amount'] = $contract->buyerPrice($c_ts['mins']);

      $total['mins']   += $c_ts['mins'];
      $total['amount'] += $c_ts['amount'];
    }

    $opened_contracts = Contract::buyer_opened_contracts($user->id, ['start'=>$from, 'end'=>$to]);
    foreach ($opened_contracts as $c) {
      if (!isset($timesheets[$c->id])) {
        $timesheets[$c->id] = array(
            'week'      => array(), 
            'contract'  => $c->contractor->fullname()." - ".$c->title, 
            'mins'      => 0, 
            'amount'    => 0
          );
      }
    }

    //dd($timesheets);

    // Fixed-Price and Others Payment
    $others = Transaction::search(array(
          'user_id' => $user->id, 
          'from'    => $from, 
          'to'      => $to, 
          'type'    => array(Transaction::TYPE_FIXED, Transaction::TYPE_BONUS, Transaction::TYPE_REFUND), 
          'contract_id' => 0, 
          'project_id'  => 0, 
          'for' => Transaction::FOR_BUYER, 
        ));

    foreach ($others as $t) {
      $total['others'] += $t->amount;
    }

    // Check if period is week
    $periodUnit = 'week';
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);
      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }

    return view('pages.buyer.report.weekly_summary', [
      'page'        => 'buyer.report.weekly_summary',
      'dates'       => $dates,  
      'prev'  => $prev, 
      'next'  => $next, 

      'total'       => $total, 
      'timesheets'  => $timesheets, 
      'others'      => $others,
    ]);
  }

  /**
   * Buyer Budget Page
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function budgets(Request $request)
  {
    $user = Auth::user();
    
    $options = array(
        'user_id' => $user->id, 
        'for'     => Transaction::FOR_BUYER, 
        'status'  => Transaction::STATUS_PENDING, 
      );
    $transactions = Transaction::search($options);
    $pendings = 0;
    foreach ($transactions as $t) {
      $pendings += $t->amount;
    }

    return view('pages.buyer.report.budgets', [
      'page'          => 'buyer.report.budgets',
      'transactions'  => $transactions, 
      'balance'       => $user->myBalance(),
      'total_pendings'=> $pendings,  
    ]);
  }  

  /**
   * Transactions Page
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function transactions(Request $request)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->transactions_buyer($request);
    } elseif ($user->isFreelancer()) {
      return $this->transactions_freelancer($request);
    }
  }
  protected function transactions_buyer(Request $request)
  {
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;
    $type        = 'all';

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range', 
                          'transaction_type', 
                          'contract_selector');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }

      $type = $request->input('transaction_type');
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date("M j, Y", strtotime($dates['from']));
    $dates['to']   = date("M j, Y", strtotime($dates['to']));

    // Transactions Data
    $from = date_format(date_create($dates['from']), "Y-m-d 00:00:00");
    $to   = date_format(date_create($dates['to']), "Y-m-d 23:59:59");

    //Get Transaction Data
    $user_id = $user->id;
    $for = Transaction::FOR_BUYER;

    $transactions = Transaction::search(compact('user_id', 'from', 'to', 'type', 'contract_id', 'project_id', 'for'));

    $_s = Transaction::getStatement(array(
        'user_id' => $user->id, 
        'from'    => $from, 
        'to'      => $to
      ));

    $statement = array(
        'beginning' => $_s['beginning'],
        'debits'   => $_s['out'], 
        'credits'  => $_s['in'], 
        'change'   => $_s['change'], 
        'ending'   => $_s['ending']
      );

    // Contract Selector
    $contracts = Contract::buyer_contracts_selector_data($user->id, $contract_id, $project_id, ['start'=>$dates['from'], 'end'=>$dates['to']]);    

    // Check if period is week or month or year
    $periodUnit = getPeriodUnit($from, $to, 'Y-m-d H:i:s');
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);

      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }


    return view('pages.buyer.report.transactions', [
      'page'        => 'buyer.report.transactions',
      'contracts'   => $contracts,
      'type'        => $type, 
      'dates'       => $dates,  
      'transactions'=> $transactions, 
      'statement'   => $statement, 

      'prev'  => $prev, 
      'next'  => $next, 

      'balance'     => $user->myBalance(),
    ]);

  }  

  /**
   * Timesheet Page
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function timesheet(Request $request)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->timesheet_buyer($request);
    }
    elseif ($user->isFreelancer()) {
      return $this->timesheet_freelancer($request);
    }
  }
  public function timesheet_buyer(Request $request)
  {
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;

    $mode = "day";
    if ( isset($_COOKIE['report_mode']) ) {
      $mode = $_COOKIE['report_mode'];
    }

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range', 
                          'contract_selector');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date("M j, Y", strtotime($dates['from']));
    $dates['to']   = date("M j, Y", strtotime($dates['to']));

    // Report Data
    $r_data = array();

    $from = date_format(date_create($dates['from']), "Y-m-d");
    $to   = date_format(date_create($dates['to']), "Y-m-d");

    $qbuilder = DB::table('hourly_log_maps')
              ->leftJoin('contracts', 'contract_id', '=', 'contracts.id')
              ->where("contracts.buyer_id", "=", $user->id);

    if ($contract_id) {
      $qbuilder->where('contract_id', '=', $contract_id);
    }
    else if ($project_id) {
      $qbuilder->where('contracts.project_id', '=', $project_id);
    }

    if ($mode == "day") {
      $data = $qbuilder->where('date', '>=', $from)
                       ->where('date', '<=', $to)
                       ->select("date", "contract_id", "mins")
                       ->orderBy('date', 'ASC')
                       ->get();

      $contracts = array();
      foreach ($data as $d) {
        if (!isset($contracts[$d->contract_id])) {
          $contracts[$d->contract_id] = Contract::find($d->contract_id);
        }
        $contract = $contracts[$d->contract_id];

        $r_data[] = array(
            'date'        => getFormattedDate($d->date), 
            'freelancer'  => $contract->contractor, 
            'mins'        => $d->mins, 
            'amount'      => $contract->buyerPrice($d->mins), 
          );
      }
    } else if ($mode == "week") {
      $weeks = $this->getPeriodList($from, $to, $mode);
      $contracts = array();
      foreach ($weeks as $week) {
        $wbuilder = clone $qbuilder;
        $w_from = date_format(date_create($week[0]), "Y-m-d");
        $w_to   = date_format(date_create($week[1]), "Y-m-d");

        $data = $wbuilder->where('date', '>=', $w_from)
                         ->where('date', '<=', $w_to)
                         ->select('contract_id', DB::raw('SUM(mins) as mins'))
                         ->groupBy('contract_id')
                         ->get();

        foreach ($data as $d) {
          if (!isset($contracts[$d->contract_id])) {
            $contracts[$d->contract_id] = Contract::find($d->contract_id);
          }
          $contract = $contracts[$d->contract_id];

          $r_data[] = array(
              'date'        => getFormattedDate($w_from) . " ~ " . getFormattedDate($w_to), 
              'freelancer'  => $contract->contractor, 
              'mins'        => $d->mins, 
              'amount'      => $contract->buyerPrice($d->mins), 
            );
        }// Group By One Week

      }
    } else if ($mode == "month") {
      $months = $this->getPeriodList($from, $to, $mode);
      $contracts = array();
      foreach ($months as $month) {
        $wbuilder = clone $qbuilder;
        $m_from = date_format(date_create($month[0]), "Y-m-d");
        $m_to   = date_format(date_create($month[1]), "Y-m-d");

        $data = $wbuilder->where('date', '>=', $m_from)
                         ->where('date', '<=', $m_to)
                         ->select('contract_id', DB::raw('SUM(mins) as mins'))
                         ->groupBy('contract_id')
                         ->get();

        foreach ($data as $d) {
          if (!isset($contracts[$d->contract_id])) {
            $contracts[$d->contract_id] = Contract::find($d->contract_id);
          }
          $contract = $contracts[$d->contract_id];

          $r_data[] = array(
            'date'        => date_format(date_create($m_to), "Y-m"), 
            'freelancer'  => $contract->contractor, 
            'mins'        => $d->mins, 
            'amount'      => $contract->buyerPrice($d->mins), 
          );
        }// Group By One Week
      }
    }

    // Contract Selector
    $contracts = Contract::buyer_contracts_selector_data($user->id, $contract_id, $project_id, ['start'=>$dates['from'], 'end'=>$dates['to']]);    

    // Check if period is week or month or year
    $periodUnit = getPeriodUnit($from, $to, 'Y-m-d');
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);
      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }

    return view('pages.buyer.report.timesheet', [
      'page'        => 'buyer.report.timesheet',
      'contracts'   => $contracts,
      'dates' => $dates,
      'r_data'=> $r_data, 
      'mode'  => $mode, 
      'prev'  => $prev, 
      'next'  => $next
    ]);
  }

  /**
   * Timelog Page
   *
   * @author Ri Chol Min
   * @since Mar 21, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function timelogs(Request $request)
  {
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;
    $type        = 'all';

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));
      list($dates['from'], $dates['to']) = weekRange($dates['from']);

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }

      $type = $request->input('transaction_type');
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date("M j, Y", strtotime($dates['from']));
    $dates['to']   = date("M j, Y", strtotime($dates['to']));

    // Transactions Data
    $total      = array(
        'mins'    => 0, 
        'amount'  => 0,
        //'average_rate'  => 0,
        'others'  => 0
      );
    // Transactions Data
    $last_total      = array(
        'mins'    => 0, 
        'amount'  => 0,
        //'average_rate'  => 0,
        'others'  => 0
      );


    $timesheets = array();
    $last_timesheets = array();
    $others     = array();

    $from       = date_format(date_create($dates['from']), "Y-m-d");
    $last_from  = date_add(date_create($dates['from']), date_interval_create_from_date_string("-7 days"));
    $last_from  = date_format($last_from, "Y-m-d");
    $to         = date_format(date_create($dates['to']), "Y-m-d");


    // Timesheet Section
    $qbuilder = DB::table('hourly_log_maps')
              ->leftJoin('contracts', 'contract_id', '=', 'contracts.id')
              ->where("contracts.contractor_id", "=", $user->id);

    $last_qbuilder = clone $qbuilder;

    $data = $qbuilder->where('date', '>=', $from)
                     ->where('date', '<=', $to)
                     ->select("date", "contract_id", "mins")
                     ->orderBy('date', 'ASC')
                     ->get();



    $last_data = $last_qbuilder->where('date', '>=', $last_from)
                     ->where('date', '<', $from)
                     ->select("date", "contract_id", "mins")
                     ->orderBy('date', 'ASC')
                     ->get();

    // Get Manual Times
    $manual_hours       = 0;
    $last_manual_hours  = 0;

    foreach ($data as $d) {
      $day = date_format(date_create($d->date), "N");
      $timesheets[$d->contract_id]['week'][$day] = $d;
    }

    foreach ($last_data as $d) {
      $day = date_format(date_create($d->date), "N");
      $last_timesheets[$d->contract_id]['week'][$day] = $d;
    }

    foreach ($timesheets as $contract_id => &$c_ts) {
      $contract = Contract::find($contract_id);
      $c_ts['contract'] = $contract->title;
      $c_ts['mins'] = 0;
      foreach ($c_ts['week'] as $c) {
        $c_ts['mins'] += $c->mins;
      }
      $c_ts['amount'] = $contract->freelancerPrice($c_ts['mins']);
      $c_ts['rate'] = $contract->freelancerRate();

      $total['mins']   += $c_ts['mins'];
      $total['amount'] += $c_ts['amount'];
      //$total['average_rate'] += $c_ts['rate'];
      $manual_hours   += DB::table('hourly_logs')->where('taken_at', '>=', $from)
                               ->where('taken_at', '<=', $to)
                               ->where('is_manual', 1)
                               ->whereNull('deleted_at')
                               ->where('is_overlimit', '<>', 1)
                               ->where('contract_id', $contract_id)
                               ->count();
    }

    foreach ($last_timesheets as $contract_id => &$c_ts) {
      $contract = Contract::find($contract_id);
      $c_ts['contract'] = $contract->title;
      $c_ts['mins'] = 0;
      foreach ($c_ts['week'] as $c) {
        $c_ts['mins'] += $c->mins;
      }
      $c_ts['amount'] = $contract->freelancerPrice($c_ts['mins']);
      $c_ts['rate'] = $contract->freelancerRate();

      $last_total['mins']   += $c_ts['mins'];
      $last_total['amount'] += $c_ts['amount'];
      //$last_total['average_rate'] += $c_ts['rate'];
      $last_manual_hours   += DB::table('hourly_logs')->where('taken_at', '>=', $from)
                               ->where('taken_at', '<=', $to)
                               ->where('is_manual', 1)
                               ->whereNull('deleted_at')
                               ->where('is_overlimit', '<>', 1)
                               ->where('contract_id', $contract_id)
                               ->count();
    }

    //$total['average_rate'] = $timesheets ? $total['average_rate'] / count($timesheets) : 0.00;
    //$last_total['average_rate'] = $last_timesheets ? $last_total['average_rate'] / count($last_timesheets) : 0.00;

    $opened_contracts = Contract::freelancer_opened_contracts($user->id, ['start'=>$from, 'end'=>$to]);
    foreach ($opened_contracts as $c) {
      if (!isset($timesheets[$c->id])) {
        $timesheets[$c->id] = array(
            'week'      => array(), 
            'contract'  => $c->title,
            'rate'      => $c->freelancerRate(),
            'mins'      => 0, 
            'amount'    => 0
          );
      }
      if (!isset($last_timesheets[$c->id])) {
        $last_timesheets[$c->id] = array(
            'week'      => array(), 
            'contract'  => $c->title,
            'rate'      => $c->freelancerRate(),
            'mins'      => 0, 
            'amount'    => 0
          );
      }
    }   

    // Fixed-Price and Others Payment
    $others = Transaction::search(array(
          'user_id' => $user->id, 
          'from'    => $from, 
          'to'      => $to, 
          'type'    => array(Transaction::TYPE_FIXED, Transaction::TYPE_BONUS, Transaction::TYPE_REFUND), 
          'contract_id' => 0, 
          'project_id'  => 0, 
          'for' => Transaction::FOR_FREELANCER, 
        ));

    foreach ($others as $t) {
      $total['others'] -= $t->amount;
    }

    // Check if period is week
    $periodUnit = 'week';
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);
      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }

    // Last payment
    $last_withdrawl_amount = Transaction::lastWithdrawalAmount($user->id);
    
    return view('pages.freelancer.report.timelogs', [
      'page'        => 'freelancer.report.timelogs',
      'dates'       => $dates,
      'prev'        => $prev,
      'next'        => $next,
      'total'       => $total,
      'timesheets'  => $timesheets,
      'last_total'       => $last_total,
      'last_timesheets'  => $last_timesheets,
      'others'      => $others,
      'manual_hours'=> $manual_hours * 10,
      'last_manual_hours'=> $last_manual_hours * 10,
      'last_withdrawl_amount' => $last_withdrawl_amount
    ]);
  }

  /**
   * Weekly Summary Page
   *
   * @author Ri Chol Min
   * @since Mar 28, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  protected function weekly_summary_freelancer(Request $request){
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;
    $type        = 'all';

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));
      list($dates['from'], $dates['to']) = weekRange($dates['from']);

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }

      $type = $request->input('transaction_type');
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date("M j, Y", strtotime($dates['from']));
    $dates['to']   = date("M j, Y", strtotime($dates['to']));

    // Transactions Data
    $total      = array(
        'mins'    => 0, 
        'amount'  => 0, 
        'others'  => 0
      );

    $timesheets = array();
    $others     = array();

    $from = date_format(date_create($dates['from']), "Y-m-d");
    $to   = date_format(date_create($dates['to']), "Y-m-d");

    // Timesheet Section
    $qbuilder = DB::table('hourly_log_maps')
              ->leftJoin('contracts', 'contract_id', '=', 'contracts.id')
              ->where("contracts.contractor_id", "=", $user->id);

    $data = $qbuilder->where('date', '>=', $from)
                     ->where('date', '<=', $to)
                     ->select("date", "contract_id", "mins")
                     ->orderBy('date', 'ASC')
                     ->get();

    foreach ($data as $d) {
      $day = date_format(date_create($d->date), "N");
      $timesheets[$d->contract_id]['week'][$day] = $d;
    }

    foreach ($timesheets as $contract_id => &$c_ts) {
      $contract = Contract::find($contract_id);
      $c_ts['contract'] = $contract->contractor->fullname()." - ".$contract->title;
      $c_ts['mins'] = 0;
      foreach ($c_ts['week'] as $c) {

        $c_ts['mins'] += $c->mins;
      }
      $c_ts['amount'] = $contract->freelancerPrice($c_ts['mins']);

      $total['mins']   += $c_ts['mins'];
      $total['amount'] += $c_ts['amount'];
    }

    $opened_contracts = Contract::freelancer_opened_contracts($user->id, ['start'=>$from, 'end'=>$to]);
    foreach ($opened_contracts as $c) {
      if (!isset($timesheets[$c->id])) {
        $timesheets[$c->id] = array(
            'week'      => array(), 
            'contract'  => $c->contractor->fullname()." - ".$c->title, 
            'mins'      => 0, 
            'amount'    => 0
          );
      }
    }

    // Fixed-Price and Others Payment
    $others = Transaction::search(array(
          'user_id' => $user->id, 
          'from'    => $from, 
          'to'      => $to, 
          'type'    => array(Transaction::TYPE_FIXED, Transaction::TYPE_BONUS, Transaction::TYPE_REFUND), 
          'contract_id' => 0, 
          'project_id'  => 0, 
          'for' => Transaction::FOR_FREELANCER, 
        ));

    foreach ($others as $t) {
      $total['others'] += $t->amount;
    }

    // Check if period is week
    $periodUnit = 'week';
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);
      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }

    return view('pages.freelancer.report.transaction_weekly_summary', [
      'page'        => 'freelancer.report.transaction_weekly_summary',
      'dates'       => $dates,
      'prev'        => $prev,
      'next'        => $next,
      'total'       => $total,
      'timesheets'  => $timesheets,
      'others'      => $others,
    ]);
  }

  /**
   * Timesheet Page
   *
   * @author Ri Chol Min
   * @since Mar 25, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  function timesheet_freelancer(Request $request){
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;

    $mode = "day";
    if ( isset($_COOKIE['report_mode']) ) {
      $mode = $_COOKIE['report_mode'];
    }

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range', 
                          'contract_selector');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date_format(date_create($dates['from']), "M j, Y");
    $dates['to']   = date_format(date_create($dates['to']), "M j, Y");

    // Report Data
    $r_data = array();

    $from = date_format(date_create($dates['from']), "Y-m-d");
    $to   = date_format(date_create($dates['to']), "Y-m-d");

    $qbuilder = DB::table('hourly_log_maps')
              ->leftJoin('contracts', 'contract_id', '=', 'contracts.id')
              ->where("contracts.contractor_id", "=", $user->id);

    if ($contract_id) {
      $qbuilder->where('contract_id', '=', $contract_id);
    }
    else if ($project_id) {
      $qbuilder->where('contracts.project_id', '=', $project_id);
    }

    if ($mode == "day") {
      $data = $qbuilder->where('date', '>=', $from)
                       ->where('date', '<=', $to)
                       ->select("date", "contract_id", "mins")
                       ->orderBy('date', 'ASC')
                       ->get();

      $contracts = array();
      foreach ($data as $d) {
        if (!isset($contracts[$d->contract_id])) {
          $contracts[$d->contract_id] = Contract::find($d->contract_id);
        }
        $contract = $contracts[$d->contract_id];

        $r_data[] = array(
            'date'        => $d->date, 
            'client'      => $contract->buyer, 
            'mins'        => $d->mins, 
            'amount'      => $contract->freelancerPrice($d->mins), 
          );
      }
    }
    else if ($mode == "week") {
      $weeks = $this->getPeriodList($from, $to, $mode);
      $contracts = array();
      foreach ($weeks as $week) {
        $wbuilder = clone $qbuilder;
        $w_from = date_format(date_create($week[0]), "Y-m-d");
        $w_to   = date_format(date_create($week[1]), "Y-m-d");

        $data = $wbuilder->where('date', '>=', $w_from)
                         ->where('date', '<=', $w_to)
                         ->select('contract_id', DB::raw('SUM(mins) as mins'))
                         ->groupBy('contract_id')
                         ->get();

        foreach ($data as $d) {
          if (!isset($contracts[$d->contract_id])) {
            $contracts[$d->contract_id] = Contract::find($d->contract_id);
          }
          $contract = $contracts[$d->contract_id];

          $r_data[] = array(
              'date'        => $w_from . " ~ " . $w_to, 
              'client'      => $contract->buyer, 
              'mins'        => $d->mins, 
              'amount'      => $contract->freelancerPrice($d->mins), 
            );
        }// Group By One Week

      }
    }
    else if ($mode == "month") {
      $months = $this->getPeriodList($from, $to, $mode);
      $contracts = array();
      foreach ($months as $month) {
        $wbuilder = clone $qbuilder;
        $m_from = date_format(date_create($month[0]), "Y-m-d");
        $m_to   = date_format(date_create($month[1]), "Y-m-d");

        $data = $wbuilder->where('date', '>=', $m_from)
                         ->where('date', '<=', $m_to)
                         ->select('contract_id', DB::raw('SUM(mins) as mins'))
                         ->groupBy('contract_id')
                         ->get();

        foreach ($data as $d) {
          if (!isset($contracts[$d->contract_id])) {
            $contracts[$d->contract_id] = Contract::find($d->contract_id);
          }
          $contract = $contracts[$d->contract_id];

          $r_data[] = array(
              'date'        => date_format(date_create($m_to), "Y-m"), 
              'client'      => $contract->buyer, 
              'mins'        => $d->mins, 
              'amount'      => $contract->freelancerPrice($d->mins), 
            );
        }// Group By One Week
      }
    }

    // Contract Selector
    $contracts = Contract::freelancer_contracts_selector_data($user->id, $contract_id, $project_id, ['start'=>$dates['from'], 'end'=>$dates['to']]);

    // Check if period is week or month or year
    $periodUnit = getPeriodUnit($from, $to, 'Y-m-d');
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);
      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }

    return view('pages.freelancer.report.transaction_timesheet', [
      'page'        => 'freelancer.report.transaction_timesheet',
      'contracts'   => $contracts,
      'dates' => $dates,  
      'r_data'=> $r_data, 
      'mode'  => $mode, 
      'prev'  => $prev, 
      'next'  => $next
    ]);
  }

  /**
   * Transaction History Page
   *
   * @author Ri Chol Min
   * @since Mar 31, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  function transactions_freelancer(Request $request){
    $user = Auth::user();

    $dates['from'] = "";
    $dates['to']   = "";

    $contract_id = 0;
    $project_id  = 0;
    $type        = 'all';

    if ($request->isMethod('post')) {

      // Flash data to the session.
      $request->flashOnly('date_range', 
                          'transaction_type', 
                          'contract_selector');

      list($dates['from'], $dates['to']) = parseDateRange($request->input('date_range'));

      if (starts_with($request->input('contract_selector'), "p")) {
        $project_id = substr($request->input('contract_selector'), 1);
      } else {
        $contract_id = $request->input('contract_selector');
      }

      $type = $request->input('transaction_type');
    } else {
      list($dates['from'], $dates['to']) = weekRange();
    }

    $dates['from'] = date_format(date_create($dates['from']), "M j, Y");
    $dates['to']   = date_format(date_create($dates['to']), "M j, Y");

    // Transactions Data
    $from = date_format(date_create($dates['from']), "Y-m-d 00:00:00");
    $to   = date_format(date_create($dates['to']), "Y-m-d 23:59:59");

    //Get Transaction Data
    $user_id = $user->id;
    $for = Transaction::FOR_FREELANCER;

    $transactions = Transaction::search(compact('user_id', 'from', 'to', 'type', 'contract_id', 'project_id', 'for'));

    $_s = Transaction::getStatement(array(
        'user_id' => $user->id, 
        'from'    => $from,
        'to'      => $to
      ));

    $statement = array(
        'beginning' => $_s['beginning'],
        'debits'   => $_s['out'], 
        'credits'  => $_s['in'], 
        'change'   => $_s['change'], 
        'ending'   => $_s['ending']
      );

    // Contract Selector
    $contracts = Contract::freelancer_contracts_selector_data($user->id, $contract_id, $project_id, ['start'=>$dates['from'], 'end'=>$dates['to']]);    

    // Check if period is week or month or year
    $periodUnit = getPeriodUnit($from, $to, 'Y-m-d H:i:s');
    $prev = $next = "";
    if ($periodUnit) {
      $p_first = strtotime("-1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $p_first);
      $prev = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));

      $n_first = strtotime("+1 {$periodUnit}", strtotime($from));
      $range = call_user_func($periodUnit."Range", $n_first);

      if ($range[0]>date('Y-m-d H:i:s')) {
        // Disabled Next
      } else {
        $next = date("M d, Y", strtotime($range[0])) . " - " . date("M d, Y", strtotime($range[1]));
      }
    }


    return view('pages.freelancer.report.transaction_history', [
      'page'        => 'freelancer.report.transaction_history',
      'contracts'   => $contracts,
      'type'        => $type, 
      'dates'       => $dates,  
      'transactions'=> $transactions, 
      'statement'   => $statement, 

      'prev'  => $prev, 
      'next'  => $next, 

      'balance'     => $user->myBalance(),
    ]);

  }

  protected function getPeriodUnit($from, $to, $format) {
    //Week
    list($w_from, $w_to) = weekRange($from);
    $w_from = date_format(date_create($w_from), $format);
    $w_to   = date_format(date_create($w_to), $format);
    if ($w_from == $from && $w_to == $to ) {
      return "week";
    }

    //Month
    list($m_from, $m_to) = monthRange($from);
    $m_from = date_format(date_create($m_from), $format);
    $m_to   = date_format(date_create($m_to), $format);
    if ($m_from == $from && $m_to == $to ) {
      return "month";
    }

    //Year
    list($y_from, $y_to) = yearRange($from);
    $y_from = date_format(date_create($y_from), $format);
    $y_to   = date_format(date_create($y_to), $format);
    if ($y_from == $from && $y_to == $to ) {
      return "year";
    }

    return false;

  }

  /**
   * Get Period Lists between two dates ($from, $to)
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   * @param  $from, $to : Range of Date.
   *         $mode : week - in Week, month - in Month
   * @return Response
   *         List of Period
   *
   * getPeriodList("2016-03-23", "2016-03-29", "w")
   *    return: array(0=>[2016-03-21, 2016-03-27], 
   *                  1=>[2016-03-28, 2016-04-02])
   */
  protected function getPeriodList($from, $to, $mode) {
    $list = array();

    if ($mode == 'week') {
      $from_week = weekRange($from);
      $to_week   = weekRange($to);

      $list[] = $from_week;
      $next_week = $from_week;

      while ($next_week[1] < $to_week[1]) {
        $next_week = weekRange(strtotime("+4 days", strtotime($next_week[1])));
        $list[] = $next_week;
      }
    }
    else if ($mode == 'month') {
      $from_month = monthRange($from);
      $to_month   = monthRange($to);

      $list[] = $from_month;
      $next_month = $from_month;

      while ($next_month[1] < $to_month[1]) {
        $next_month = monthRange(strtotime("+15 days", strtotime($next_month[1])));
        $list[] = $next_month;
      }
    }

    return $list;
  }
}