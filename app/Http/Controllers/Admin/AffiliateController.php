<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

// Models
use Wawjob\AffiliateSetting;
class AffiliateController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show Affiliate Setting values.
   *
   * @return Response
   */
  public function edit()
  {
    $affiliate = AffiliateSetting::first();
    
    return view('pages.admin.system.affiliate.edit', [
      'page' => 'affiliate.edit',
      'data' => $affiliate,
      'j_trans'=> [
        'affiliate_update' => trans('j_message.admin.affiliate.update'),
        'affiliate_saved' => trans('j_message.admin.affiliate.saved'),
      ]

    ]);
  }

  /**
   * Save the changes(ajax).
   *
   * @return Response
   */
  public function update(Request $request)
  {
    $affiliate = AffiliateSetting::findOrNew($request->input('affiliate_id'));
    $affiliate->percent = $request->input('percent');
    $affiliate->duration = $request->input('duration');

    $affiliate->save();
    
    return response()->json([
      'status' => 'success',
    ]);
  }
}