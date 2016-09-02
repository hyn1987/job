<?php
/**
   * Show all users.
   *
   * @author Jin
   * @since Jan 14, 2016
   * @version 1.0 show simple list
   * @return Response
   */
?>
@extends('layouts/admin/index')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="navbar navbar-default" role="navigation">
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <form class="navbar-form navbar-right" role="search" name="user_list" method="post" action="{{ route('admin.user.list') }}">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <div class="input-icon right">
                <i class="fa fa-user"></i>
                <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username">
              </div>
            </div>
            <div class="form-group">
              <div class="input-icon right">
                <i class="fa fa-envelope"></i>
                <input type="text" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email">
              </div>
            </div>
            <div class="form-group">
              <select class="form-control" name="user_type">
                <option value="">User Type</option>
                @foreach ($userTypeList as $type)
                  <option value="{{ $type }}">{{ trans('common.user.types.' . $type) }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <select class="form-control" name="status">
                <option value="">Status</option>
                @foreach ($userStatusList as $status)
                  <option value="{{ $status }}" {{ old('status') != null && old('status') == $status ? ' selected' : '' }}>{{ trans('common.user.status.' . $status) }}</option>
                @endforeach
              </select>
            </div>
            <button class="btn blue" type="submit">search</button>

        </form>
      </div>
    </div>
  </div>

  <div class="col-sm-12">
    <div class="alert alert-success" role="alert">
      <strong>{{ $users->total() . ' ' . trans('message.admin.user.found') }}</strong>
    </div>
  </div>

  <div class="col-sm-12">
    <div class="pull-right">
      {!! $users->render() !!}
    </div>
    <div style="clear:both;"></div>
  </div>

  <div class="col-sm-12">
    <table class="table table-bordered table-hover" >
      <thead>
        <tr class="tr_head">
          <th>No</th>
          <th>Username</th>
          <th>Email</th>
          <th>Name</th>
          <th>Gender</th>
          <th>User Type</th>
          <th>Country</th>
          <th>Phone</th>
          <th>Status</th>
          <th>Edit</th>
        </tr>
      </thead>
      <tbody>

      @foreach ($users as $id => $user)
      <tr>
        <td>{{ ($users->currentPage() - 1) * $per_page + $id + 1 }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>
        @if ($user->contact)
          {{ $user->contact->first_name }}
          {{ $user->contact->last_name }}
        @endif
        </td>
        <td>
          @if ($user->contact){{ trans('common.user.gender.' . $user->contact->gender ) }}@endif
        </td>
        <td>
          @if ($user->contact){{ trans('common.user.types.' . $user->userType()) }}@endif
        </td>
        <td>
          @if ($user->contact){{ $user->contact->country ? $user->contact->country->name : '' }}@endif
        </td>
        <td>
          @if ($user->contact){{ $user->contact->phone }}@endif
        </td>
        <td>
          {{ trans('common.user.status.' . $user->status ) }}
        </td>
        <td>
          <a href="{{ route('admin.user.edit', ['id' => $user->id]) }}">Edit</a>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>
  </div>

  <div class="col-sm-12">
    <div class="pull-right">
      {!! $users->render() !!}
    </div>
    <div style="clear:both;"></div>
  </div>
</div>

@endsection