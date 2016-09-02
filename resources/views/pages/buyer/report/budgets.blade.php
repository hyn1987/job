<?php
/**
 * Budget Page (report/budget)
 *
 * @author  - nada
 */
?>
@extends('layouts/buyer/report')

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  <div class="balance pull-right">
    {{ trans('common.balance') }} : {{ $balance<0? '($'.formatCurrency(abs($balance)).')' : '$'.formatCurrency($balance) }}
  </div>
</div>
<div class="page-content-section buyer-report-page">
  {{ show_messages() }}
  <div class="summary-section section">
    <div class="section-content">
      <div class="row">
        <div class="col-sm-6 col-xs-12">
          <div class="info-line row">
            <div class="col-xs-9">{{ trans('report.total_pendings') }}:</div>
            <div class="col-xs-3 text-right">
              <strong>
                {{ $total_pendings<=0? '($'.formatCurrency(abs($total_pendings)).')' : '$'.formatCurrency($total_pendings).'' }}
              </strong>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-xs-12 text-right">
            <a class="btn btn-primary charge-action" href="{{ route('payment.charge') }}">{{ trans('common.charge') }}</a>
        </div>
      </div>
    </div>
  </div>

  <div class="transactions-section table-scrollable">
    <div class="section-title">{{ trans('report.pending_transactions') }}</div>
    <table class="table">
      <thead>
        <tr>
          <th>#ID</th>
          <th>{{ trans('report.date') }}</th>
          <th>{{ trans('report.type') }}</th>
          <th>{{ trans('report.description') }}</th>
          <th>{{ trans('report.freelancer') }}</th>
          <th class="text-right">{{ trans('report.amount') }}</th>
        </tr>
      </thead>
      <tbody>
        @if (!count($transactions))
          <tr>
            <td colspan="6">
              <div class="empty-data-section">{{ trans('report.no_pending_transactions') }}</div>
            </td>
          </tr>
        @else
          @foreach ($transactions as $t)
          <tr class="status-{{ strtolower($t->status_string()) }}">
            <td>{{ $t->id }}</td>
            <td>{{ getFormattedDate($t->created_at) }}</td>
            <td>{{ $t->type_string() }}</td>
            <td>{!! $t->desc !!}</td>
            <td>{{ $t->contractor_fullname }}</td>
            <td class="text-right">
               {{ $t->amount<0? '($'.formatCurrency(abs($t->amount)).')' : '$'.formatCurrency($t->amount) }}
            </td>
          </tr>
          @endforeach
        @endif

      </tbody>
    </table>
  </div><!-- END OF .transactions-section -->
</div>
@endsection