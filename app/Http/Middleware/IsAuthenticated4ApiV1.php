<?php namespace Wawjob\Http\Middleware;

use Closure;
use Config;

use Wawjob\UserToken;

class IsAuthenticated4ApiV1 {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    $payload = parse_jwt(Config::get('api.key.v1'), $request->header('JWT'));

    if ($payload !== false) {
      $token = $payload['token'];

      if (UserToken::where('api_v1_token', $token)->count()) {
        return $next($request);
      }
    }

    return response()->json([
      'error' => $payload
    ]);
  }
}