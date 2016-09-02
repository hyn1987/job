<?php
/**
 * Contract Detail Page (contract/{id})
 *
 * @author  - nada
 */

use Wawjob\Project;
use Wawjob\Contract;
use Wawjob\Transaction;

?>
@extends('layouts/buyer/index')

@section('content')
<input type="hidden" name="cid" value="{{ $contract->id }}">
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <div class="view-section job-content-section">
        <div class="title-section">
          <span class="title">{{$contract->title}}</span>&nbsp;
          @if ($contract->status == Contract::STATUS_CLOSED)
          <span class="contract-status status-closed">[{{ strtoupper(trans('contract.closed')) }}]</span>
          @elseif ($contract->status == Contract::STATUS_PAUSED)
           <span class="contract-status status-paused">[{{ strtoupper(trans('contract.paused')) }}]</span>
          @endif
        </div>
        <div class="section clearfix">
          <div class="contracts pull-left">{!! trans('contract.hired_sb', ['name' => $contract->contractor->fullname()]) !!}</div>
          <div class="link pull-right"><a href="{{ route('job.view', ['id' => $contract->project_id]) }}">{{ trans('contract.view_original_job_posting') }}</a></div>
        </div>
        {{ show_messages() }}
        <div class="box-section clearfix">
          <div class="col-md-6 col-sm-6">
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">{{ trans('contract.contractor') }}</div>
              <div class="col-md-8 col-sm-9"><a href="{{ route('user.profile', $contract->contractor->id) }}"><strong>{{$contract->contractor->fullname()}}</strong></a></div>
            </div>                
            
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">{{ trans('contract.start_date') }}</div>
              <div class="col-md-8 col-sm-9">{{ getFormattedDate($contract->started_at) }}</div>
            </div>
            @if ($contract->status == Contract::STATUS_CLOSED)
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">{{ trans('contract.end_date') }}</div>
              <div class="col-md-8 col-sm-9">{{ getFormattedDate($contract->ended_at) }}</div>
            </div>
            @endif
            
            <div class="content clearfix margin-bottom-20">
              <div class="col-md-12 col-sm-12">
                <a class="links" href="{{ route('message.list') }}?thread={{$contract->application->getMessageThread()->id}}">{{ trans('contract.send_message') }}</a> 
                @if ($contract->type == Project::TYPE_HOURLY)
                | 
                <a class="links " href="{{ route('workdiary.view', ['id'=>$contract->id])}}">{{ trans('contract.view_work_diary') }}</a>
                @endif
              </div>
            </div>

            @if ( $contract->status == Contract::STATUS_CLOSED )
              @if (  $contractFeedback->buyer_feedback ) 
              <div class="alert alert-info feedback" role="alert">
                <div class="content clearfix">
                  <div class="col-md-4 col-sm-3">{{ trans('contract.score') }}</div>
                  <div class="col-md-8 col-sm-9">
                    <input name="rate" type="radio" class="rate" value="1" {{ ($contractFeedback->buyer_score == 1) ? 'checked' : '' }} /> 
                    <input name="rate" type="radio" class="rate" value="2" {{ ($contractFeedback->buyer_score == 2) ? 'checked' : '' }} /> 
                    <input name="rate" type="radio" class="rate" value="3" {{ ($contractFeedback->buyer_score == 3) ? 'checked' : '' }} /> 
                    <input name="rate" type="radio" class="rate" value="4" {{ ($contractFeedback->buyer_score == 4) ? 'checked' : '' }} /> 
                    <input name="rate" type="radio" class="rate" value="5" {{ ($contractFeedback->buyer_score == 5) ? 'checked' : '' }} />
                  </div>
                </div>
                <div class="content clearfix">
                  <div class="col-md-4 col-sm-3">{{ trans('contract.feedback') }}</div>
                  <div class="col-md-8 col-sm-9">{!! nl2br($contractFeedback->buyer_feedback) !!}</div>
                </div>
              </div>
              @else
              <div class="alert alert-info" role="alert">
                <div class="content clearfix">
                  <span>{{ trans('contract.no_feedback_given') }}</span>
                </div>
              </div>
              @endif

              @if ( !$contractFeedback->freelancer_feedback )
                <form id="CloseContractForm" class="form-horizontal" method="post" action="{{ route('contract.contract_view', ['id' => $contract->id]) }}" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <input type="hidden" name="close_action" value="1">
                  <div class="content clearfix">
                    <div class="col-md-8 col-sm-6"><a id="closeContractBtn" class="custom-btn btn btn-primary" href="{{ route('contract.feedback', ['id' => $contract->id]) }}">{{ trans('contract.give_feedback') }}</a></div>
                  </div>
                </form>
              @endif
            @else
              <form id="CloseContractForm" class="form-horizontal" method="post" action="{{ route('contract.contract_view', ['id' => $contract->id]) }}" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="close_action" value="1">
                <div class="content clearfix">
                  <div class="col-md-8 col-sm-6"><a id="closeContractBtn" class="custom-btn btn btn-primary" href="{{ route('contract.feedback', ['id' => $contract->id]) }}">{{ trans('contract.close_contract') }}</a></div>
                </div>
              </form>
            @endif
            
          </div>

          <div class="col-md-6 col-sm-6">
            <div class="content clearfix">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.job_type') }}</div>
              <div class="col-sm-8 col-xs-9">{{ ($contract->type == Project::TYPE_HOURLY) ? trans('common.hourly_job') : trans('common.fixed_price_job') }}</div>
            </div>
            @if ($contract->type == Project::TYPE_HOURLY)
            <div class="content clearfix">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.price') }}</div>
              <div class="col-sm-8 col-xs-9">$<strong>{{ formatCurrency($contract->price) }}</strong>/{{ trans('common.hr') }}</div>
            </div>
            <div class="content clearfix">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.weekly_limit') }}</div>
              <div class="col-sm-8 col-xs-9">
                <div class="section-edit-limit" style="display: none;">
                  <input type="text" name="weekly_limit" value="{{ $contract->limit }}" size="2"> {{ trans('contract.hours') }}
                  <a class="pointer update-limit">{{ trans('contract.update') }}</a>&nbsp;
                  <a class="pointer cancel-edit-limit">{{ trans('contract.cancel') }}</a>
                </div>
                <div class="section-view-limit">
                  <span class="strong limit-value">@if (App::getLocale() == 'en')
                  {{ $contract->limit == 0 ? 'No Limit' : $contract->limit . ' ' . str_plural('hour', $contract->limit) }}
                  @else
                    {{ $contract->limit == 0 ? 'no_limit' : trans('common.n_hrs', ['n' =>  $contract->limit]) }}
                  @endif
                  </span>&nbsp;
                  <a class="pointer edit-limit">{{ trans('contract.edit') }}</a>
                </div>
              </div>
            </div>
            <div class="content clearfix margin-bottom-30">
              <div class="small-font col-sm-12 col-xs-12">{{ trans('contract.'.($contract->is_allowed_manual_time == 1 ? 'allowed_log_manual' : 'not_allowed_log_manual')) }}</div>
            </div>

            <div class="content clearfix this-week">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.this_week') }}</div>
              <div class="col-sm-4 col-xs-4"><strong>{{ formatMinuteInterval($contract->this_week_mins) }}</strong> hrs</div>
              <div class="col-sm-4 col-xs-5">$<strong>{{ formatCurrency($contract->buyerPrice($contract->this_week_mins)) }}</strong></div>
            </div>
            <div class="content clearfix last-week">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.last_week') }}</div>
              <div class="col-sm-4 col-xs-4"><strong>{{ formatMinuteInterval($contract->last_week_mins) }}</strong> hrs</div>
              <div class="col-sm-4 col-xs-5">$<strong>{{ formatCurrency($contract->buyerPrice($contract->last_week_mins)) }}</strong></div>
            </div>
            <div class="content clearfix total-week">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.total_hours') }}</div>
              <div class="col-sm-4 col-xs-4"><strong>{{ formatMinuteInterval($contract->all_week_mins) }}</strong> hrs</div>
              <div class="col-sm-4 col-xs-5">$<strong>{{ formatCurrency($contract->buyerPrice($contract->all_week_mins)) }}</strong></div>
            </div>

            <div class="content clearfix margin-top-30">
              <div class="col-xs-12">
                <a class="links " href="#modalPayment" data-type="bonus" data-toggle="modal" data-backdrop="static">{{ trans('contract.give_bonus') }}</a>
              </div>
            </div>
            @else
            <div class="content clearfix">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.price') }}</div>
              <div class="col-sm-8 col-xs-9">$<strong>{{ formatCurrency($contract->price) }}</strong></div>
            </div>
            <div class="content clearfix">
              <div class="col-sm-4 col-xs-3">{{ trans('contract.paid_amount') }}</div>
              <div class="col-sm-4 col-xs-9">
                <strong>{{ $total_paid >=0 ? '($'.formatCurrency($total_paid).')' : '$'.formatCurrency(abs($total_paid)).'' }}
                </strong>
              </div>
            </div>
            <div class="content clearfix">
              <div class="col-xs-12">
                <a class="links" href="#modalPayment" data-type="fixed" data-toggle="modal" data-backdrop="static">{{ trans('contract.make_payment') }}</a> 
                | 
                <a class="links" href="#modalPayment" data-type="bonus" data-toggle="modal" data-backdrop="static">{{ trans('contract.give_bonus') }}</a>
              </div>
            </div>  
            @endif

            <div class="content">
              <div class="paid-transactions">
                @if (count($paid_transactions))
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#ID</th>
                        <th>{{ trans('report.date') }}</th>
                        <th>{{ trans('report.type') }}</th>
                        <th class="text-right">{{ trans('report.amount') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($paid_transactions as $t)
                      <tr class="status-{{ strtolower($t->status_string()) }}">
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->status==Transaction::STATUS_AVAILABLE? 
                            date_format(date_create($t->done_at), "M d, Y"):
                            date_format(date_create($t->created_at), "M d, Y") }}
                        </th>
                        <td>{{ $t->type_string() }}</td>
                        <td class="text-right">
                           {{ $t->amount>0? '$'.formatCurrency($t->amount).'' : '($'.formatCurrency(abs($t->amount)).')' }}
                        </th>
                      </tr>
                      @endforeach
                      <tr>
                        <td colspan="4" class="text-right">
                          <strong>{{ $total_paid>0? '$'.formatCurrency($total_paid) : '($'.formatCurrency(abs($total_paid)).')' }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                @endif
              </div><!-- END OF .paid-transactions -->
            </div>

          </div>
        </div>
      </div>
  </div>
</div>

@include('pages.buyer.contract.section.payment')

@endsection