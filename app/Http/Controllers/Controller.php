<?php namespace Wawjob\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Auth;
use Storage;
use Config;
use App;

// Models
use Wawjob\UserNotification;

abstract class Controller extends BaseController {

  use DispatchesCommands, ValidatesRequests;
  /**
   * Constructor
   */
  public function __construct() {
	// Do something with authenticated user.
    $user = Auth::user();
    // Share the global vars with view.
    if ($user) {
      $unread_notifications = UserNotification::getUnread($user->id);
      $system_notifications = UserNotification::getSystem($user->id);

      $unread_cnt = count($unread_notifications);
      view()->share([
        'unread_notifications' => $unread_notifications,
        'unread_cnt' => $unread_cnt == 0 ? '' : $unread_cnt,
        'system_notifications' => $system_notifications,
      ]);

      // Locale 
      $locale = $user->getLocale();
      if ($locale) {
        App::setLocale($locale);
      }
    }
    view()->share('current_user', $user ? $user : false);

    
  }

/**
  * Return failure flag to ajax caller
  *
  * @author paulz
  * @created Mar 22, 2016
  */
  protected function failed($msg = '') {
    return response()->json([
      'success' => false,
      'msg' => $msg
    ]);
  }

}