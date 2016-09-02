<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Auth;
use Storage;
use Config;
use Session;
use Exception;

// Models
use Wawjob\User;
use Wawjob\Role;
use Wawjob\Project;
use Wawjob\ProjectApplication;
use Wawjob\ProjectMessageThread;
use Wawjob\ProjectMessage;
use Wawjob\Contract;
use Wawjob\ContractFeedback;
use Wawjob\HourlyLogMap;
use Wawjob\Transaction;
use Wawjob\Notification;

class ContractController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * My All Contracts Page
   *
   * @author nada
   * @since Mar 15, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function all_contracts(Request $request)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->all_contracts_buyer($request);
    }
    else if ($user->isFreelancer()) {
      return $this->all_contracts_freelancer($request);
    }
  }
  protected function all_contracts_buyer(Request $request)
  {
    $user = Auth::user();

    $contracts = Contract::where('buyer_id','=', $user->id)
                      ->where('status', '<>', Contract::STATUS_OFFER)
                      ->orderBy('status', 'asc')
                      ->orderBy('ended_at', 'desc')
                      ->orderBy('started_at', 'desc')
                      ->paginate(10);

    return view('pages.buyer.contract.all_contracts', [
      'page'        => 'buyer.contract.all_contracts',
      'contracts'   => $contracts,
    ]);
  }

  /**
   * My Freelancers (my-freelancers)
   *
   * @author nada
   * @since Apr 04, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function my_freelancers_buyer(Request $request) {
    $user = Auth::user();

    $query = Contract::where('buyer_id', '=', $user->id)
                         ->where('status', '>', Contract::STATUS_OFFER)
                         ->orderBy('status', 'ASC')
                         ->orderBy('ended_at', 'DESC')
                         ->orderBy('started_at', 'DESC');

    $query_c = clone $query;
    $query_p = clone $query;
    $query_cc= clone $query;

    $projects  = array();
    $_pc = $query_p->groupBy('project_id')->get();
    foreach ($_pc as $_c) {
      $project = Project::find($_c->project_id);
      if ($project) {
        $projects[] = $project;
      }
    }

    $filter_project = 0;
    if ($request->isMethod('post')) {
      if ($request->input('project')) {
        $filter_project = $request->input('project');
      }
    }

    $l_contracts = array();     //Last Contract for contract ID;
    if ($filter_project == 0) {
      $l_contracts = $query_c->groupBy('contractor_id')
                           ->paginate(5);
    } else {
      $l_contracts = $query_c->where('project_id', '=', $filter_project)
                           ->groupBy('contractor_id')
                           ->get();
    }

    $contractors = $l_contracts;
    $cc = $query_cc->get()->groupBy('contractor_id');
    foreach ($contractors as &$c) {
      $_qc = clone $query_cc;
      $cc = $_qc->where('contractor_id', '=', $c->contractor_id)->get();

      $c->contracts = $cc;
      $c->user_score = $c->contractor->totalScore();
    }
    //dd($contractors);

    return view('pages.buyer.contract.my_freelancers', [
        'page'          => 'buyer.contract.my_freelancers',
        'contractors'   => $contractors,
        'filter_project'=> $filter_project, 
        'projects'      => $projects, 
      ]);
  }

  /**
   * My Contracts Page (my-contracts)
   *
   * @author Ri Chol Min
   * @since Mar 10, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function all_contracts_freelancer(Request $request)
  {
    $user = Auth::user();

    try {
      if ( $request->input('search_contracts') != null && $request->input('search_contracts') != '' ){
        $contracts = Contract::where('contractor_id', $user->id)->whereIn('status', [ Contract::STATUS_OPEN, Contract::STATUS_CLOSED ])->where('title', 'like', '%' . $request->input('search_contracts') . '%')->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(10);
      }else{
        $contracts = Contract::where('contractor_id', $user->id)->whereIn('status', [ Contract::STATUS_OPEN, Contract::STATUS_CLOSED ])->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(10);
      }

      return view('pages.freelancer.contract.my_contracts', [
        'page'        => 'freelancer.contract.my_contracts',
        'contracts'   => $contracts,
        'sort'        => $request->input('search_contracts')
      ]);
    }
    catch(ModelNotFoundException $e) {
    }
  }
  
  /**
   * Contract View Page (contract-view) [Buyer]
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function contract_view(Request $request, $contract_id)
  {
    $user = Auth::user();
    if ($user->isBuyer()) {
      return $this->contract_view_buyer($request, $contract_id);
    } elseif ($user->isFreelancer()) {
      return $this->contract_view_freelancer($request, $contract_id);
    }
  }
  protected function contract_view_buyer(Request $request, $contract_id)
  {
    $user = Auth::user();

    try {

      $contract = Contract::findOrFail($contract_id);
      if (!$contract->checkIsAuthor($user->id)) {
        throw new Exception();
      }

      $contract->calcWeekMinutes();

      $contractFeedbackCol = ContractFeedback::where('contract_id', '=', $contract_id)->get();

      if ( $request->isMethod('post') && $request->input('close_action') == 1  ){
        $contract->status = Contract::STATUS_CLOSED;
        $contract->ended_at = date('Y-m-d H:i:s');
        $contract->save();
      }
      else if ( $request->isMethod('post') && $request->input('_action') == 'payment'  ){
        $amount = str_replace(array(",", " "), array("", ""), $request->input('payment_amount'));
        $type   = $request->input('payment_type');
        $note   = $request->input('payment_note');

        if ($amount > 0) {
          if (Transaction::pay(array(
                'cid'     => $contract_id, 
                'amount'  => $amount, 
                'type'    => $type, 
                'note'    => $note, 
              ))) 
          {
            add_message(trans('message.buyer.payment.contract.success_paid', ['amount'=>formatCurrency($amount)]), 'success');
          }
        } else {
          add_message(trans('message.buyer.price.lt_zero'), "danger");
        }
      }

      $paid = array();
      $total_paid = 0;
      if ($contract->type == Project::TYPE_FIXED) {
        $paid = Transaction::search(array(
          'user_id' => $user->id, 
          'contract_id' => $contract->id, 
          'for' => Transaction::FOR_BUYER, 
        ));
        foreach ($paid as $t) {
          $total_paid += $t->amount;
        }
      }
      else if ($contract->type == Project::TYPE_HOURLY) {
        $paid = Transaction::search(array(
          'user_id' => $user->id, 
          'contract_id' => $contract->id, 
          'type' => [Transaction::TYPE_BONUS, Transaction::TYPE_REFUND], 
          'for' => Transaction::FOR_BUYER, 
        ));
        foreach ($paid as $t) {
          $total_paid += $t->amount;
        }
      }

      return view('pages.buyer.contract.contract_view', [
        'page'       => 'buyer.contract.contract_view',
        'contract'   => $contract,
        'paid_transactions' => $paid, 
        'total_paid' => $total_paid, 
        'contractFeedback'   => $contractFeedbackCol->first(),
      ]);
    }
    catch(ModelNotFoundException $e) {
      return redirect()->route('contract.all_contracts');
    }
    catch(Exception $e) {
      // Not found Job
      return redirect()->route('job.my_jobs');
    }
  }

  /**
   * Contract Detail Page (contract-detail)
   *
   * @author Ri Chol Min
   * @since Mar 14, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function contract_view_freelancer(Request $request, $contract_id)
  {
    $user = Auth::user();

    try {

      $contract = Contract::findOrFail($contract_id);
      $contract->calcWeekMinutes();

      $contractFeedbackCol = ContractFeedback::where('contract_id', '=', $contract_id)->get();

      $paid = array();
      $total_paid = 0;
      if ($contract->type == Project::TYPE_FIXED) {
        $paid = Transaction::search(array(
          'user_id' => $user->id, 
          'contract_id' => $contract->id, 
          'for' => Transaction::FOR_FREELANCER, 
        ));
        foreach ($paid as $t) {
          $total_paid += $t->amount;
        }
      }
      else if ($contract->type == Project::TYPE_HOURLY) {
        $paid = Transaction::search(array(
          'user_id' => $user->id, 
          'contract_id' => $contract->id,
          'type' => [Transaction::TYPE_BONUS, Transaction::TYPE_REFUND], 
          'for' => Transaction::FOR_FREELANCER, 
        ));
        foreach ($paid as $t) {
          $total_paid += $t->amount;
        }
      }
      
      if ( $request->isMethod('post') && $request->input('close_action') == 1  ){
        $contract->status = Contract::STATUS_CLOSED;
        $contract->ended_at = date('Y-m-d H:i:s');
        $contract->save();
        add_message(trans('message.freelancer.contract.close.success_close'), 'success');
      }else if ( $request->isMethod('post') && $request->input('_action') == 'payment'  ){
        $amount = str_replace(array(",", " "), array("", ""), $request->input('payment_amount'));
        $type   = $request->input('payment_type');
        $note   = $request->input('payment_note');

        if ($amount > 0 && $amount < $total_paid ) {
          if (Transaction::pay(array(
                'cid'     => $contract_id, 
                'amount'  => $amount, 
                'type'    => $type, 
                'note'    => $note, 
              ))) 
          {
            //add_message("You refunded $".formatCurrency($amount).", successfully.", 'success');
            add_message(trans('message.freelancer.payment.contract.success_refund', ['amount'=>formatCurrency($amount)]), 'success');
          }
        } else {
          add_message("Amount must between from zero to paid amount.", "danger");
        }
      }

      $paid = array();
      $total_paid = 0;
      if ($contract->type == Project::TYPE_FIXED) {
        $paid = Transaction::search(array(
          'user_id' => $user->id, 
          'contract_id' => $contract->id, 
          'for' => Transaction::FOR_FREELANCER, 
        ));
        foreach ($paid as $t) {
          $total_paid += $t->amount;
        }
      }
      else if ($contract->type == Project::TYPE_HOURLY) {
        $paid = Transaction::search(array(
          'user_id' => $user->id, 
          'contract_id' => $contract->id,
          'type' => [Transaction::TYPE_BONUS, Transaction::TYPE_REFUND], 
          'for' => Transaction::FOR_FREELANCER, 
        ));
        foreach ($paid as $t) {
          $total_paid += $t->amount;
        }
      }

      return view('pages.freelancer.contract.contract_detail', [
        'page'              => 'freelancer.contract.contract_detail',
        'contract'          => $contract,
        'paid_transactions' => $paid,
        'total_paid'        => $total_paid,
        'contractFeedback'  => $contractFeedbackCol->first(),
      ]);

    } catch(ModelNotFoundException $e) {

    }
  }

  /**
   * Contract Feedback Create 
   *
   * @author So
   * @since Mar 27, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function feedback(Request $request, $id)
  {
    $user = Auth::user();
    $arryInputs = $request->all();

    $status = Contract::find($id)->status;
    $contractFeedback = ContractFeedback::where('contract_id', $id)->first();

    if ( empty($arryInputs) ) {
      if ( $user->isBuyer() ) {
        if ($contractFeedback)
          $rate = $contractFeedback->freelancer_score;
        else
          $rate = 0;

        return view('pages.buyer.contract.feedback', [
                      'page'       => 'buyer.contract.feedback',
                      'contractId'   => $id,
                      'status'   => $status,
                      'rate'   => $rate,
                    ]);
      } else if ( $user->isFreelancer() ) {
        if ($contractFeedback)
          $rate = $contractFeedback->buyer_score;
        else
          $rate = 0;

        return view('pages.freelancer.contract.feedback', [
                      'page'       => 'freelancer.contract.feedback',
                      'contractId'   => $id,
                      'status'   => $status,
                      'rate'   => $rate,
                    ]);
      }
    } else {

      $contract = Contract::find($id);
      $projectApplication = ProjectApplication::find($contract->application_id);
      $contractFeedback = ContractFeedback::firstOrNew(['contract_id' => $id]);

      if ( $user->isBuyer() ) {

        if ($status != Contract::STATUS_CLOSED) {
          $contract->closed_by = 0;
          $contract->closed_reason = $arryInputs['reason'];
          $contract->status = Contract::STATUS_CLOSED;
          $contract->ended_at = date("Y-m-d H:i:s");
          $contract->save();

          $projectApplication->status = ProjectApplication::STATUS_HIRING_CLOSED;
          $projectApplication->reason = $arryInputs['reason'];
          $projectApplication->save();
        }        

        $contractFeedback->freelancer_score = $arryInputs['rate'];
        $contractFeedback->freelancer_feedback = $arryInputs['feedback'];
        $contractFeedback->save();

        Notification::send(Notification::CONTRACT_CLOSED, 
                             SUPERADMIN_ID,
                             $contract->contractor_id, 
                             ["contract_title" => $contract->title]);

        add_message(trans('message.freelancer.contract.close.success_close'), 'success');

        return redirect()->route('contract.contract_view', ['id'=>$id]);
      } else if ( $user->isFreelancer() ) {

        if ($status != Contract::STATUS_CLOSED) {
          $contract->closed_by = 1;
          $contract->closed_reason = $arryInputs['reason'];
          $contract->status = Contract::STATUS_CLOSED;
          $contract->save();

          $projectApplication->status = ProjectApplication::STATUS_HIRING_CLOSED;
          $projectApplication->reason = $arryInputs['reason'];
          $projectApplication->save();

          Notification::send(Notification::CONTRACT_CLOSED, 
                             SUPERADMIN_ID,
                             $contract->buyer_id, 
                             ["contract_title" => $contract->title]);

        }       

        $contractFeedback->buyer_score = $arryInputs['rate'];
        $contractFeedback->buyer_feedback = $arryInputs['feedback'];
        $contractFeedback->save();

        add_message(trans('message.freelancer.contract.close.success_close'), 'success');

        return redirect()->route('contract.contract_view', ['id'=>$id]);
      }
    }   
  }

  public function ajaxAction(Request $request)
  {
    if ( !$request->ajax() ) {
      return false;
    }

    $cmd = $request->input("cmd");
    if ($cmd == "update_weekly_limit") {
      $cid = $request->input("cid");
      $weekly_limit = $request->input("weekly_limit");

      $ret = Contract::where('id', $cid)->update([
        'limit' => $weekly_limit
      ]);

      // @todo: send_email() both to buyer and contractor

      return response()->json([
        'success' => true,
        'msg' => 'Successfully updated weekly limit.',
        'str_limit' => $weekly_limit . ' ' . str_plural('hour', $weekly_limit)
      ]);
    }

    return response()->json([
      'success' => false,
      'msg' => 'Unknown command.',
    ]);
  }
}