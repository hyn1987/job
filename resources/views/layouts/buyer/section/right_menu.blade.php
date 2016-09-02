<?php

/**
* Header for Buyer Pages
* @author 	nada
* @since 	12/25/2015
*/
?>
<div class="right-menu buyer-right-menu">
  <ul class="nav navbar-nav pull-right">
    <!-- BEGIN NOTIFICATION DROPDOWN -->
    <li class="dropdown dropdown-extended dropdown-inbox" id="header_search_bar">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
        <i class="icon icon-magnifier"></i>
      </a>
      
      <div class="search-form dropdown-menu">
        <form id="frm_header_search" class="form-inline" action="{{ route('search.job') }}" method="post">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <select class="form-control" name="searchType" id="searchType">
            <option value="job">{{ trans('common.job') }}</option>
            <option value="user">{{ trans('common.word.user') }}</option>
          </select> 
          <div class="input-group">
            <input type="text" placeholder="{{ trans('common.search') }}" class="form-control" name="search_title">
            <span class="input-group-btn">
              <button id="search-btn" class="btn btn-primary" type="submit">
              <i class="fa fa-arrow-circle-right"></i>
              </button>
            </span>
          </div>
        </form>
      </div>
      
    </li>
    <!-- END NOTIFICATION DROPDOWN -->
    <!-- BEGIN Help DROPDOWN -->
    <li class="dropdown dropdown-extended dropdown-inbox" id="header_help_bar">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
      <i class="icon icon-question"></i>
      </a>
      <ul class="dropdown-menu">
        <li class="">
          <a href="{{route('faq.list')}}">{{ trans('common.faq') }}</a>
        </li>
        <li class="">
          <a href="{{route('ticket.list')}}">{{ trans('common.ticket') }}</a>
        </li>
      </ul>
    </li>
    <!-- END Help DROPDOWN -->
    <!-- BEGIN TODO DROPDOWN -->
    <li class="dropdown dropdown-extended dropdown-tasks" id="header_notification_bar">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
      <i class="icon-bell icon"></i>
      <span class="badge badge-default notfication-cnt">{{ $unread_cnt }}</span>
      </a>
      <ul class="dropdown-menu">
        @foreach ($unread_notifications as $unread)
        <li class="notification nid{{$unread->id}}">
          <a href="#" notification-id="{{$unread->id}}">{!!nl2br($unread->notification)!!}</a>
        </li>
        @endforeach
        <li class="notification-all">
          <a href="{{route('notification.list')}}">{{ trans('header.see_all_notifications') }}</a>
        </li>
      </ul>
    </li>
    <!-- END TODO DROPDOWN -->
    <!-- BEGIN USER LOGIN DROPDOWN -->
    <li class="dropdown dropdown-user">
      <a href="#" class="user-menu-link dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
        <img alt="" class="img-circle hide1" src="{{ avatarUrl($current_user) }}" width="32" height="32" />
        <span class="username username-hide-on-mobile">{{ $current_user? $current_user->fullname() : "" }}</span>
        <i class="fa fa-angle-down"></i>
      </a>
      <ul class="dropdown-menu">
        @if($right_menu)
        @foreach ($right_menu as $root_key => $root)
          @if ($root['route'] == '#')
            <li class="divider"></li>
          @else
          <li>
            <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
              @if ($root['icon'])
              <i class="{{ $root['icon'] }}"></i>
              @endif
              {{ trans('menu.buyer_right_menu.' . $root_key . '.title') }} 
            </a>
          </li>
          @endif
        @endforeach
        @endif
      </ul>
    </li>
    <!-- END USER LOGIN DROPDOWN -->
    
  </ul>
</div>