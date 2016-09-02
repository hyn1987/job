<?php
  /**
   * Show dashboards.
   *
   * @author Sunlight
   * @since March 22, 2016
   * @version 1.0 show simple list
   * @return Response
   */
?>
@extends('layouts/admin/index')

@section('content')

<div class="row-md page-body">


  <div class="row row-md">
    <div class="col-md-12">

      <div class="tabbable tabbable-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab_stats_weekly" data-toggle="tab"><i class="fa fa-calendar"></i>Weekly Stats</a></li>
          <li><a href="#tab_stats_overal" data-toggle="tab"><i class="fa fa-bullseye"></i>Overal Stats</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_stats_weekly">
            @include('pages.admin.dashboard._weekly_stat')  
          </div>
          <div class="tab-pane" id="tab_stats_overal">
            @include('pages.admin.dashboard._overal_stat')      
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="row row-md">
    <div class="col-md-6">
      @include('pages.admin.dashboard._completes')        
    </div>

    <div class="col-md-6">
      @include('pages.admin.dashboard._server_stats')
    </div>

  </div>


  <div class="row row-md">
    
    <div class="col-md-6">
      @include('pages.admin.dashboard._regional_user_stat')      
    </div>
    <div class="col-md-6">
      @include('pages.admin.dashboard._recents')
    </div>

  </div>

  <div class="row row-md">
    <div class="col-md-6">
      @include('pages.admin.dashboard._recent_posts')
    </div>


    <div class="col-md-6">
      @include('pages.admin.dashboard._recent_transactions')      
    </div>
  </div>

  

</div><!-- END DASHBOARD STATS -->
@endsection