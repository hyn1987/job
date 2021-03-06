<?php namespace Wawjob\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class IsFreelancer {

  /**
   * The Guard implementation.
   *
   * @var Guard
   */
  protected $auth;

  /**
   * Create a new filter instance.
   *
   * @param  Guard  $auth
   * @return void
   */
  public function __construct(Guard $auth)
  {
    $this->auth = $auth;
  }

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if ($this->auth->guest()) {
      if ($request->ajax()) {
        return response('Unauthorized.', 401);
      } else {
        return redirect()->route('user.login');
      }
    }

    $roles = ['user_freelancer'];
    $user = $this->auth->user();

    foreach ($user->roles as $role) {
      if (in_array($role->slug, $roles) !== false) {
        return $next($request);
      }
    }

    if ($request->ajax()) {
      return response('Denied.', 401);
    } else {
      return redirect()->route('home');
    }
  }
}