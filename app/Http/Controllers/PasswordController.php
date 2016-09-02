<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;
use Mail;
use Hash;

use Wawjob\User;
use Wawjob\UserToken;

class PasswordController extends Controller
{
  protected $redirectPath = '/';

  /**
   * Create a new password controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Display the form to request a password reset link.
   *
   * @return Response
   */
  public function getEmail()
  {
    return view('pages.auth.password', [
      'page' => 'auth.password'
    ]);
  }

  /**
   * Send a reset link to the given user.
   *
   * @param  Request  $request
   * @return Response
   */
  public function postEmail(Request $request)
  {
    $validator = Validator::make($request->all(), ['email' => 'required|email']);

    $email = $request->input('email');
    if ($validator->fails()) {
      return view('pages.auth.password', [
        'page' => 'auth.password',
        'email' => $email,
        'success' => false,
        'msg' => $validator->errors()->first('email'),
      ]);
    }

    $user = User::where('email', $email)->first();
    if (!$user) {
      return view('pages.auth.password', [
        'page' => 'auth.password',
        'email' => $email,
        'success' => false,
        'msg' => 'Failed to reset password.',
      ]);
    }

    $token = hash_hmac('sha256', str_random(40), config('auth.password.key'));
    $msg = 'http://www.wawjob.com/password/reset/' . $token;

    $user_token = UserToken::findOrNew($user->id);

    $user_token->user_id = $user->id;
    $user_token->reset_pwd_token = $token;
    $user_token->reset_pwd_at = date('Y-m-d H:i:s');
    $user_token->save();

    Mail::raw($msg, function ($m) use ($email) {
      $m->from('info@wawjob.com');
      $m->to($email)->subject('Reset Password');
    });

    return view('pages.auth.password', [
      'page' => 'auth.password',
      'email' => $email,
      'success' => true,
      'msg' => 'Reset password email has sent, please check your email.',
    ]);
  }

  /**
   * Display the password reset view for the given token.
   *
   * @param  string  $token
   * @return Response
   */
  public function getReset($token = null)
  {
    $error = false;
    if (is_null($token)) {
      $error = true;
    } else {
      $user_token = UserToken::where('reset_pwd_token', $token)->first(); //->where('reset_pwd_at', '>', date('Y-m-d H:i:s'))
      if (!$user_token) {
        $error = true;
      }
    }
    return view('pages.auth.reset', [
      'page' => 'auth.reset',
      'token' => $token,
      'error' =>  $error ? 'This is not a valid token.' : false,
    ]);
  }

  /**
   * Reset the given user's password.
   *
   * @param  Request  $request
   * @return Response
   */
  public function postReset(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'token' => 'required',
      'password' => 'required|confirmed',
    ]);

    $credentials = $request->only(
      'password', 'token'
    );

    if ($validator->fails()) {
      return view('pages.auth.reset', [
        'page' => 'auth.reset',
        'token' => $credentials['token'],
        'msg' => $validator->errors()->first('password'),
      ]);
    }

    $user_token = UserToken::where('reset_pwd_token', $credentials['token'])->first(); //->where('reset_pwd_at', '>', date('Y-m-d H:i:s'))
    if (! $user_token) {
      return view('pages.auth.reset', [
        'page' => 'auth.reset',
        'token' => $credentials['token'],
        'msg' => 'This is not a valid token.',
      ]);
    }

    $user = User::find($user_token->user_id);
    $user->password = Hash::make($credentials['password']);
    $user->save();

    Auth::login($user);

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
