<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

use Config;

// Models
use DB;
use Wawjob\User;
use Wawjob\Contract;
use Wawjob\UserContact;
use Wawjob\Role;
use Wawjob\Country;
use Wawjob\Timezone;

class ContractController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show the Contract detail page
   *
   * @author Ray
   * @since Mach 24, 2016
   * @version 1.0 initial (by Ray)
   * @param Request
   * @return Response
   */
  public function details(Request $request, $id = 0)
  {
    
    $contract = Contract::find($id);
    if ($contract) {

      return view('pages.admin.contract.details', [
        'page' => 'contract.details',
        'css' => 'contract.details',
        'u' => $contract,
      ]);  

    } else {
      return redirect()->route('admin.contract.list');     
    }
    
  }

  /**
   * Search the Contract.
   *
   * @author Ray
   * @since Mach 4, 2016
   * @version 1.0 initial (by Ray)
   * @param Request
   * @return Response
   */
  public function search(Request $request)
  {

    // Get the settings from config.
    $per_page = Config::get('settings.admin.per_page');

    // compose search rules.
    $search_rules = [
      ['contracts.id', '=', 'id'],
      ['contracts.title', 'like', 'title', '%{$$$}%'],
      ['contracts.type', '=', 'type'],
      ['contracts.status', '=', 'status'],
      ['b.username', '=', 'buyer'],
      ['l.username', '=', 'lancer'],
    ];

    // $contract_model = User::query();
    $contract_model = Contract::leftJoin('users as b', 'b.id', '=', 'contracts.buyer_id')
      ->leftJoin('users as l', 'l.id', '=', 'contracts.contractor_id')
      ->select('contracts.*')
      ;

    foreach($search_rules AS $rule) {
      $v = '';
      if (count($rule) < 4) {
        $v = $request->input($rule[2]);        
      } else {
        $v = str_replace('{$$$}', $request->input($rule[2]), $v);
      }

      // let's exclude # for contract number
      if ($rule[2] == 'id') {
        if(strpos($v, '#') === 0) $v = str_replace('#', '', $v);
        $v = intval($v);
        if ($v === 0) $v = '';

      }

      if ($v != '') {
        $contract_model = $contract_model->where($rule[0], $rule[1], $v);
      }
    }

    $contracts = $contract_model->groupBy('contracts.id')->paginate($per_page);

    $request->flashOnly('id', 'title', 'type', 'status', 'buyer', 'lancer');
    return view('pages.admin.contract.list', [
      'page' => 'contract.list',
      'css' => 'contract.list',
      'contracts' => $contracts,
      'per_page' => $per_page,
    ]);  
  }

  /**
   * Update the contract status
   *
   * @author Ray
   * @since Mach 12, 2016
   * @version 1.0 initial (by Ray)
   * @param Request
   * @return Response
   */
  public function update_status(Request $request) {
    $contract_id = $request->input('i');
    $status = $request->input('s');

    $contract = Contract::find($contract_id);   

    $is_success = false;
    $message = '';

    if (!$contract) {
      // Contract is not existing, so stop.
      $message = 'The update action is invalid. The contract is not existing.';
    } else if(!$this->is_possible_update_status($contract->status, $status)) {
      // Contract is existing, but not a vaild update
      $message = 'The update action is not permitted. The status to be updated is not valid.';
    } else {
      // $status = ($status == '0')?'4':$status;
      $contract->status = $status;
      $is_success = true;
      $message = 'The contract status has been changed.';
      $contract->save();
    }

    if ( !$request->ajax() ) {
      if ($is_success) {
        $request->session()->flash('messages', [$message]);  
      } else {
        $request->session()->flash('errors', [$message]);  
      }
      return redirect()->route('admin.contract.list');     

    } else {

      return response()->json([
        'success' => $is_success,
        'msg' => $message,
        'i' => $contract_id,
        's' => $status,
        'sc' => app('translator')->trans('common.contract.status-icon.' . $status),
      ]);

    }

    
  }


  /**
   * Check if this is valid status update
   *
   * @author Ray
   * @since Mach 13, 2016
   * @version 1.0 initial (by Ray)
   * @param $current, $todo
   * @return boolean
   */
  private function is_possible_update_status($current, $todo) {

    $possible_status = [
      '0' => [3],
      '1' => [4, 2, 3],
      '2' => [4, 1, 3],
      '3' => [4, 1],
    ];

    if (array_key_exists($current, $possible_status)) {
      if (in_array($todo, $possible_status[$current])) {
        return true;
      }
    }
    return false;
  }


}