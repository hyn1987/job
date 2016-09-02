@extends('layouts/admin/index')

@section('content')
  <div class="row page-body">
    <div class="col-sm-12">
      <ul class="apis" data-call-url="{{ route('admin.api.v1') }}">
        <li class="api-login">
          <div class="title">Login</div>
          <div class="actions">
            <button class="btn-test btn-login-test btn btn-success btn-xs" type="button">Test <i class="fa fa-bolt"></i></button>
          </div>
          <div class="spec">
            <div class="url"><span class="label label-info">GET</span>/login</div>
          </div>
          <div class="inputs row">
            <div class="col-sm-6">
              <input class="username input input-sm" type="text" placeholder="Username" value="so">
            </div>
            <div class="col-sm-6">
              <input class="password input input-sm" type="text" placeholder="Password" value="so">
            </div>
          </div>
          <pre class="response hidden"></pre>
        </li>
        <li class="api-logout">
          <div class="title">Logout</div>
          <div class="actions">
            <button class="btn-test btn btn-success btn-xs" type="button" disabled>Test <i class="fa fa-bolt"></i></button>
          </div>
          <div class="spec">
            <div class="url"><span class="label label-info">GET</span>/logout</div>
          </div>
          <pre class="response hidden"></pre>
        </li>
      </ul>
    </div>
  </div>
@endsection