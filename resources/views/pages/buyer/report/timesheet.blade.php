<?php
/**
 * Budget Page (report/budget)
 *
 * @author  - nada
 */
?>
@extends('layouts/buyer/report')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
</div>
<div class="page-content-section buyer-report-page report-timesheet-page">
  <div class="filter-section clearfix">
    <form id="frm_timesheet_filter" method="POST">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      {{ show_messages() }}
      
      <div class="date-filter-section form-group pull-left">
        <div class="date-filter">
          @if ($prev)
          <a class="prev-unit" href="#" data-range="{{$prev}}"><i class="glyphicon glyphicon-chevron-left"></i></a>
          @endif
          <div class="input-group" id="date_range">
            @include("pages.snippet.daterange")
            <span class="input-group-btn">
              <button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
            </span>
          </div>
          @if ($next)
          <a class="next-unit" href="#" data-range="{{$next}}"><i class="glyphicon glyphicon-chevron-right"></i></a>
          @endif
        </div>
      </div>

      <div class="contract-filter-section">
        @include("pages.buyer.report.section.contract_selector")
      </div>
    </form>
  </div><!-- END OF .filter-section -->

  <div class="report-mode-section clearfix">
    <div class="section-content pull-right">
      <div class="mode-item {{ $mode=='day'?  'active':'' }}" data-mode="day">{{ trans('report.in_days') }}</div>
      <div class="mode-item {{ $mode=='week'? 'active':'' }}" data-mode="week">{{ trans('report.in_weeks') }}</div>
      <div class="mode-item {{ $mode=='month'?'active':'' }}" data-mode="month">{{ trans('report.in_months') }}</div>
    </div>
  </div>

  <div class="timesheet-section table-scrollable">
    <table class="table">
      <thead>
        <tr>
          <th>{{ trans('report.date') }}</th>
          <th>{{ trans('report.freelancer') }}</th>
          <th>{{ trans('report.hours') }}</th>
          <th class="text-right">{{ trans('report.amount') }}</th>
        </tr>
      </thead>
      <tbody>

        @if (empty($r_data))
          <tr>
            <td colspan="4">
              <div class="empty-data-section">{{ trans('report.no_matching_records') }}</div>
            </td>
          </tr>
        @else
          @foreach ($r_data as $d)
          <tr>
            <td>{{ $d['date']}}</td>
            <td>{{ $d['freelancer']->fullname() }}</td>
            <td>{{ formatMinuteInterval($d['mins']) }}</td>
            <td class="text-right">${{ formatCurrency($d['amount']) }}</td>
          </tr>
          @endforeach
        @endif

      </tbody>
    </table>
  </div><!-- END OF .timesheet-section -->
</div>
@endsection