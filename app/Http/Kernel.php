<?php namespace Wawjob\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

  /**
   * The application's global HTTP middleware stack.
   *
   * @var array
   */
  protected $middleware = [
    'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
    'Illuminate\Cookie\Middleware\EncryptCookies',
    'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
    'Illuminate\Session\Middleware\StartSession',
    'Illuminate\View\Middleware\ShareErrorsFromSession',
    'Wawjob\Http\Middleware\VerifyCsrfToken',
  ];

  /**
   * The application's route middleware.
   *
   * @var array
   */
  protected $routeMiddleware = [
    'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
    'auth' => 'Wawjob\Http\Middleware\Authenticate',
    'guest' => 'Wawjob\Http\Middleware\RedirectIfAuthenticated',
    'auth.admin' => 'Wawjob\Http\Middleware\IsAdmin',
    'auth.buyer' => 'Wawjob\Http\Middleware\IsBuyer',
    'auth.freelancer' => 'Wawjob\Http\Middleware\IsFreelancer',
    'auth.customer' => 'Wawjob\Http\Middleware\IsCustomer',
    'auth.api_v1' => 'Wawjob\Http\Middleware\IsAuthenticated4ApiV1',
  ];
}