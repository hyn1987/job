<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Auth;
use Storage;
use Config;

// Models
use Wawjob\User;
use Wawjob\Faq;
use Wawjob\Category;

//DB
use DB;

class FaqController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Retrieve Faq list
   * @author Brice
   * @since April 03, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function all(Request $request)
  {
    $user = Auth::user();
    $type = array(1);
    if ($user->isBuyer()) {
      $type[1] = 0;
    }else if ($user->isFreelancer()) {
      $type[1] = 2;
    }
    
    $cat = Category::byType(Category::TYPE_FAQ);
    $my_cat = Faq::getCatCount($type);
    $catId = 0;
    foreach ($cat as &$c) {
      if (isset($my_cat[$c['id']])) {
        $c['cnt'] = $my_cat[$c['id']];  
      } else {
        $c['cnt'] = 0;
      }
      if ($catId == 0 && $c['cnt'] != 0) {
        $catId = $c['id'];
      }
    }
    $faq_list = Faq::get($catId, $type);
    return view('pages.faq.list', [
                'page'       => 'faq.list',
                'faq_list'   => $faq_list,
                'categories' => $cat,
                'catid'      => $catId,
              ]);
  }
  /**
   * load faqs with the category id.
   *
   * @return Response
   */
  public function load(Request $request)
  {
    $user = Auth::user();
    $type = array(1);
    if ($user->isBuyer()) {
      $type[1] = 0;
    }else if ($user->isFreelancer()) {
      $type[1] = 2;
    }
    $cat_id = $request->input('cat_id');
    $result = Faq::get($cat_id, $type);
    foreach ($result as &$faq) {
      $faq["title"] = parse_multilang($faq["title"], "CH");
      $faq["content"] = parse_multilang($faq["content"], "KP");
    }
    return response()->json([
      'status' => count($result) == 0? 'fail' : 'success',
      'faqs' => $result,
    ]);
  }
}