<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

// Models
use Wawjob\Skill;

class FeeController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show all skills.
   *
   * @return Response
   */
  public function all()
  {
    $skills = Skill::withTrashed()->orderBy('name', 'asc')->get();

    return view('pages.admin.system.fee.settings', [
      'page' => 'fee.settings',
      'css' => 'fee.settings',
      'component_css' => [
        '/assets/plugins/ion.rangeslider/css/ion.rangeSlider',
        '/assets/plugins/ion.rangeslider/css/ion.rangeSlider.Metronic'], 

      'fees' => $skills,
    ]);
  }

}