<?php
/**
 * Work Diary Page (work_diary/)
 *
 * @author  - Ri Chol Min
 */

use Wawjob\Contract;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <div class="view-section job-content-section">
          <div class="title-section">
            <span class="title">{{ trans('common.work_diary') }}</span>
          </div>
          <div class="choose-project">
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
                <a class="title" href=""/>{{$contract->title}}</a>
                <div class="details">Hired by {{$contract->buyer->fullname()}}</div>
              </div>
              <div class="col-md-2 period">
                {{getFormattedDate($contract->created_at)}}
                <span> - </span>
                @if ( $contract->status == Contract::STATUS_CLOSED )
                {{getFormattedDate($contract->updated_at)}}
                @else
                present
                @endif
              </div>
              <div class="col-md-2">
                <div class="terms">
                @if ( $contract->isHourly() )
                @if ( $contract->limit == 0 )
                  No limit</div><div class="terms">${{$contract->price}}/hour
                @else
                  {{ $contract->limit }} maximum hours/week</div><div class="terms">${{$contract->price}}/hour
                @endif
                @else
                  Fixed Amount</div><div class="terms">${{$contract->price}}
                @endif
                </div>
              </div>
              <div class="col-md-2">
                @if ( $contract->status == Contract::STATUS_OPEN && $contract->type == 1 )
                  <a class="link" href="">Work Diary ></a>
                @endif
              </div>
            </div>
            @endforeach
            @endif
          </div>
      </div>      
  </div>
</div>
@endsection