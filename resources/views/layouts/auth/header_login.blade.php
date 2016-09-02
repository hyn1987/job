<!-- BEGIN HEADER -->
<div class="header-wrapper">
    <div class="header login-header">
        <div class="header-section container">
          <div class="logo-section">
            <a href="#" class="logo">{{ trans('page.home.logo') }}</a>
          </div>
          <div class="right-menu">
            <a href="{{ route('user.signup') }}">{{ trans('page.auth.signup.title') }}</a>
          </div>
        </div>
    </div>
    <div class="header-2 login-header">
        <div class="header-section container">
            <span>{{ trans('page.auth.login.welcome') }}</span>&nbsp;&nbsp;
            <a href="#">{{ trans('page.auth.login.learn_more') }}</a>
        </div>
    </div>
  </div><!-- END HEADER -->