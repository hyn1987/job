<?php
/**
 * Transactions Page (report/usertransactions)
 *
 * @author  - brice
 */

use Wawjob\Transaction;

?>
@extends('layouts/admin/index')
@section('actions')
  @if ($u && (in_array('user_buyer', $role_slugs) || in_array('user_freelancer', $role_slugs)))  
    <li><a href="{{ route('admin.contract.list') }}?{{ in_array('user_freelancer', $role_slugs)?'lancer':'buyer' }}={{ $u->username }}"><button type="button" class="btn-cancel btn btn-info btn-sm"><i class="fa fa-list-ul"></i> Contract List</button></a></li> 
  @endif
  <li><a href="{{ route('admin.user.list') }}"><button type="button" class="btn-cancel btn btn-info btn-sm"><i class="fa fa-list-ul"></i> User List</button></a></li>
@endsection
@section('content')
<script>
var date_from = '{{ $dates['from'] }}', 
    date_to   = '{{ $dates['to'] }}';
</script>
<div class="row page-body">
  <div class="col-sm-12">
    <div class="title-section clearfix">
      <div class="row">
        <div class="col-sm-9 col-xs-12 infoset">
          <img class="avatar img-circle for-collapse" width="32px" height="32px" src="{{ avatarUrl($u, 32) }}">
          <h5>
            @if ($u->contact){!! $u->contact->country ? '<span class="marker country"><img src="/assets/images/common/flags/' . strtolower($u->contact->country->charcode) . '.png" /></span>' : '' !!}@endif
            {{ $u->username }} 
            <span class="star_wrapper" title=""><div class="rating_wrapper"><div class="star set set5" style="width: {{ $u->getRatingPercent() }}%;" title=""></div><div class="star set5"></div></div></span>
            <span class="marker email"><i class="fa fa-envelope"></i>&nbsp;{{ $u->email }}</span>
            <span class="marker user-type user-type-@if ($u->contact){{ $u->userType() }}@endif">
              @if ($u->contact){{ trans('common.user.types.' . $u->userType()) }}@endif
            </span>
          </h5>
        </div>
        <div class="col-sm-3 col-xs-12">
          <div class="balance pull-right">
            Balance : {{ $balance<0? '($'.formatCurrency(abs($balance)).')' : '$'.formatCurrency($balance) }}
          </div>
        </div>
      </div>
    </div>
    <div class="page-content-section buyer-report-page report-transactions-page">
      <div class="filter-section clearfix">
        <form id="frm_transactions_filter" method="POST">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">

          <div class="date-filter-section form-group pull-left">
            <div class="date-filter">
              @if ($prev)
              <a class="prev-unit" href="#" data-range="{{$prev}}"><i class="glyphicon glyphicon-chevron-left"></i></a>
              @endif
              <div class="input-group" id="date_range">
                <input type="text" class="form-control" name="date_range" value="{{ $dates['from']." - ".$dates['to'] }}">
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

          <div class="transaction-type-section clearfix">
            <div class="section-content pull-right">
              <select class="form-control" id="transaction_type" name="transaction_type" placeholder="Transaction Type">
                <option value="all" {{ $type=='all'? 'selected':'' }}>All Transactions</option>
                <option value="{{ Transaction::TYPE_FIXED }}" 
                        {{ $type==(string)Transaction::TYPE_FIXED? 'selected':'' }}>Fixed</option>
                <option value="{{ Transaction::TYPE_HOURLY }}" 
                        {{ $type==(string)Transaction::TYPE_HOURLY? 'selected':'' }}>Hourly</option>
                <option value="{{ Transaction::TYPE_BONUS }}" 
                        {{ $type==(string)Transaction::TYPE_BONUS? 'selected':'' }}>Bonus</option>
                <option value="{{ Transaction::TYPE_CHARGE }}" 
                        {{ $type==(string)Transaction::TYPE_CHARGE? 'selected':'' }}>Charge</option>
                <option value="{{ Transaction::TYPE_WITHDRAWAL }}" 
                        {{ $type==(string)Transaction::TYPE_WITHDRAWAL? 'selected':'' }}>Withdrawal</option>
                <option value="{{ Transaction::TYPE_REFUND }}" 
                        {{ $type==(string)Transaction::TYPE_REFUND? 'selected':'' }}>Refund</option>
              </select>
            </div>
          </div>
          

        </form>
      </div><!-- END OF .filter-section -->

      <div class="transactions-section table-scrollable">
        <table class="table">
          <thead>
            <tr>
              <th>
                 #ID
              </th>
              <th>
                 Date
              </th>
              <th>
                 Type
              </th>
              <th>
                 Description
              </th>
              <th>
                 Freelancer
              </th>
              <th class="text-right">
                 Amount
              </th>
            </tr>
          </thead>
          <tbody>
            @if (empty($transactions))
              <tr>
                <td colspan="6">
                  <div class="empty-data-section">
                    No Transactions
                  </div>
                </td>
              </tr>
            @else
              @foreach ($transactions as $t)
              <tr>
                <td>
                   {{ $t->id }}
                </td>
                <td>
                  {{ date_format(date_create($t->created_at), "M d, Y") }}
                </td>
                <td>
                   {{ $t->type_string() }}
                </td>
                <td>
                   {!! $t->desc !!}
                </td>
                <td>
                   {{ $t->contractor_fullname }}
                </td>
                <td class="text-right">
                   {{ $t->amount<0? '($'.formatCurrency(abs($t->amount)).')' : '$'.formatCurrency($t->amount) }}
                </td>
              </tr>
              @endforeach
            @endif

          </tbody>
        </table>
      </div><!-- END OF .transactions-section -->

      <div class="statement-section row">
        <div class="col-sm-offset-8 col-sm-4 col-xs-12">
          <div class="statement-label">
            <div><strong>Statement Period</strong></div>
            <div>{{ $dates['from']." - ".$dates['to'] }}</div>
          </div>
          <div class="statement-content">
            <table class="">
              <tbody>
                <tr>
                  <td class="info-label"><strong>Begining Balance</strong></td>
                  <td class="amount"><strong>
                    {{ $statement['beginning']<0? '($'.formatCurrency(abs($statement['beginning'])).')' : '$'.formatCurrency($statement['beginning']) }}
                  </strong></td>
                </tr>
                <tr>
                  <td class="info-label">Total Debits</td>
                  <td class="amount">
                    {{ $statement['debits']<0? '($'.formatCurrency(abs($statement['debits'])).')' : '$'.formatCurrency($statement['debits']) }}
                  </td>
                </tr>
                <tr>
                  <td class="info-label">Total Credits</td>
                  <td class="amount">
                    {{ $statement['credits']<0? '($'.formatCurrency(abs($statement['credits'])).')' : '$'.formatCurrency($statement['credits']) }}
                  </td>
                </tr>
                <tr>
                  <td class="info-label">Total Change</td>
                  <td class="amount">
                    {{ $statement['change']<0? '($'.formatCurrency(abs($statement['change'])).')' : '$'.formatCurrency($statement['change']) }}
                  </td>
                </tr>
                <tr>
                  <td class="info-label last"><strong>Ending Balance</strong></td>
                  <td class="amount last"><strong>
                    {{ $statement['ending']<0? '($'.formatCurrency(abs($statement['ending'])).')' : '$'.formatCurrency($statement['ending']) }}
                  </strong></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection