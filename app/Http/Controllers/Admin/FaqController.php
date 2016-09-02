<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

// Models
use Wawjob\Category;
use Wawjob\Faq;
class FaqController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all categories.
   *
   * @return Response
   */
  public function all()
  {
    $data = [];
    $data = Category::byType(Category::TYPE_FAQ);
    
    return view('pages.admin.system.faq.list', [
      'page' => 'faq.list',
      'css' => 'faq.list',
      'data' => $data,
      
      'j_trans'=> [
        'remove_faq' => trans('j_message.admin.faq.remove_faq'), 
      ]

    ]);
  }

  /**
   * load faqs with the category id.
   *
   * @return Response
   */
  public function load(Request $request)
  {
    $cat_id = $request->input('cat_id');
    $result = Faq::get($cat_id);
    
    return response()->json([
      'status' => count($result) == 0? 'fail' : 'success',
      'faqs' => $result,
    ]);
  }

  /**
   * Save the changes(ajax).
   *
   * @return Response
   */
  public function save(Request $request)
  {
    $data = $request->input('changes');
    $result = Faq::saveModified($data);
    
    return response()->json([
      'status' => is_array($result)?'success':'fail',
      'reflects' => $result,
    ]);
  }
}