<?php
/**
 * All Job Page (job/all)
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
      <form id="form_all_contracts" method="post" action="{{ route('job.all')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        {{ show_messages() }}

        <div class="open-jobs section">
          <div class="title-section">
            <span class="title">{{ trans('contract.all_contracts') }}</span>
          </div>

          <div class="section-content">
          @if (!count($contracts))
            <div class="empty-contract text-center">You have no contract</div>
          @else
            @foreach ($contracts as $contract)
            <div class="object-item row {{ strtolower($contract->status_string()) }}">
              <div class="col-xs-6">
                <div class="contract-title">
                  <a href="{{ route('contract.contract_view', ['id'=>$contract->id]) }}">{{ $contract->title }}</a>
                </div>
                <div class="contractor-name">
                  {{ $contract->contractor->fullname() }}
                </div>
                <div class="contract-type">
                  @if ($contract->type == Project::TYPE_HOURLY)
                  <span>{{ trans('common.hourly_job') }}</span>
                  @elseif ($contract->type == Project::TYPE_FIXED)
                  <span>{{ trans('common.fixed_price_job') }}</span>
                  @endif
                </div>
                @if ($contract->status == Contract::STATUS_PAUSED)
                <div class="contract-status status-paused">{{ trans('contract.contract_is_paused') }}</div>
                @endif
              </div>
              <div class="col-xs-3">
                <div class="period">
                  {{ date("M d, Y", strtotime($contract->started_at)) }} - 
                  @if ($contract->status == Contract::STATUS_CLOSED)
                    {{ date("M d, Y", strtotime($contract->ended_at)) }}
                  @else
                    {{ trans('common.present') }}
                  @endif
                </div>
              </div>
              <div class="col-xs-3">
                <div class="contract-term">
                @if ($contract->type == Project::TYPE_HOURLY)
                  <div class="price">{{ trans('profile.hourly_rate', ['rate' => formatCurrency($contract->price)]) }}</div>

                  <div class="hourly-limit">
                  @if($contract->limit)
                    {{ trans('common.n_max_hours_per_week', array('n'=>$contract->limit)) }}
                  @else
                    {{ trans('contract.no_limit') }}
                  @endif  
                  </div>

                  <div>
                    {{ trans('contract.allowed_log_manual') }}
                  </div>
                @else
                  <div class="price">${{ formatCurrency($contract->price) }}</div>
                @endif
                </div>

                @if ($contract->type == Project::TYPE_HOURLY)
                <div class="workdiary-action">
                  <a href="{{ route('workdiary.view', ['cid'=>$contract->id]) }}">{{ trans('job.view_work_diary') }}</a>
                </div>
                @endif
                
              </div>
            </div>
            @endforeach
          @endif
          </div>
          <div class="clearfix">
            <div class="pull-right">
              {!! $contracts->render() !!}
            </div>
          </div>
        </div> 
      </form>
    </div>
  </div>
</div>
@endsection