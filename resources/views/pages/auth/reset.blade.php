@extends('layouts/auth/login')

@section('content')
<h4>Reset Password</h4>
@if ($error)
<div class="alert alert-danger">{{ $error }}</div>
@else
<form class="form-horizontal" method="post" action="{{ route('password.reset') }}" autocomplete="off">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input type="hidden" name="token" value="{{ $token }}">
  @if (isset($error))
  <div class="has-error"><span class="help-block">{{ $error }}</span></div>
  @endif

  <div class="form-group">
    <input type="password" class="form-control" name="password" placeholder="New Password">
  </div>

  <div class="form-group">
    <input type="password" class="form-control" name="password_confirmation" placeholder="Password Confirm">
  </div>

  <div class="form-group">
    <button type="submit" class="btn btn-primary">Reset</button>
  </div>
</form>
@endif
@endsection