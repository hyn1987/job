<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;

use Illuminate\Http\Request;

use Config;

// Models
use Wawjob\User;
use Wawjob\UserContact;
use Wawjob\Role;
use Wawjob\Country;
use Wawjob\Timezone;
use Wawjob\Wallet;

class UserController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
    $countries = Country::all();
    $timezones = Timezone::all();

    view()->share('userStatusList', User::$userStatusList);
    // view()->share('userTypeList', User::$userTypeList);
    view()->share('userTypeList', Role::whereNotNull('parent_id')->get());
    view()->share('countries', $countries);
    view()->share('timezones', $timezones);
  }

  /**
   * Show all users.
   *
   * @author Ray
   * @since Mach 4, 2016
   * @version 1.0 initial (by Ray)
   * @param Request
   * @return Response
   */
  public function all(Request $request)
  {
    // Get the parameters.
    $username = $request->input('username');
    $email = $request->input('email');
    $user_type = $request->input('user_type');
    $status = $request->input('status');

    $user_model = User::query();

    // if user_type is defined, then need to left join with role-rel table
    if ($user_type != '') {
      $user_model = User::leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                      ->where('users_roles.role_id', '=', $user_type);
    }

    if ($username) {
      $user_model->where('username','like', '%' . $username . '%');
    }
    if ($email) {
      $user_model->where('email','like', '%'.$email.'%' );
    } 
    if ($status != '') {
      $user_model->where('status', $status );
    }

    $users = $user_model->groupBy('users.id')->paginate($this->per_page);

    $request->flashOnly('username', 'email', 'status', 'user_type');
    // $this->removeBreadcrumb('admin.user.list');
    return view('pages.admin.user.list', [
      'page' => 'user.list',
      'css' => 'user.list',
      'users' => $users,
      'messages' => $request->session()->pull('messages'),
    ]);  
  }

  /**
   * Show add new user page.
   *
   * @author Ray
   * @since Mach 2, 2016
   * @version 1.0 show user edit page (by Ray)
   * @param 
   * @return Response
   */
  public function add()
  {
    
    return view('pages.admin.user.edit', [
      'page' => 'user.add',
      'u' => false,
      'css' => 'user.edit',
      'component_css' => [
        'assets/plugins/bootstrap-datepicker/css/datepicker3',
        'assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min',
      ],
      'role_ids' => [],
      'edit_cell_class' => 'input-group-fancy',
    ]);
  }

  /**
   * Show edit user page.
   *
   * @author Ray
   * @since Mach 2, 2016
   * @version 1.0 show user edit page (by Ray)
   * @param Request
   * @return Response
   */
  public function edit(Request $request, $id = 0)
  {

    $user = User::find($id);

    return view('pages.admin.user.edit', [
      'page' => 'user.edit',
      'u' => $user,
      'css' => 'user.edit',
      'component_css' => [
        'assets/plugins/bootstrap-datepicker/css/datepicker3',
        'assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min',
      ],
      'role_ids' => $user->getRoleIds(),
      'role_slugs' => $user->getRoleSlugs(),
      'messages' => $request->session()->pull('messages'),
      'edit_cell_class' => 'input-group-fancy',
    ]);
  }

  /**
   * Update user info.
   *
   * @author Ray
   * @since Mach 6, 2016
   * @version 1.0 initial (by Ray)
   * @param Request
   * @return Response
   */
  public function update(Request $request) {

    ## error stack where all errors resides
    $error_stack = [];

    ## grab the id of the user
    $id = $request->input('user_id');

    ///////////////////////////////////////////////////
    // let's build validation rules and messages here
    //
    $rules = [
        'username' => 'required|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'same:password_cfm',
        // 'status' => 'required',
        'user_type' => 'required',
      ];
    $rule_messages = [
        'password.same' => 'Password is mismatched with confirming value.',
      ];

    ///////////////////////////////////////////////////
    // update user info & contact info
    //
    if ($id) { // if user id is given

      $user = User::find($id);
      if (!$user) { // if user is not existing, then return to the user list with errors
        $error_stack[] = 'Update failed! No user found.';
        return redirect()->route('admin.user.list')->with('errors', $error_stack);
      }

      ## let's validate user input
      $rules['username'] .= ',' . $id;
      $rules['email'] .= ',' . $id;
      $this->validate($request, $rules, $rule_messages);

      ## save user
      if ($request->input('password')) {
        $user->password = bcrypt($request->input('password'));  
      }

      $user->username = $request->input('username');
      $user->email = $request->input('email');
      // $user->status = $request->input('status');
      $user->syncRoles($request->input('user_type'));
      $user->save();


    } else {
      
      $rules['password'] .= '|required';
      $this->validate($request, $rules, $rule_messages);

      $user = new User;
      
      // Save user credential info.
      $user->username = $request->input('username');
      $user->email = $request->input('email');
      $user->status = $request->input('status');
      $user->password = bcrypt($request->input('password'));
      $user->status = $request->input('status');
      $user->save();

      /*
      $role = Role::where('slug', '=', $request->input('user_type'))->first();
      $user->syncRoles($role->id);
      */

      // Wallet
      $wallet = new Wallet;
      $wallet->user_id  = $user->id;
      $wallet->save();      
    }

    // update contact information
    $contact = UserContact::firstOrCreate(['user_id' => $user->id]);
    $contact->first_name = $request->input('first_name');
    $contact->last_name = $request->input('last_name');
    $contact->birthday = $request->input('birthday');
    $contact->gender = $request->input('gender');
    $contact->country_code = $request->input('country');
    $contact->timezone_id = $request->input('timezone');
    $contact->city = $request->input('city');
    $contact->address = $request->input('address');
    $contact->address2 = $request->input('address2');
    $contact->zipcode = $request->input('zipcode');
    $contact->phone = $request->input('phone');
    $contact->fax = $request->input('fax');
    $contact->save();
    
    $request->session()->flash('messages', ['User information has been successfuly updated']);
    return redirect()->route('admin.user.list');
    
    /*
    $request->session()->flash('messages', ['User information has been successfuly updated']);
    return redirect()->route('admin.user.edit', $user->id);// ->with('messages', ['User information has been successfuly updated']);
    */

    
  }


  /**
   * Do user ajax action
   *
   * @author Ray
   * @since April 7, 2016
   * @version 1.0 initial (by Ray)
   * @param Request
   * @return Response
   */
  public function ajax_action(Request $request) {

    $user_id = $request->input('i');
    $type = $request->input('s');
    $new_status = $request->input('sv');
    $message = $request->input('m');

    $user = User::find($user_id);   

    $is_success = false;
    $message = '';

    if (!$user) {
      // Contract is not existing, so stop.
      $message = 'The user is not existing.';
    } else {

      if ($type == 'message') {
        // Send message logic comes here
        $is_success = true;
        $message = 'The message has been sent successfuly.';

      } else if ($type == 'status') {
        // Status update logic is following

        if(!$this->is_possible_update_status($user->status, $new_status)) {
          // user is existing, but not a vaild update
          $message = 'The status to be updated is not valid.';
        } else {
          // $status = ($status == '0')?'4':$status;
          $user->status = $new_status;
          $is_success = true;
          $message = 'The user status has been changed.';
          $user->save();
        }

      }

    }

    if ( !$request->ajax() ) {
      if ($is_success) {
        $request->session()->flash('messages', [$message]);  
      } else {
        $request->session()->flash('errors', [$message]);  
      }
      return redirect()->route('admin.user.list');     

    } else {

      return response()->json([
        'success' => $is_success,
        'msg' => $message,
        'i' => $user_id,
        's' => $type,
        'sv' => $new_status,
        'svl' => trans('common.user.status.' . $new_status ),
      ]);

    }

    
  }

  /**
   * Check if this is valid status update
   *
   * @author Ray
   * @since April 7, 2016
   * @version 1.0 initial (by Ray)
   * @param $current, $todo
   * @return boolean
   */
  private function is_possible_update_status($current, $todo) {

    $possible_status = [
      '0' => [1, 9],        // in active
      '1' => [2, 3, 4, 9],  // active
      '2' => [1, 3, 4, 9],     // block
      '3' => [1, 2, 4, 9],        // f-block
      '4' => [1, 2, 3, 9],        // suspend
      '9' => [],        // closed
    ];

    if (array_key_exists($current, $possible_status)) {
      if (in_array($todo, $possible_status[$current])) {
        return true;
      }
    }
    return false;
  }


}