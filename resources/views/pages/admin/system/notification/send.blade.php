<?php
  /**
   * Show notification form.
   *
   * @return Response
   */
?>
@extends('layouts/admin/index')

@section('actions')
  <li><button class="btn-send btn btn-success btn-sm" type="button">Send Now</button></li>
  <li><button class="btn-cron btn btn-primary btn-sm" type="button">Add CronJob</button></li>
@endsection

@section('content')
<div class="page-body">
  <div class="row">
    <div class="col-md-12">
      <div class="portlet block light">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-users"></i>  Users
          </div>
          <div class="tools">
            <a href="javascript:;" class="collapse"></a>
          </div>
        </div>

        <div class="portlet-body form">

          <div class="row">
            <div class="navbar-form user-search">
              <div class="input-group">
                <span class="input-group-addon input-circle-left"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" id="username" name="username" value="" placeholder="Username">
              </div>

              <div class="input-group">
                <span class="input-group-addon input-circle-left"><i class="fa fa-envelope"></i></span>
                <input type="text" class="form-control" id="email" name="email" value="" placeholder="Email">
              </div>

              <div class="input-group">
                <select class="form-control" name="user_type" id="user_type">
                  <option value="">All Users</option>
                  @foreach ($userTypeList as $utype)
                    <option value="{{ $utype->id }}">{{ trans('common.user.types.' . $utype->slug) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="input-group">
                <select class="form-control" name="status" id="status">
                  <option value="">All Status</option>
                  @foreach ($userStatusList as $status)
                    <option value="{{ $status }}">{{ trans('common.user.status.' . $status) }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="portlet notification">
                <div class="portlet-title">
                  <div class="caption"><i class="fa fa-search"></i>Search Result</div>
                  <div class="actions">
                    <a href="javascript:;" class="btn btn-default btn-sm btn-add-all"><i class="fa fa-plus"></i> Add All</a>
                    <a href="javascript:;" class="btn btn-default btn-sm btn-add" title="After select the following users, please click me."><i class="fa fa-plus"></i> Add </a>
                  </div>
                </div>
                <div class="portlet-body">
                  <ul class="scroller list search-users" data-rail-visible="1" data-rail-color="gray" data-handle-color="#a1b2bd">
                    @foreach ($users as $u)
                    <li class="dd-item user{{$u->id}} searched" uid="{{$u->id}}">
                      <div class="item">
                        <div class="item-header row">
                          <div class="col-sm-12 infoset">
                            <img class="avatar img-circle for-collapse" width="32px" height="32px" src="{{ avatarUrl($u, 32) }}">
                            <h5>
                              @if ($u->contact){!! $u->contact->country ? '<span class="marker country"><img src="/assets/images/common/flags/' . strtolower($u->contact->country->charcode) . '.png" /></span>' : '' !!}@endif
                              <span class="username">{{ $u->username }}</span>
                              <span class="star_wrapper" title=""><div class="rating_wrapper"><div class="star set set5" style="width: {{ $u->getRatingPercent() }}%;" title=""></div><div class="star set5"></div></div></span>
                              <span class="marker email"><i class="fa fa-envelope"></i>&nbsp;{{ $u->email }}</span>
                              <span class="marker user-type user-type-{{ $u->role_id }}">
                                @if ($u->contact){{ trans('common.user.types.' . $u->userType()) }}@endif
                              </span>
                              <span class="marker state-{{$u->status}}">{{ trans('common.user.status.' . $u->status ) }}</span>
                            </h5>
                          </div>
                        </div>
                      </div>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="portlet notification">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-users"></i>Users for Notification</div>
                  <div class="actions">
                    <a href="javascript:;" class="btn btn-default btn-sm btn-remove-all"><i class="fa fa-minus"></i> Remove All</a>
                    <a href="javascript:;" class="btn btn-default btn-sm btn-remove" title="After select the following users, please click me."><i class="fa fa-minus"></i> Remove </a>
                  </div>
                </div>
                <div class="portlet-body">
                  <ul class="scroller list notification-users" data-rail-visible="1" data-rail-color="gray" data-handle-color="#a1b2bd">
                    
                  </ul>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="portlet block light">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-bell"></i>  Notification
          </div>
          <div class="tools">
            <a href="javascript:;" class="collapse"></a>
          </div>
        </div>

        <div class="portlet-body form">

          <div class="row">
            <div class="navbar-form notification-search">
              <div class="input-group">
                <span class="input-group-addon input-circle-left"><i class="fa fa-bell"></i></span>
                <input type="text" class="form-control" id="notification_name" name="notification_name" value="" placeholder="Notification Name">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="portlet notification">
                <div class="portlet-title">
                  <div class="caption"><i class="fa fa-search"></i>Search Result</div>
                </div>
                <div class="portlet-body">
                  <ul class="scroller notifications list">
                    @forelse ($slugs as $s)
                    <li class="notification searched" data-id="{{$s->id}}">
                      <div class="header">
                        <div class="title noti-slug">{{$s->slug}}</div>
                        <div class="noti-body">
                          <div class="msgs collapse" aria-expanded="true">
                            <ul>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </li>
                    @empty
                    <li class="notification searched" data-id="0">
                      <div class="header">
                        <div class="title no-slug">No notifications found.</div>
                      </div>
                    </li>
                    @endforelse
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="portlet notification">
                <form id="frm_notification_multicast" class="form-horizontal clearfix" action="{{ route('admin.notification.multicast') }}" method="post" role="form">
                  <div class="portlet-title">
                    <div class="caption"><i class="fa fa-search"></i>Parameters for Replace     </div>
                    <div class="input-group date form_datetime noti-datetime">
                      <input type="text" size="16" name="valid_date" class="form-control datepickerinput " placeholder="Valid Date for Notification">
                      <span class="input-group-btn">
                      <button class="btn default date-set " type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                  </div>
                  <div class="portlet-body">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" name="users" id="users" value="" />
                      <input type="hidden" name="slug" id="slug" value="" />
                      <input type="hidden" name="mode" id="mode" value="0" />
                      
                      <ul class="scroller params list">
                      </ul>
                  </div>
                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection