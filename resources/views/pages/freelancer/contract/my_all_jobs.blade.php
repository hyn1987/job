<?php
/**
 * My Jobs Page (my_all_jobs)
 *
 * @author  - Ri Chol Min
 */

use Wawjob\ProjectApplication;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <div class="view-section job-content-section">       
          <div class="row">
            <div class="col-md-9 col-sm-8 clearfix">
              <div class="title-section">
                <span class="title">{{ trans('job.my_jobs') }}</span>
                <a class="page-link" href="{{route('contract.all_contracts')}}">{{ trans('job.view_all_contracts') }} ></a>
              </div>
              {{ show_messages() }}
              @if ( count($offers) > 0 )
              <div class="job-type">{{ trans('job.offers') }}</div>
              @foreach ( $offers as $offer )
              <div class="small-box-section">
                <div class="sub-section">
                  <div class="title">{{$offer->title}}</div>
                  <div class="row">
                    <div class="col-md-9 col-sm-8">
                      <div class="block">
                        <div class="links"><a href="{{ route('job.view', ['id'=>$offer->project_id]) }}">{{ trans('job.job_detail') }}</a><span>|</span><a href="{{ route('message.list') }}">{{ trans('contract.send_message') }}...</a></div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                      <div class="block margin-top-10">
                        <div class="box-links"><a href="{{ route('job.apply_offer', ['id'=>$offer->id]) }}">{{ trans('job.view_offer') }} > </a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
              @endif
              @if ( count($hourly_jobs) > 0 )
              <div class="job-type">{{ trans('common.hourly') }}</div>
              @foreach ( $hourly_jobs as $hourly_job )
              <div class="small-box-section">
                <div class="sub-section">
                  <div class="title">{{$hourly_job->title}}</div>
                  <div class="row">
                    <div class="col-md-9 col-sm-8">
                      <div class="block">
                        <div class="contracts-desc">{{ trans('contract.hired_by_sb', ['sb' => $hourly_job->buyer->fullname()]) }}, {{ trans('contract.staffed_by_sb', ['sb' => $hourly_job->contractor->fullname()]) }}</div>
                        <div class="links"><a href="{{ route('contract.contract_view', ['id'=>$hourly_job->id]) }}">{{ trans('job.contract_detail') }}</a><span>|</span><a href="{{ route('message.list') }}">{{ trans('contract.send_message') }}...</a></div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                      <div class="block margin-top-10">
                        <div class="progress-view">{!! trans($hourly_job->limit == 0 ? 'contract.this_week_log_hours_no_limit' : 'contract.this_week_log_hours', ['log_hours' => formatMinuteInterval($hourly_job->week_mins), 'total' => $hourly_job->limit]) !!}</div>
                        <div class="box-links"><a href="{{ route('workdiary.view', ['id'=>$hourly_job->id]) }}">{{ trans('contract.view_work_diary') }} > </a></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
              @endif
              @if ( count($fixed_jobs) > 0 )
              <div class="job-type">Fixed</div>
               @foreach ( $fixed_jobs as $fixed_job )
              <div class="small-box-section">
                <div class="sub-section">
                  <div class="title">{{$fixed_job->title}}</div>
                  <div class="row">
                    <div class="col-md-9 col-sm-8">
                      <div class="block">
                        <div class="contracts-desc">{{ trans('contract.hired_by_sb', ['sb' => $fixed_job->buyer->fullname()]) }}, {{ trans('contract.staffed_by_sb', ['sb' => $fixed_job->contractor->fullname()]) }}</div>
                        <div class="links"><a href="{{ route('contract.contract_view', ['id'=>$fixed_job->id]) }}">{{ trans('job.contract_detail') }}</a><span>|</span><a href="{{ route('message.list') }}">{{ trans('contract.contract_detail') }}...</a></div>
                      </div>
                    </div>
                    <div class="col-md-3 col-sm-4">
                      <div class="block margin-top-10">
                        <div class="progress-view">$0 paid of ${{ $fixed_job->price }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
              @endif
            </div>
            <div class="col-md-3 col-sm-4">
              <div class="client-info">
                <div class="small-box-section no-padding">
                  <div class="sub-section">
                    <div class="divided-block title">{{ trans('contract.earnings') }}</div>
                    <div class="divided-block">
                      <div>Available now: - </div>
                      <a href="">{{ trans('contract.view_pending_earnings') }} > </a>
                    </div>
                    <div class="last-div-block">
                      <a href="">{{ trans('contract.learn_about_payment_schedule') }} > </a>
                    </div>                
                  </div>
                </div>
                <div class="links"><a href="{{ route('report.timelogs') }}">{{ trans('common.reports') }} > </a></div>
              </div>
            </div>
          </div>
        </form>
      </div>      
  </div>
</div>
@endsection