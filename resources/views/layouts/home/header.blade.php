{{-- Start Header --}}
<header class="page-header" role="header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="toggle-icon">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </span>
        </button>

        {{-- Start Logo --}}
        <a class="page-logo" href="{{ url('') }}" title="{{ trans('page.title') }}">{{ trans('page.logo') }}<span>{{ trans('page.sub_logo') }}</span></a>

        {{-- Start TopNav --}}
        <div class="top-nav">
          <ul class="nav navbar-nav">
            @unless ($current_user)
            <li>
              <a href="{{ route('user.signup') }}" class="text-uppercase"><i class="icon-users"></i>{{ trans('page.auth.signup.title_with_space') }}</a>
            </li>
            @endunless
            @if ($current_user)
            <li>
              <a href="{{ route('user.my_info') }}" class="user-avatar">
                <img alt="{{ $current_user->fullname() }}" class="img-circle hide1" src="{{ avatarUrl($current_user) }}" width="32" height="32" />
                <span class="username username-hide-on-mobile">{{ $current_user->fullname() }}</span>
              </a>
            <li>
              <a href="{{ route('user.logout') }}" class="text-uppercase"><i class="icon-logout"></i>{{ trans('page.auth.logout.title') }}</a>
            </li>
            @else
            <li>
              <a href="{{ route('user.login') }}" class="text-uppercase"><i class="icon-login"></i>{{ trans('page.auth.login.title_with_space') }}</a>
            </li>
            @endif
          </ul>
        </div>{{-- End TopNav --}}
      </div>
    </div>
  </div>
</header>{{-- End Header --}}