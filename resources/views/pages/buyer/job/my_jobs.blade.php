<?php
/**
 * My Info Page (job/my-jobs)
 *
 * @author  - nada
 */

use Wawjob\Project;
use Wawjob\Contract;
?>
@extends('layouts/buyer/index')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_my_jobs" method="post" action="{{ route('job.my_jobs')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        {{ show_messages() }}

        <div class="open-jobs section">
          <div class="title-section">
            <i class="fa fa-tasks title-icon"></i>
            <span class="title">{{ trans('job.open_jobs') }}</span>
            <div class="right-action-link pull-right">
              <a href="{{ route('job.create') }}" class="btn btn-primary">{{ trans('job.post_job') }}</a>
            </div>
          </div>

          <div class="section-content">
          @if (empty($jobs))
            <div class="empty-jobs text-center">{{ trans('job.no_open_jobs') }}</div>
          @else
            @foreach ($jobs as $job)
            <div class="object-item row{{ isset($job['#first'])? " first" : "" }}">
              <div class="col-sm-4 col-xs-12">
                <div class="job-title">
                  <a href="{{ route('job.view', array('id'=>$job['job_id'])) }}">{{ $job['title'] }}</a>
                </div>
                <div class="">{{ $job['type'] }} - {{ trans('common.posted') }} {{ $job['posted_ago'] }}</div>
              </div>
              <div class="col-sm-6 col-xs-12">
                <div class="job-info col-sm-3 col-xs-4">
                  <div class="job-info-value">
                    @if ($job['applicants'] > 0)
                    <a class="applicant-count" href="{{ route('job.applicants', array('id'=>$job['job_id'])) }}">{{ $job['applicants'] }}</a>
                    @else
                    -
                    @endif
                  </div>
                  <div>{{ trans('job.applicants') }}</div>
                </div>
                <div class="job-info col-sm-3 col-xs-4">
                  <div class="job-info-value">
                    @if ($job['messages'] > 0)
                    <a class="applicant-count" href="{{ route('job.messaged_applicants', array('id'=>$job['job_id'])) }}">{{ $job['messages'] }}</a>
                    @else
                    -
                    @endif
                  </div>
                  <div>{{ trans('job.messaged') }}</div>
                </div>
                <div class="job-info col-sm-3 col-xs-4">
                  <div class="job-info-value">
                    @if ($job['offers_hires'] > 0)
                    <a class="applicant-count" href="{{ route('job.offer_hired_applicants', array('id'=>$job['job_id'])) }}">{{ $job['offers_hires'] }}</a>
                    @else
                    -
                    @endif
                  </div>
                  <div>{{ trans('job.offers') }}/{{ trans('job.hires') }}</div>
                </div>
                <div class="job-info col-sm-3 col-xs-12 job-status status-{{ strtolower($job['_object']->is_public_string(false)) }}">{{ $job['status'] }}</div>
              </div>
              <div class="job-info col-sm-2 col-xs-12 job-action">
                <select class="job-action-control form-control" data-show-subtext="true">
                  <option value="" data-content='<div class="placeholder">-- {{ trans('job.action') }} --</div>'></option>
                  <option value="view" data-content='<div class="job-action-link direct-link" data-url="{{ route('job.view', array('id'=>$job['job_id'])) }}"><i class="icon-eye"></i>{{ trans('job.view_posting') }}</div>'></option>
                  <option value="edit" data-content='<div class="job-action-link direct-link" data-url="{{ route('job.edit', array('id'=>$job['job_id'])) }}"><i class="icon-pencil"></i>{{ trans('job.edit_posting') }}</div>'></option>
                  @if ($job['_object']->status == Project::STATUS_OPEN)
                    <option value="close" data-content='<div class="job-action-link status-link status-closed" data-url="{{ route('job.change_status.ajax', array('id'=>$job['job_id'], 'status'=>Project::STATUS_CLOSED)) }}"><i class="icon-close"></i>{{ trans('job.close_posting') }}</div>'></option>
                    @if ($job['_object']->is_public == Project::STATUS_PRIVATE)
                    <option value="public" data-content='<div class="job-action-link public-link" data-url="{{ route('job.change_public.ajax', array('id' => $job['job_id'], 'public'=>Project::STATUS_PUBLIC)) }}" data-status="public">
                      <i class="icon-magnifier"></i>{{ trans('job.make_public') }}</div>
                    '></option>
                    @endif
                    @if ($job['_object']->is_public == Project::STATUS_PUBLIC)
                    <option value="private" data-content='<div class="job-action-link public-link" data-url="{{ route('job.change_public.ajax', array('id'=>$job['job_id'], 'public'=>Project::STATUS_PRIVATE)) }}" data-status="private">
                      <i class="icon-lock"></i>{{ trans('job.make_private') }}</div>
                    '></option>
                    @endif
                  @endif
                  
                </select>
              </div>
            </div>
            @endforeach
          @endif
          </div>
          <div class="all-job-postings">
            <a href="{{ route("job.all") }}">{{ trans('job.view_all_jobs') }}</a>
          </div>

        </div>

        <div class="contracts section">
          <div class="title-section">
            <i class="fa fa-list-alt title-icon"></i>
            <span class="title">{{ trans('job.contracts') }}</span>
          </div>

          <div class="section-content">
            @if (!count($contracts))
            <div class="not-hired text-center">You have no hired freelancers in this team</div>
            @else
              @foreach ($contracts as $p_contracts)
              <div class="project-item">
                <div class="job-title">
                  {{ $p_contracts['project']? $p_contracts['project']->subject : ""}}
                </div>
              </div>
              <ul class="contract-list">
              @foreach ($p_contracts['contracts'] as $contract)
                <li class="contract-item">
                  <div class="contract-wrapper clearfix">
                    <div class='user-avatar pull-left'>
                      <img class="img-circle" src="{{ avatarUrl($contract->contractor) }}" width="64" height="64" />
                    </div>
                    <div class="contract-item-section">
                      <div class="contract-title">{{ $contract->title }}</div>
                      <div class="row">
                        <div class="col-sm-8 margin-bottom-10">
                          @if ($contract->status == Contract::STATUS_PAUSED)
                          <div class="contract-status status-paused">{{ trans('contract.contract_is_paused') }}</div>
                          @endif
                          <div class="contract-type">
                            @if ($contract->type == Project::TYPE_HOURLY)
                            <span>{{ trans('common.hourly_job') }}</span> - 
                            <span class="price">${{ formatCurrency($contract->price) }}/hr</span>
                              @if($contract->is_allowed_manual_time)
                               - <span>{{ trans('contract.allowed_log_manual') }}</span>
                              @endif
                            @else
                            <span>{{ trans('common.fixed_price_job') }}</span> - <span class="price">${{ formatCurrency($contract->price) }}</span>
                            @endif
                          </div>
                          <div class="contractor-info">
                            {{ trans('common.hired') }} <span class="contractor-name">{{ $contract->contractor->fullname() }}</span>
                            <span class="start-time">{{ trans('common.at_time', array('time' => getFormattedDate($contract->started_at))) }}</span>
                          </div>
                          <div class="contract-actions">
                            <a href="{{ route('contract.contract_view', ['id' => $contract->id]) }}">{{ trans('job.contract_detail') }}</a> | 
                            <a href="{{ route('message.list') }}?thread={{ $contract->application->getMessageThread()->id }}">{{ trans('job.send_message') }}</a>
                          </div>
                        </div>
                        <div class="col-sm-4 text-right">
                          @if ($contract->type == Project::TYPE_HOURLY)
                          <div class="this-week-time">
                            <span>
                              @if ($contract->limit)
                                {!! trans('contract.this_week_log_hours', array('log_hours'=>formatMinuteInterval($contract->this_week_mins), 'total'=>$contract->limit) ) !!}
                              @else 
                                {!! trans('contract.this_week_log_hours_no_limit', array('log_hours'=>formatMinuteInterval($contract->this_week_mins) )) !!}
                              @endif
                            </span>
                          </div>
                          <div class="this-week-total">
                            <strong>${{ formatCurrency($contract->buyerPrice($contract->this_week_mins)) }}</strong>
                          </div>
                          <div class="workdiary-action">
                            <a href="{{ route('workdiary.view', ['cid'=>$contract->id]) }}" class="">{{ trans('job.view_work_diary') }}</a>
                          </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              @endforeach
              </ul>
              @endforeach
            @endif
          </div>

          <div class="all-job-postings">
            <a href="{{ route('contract.all_contracts') }}">{{ trans('job.view_all_contracts') }}</a>
          </div>

        </div>
      </form>
    </div>
  </div>
</div>
@endsection