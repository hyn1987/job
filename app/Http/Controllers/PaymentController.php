<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Storage;
use Config;

// Models
use Wawjob\User;
use Wawjob\Notification;
use Wawjob\UserNotification;
use Wawjob\Transaction;

//DB
use DB;

class PaymentController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Charging Page
   *
   * @author nada
   * @since Apr 01, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function charge(Request $request) {
    $user = Auth::user();

    if ($request->isMethod('post')) {
      
      $amount = priceRaw($request->input('charge_amount'));

      Transaction::charge($user->id, $amount);

      add_message("You charged $".formatCurrency($amount).", succesfully.", 'success');
      // Notification
      Notification::send(Notification::USER_CHARGE, 
                             SUPERADMIN_ID,
                             $contract->contractor_id, 
                             ["amount"      => formatCurrency($amount)]);

      return redirect()->route('report.budgets');
    }

    return view('pages.buyer.payment.charge', [
      'page'        => 'buyer.payment.charge',
      'balance'     => $user->myBalance(), 

      'j_trans'       => [
          'charge' => trans('j_message.buyer.payment.charge'), 
        ]
    ]);
  }
  /**
   * Withdraw Page
   *
   * @author Ri CholMin
   * @since Apr 06, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function withdraw(Request $request) {
    $user = Auth::user();

    if ($request->isMethod('post')) {
      $amount = floatval($request->input('withdraw_amount'));
      $amount = round($amount * 100) / 100;

      if ( Transaction::withdraw($user->id, $amount) ){
        add_message("You withdraw $".formatCurrency($amount).", succesfully.", 'success');
        return redirect()->route('report.transactions');
      }else{
        add_message("You couldn't withdraw this amount.", 'danger');
      }
      
    }

    return view('pages.freelancer.payment.withdraw', [
      'page'        => 'freelancer.payment.withdraw',
      'balance'     => $user->myBalance(), 

      'j_trans'=> [
        'withdraw' => trans('j_message.freelancer.payment.withdraw'), 
      ]
    ]);
  }
}