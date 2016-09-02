<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Wawjob\User;
use Wawjob\UserContact;
use Wawjob\UserProfile;
use Wawjob\Country;
use Wawjob\UserAnalytic;
use Wawjob\Wallet;
use Wawjob\UserAffiliate;
use Wawjob\AffiliateSetting;

class AuthController extends Controller {

  /**
   * Constructor
   */
  public function __construct()
  {
    $countries = Country::all();

    view()->share([
      'countries' => $countries,
      'defaults' => [
        'country' => 'CN', // China
        'how_hear' => 1, // Google
      ],
      'how_hear_options' => [
        'Google' => 1,
        'Friends' => 2
      ]
    ]);
  }


  /**
   * Authenticate user info.
   *
   * @author Sunlight
   * @param  Request $request
   * @return Response
   */
  public function login(Request $request)
  {
    // If user input info
    if ($request->isMethod('post')) {
      $checklist = ['username', 'email'];
      $error = 'Invalid user info, please try again.';

      // Gather user information from request.
      $username = $request->input('username');
      $password = $request->input('password');
      $remember = $request->input('remember');

      // Attempt to login to the system.
      foreach ($checklist as $key) {
        $credential = [
          'password' => $password
        ];
        $credential[$key] = $username;

        if (Auth::attempt($credential, $remember)) {
          $user = Auth::user();

          // Log user login
          UserAnalytic::insert([
            'user_id' => $user->id,
            'login_ipv4' => $_SERVER['REMOTE_ADDR'],
            'logged_at' => date("Y-m-d H:i:s")
          ]);

          // Redirect to the welcome pages by user type.
          if ($user->isFreelancer()) {
            return redirect()->route('user.account');
          } else if ($user->isBuyer()) {
            return redirect()->route('job.my_jobs');
          } else {
            return redirect()->route('admin.dashboard');
          }
        }
      }

      // Flash email to the session.
      $request->flashOnly('username', 'remember');
    }

    return view('pages.auth.login', [
      'page' => 'auth.login',
      'error' => isset($error) ? $error : null,
    ]);
  }

	/**
	* Author : Ri Chol Min
	*
	* @param  Request $request
	* @return Response
	*/
	public function signup(Request $request)
	{
    // If user input info
		if ($request->isMethod('post')) {

			// Gather information from request.
			$register_type = $request->input('UserType');
			if ( $register_type == 'F' ){
        return redirect()->route('user.signup.freelancer');
			}else if ( $register_type == 'B' ){
				return redirect()->route('user.signup.buyer');
			}
		}

		return view('pages.auth.signup', [
			'page' => 'auth.signup',
		]);

	}


  /**
  * Author : Ri Chol Min
  *
  * @param  Request $request
  * @return Response
  */
  public function signup_checkusername(Request $request)
  {
    if ($request->isMethod('get')) {
      $duplicated_user = User::where('username', '=', $request->input('username_ajax'))->first();
      if ( !$duplicated_user ){
        return 'success';
      }else{
        return 'error';
      }
    }
  }

    /**
  * Author : Ri Chol Min
  *
  * @param  Request $request
  * @return Response
  */
  public function signup_checkemail(Request $request)
  {
    if ($request->isMethod('get')) {
      $duplicated_user = User::where('email', '=', $request->input('email_ajax'))->first();
      if ( !$duplicated_user ){
        return 'success';
      } else {
        return 'error';
      }
    }
  }

  public function signup_checkfield(Request $request)
  {
    $field = $request->input('field');
    $value = $request->input('value');

    if ( !$field ) {
      return response()->json([
        'success' => false,
        'msg' => 'Invalid field to check.'
      ]);
    }

    $msgs = [
      'username' => "This username already exists.",
      'email' => "This email address already exists."
    ];

    $exists = User::where($field, $value)->exists();
    if ($exists) {
      $msg = $msgs[$field];
    } else {
      $msg = '';
    }

    return response()->json([
      'success' => !$exists,
      'msg' => $msg
    ]);
  }

