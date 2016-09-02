<?php namespace Wawjob\Providers;

use Illuminate\Support\ServiceProvider;

class CreatorServiceProvider extends ServiceProvider
{

  /**
   * Register bindings in the container.
   *
   * @return void
   */
  public function boot()
  {
    view()->creator(
      'pages.admin.*', 'Wawjob\Http\ViewCreators\SidebarCreator'
    );
    view()->creator(
      '*', 'Wawjob\Http\ViewCreators\UserViewCreator'
    );
    view()->creator(
      '*', 'Wawjob\Http\ViewCreators\MenuCreator'
    );
  }

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}