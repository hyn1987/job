<?php namespace Wawjob\Http\Middleware;

use Closure;
use Log;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if ($request->is('api/*')) {
      return $next($request);
    }

    return parent::handle($request, $next);
  }
}