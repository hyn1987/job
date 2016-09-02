<?php namespace Wawjob\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Auth;
use Config;


abstract class AdminController extends BaseController {

  use DispatchesCommands, ValidatesRequests;

  /**
   * The authenticated user.
   *
   * @var User Model
   */
  protected $auth_user;

  /**
   * The flag if the user own super admin role.
   *
   * @var boolean
   */
  protected $is_super_admin = false;

  protected static $tmp;

  /**
   * Constructor
   */
  public function __construct() {
    $this->avatar_size = Config::get("settings.admin.avatar_size");
    $this->per_page = Config::get('settings.admin.per_page');

    // Do something with authenticated user.
    $this->auth_user = Auth::user();
    $this->is_super_admin = $this->auth_user->isSuperAdmin();

    // Share the global vars with view.
  	view()->share([
      'auth_user' => $this->auth_user,
      'is_super_admin' => $this->is_super_admin,

      'avatar_size' => $this->avatar_size,
      'per_page' => $this->per_page
    ]);
  }

  /**
  * Return failure flag to ajax caller
  *
  * @author paulz
  * @created Mar 9, 2016
  */
  protected function failed($msg = '') {
    return response()->json([
      'success' => false,
      'msg' => $msg
    ]);
  }
}