  /**
  * @author paulz
  * @created Apr 19, 2016
  * @param  Request $request
  * @return Response
  */
  public function signup_freelancer(Request $request)
  {
    // If user input info
    if ($request->isMethod('post')) {

      /*$duplicated_user = User::where('username', $request->input('username'))->count();
      $duplicated_email = User::where('email', $request->input('email'))->count();

      if ($duplicated_user || $duplicated_email) {
        return false;
      }*/

      try{
        // User
        $user = new User;
        $user->email         = $request->input('email');
        $user->username      = $request->input('username');
        $user->password      = bcrypt($request->input('password'));
        //$user->question_id   = $request->input('selectquestion');
        $user->save();

        // Role
        $user->syncRoles(User::ROLE_USER_FREELANCER);

        // Contact
        $contact = new UserContact;
        $contact->user_id       = $user->id;
        $contact->first_name    = $request->input('first_name');
        $contact->last_name     = $request->input('last_name');
        $contact->country_code  = $request->input('country');
        $contact->save();

        // User profile
        $profile = new UserProfile;
        $profile->user_id = $user->id;
        $profile->save();

        // Wallet
        $wallet = new Wallet;
        $wallet->user_id  = $user->id;
        $wallet->save();
        add_message(trans('message.freelancer.signup.success_signup', ['user_id'=>$request->input('username')]), 'success');

        // If the current user has affiliate id
        $token = base64_decode(urldecode($request->input('token')));
        if (is_numeric($token)) {
          $affiliate_info = AffiliateSetting::first();
          $affiliate = new UserAffiliate;
          $affiliate->user_id = $user->id;
          $affiliate->affiliate_id = $token;
          $affiliate->percent = $affiliate_info->percent;
          $affiliate->duration = $affiliate_info->duration;
          $affiliate->created_at = date("Y-m-d H:i:s");
          $affiliate->save();
        }
        return redirect()->route('user.login');

      } catch(Exception $e) {
        add_message($e->getMessage(), 'danger');
      }
    }
    $param = '';
    if ($request->input('token')) {
      $param = '?token=' . $request->input('token');
    }
    return view('pages.auth.signup_freelancer', [
      'page' => 'auth.signup.freelancer',
      'param' => $param,
    ]);

  }

  /**
  * Author : Ri Chol Min
  *
  * @param  Request $request
  * @return Response
  */
  public function signup_buyer(Request $request)
  {
    // If user input info
    if ($request->isMethod('post')) {

      $duplicated_user = User::where('username', $request->input('username'))->count();
      $duplicated_email = User::where('email', $request->input('email'))->count();

      if ( !$duplicated_user && !$duplicated_email ){
        // User
        $user = new User;
        $user->email         = $request->input('email');
        $user->username      = $request->input('username');
        $user->password      = bcrypt($request->input('password'));
        $user->question_id   = $request->input('selectquestion');
        $user->save();

        // Role
        $user->syncRoles(User::ROLE_USER_BUYER);

        // User Contact
        $contact = new UserContact;
        $contact->user_id       = $user->id;
        $contact->first_name    = $request->input('first_name');
        $contact->last_name     = $request->input('last_name');
        $contact->country_code  = $request->input('country');    
        $contact->save();

        // User profile
        $profile = new UserProfile;
        $profile->user_id = $user->id;
        $profile->save();

        // Wallet
        $wallet = new Wallet;
        $wallet->user_id  = $user->id;
        $wallet->save();

        add_message(trans('message.freelancer.signup.success_signup', ['user_id'=>$request->input('username')]), 'success');

        return redirect()->route('user.login');
      }else{
        // Flash email to the session.
        $request->flashOnly('firstname', 'lastname', 'email', 'country', 'username', 'password', 'password2', 'selectquestion');
        
        if ( $duplicated_user &&  $duplicated_email ){
          return view('pages.auth.signup_buyer', [
            'page' => 'auth.signup.buyer', 
            'duplicated_user_error' => 'duplicated-user-error',
            'duplicated_email_error' => 'duplicated-email-error',
          ]);
        }else if ( $duplicated_user ){
          return view('pages.auth.signup_freelancer', [
            'page' => 'auth.signup.freelancer', 
            'duplicated_user_error' => 'duplicated-user-error',
            'duplicated_email_error' => null,
          ]);
        }else if ( $duplicated_email ){
          return view('pages.auth.signup_freelancer', [
            'page' => 'auth.signup.freelancer', 
            'duplicated_user_error' => 'duplicated-user-error',
            'duplicated_email_error' => null,
          ]);
        } 
      }
      
    }

    return view('pages.auth.signup_buyer', [
      'page' => 'auth.signup.buyer',
      'duplicated_user_error' => null,
      'duplicated_email_error' => null,
    ]);

  }

  /**
   * Log the user out of the application.
   *
   * @return Response
   */
  public function logout()
  {
    // Log the user out.
    Auth::logout();

    return redirect()->route('user.login');
  }
}