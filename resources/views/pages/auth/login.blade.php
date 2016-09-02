@extends('layouts/auth/login')

@section('content')
<h4>{{ trans('page.auth.login.login_and_work')}}</h4>
{{ show_messages() }}
<form id="login_form" class="form-horizontal form-without-legend" method="post" action="{{ route('user.login') }}">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  @if (isset($error))
  <div class="has-error"><span class="help-block">{{ $error }}</span></div>
  @endif

  <div class="form-group">
    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="{{ trans('page.auth.login.username_or_email')}}" index="-9999">
    </div>
  </div>
  
  <div class="form-group">
    <input type="password" class="form-control" id="password" name="password" placeholder="{{ trans('page.auth.signup.password')}}" index="1">
  </div>

  <div class="padding-top-20">
    <button type="submit" class="btn btn-primary">{{ trans('page.auth.login.title')}}</button>
    &nbsp;&nbsp;&nbsp;
    <div class="checkbox-inline">
      <label class="gray-text-color">
        <input type="checkbox" name="remember" value="1"{{ old('remember') ? ' checked' : ''}}> {{ trans('page.auth.login.remember')}}
      </label>
    </div>
  </div>

  <div class="padding-top-20">
    <a href="{{ route('password.reset.email') }}">{{ trans('page.auth.login.forgot') }}?</a>&nbsp;&nbsp;
    <a href="{{ route('user.signup') }}">{{ trans('page.auth.login.signup') }}</a>
  </div>
</form>
@endsection