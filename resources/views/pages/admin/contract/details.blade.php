<?php
/**
   * Edit user data.
   *
   * @author Ray
   * @since March 6, 2016
   * @version 1.0 Edit user data page
   */
?>
@extends('layouts/admin/index')

@section('actions')
  <li><a href="{{ route('admin.contract.list') }}"><button type="button" class="btn-cancel btn btn-info btn-sm"><i class="fa fa-list-ul"></i> Contract List</button></a></li>
@endsection

@section('content')
<div class="row page-body">


  @include('layouts.admin.common_infos')
  @include('layouts.admin.common_errors')

  <div class="col-md-8">
    <div class="portlet block light border-radius-lefttop border-radius-rightbottom with-bottom-padding">

      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-sitemap"></i>  Details
        </div>
        <div class="tools">
          <a href="javascript:;" class="collapse"></a>
        </div>
      </div>

      <div class="portlet-body form">

        <div class="row">
          <div class="col-md-7">

            <!-- <h3 class="form-section"><i class="fa fa-sitemap"></i>  Details</h3> -->
            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="control-label">Title</label>', 
              ($u->project)?'<a href="#' . /*route('admin.project.details', $u->project->id)*/'' . '">' . $u->project->subject . '&nbsp;<i class="fa fa-angle-double-right"></i></a>' : $u->title
            ]])

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="control-label">Buyer</label>', 
              '<span class="badge-box">
                <img class="avatar img-circle" width="16px" height="16px" src="' . avatarUrl($u->buyer, 16) . '">
                <a class="avatar-name" href="' . route('admin.user.edit', $u->buyer->id) . '">' . $u->buyer->username . '</a>
              </span>'
            ]])

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="control-label">Contractor</label>', 
              '<span class="badge-box">
                <img class="avatar img-circle" width="16px" height="16px" src="' . avatarUrl($u->contractor, 16) . '">
                <a class="avatar-name" href="' . route('admin.user.edit', $u->contractor->id) . '">' . $u->contractor->username . '</a>
              </span>'
            ]])

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="control-label">Status</label>', 
              trans('common.contract.status.' . $u->status)
            ]])

            <div class="h-sepa"></div>

            @if ($u->type == '0')
              @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
                '<label class="control-label">Contract Type</label>', 
                'Hourly' . (($u->is_allowed_manual_time == '1')?'&nbsp;&nbsp;<a href="#" class="is_manual_enabled marker blue fa tooltips" data-original-title="Manual time log is enabled." data-container="body">M</a>':'')
              ]])
              @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
                '<label class="control-label">Hourly Rate</label>', 
                $u->price . ' $/hr'
              ]])
              @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
                '<label class="control-label">Weekly Limit</label>', 
                ($u->limit == '0')?'No Limit':$u->limit . ' hr'
              ]])
            @else
              @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
                '<label class="control-label">Contract Type</label>', 
                'Fixed'
              ]])
              @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
                '<label class="control-label">Price</label>', 
                $u->price . ' $'
              ]])
            @endif

            <div class="h-sepa"></div>

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="control-label">Period</label>', 
              ''
            ]])

          </div>
          <div class="col-md-5 button-group">

            @include('layouts.admin.bootstrap_row_xs2_eq', ['_inn_data' => [
              '<a href="#" class="btn-box" title="Report"><i class="icon-graph"></i><span class="subtitle">Report</span></a>', 
              '<a href="#" class="btn-box" title="Time Logs"><i class="icon-camera"></i><span class="subtitle">Time Logs</span></a>', 
            ]])

            @include('layouts.admin.bootstrap_row_xs2_eq', ['_inn_data' => [
              '<a href="#" class="btn-box" title="Transactions"><i class="fa fa-dollar"></i><span class="subtitle">Transactions</span></a>', 
              '<a href="#" class="btn-box" title="Messages"><i class="icon-envelope-letter"></i><span class="subtitle">Messages</span></a>',
            ]])
            
          </div>
        </div>

      </div>
      
    </div>
  </div>

  <div class="col-md-4">

    <div class="portlet block light border-radius-lefttop">
      
      <div class="portlet-title">
        <div class="caption">
          &nbsp;{{ ($u->type == '0')?'Hours &':'' }} Paid
        </div>
      </div>


      <div class="portlet-body">
          <!-- <h3 class="form-section">
            <i class="fa fa-calendar"></i> &nbsp;Hours
            <a href="#" class="fright"><button class="btn btn-info btn-xs"><i class="fa fa-clock-o"></i>&nbsp;Details&nbsp;<i class="fa fa-angle-double-right"></i></button></a>
          </h3> -->

          @if ($u->type == '0')

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="one-line control-label"><i class="fa fa-clock-o"></i> &nbsp;This Week</label>', 
              '40 hr'
            ]])

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="one-line control-label"><i class="fa fa-clock-o"></i> &nbsp;So Far</label>', 
              '4000 hr'
            ]])

          @endif

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="one-line control-label"><i class="fa fa-dollar"></i> &nbsp;Total Paid</label>', 
              '4000 $'
            ]])

            @include('layouts.admin.bootstrap_row_xs2', ['_inn_data' => [
              '<label class="one-line control-label"><i class="fa fa-dollar"></i> &nbsp;Pending</label>', 
              '2000 $'
            ]])

      </div>
    </div>
    


    <div class="portlet block light with-bottom-padding border-radius-rightbottom">
      <div class="portlet-body">

        <div class="feedback-block" rating="78%" ratingFrom="0">
        <!-- feedback from Buyer to Freelancer -->
          <div class="black-curtain">
            <a href="#" class="lnk_feedbackviewr"><button class="btn btn-info btn-sm" style="margin-top: 15px;"><i class="fa fa-comments-o"></i> Read</button></a>
          </div>
          <div class="white-gradient"></div>
          <div class="feedback-icons">
            <div class="feedback-type center">
              <img src="/assets/images/pages/auth/buyer.png" width="16px">
              <i class="fa fa-arrow-right"></i>
              <img src="/assets/images/pages/auth/freelancer.png" width="26px">
            </div>

            <div class="feedback-stars">
              <span class="star_wrapper" title=""><div class="rating_wrapper"><div class="star set set5" style="width: 78%;" title=""></div><div class="star set5"></div></div></span>
            </div>
          </div>

          <div class="feedback-content">
            Very impressive skills! I am so happy with the result he delivered to me so far.
            Look forward to next chance to working with him again.
          </div>
        </div>

        <!--
      </div>
    </div>

    <div class="portlet block">
      <div class="portlet-body">
        -->
        <div class="h-sepa"></div>
        <div class="feedback-block" rating="88%" ratingFrom="1">
        <!-- feedback from Freelancer to Buyer -->
          <div class="black-curtain">
            <a href="#" class="lnk_feedbackviewr"><button class="btn btn-info btn-sm" style="margin-top: 15px;"><i class="fa fa-comments-o"></i> Read</button></a>
          </div>
          <div class="white-gradient"></div>
          <div class="feedback-icons">
            <div class="feedback-type center">
              <img src="/assets/images/pages/auth/buyer.png" width="16px">
              <i class="fa fa-arrow-left"></i>
              <img src="/assets/images/pages/auth/freelancer.png" width="26px">
            </div>

            <div class="feedback-stars">
              <span class="star_wrapper" title=""><div class="rating_wrapper"><div class="star set set5" style="width: 88%;" title=""></div><div class="star set5"></div></div></span>
            </div>
          </div>

          <div class="feedback-content">
            Perfect buyer! Very clear on the requirements with so much kindness.
          </div>
        </div>
      </div>
    </div>



  </div>


</div>
@endsection



{{-- Reviewer Modal --}}
@include('pages.admin.contract.modal_feedback')


