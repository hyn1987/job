<?php namespace Wawjob\Http\Controllers\Api\v1;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Api\v1\ApiController;

use Illuminate\Http\Request;

use Auth;

// Models
use Wawjob\User;
use Wawjob\Contract;
use Wawjob\UserToken;
use Wawjob\UserAnalytic;
use Wawjob\Project;

class AuthController extends ApiController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Sync the time with server.
   *
   * @param  Request $request
   * @return JSON
   */
  public function sync(Request $request)
  {
    return response()->json([
      'time' => time(),
    ]);
  }

  /**
   * Validate user info by token and get relation info.
   *
   * @param  Request $request
   * @return JSON
   */
  public function valid(Request $request)
  {
    $return = [];
    $payload = $this->parseJWT($request->header('JWT'));

    $token_row = UserToken::where('api_v1_token', $payload['token'])->first();
    $user = User::find($token_row->user_id);
    $contracts = Contract::where('contractor_id', $user->id)
      ->where('type', Project::TYPE_HOURLY)
      ->where('status', Contract::STATUS_OPEN)
      ->with('buyer')->get();

    $return['name'] = $user->fullname();

    $return['contracts'] = [];
    foreach ($contracts as $contract) {
      $return['contracts'][] = [
        'id' => $contract->id,
        'title' => $contract->title,
        'buyer' => $contract->buyer->fullname(),
      ];
    }
    return response()->json($return);
  }

  /**
   * Authenticate user info.
   *
   * @param  Request $request
   * @return JSON
   */
  public function login(Request $request)
  {
    $payload = $this->parseJWT($request->header('JWT'));

    if ($payload !== false && isset($payload['username']) && isset($payload['password'])) {
      $return = [];
      $checklist = ['username', 'email'];

      // Gather user information from request.
      $username = $payload['username'];
      $password = $payload['password'];

      // Attempt to login to the system.
      foreach ($checklist as $key) {
        $credential = [
          'password' => $password
        ];
        $credential[$key] = $username;

        if (Auth::validate($credential)) {
          $user = User::where($key, $username)->with('tokens')->first();

          // Log user login
          UserAnalytic::insert([
            'user_id' => $user->id,
            'login_ipv4' => $_SERVER['REMOTE_ADDR'],
            'logged_at' => date("Y-m-d H:i:s")
          ]);

          $return['name'] = $user->fullname();

          do {
            $return['token'] = str_random(118) . time();
          } while (UserToken::where('api_v1_token', $return['token'])->count());

          if (!$user->tokens) {
            $user->tokens = new UserToken;
            $user->tokens->user_id = $user->id;
          }
          $user->tokens->api_v1_token = $return['token'];
          $user->tokens->save();

          $contracts = Contract::where('contractor_id', $user->id)
            ->where('type', Project::TYPE_HOURLY)
            ->where('status', Contract::STATUS_OPEN)
            ->with('buyer')->get();

          $return['contracts'] = [];
          foreach ($contracts as $contract) {
            $return['contracts'][] = [
              'id' => $contract->id,
              'title' => $contract->title,
              'buyer' => $contract->buyer->fullname(),
            ];
          }

          return response()->json($return);
        }
      }

      return response()->json([
        'error' => '[1001] Invalid user info.',
        'data' => $payload,
      ]);
    }

    return response()->json([
      'error' => '[1002] Failed to authenticate user info.',
      'data' => $payload,
    ]);
  }

  /**
   * Log the user out.
   *
   * @return JSON
   */
  public function logout(Request $request)
  {
    $jwt = $request->header('JWT');

    $payload = $this->parseJWT($jwt);
    $token = $payload['token'];

    $return = [
      'error' => false,
    ];

    if (!UserToken::where('api_v1_token', $token)->update(['api_v1_token' => ''])) {
      $return['error'] = 'Failed to logout.';
    }

    return response()->json($return);
  }
}