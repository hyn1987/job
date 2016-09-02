<?php
/**
 * All Job Page (job/all)
 *
 * @author  - nada
 */

use Wawjob\Project;
?>
@extends('layouts/buyer/index')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_all_jobs" method="post" action="{{ route('job.all')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        {{ show_messages() }}

        <div class="open-jobs section">
          <div class="title-section">
            <span class="title">All Jobs</span>
          </div>

          <div class="section-content">
          @if (!count($jobs))
            <div class="empty-jobs text-center">You have no job postings</div>
          @else
            @foreach ($jobs as $job)
            <div class="object-item row {{ strtolower($job->status_string()) }}{{ isset($job->is_first)? " first" : "" }}">
              <div class="col-sm-4 col-xs-12">
                <div class="job-title">
                  <a href="{{ route('job.view', array('id'=>$job->id)) }}">{{ $job->subject }}</a>
                </div>
                <div class="">
                  {{ $job->type_string() }} - Posted {{ ago($job->created_at) }}
                </div>
              </div>
              <div class="col-sm-6 col-xs-12">
                @if ($job->status == Project::STATUS_OPEN)
                <div class="job-info col-sm-3 col-xs-4">
                  <div class="job-info-value">{{ $job->normalApplicationsCount() }}</div>
                  <div>Applicants</div>
                </div>
                <div class="job-info col-sm-3 col-xs-4">
                  <div class="job-info-value">{{ $job->messagedApplicationsCount() }}</div>
                  <div>Messaged</div>
                </div>
                <div class="job-info col-sm-3 col-xs-4">
                  <div class="job-info-value">{{$job->offerHiredContractsCount()}}</div>
                  <div>Offers/Hires</div>
                </div>
                @else
                <div class="job-info col-sm-9 col-xs-12">
                </div>
                @endif
                <div class="job-info col-sm-3 col-xs-12 job-status">
                  {{ strtoupper($job->status_string()) }}
                </div>
              </div>
              <div class="job-info col-sm-2 col-xs-12 job-action">
                <select class="job-action-control form-control" data-show-subtext="true">
                  <option value="" data-content='
                    <div class="placeholder">-- Action --</div>
                  '></option>
                  <option value="view" data-content='<div class="job-action-link direct-link" data-url="{{ route('job.view', array('id'=>$job['job_id'])) }}">
                    <i class="icon-eye"></i>View Posting</div>
                  '></option>
                  <option value="edit" data-content='<div class="job-action-link direct-link" data-url="{{ route('job.edit', array('id'=>$job['job_id'])) }}">
                    <i class="icon-pencil"></i>Edit Posting</div>
                  '></option>
                  @if ($job->status == Project::STATUS_OPEN)
                    <option value="close" data-content='<div class="job-action-link status-link status-closed" data-url="{{ route('job.change_status.ajax', array('id'=>$job->id, 'status'=>Project::STATUS_CLOSED)) }}">
                      <i class="icon-close"></i>Close Posting</div>
                    '></option>
                    @if ($job->is_public == Project::STATUS_PRIVATE)
                    <option value="public" data-content='<div class="job-action-link public-link" data-url="{{ route('job.change_public.ajax', array('id'=>$job->id, 'public'=>Project::STATUS_PUBLIC)) }}" data-status="public">
                      <i class="icon-magnifier"></i>Make Public</div>
                    '></option>
                    @endif
                    @if ($job->is_public == Project::STATUS_PUBLIC)
                    <option value="private" data-content='<div class="job-action-link public-link" data-url="{{ route('job.change_public.ajax', array('id'=>$job->id, 'public'=>Project::STATUS_PRIVATE)) }}" data-status="private">
                      <i class="icon-lock"></i>Make Private</div>
                    '></option>
                    @endif
                  @endif
                  
                </select>
              </div>
            </div>
            @endforeach
          @endif
          </div>
          <div class="clearfix">
            <div class="pull-right">
              {!! $jobs->render() !!}
            </div>
          </div>
        </div> 
      </form>
    </div>
  </div>
</div>
@endsection