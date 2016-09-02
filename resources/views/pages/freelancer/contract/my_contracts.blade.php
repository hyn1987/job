<?php
/**
 * My Contracts Page (my_contracts)
 *
 * @author  - Ri Chol Min
 */

use Wawjob\ProjectApplication;
use Wawjob\Contract;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <div class="view-section job-content-section">
          <div class="title-section">
            <span class="title">{{ trans('job.contracts') }}</span>
          </div>
          {{ show_messages() }}
          <form id="MyContractsForm" class="form-horizontal" method="post" action="{{ route('contract.all_contracts') }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="search-section">
              <input type="text" value="" placeholder="{{ trans('contract.name_or_title') }}" class="form-control" name="search_contracts" id="SearchContracts">
            </div>
            <div class="list-header row">
              <div class="col-md-6">{{ trans('common.word.contract') }}</div>
              <div class="col-md-2">{{ trans('contract.time_period') }}</div>
              <div class="col-md-2">{{ trans('contract.terms') }}</div>
            </div>
            <div class="box-section margin-bottom-35">
              @if ( count($contracts) > 0 )              
              @foreach ( $contracts as $contract )
              <div class="list-body clearfix {{ $contract->status == Contract::STATUS_CLOSED ? 'closed-job' : '' }}">
                <div class="col-md-6 subject">
                  <a class="title" href="{{ route('contract.contract_view', ['id'=>$contract->id]) }}"/>{{$contract->title}}</a>
                  <div class="details">{{ trans('contract.hired_by_sb', ['sb' => $contract->buyer->fullname()]) }}</div>
                </div>
                <div class="col-md-2 period">
                  {{ getFormattedDate($contract->created_at) }}
                  <span> - </span>
                  @if ( $contract->status == Contract::STATUS_CLOSED )
                  {{getFormattedDate($contract->updated_at)}}
                  @else
                  {{ trans('common.present') }}
                  @endif
                </div>
                <div class="col-md-2">
                  <div class="terms">
                  @if ( $contract->type == 1 )
                  @if ( $contract->limit == 0 )
                    {{ trans('contract.no_limit') }}</div><div class="terms">${{$contract->price}}/{{ trans('common.hour') }}
                  @else
                    {{ trans('common.n_max_hours_per_week', ['n' => $contract->limit]) }}</div><div class="terms">${{$contract->price}}/{{ trans('common.hour') }}
                  @endif
                  @else
                    {{ trans('contract.fixed_amount') }}</div><div class="terms">${{$contract->price}}
                  @endif
                  </div>
                </div>
                <div class="col-md-2">
                  @if ( $contract->status == Contract::STATUS_OPEN && $contract->type == 1 )
                    <a class="link" href="{{ route('workdiary.view', ['id'=>$contract->id]) }}">{{ trans('common.work_diary') }} ></a>
                  @endif
                </div>
              </div>
              @endforeach
              @endif
            </div>
            <div class="pagination-bottom-section">{!! $contracts->appends(['search_contracts' => $sort])->render() !!}</div>
          </form>
      </div>      
  </div>
</div>
@endsection