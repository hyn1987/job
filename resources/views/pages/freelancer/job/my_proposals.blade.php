<?php
/**
 * My Proposals Page (job/my_proposals)
 *
 * @author  - Ri Chol Min
 */

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="title">{{ trans('proposal.my_proposals') }}</div>
    {{ show_messages() }}
    <div class="page-navs">
      <a href="javascript:void(0);" class="active tab">{{ trans('proposal.active') }}</a>
      <a href="/job/my-archived" class="tab">{{ trans('proposal.archived') }}</a> 
    </div>

    <div class="active-proposal section">
      <div class="title-section">{{ trans('proposal.active_candidacies') }} ({{ count($active_jobs) }})</div>
      <div class="section-content">
        <div class="item-header clearfix">
          <div class="col-xs-2">{{ trans('proposal.received_at') }}</div>
          <div class="col-xs-6">{{ trans('proposal.job') }}</div>
          <div class="col-xs-4">{{ trans('proposal.client') }}</div>
        </div>
        <div class="box-section">
        @if (count($active_jobs) > 0)
          @foreach ( $active_jobs as $job )
          <div class="active-item clearfix">
            <div class="col-xs-2">{{getFormattedDate($job->created_at)}}</div>
            <div class="col-xs-6"><a href="{{ route('job.application_detail', ['id' => $job->id]) }}">{{$job->project->subject}}</a></div>
            <div class="col-xs-4">{{$job->project->client->fullname()}}</div>
          </div>
          @endforeach
        @else
          <div class="active-item clearfix">
            <div class="empty-content">{{ trans('proposal.no_active_proposals') }}</div>
          </div>
        @endif        
        </div>
      </div>
    </div>

    <div class="invitation section">
      <div class="title-section">{{ trans('proposal.invitations_to_interview') }} ({{ count($invite_jobs) }})</div>
      <div class="section-content">
        <div class="item-header clearfix">
          <div class="col-xs-2">{{ trans('proposal.received_at') }}</div>
          <div class="col-xs-6">{{ trans('proposal.job') }}</div>
          <div class="col-xs-4">{{ trans('proposal.client') }}</div>
        </div>
        <div class="box-section">
        @if (count($invite_jobs) > 0)
          @foreach ( $invite_jobs as $job )
          <div class="active-item  clearfix">
            <div class="col-xs-2">{{getFormattedDate($job->created_at)}}</div>
            <div class="col-xs-6"><a href="{{ route('job.accept_invite', ['id'=>$job->id]) }}">{{$job->project->subject}}</a></div>
            <div class="col-xs-4">{{$job->project->client->fullname()}}</div>
          </div>
          @endforeach
        @else
          <div class="active-item  clearfix">
            <div class="empty-content">{{ trans('proposal.no_invitation_to_interview') }}</div>
          </div>
        @endif
        </div>
      </div>
    </div>

    <div class="my-applicants section">
      <div class="title-section">{{ trans('proposal.submitted_proposals') }} ({{ count($my_proposals) }})</div>
      <div class="section-content">
        <div class="item-header clearfix">
          <div class="col-xs-2">{{ trans('proposal.submitted') }}</div>
          <div class="col-xs-6">{{ trans('proposal.job') }}</div>
          <div class="col-xs-4">{{ trans('proposal.client') }}</div>
        </div>
        <div class="box-section">
        @if (count($my_proposals) > 0)
          @foreach ( $my_proposals as $job )
          <div class="active-item  clearfix">
            <div class="col-xs-2">{{ ago($job->created_at) }}</div>
            <div class="col-xs-6"><a href="{{ route('job.application_detail', ['id'=>$job->id]) }}">{{$job->project->subject}}</a></div>
            <div class="col-xs-4">{{$job->project->client->fullname()}}</div>
          </div>
          @endforeach
        @else
          <div class="active-item  clearfix">
            <div class="empty-content">{{ trans('proposal.no_submitted_proposals') }}</div>
          </div>
        @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection