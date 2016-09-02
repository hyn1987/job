@extends('layouts/auth/login')

@section('content')
<h4>Reset Password</h4>
<form id="login_form" class="form-horizontal form-without-legend" method="post" action="{{ route('password.reset.email') }}">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  @if (isset($msg))
  <div class="has-{{ $success ? 'success' : 'error' }}"><span class="help-block">{{ $msg }}</span></div>
  @endif

  <div class="form-group">
    <input type="text" class="form-control" name="email" value="{{ isset($email) ? $email : '' }}" placeholder="Email Address" index="-9999">
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Reset</button>

  <a href="{{ route('user.login') }}">{{ trans('page.auth.login.title') }}</a>
  </div>
</form>
@endsection