<?php
/**
 * Retrieve Notification list 
 *
 * @author  - Brice
 * @since March 21, 2016
 */

use Wawjob\User;
?>


@extends('layouts/notification/index')

@section('content')

<div class="title-section">
  <div class="row">
    <div class="col-sm-12">                                                                   
      <h4 class="title">{{ trans('page.' . $page . '.title') }}</h4>
    </div>
  </div>  
</div>

<div class="page-content-section notification-page">
  <div class="row">

    {{-- Search Section begin --}}
    <div class="col-sm-3">
      <div class="row" style="display:none">
        <div class="col-sm-12 margin-bottom-15" >        
          <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </div>        
      </div>
    </div>   
    {{-- Search Section end --}}

    {{-- Result Section begin--}}
    <!-- User Listing Part -->
    <div class="col-sm-12">

      <div class="dd notification-list-wrap">
        @forelse ($notification_list as $notification)
          @include('pages.notification.list_row')
        @empty
        <li class="no-items">You have not any notification.</li>
        @endforelse
      </div>
    </div>
    {{-- Result Section end--}}
    <div class="col-sm-12">
      <div class="pull-right">
        {!! $notification_list->render() !!}
      </div>
      <div style="clear:both;"></div>
    </div>
  </div>
  
</div>
@endsection