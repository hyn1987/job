<?php
/**
 * My Info Page (job/{id}/archived_applicants)
 *
 * @author  - nada
 */
use Wawjob\Project;
use Wawjob\Contract;
?>
@extends('layouts/buyer/job')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_job_applicants" method="post" action="{{ route('job.offer_hired_applicants')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="job-top-section">
          <div class="title-section">
            <span class="title">
              {{ $job->subject }}
            </span>
          </div>
        </div>

        <div class="page-content-section no-padding ">
          <div class="job-top-section">
            @include('layouts.buyer.section.job_top_links')
            {{ show_messages() }}
          </div>

          <div class="applicants-section job-content-section">
            <div class="job-box-section">
              <div class="job-nav-section">
                @include('layouts.buyer.section.job_nav_links')
              </div>
              <div class="job-box-content">
              @if (!$contracts || !count($contracts))
                <div class="text-center container no-applicants-message">
                  {{ trans('job.no_applicants')}}
                </div>
              @else
                <div class="applicants-wrapper">
                @foreach ($contracts as $contract)
                  <div class="applicant-row clearfix">
                    <div class="pull-left user-avatar">
                      <img alt="" class="img-circle" src="{{avatarUrl($contract->contractor)}}" width="64" height="64" />
                    </div>
                    <div class="pull-left applicant-info">
                      <div class="user-info row">
                        <div class="col-sm-7">
                          <div class="user-name">
                            <a href="{{ route('job.application_detail', ['id'=>$contract->application_id]) }}">{{$contract->contractor->fullname()}}</a></div>
                          <div class="user-title">{{$contract->contractor->profile? $contract->contractor->profile->title:""}}</div>
                          <div class="user-location">
                            <span>{{ $contract->contractor->contact->country->name }}</span>
                          </div>
                          
                          @if ($contract->status == contract::STATUS_OFFER)
                          <div class="contract-status status-offer">
                            <span class="status">{{ strtoupper(trans('common.offer')) }}</span> - {{ ago($contract->created_at) }}
                          </div>
                          @elseif ($contract->status == Contract::STATUS_OPEN)
                          <div class="contract-status status-hired">
                            <span class="status">{{ strtoupper(trans('common.hired')) }}</span> - {{ date("M d, Y", strtotime($contract->started_at)) }}
                          </div>
                          @endif

                          <div class="user-skill clearfix">
                            @foreach ($contract->contractor->skills as $skill)
                            <div class="skill pull-left">
                              {{ $skill->name }}
                            </div>
                            @endforeach
                          </div>
                        </div>
                        <div class="col-sm-5 action-section">
                          @if ($contract->type == Project::TYPE_HOURLY)
                          <div class="price">
                            ${{ formatCurrency($contract->price) }} / hr
                          </div>
                          <div class="weekly-limit">
                            @if ($contract->limit == 0)
                              {{ trans('contract.no_limit') }}
                            @else
                              {{ trans('common.n_max_hours_per_week', array('n'=>$contract->limit)) }}
                            @endif
                          </div>
                            @if ($contract->is_allowed_manual_time)
                            <div class="allow-manual-log">
                              {{ trans('contract.allowed_log_manual') }}
                            </div>
                            @endif
                          @else
                          <div class="price">
                            ${{ formatCurrency($contract->price) }}
                          </div>
                          @endif

                          @if ($contract->status != Contract::STATUS_OFFER)
                          <div class="action-links">
                            <a href="{{ route('contract.contract_view', ['id'=>$contract->id]) }}">{{ trans('job.contract_detail') }}</a>
                          </div>
                          @endif
                        </div>
                      </div>

                      <div class="row">
                        
                      </div>

                    </div>
                  </div><!-- END OF .applicants-wrapper -->
                  @endforeach
                </div>
                {!! $contracts->render() !!}
              @endif
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection