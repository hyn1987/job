<?php
/**
 * Make Offer Page (job/{id}/make-offer)
 *
 * @author  - nada
 */

use Wawjob\Project;
?>
@extends('layouts/buyer/job')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_make_offer" method="post" action="{{ route('job.make_offer', ['id'=>$job->id, 'uid'=>$contractor->id])}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="job-top-section">
          <div class="title-section clearfix">
            <div class="user-avatar pull-left margin-right-20">
              <img src="{{ avatarUrl($contractor) }}" alt="" class="img-circle" width="64" height="64">
            </div>
            <div class="title pull-left">
              Hire <span>{{ $contractor->fullname() }}</span>
            </div>
          </div>
          {{ show_messages() }}
        </div>

        <div class="job-content-section no-padding row">
          <div class="col-md-9 col-sm-8">
            <div class="box-section">
              <div class="sub-section">
                <div class="title">{{ trans('job.contract_details') }}</div>
                <div class="item">
                  <div class="item-label"><strong>{{ trans('job.related_job') }}</strong></div>
                  <div class="item-info">
                    <a href="{{ route('job.view', ['id'=>$job->id]) }}" target="_blank">{{ $job->subject }}</a>
                  </div>
                </div>

                {{-- Contract Title --}}
                <div class="item form-group">
                  <div class="item-label"><strong>{{ trans('job.contract_title') }}</strong></div>
                  <div class="form-line-wrapper">
                    <input type="text" class="form-control" id="contract_title" name="contract_title" placeholder="" data-rule-required="true" 
                      value="{{ old('contract_title')!==null ? old('contract_title') : $job->subject }}">
                  </div>
                </div>

                @if ($job->type==Project::TYPE_HOURLY)
                {{-- Hourly Rate --}}
                <div class="item form-group">
                  <div class="item-label"><strong>{{ trans('job.hourly_rate') }}</strong></div>
                  <div class="item-info form-line-wrapper input-group price-field">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control" id="hourly_rate" name="hourly_rate" placeholder="" data-rule-required="true" data-rule-number="true" 
                      value="{{ old('hourly_rate')!==null ? old('hourly_rate') : $hourly_rate }}">
                    <span class="input-group-addon">/hr</span>
                  </div>
                  <div class="margin-top-10 contract-limit">{{ trans('job.sb_rate_is', ['sb'=>$contractor->fullname(), 'rate'=>$contractor->profile? $contractor->profile->rate:"" ]) }}</div>
                </div>
                <div class="item">
                  <div class="item-label"><strong>{{ trans('common.limit') }}</strong></div>
                  <div class="item-info">
                    <select class="form-control" name="hourly_limit" id="hourly_limit">
                      <option value="0" {{$hourly_limit==0? "SELECTED":""}}>{{ trans('contract.no_limit') }}</option>
                      <option value="1" {{$hourly_limit==1? "SELECTED":""}}>1 {{ trans('job.hour_per_week') }}</option>
                      @for ($i=2; $i<=100; $i++)
                      <option value="{{ $i }}" {{$hourly_limit==$i? "SELECTED":""}}>{{ trans('job.n_hours_per_week', ['n'=>$i]) }}</option>
                      @endfor
                    </select>
                    <span>= $<label id="hourly_total_price">0.00</label> {{ trans('job.max_week') }}</span>
                  </div>
                  <div class="margin-top-10">
                    <div class="checkbox-inline">
                      <input type="checkbox" name="manual_log" id="manual_log" class="checkbox" data-required="0" value="1" {{  old('manual_log')? "CHECKED" : "" }}>
                      <label for="manual_log">{{ trans('job.allow_freelancer_manual_log') }}</label>
                    </div>
                  </div>
                </div>
                @else
                <div class="item">
                  <div class="item-label"><strong>{{ trans('common.fixed_price') }}</strong></div>
                  <div class="item-info form-line-wrapper input-group price-field">
                    <input type="text" class="form-control" id="fixed_price" name="fixed_price" placeholder="" data-rule-required="true" data-rule-number="true" 
                      value="{{ old('fixed_price')!==null ? old('fixed_price') : $fixed_price }}">
                    <span class="input-group-addon">$</span>
                  </div>
                </div>
                @endif
              </div>

              <div class="divider"></div>

              <div class="sub-section">
                <div class="form-group margin-top-10">
                  <div class="form-line-wrapper checkbox">
                    <input type="checkbox" name="agree_terms" id="agree_terms" class="checkbox" data-rule-required="true" value="1">
                    <label for="agree_terms">{{ trans('job.agree_term') }}</label>
                  </div>
                </div>
                <div class="form-group padding-top-50">
                  <div class="">
                    @if ($validate)
                    <button type="submit" class="btn btn-primary">{!! trans('job.hire_sb', ['sb'=>$contractor->fullname() ]) !!}</span></button>
                    @endif
                    <a href="{{ route('job.applicants', ['id'=>$job->id]) }}" class="margin-left-20">{{ trans('common.cancel') }}</a>
                  </div>
                </div>
              </div>
            </div><!-- END OF .box-section -->
          </div>
          <div class="col-md-3 col-sm-4">
            <div class="sub-section">
              <!-- <strong>How do hourly contracts work?</strong> -->
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection