<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

use Auth;

use Wawjob\User;
use Wawjob\Contract;
use Wawjob\Transaction;

class ReportController extends AdminController {

  public function transaction(Request $request, $cid)
  {
    //Transaction::payHourly(4);
    //Transaction::charge(7, 110);
    //Transaction::withdraw(9, 100);
    /*Transaction::pay([
      'cid' => 5,
      'amount' => 10,
      'type' => 'refund',
      'note' => 'Over-tracked hourly time.'
    ]);
    */
    //Transaction::processPending();
    //exit;

    if ( !$cid ) {
      return false;
    }

    // Date range
    $date_range = $request->input('date_range');
    if ( !$date_range ) {
      $date_range = date("M d, Y", strtotime("-7 days")) . " - " . date("M d, Y");
    }

    $dates = parseDateRange($date_range);

    // Transaction type
    $type = $request->input('t_type');

    // Transaction for
    $for = $request->input('t_for');

    // Contract
    $contract = Contract::find($cid);
    if (!$contract) {
      abort(404);
    }

    $rows = Transaction::search([
      'type' => $type,
      'for' => $for,
      'contract_id' => $cid,
      'from' => $dates[0] . " 00:00:00",
      'to' => $dates[1] . " 23:59:59",
    ]);

    $request->flashOnly(['t_for', 't_type']);

    return view('pages.admin.report.transaction', [
      'page' => 'report.transaction',
      'css' => 'report.transaction',
      'component_css' => [
        'assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3',
      ],
      'rows' => $rows,
      'options' => [
        'for' => Transaction::getForOptions(),
        'type' => Transaction::getTypeOptions($contract->isHourly() ? 'hourly_contract' : 'fixed_contract')
      ],
      'date_range' => $date_range
    ]);
  }

  /**
   * User Transactions Page
   *
   * @author brice
   * @since Mar 30, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function user_transaction(Request $request, $uid)
  {
    $user = User::find($uid);
    $user_id = $uid;
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
      $t = weekRange();

      $dates['from'] = date_format(date_create($t[0]), "M d, Y");
      $dates['to']   = date_format(date_create($t[1]), "M d, Y");
    }

    // Transactions Data
    $from = date_format(date_create($dates['from']), "Y-m-d 00:00:00");
    $to   = date_format(date_create($dates['to']), "Y-m-d 23:59:59");
    if ($user->isBuyer()) {
      $for = Transaction::FOR_BUYER;  
    } else if ($user->isFreelancer()) {
      $for = Transaction::FOR_FREELANCER;
    }
    

    $transactions = Transaction::search(compact('user_id', 'from', 'to', 'type', 'contract_id', 'project_id', 'for'));
    //dd($transactions);

    $_s = Transaction::getStatement(array(
        'user_id' => $uid, 
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
    $contracts = Contract::buyer_contracts_selector_data($uid, $contract_id, $project_id, ['start'=>$dates['from'], 'end'=>$dates['to']]);    

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

    return view('pages.admin.report.usertransaction', [
      'page'        => 'report.usertransaction',
      'css'         => 'report.usertransaction',
      'component_css' => [
        'assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3',
        'assets/plugins/bootstrap-select/bootstrap-select.min',
      ],
      'role_ids' => $user->getRoleIds(),
      'role_slugs' => $user->getRoleSlugs(),
      'contracts'   => $contracts,
      'u'           => $user,
      'type'        => $type, 
      'dates'       => $dates,  
      'transactions'=> $transactions, 
      'statement'   => $statement,
      'prev'  => $prev, 
      'next'  => $next, 
      'balance'     => $statement['ending'],  // Test
    ]);

  }
}