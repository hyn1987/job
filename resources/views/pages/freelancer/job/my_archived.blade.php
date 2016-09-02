<?php
/**
 * My Archived Page (job/my_archived)
 *
 * @author  - Ri Chol Min
 */
use Wawjob\ProjectApplication;
?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="title">{{ trans('proposal.my_proposals') }}</div>
    {{ show_messages() }}
    <div class="page-navs">
      <a href="/job/my-proposals" class="tab">{{ trans('proposal.active') }}</a>
      <a href="javascript:void(0);" class="active tab">{{ trans('proposal.archived') }}</a> 
    </div>
    <div class="archived-proposal section">
      <div class="section-content">
        <div class="item-header clearfix">
          <div class="col-xs-2">{{ trans('proposal.date') }}</div>
          <div class="col-xs-6">{{ trans('proposal.job') }}</div>
          <div class="col-xs-4">{{ trans('proposal.reason') }}</div>
        </div>
        <div class="box-section">
        @if (count($archived_jobs) > 0)
          @foreach ( $archived_jobs as $job )
          <div class="active-item  clearfix">
            <div class="col-xs-2">{{getFormattedDate($job->updated_at)}}</div>
            <div class="col-xs-6"><a href="/job/applicant/{{$job->project->id}}">{{$job->project->subject}}</a></div>
            <div class="col-xs-4">
              @if ($job->status == ProjectApplication::STATUS_CLIENT_DCLINED )
              {{ trans('proposal.declined_by_client') }}
              @elseif ($job->status == ProjectApplication::STATUS_FREELANCER_DECLINED )
              {{ trans('proposal.withdrawn_by_you') }}
              @elseif ($job->status == ProjectApplication::STATUS_PROJECT_CANCELLED )
              {{ trans('proposal.closed_by_customer_support') }}
              @elseif ($job->status == ProjectApplication::STATUS_PROJECT_EXPIRED )
              {{ trans('proposal.expired') }}
              @endif
            </div>
          </div>
          @endforeach
        @else
          <div class="active-item  clearfix">
            <div class="empty-content">{{ trans('proposal.no_archived_proposals') }}</div>
          </div>
        @endif        
        </div>
      </div>
    </div>    
  </div>
</div>
@endsection