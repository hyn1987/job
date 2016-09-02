<?php use Wawjob\Project; ?>
@extends('layouts/admin/index')

@section('toolbar')
<form name="frm_toolbar" id="frm_toolbar">
  <div class="date-ranger-wrp pull-right">
    <label class="control-label pull-left">Date</label>
    <div class="input-group pull-left section-right" id="date_range">
      <input type="text" class="form-control" name="date_range" value="{{ old('date_range') }}">
      <span class="input-group-btn">
      <button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
      </span>
    </div>
  </div>
</form>
@endsection

@section('content')
<div class="row page-body">

  @include('pages.admin.job.list_search')

  <div class="col-sm-12">
    <div class="job-results">

      @if ( count($jobs) > 0 )
      <div class="pagi clearfix">
        <div class="pull-left total">
          <strong><span class="jresult-total">{{ $jobs->total() }}</span> jobs found</strong>
        </div>
        <div class="pull-right">
          {!! $jobs->render() !!}
        </div>
      </div>

      <div class="job-items">
        @foreach ($jobs as $id => $job)
        <div class="panel panel-default jpanel {{ strtolower($job->is_public_string()) }} {{ strtolower($job->is_open_string()) }}">
          {{-- Panel Header--}}
          <div class="panel-heading">
            <span class="job-avatar"><img src="{{ avatarUrl($job->client, $avatar_size) }}" width="{{ $avatar_size }}" height="{{ $avatar_size }}" title="{{ $job->client->fullname() }}" alt="{{ $job->client->fullname() }}"></span>
            <h4>{{ $job->subject }}<span class="closed-only"> - <i class="is-closed">Closed</i></span></h4>

            <button class="btn btn-sm btn-danger btn-cancel btn-job-action pull-right open-only" data-type="cancel" data-jid="{{ $job->id }}">Cancel</button>
            <button class="btn btn-sm btn-primary btn-reopen btn-job-action pull-right closed-only" data-type="reopen" data-jid="{{ $job->id }}">Re-open</button>

          </div>

          {{-- Panel Body --}}
          <div class="panel-body">
            <div class="row">
              <div class="col-xs-8 col-sm-9 col-md-10">
                <div class="meta">
                  <span class="job-type"><strong>{{ $job->type == Project::TYPE_HOURLY ? "Hourly" : "Fixed Price" }}</strong></span> - @if ($job->type == Project::TYPE_FIXED)Est. Budget: {{ $job->strPrice }} - @endif @if ($job->duration)Est. Time: <span class="job-duration">{{ $job->duration_string() }}</span>,@endif <span class="job-workload">{{ $job->workload_string() }}</span> - <span class="job-ago">{{ $job->ago }} ago</span>
                </div>

                {{-- Summary --}}
                <div class="job-inner">
                  <p>@if ($job->summaryMore){!! nl2br($job->summary) !!}<span class="more-anchor"> ... <span class="more pointer strong">more</span></span><span class="summary-more hide">{!! nl2br($job->summaryMore) !!}</span>@else {{ $job->desc }} @endif</p>
                </div>
              </div>

              <div class="col-xs-4 col-sm-3 col-md-2 activity-info">
                {{-- Number of applicants --}}
                <p><strong>Applicants: </strong> <span class="num-applicants">{{ $job->num['applicant'] }}</span></p>

                {{-- @todo: Number of interviewing --}}
                <p><strong>Interview: </strong> <span class="num-interview">{{ $job->num['interview'] }}</span></p>

                {{-- @todo: Number of hired --}}
                <p><strong>Hired: </strong> <span class="num-hired">{{ $job->num['hired'] }}</span></p>                
              </div>
            </div>
          </div>

          {{-- Panel Footer --}}
          <div class="panel-footer">
            <div class="row1">
              {{-- Skills --}}            
              <span class="skill-wrp">
                <span class="lbl-skills strong">Skills: </span>
              @if ( count($job->skills) )
                <span class="skill-list">
                  @foreach ($job->skills as $skill)
                  <span class="skill-item">{{ $skill->name }}</span>
                  @endforeach
                </span>
              @else
                Not required
              @endif
              </span>
            </div>
          </div>
        </div>
        @endforeach
      </ul>

      @else
      <p>No Matched Jobs Found</p>
      
      @endif
    </div>
  </div>
</div>

@endsection