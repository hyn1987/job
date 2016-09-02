<?php
/**
 * Contract Detail Page (contract/{id})
 *
 * @author  - Ri Chol Min
 */

use Wawjob\Project;
use Wawjob\Contract;
use Wawjob\Transaction;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <div class="view-section job-content-section">       
        <div class="title-section">
          <span class="title">{{$contract->title}}</span><span class="close-mark">{{ ($contract->status == Contract::STATUS_CLOSED) ? '[CLOSED]' : ''}}</span>
        </div>
        {{ show_messages() }}
        <div class="section clearfix">
          <div class="contracts pull-left">Hired by <strong>{{$contract->buyer->fullname()}}</strong></div>
          <div class="past-time pull-left">Started {{ago($contract->started_at)}}</div>
          <div class="link pull-right"><a href="{{ route('job.view', ['id' => $contract->project_id]) }}">View Original Job Posting</a></div>
        </div>

        <div class="box-section clearfix">
          <div class="col-md-6 col-sm-6">
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">Client</div>
              <div class="col-md-8 col-sm-9"><strong>{{ $contract->buyer->fullname() }}</strong></div>
            </div>

            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">Start Date</div>
              <div class="col-md-8 col-sm-9">{{ getFormattedDate($contract->started_at) }}</div>
            </div>

            @if ($contract->status == Contract::STATUS_CLOSED)
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">End Date</div>
              <div class="col-md-8 col-sm-9">{{ getFormattedDate($contract->ended_at) }}</div>
            </div>
            @endif

            <div class="content clearfix margin-bottom-20">
              <div class="col-md-12 col-sm-12">
                <a class="links" href="{{ route('message.list') }}">Send Message</a>
                @if ($contract->isHourly()) | 
                <a class="links" href="{{ route('workdiary.view', $contract->id) }}">View Work Diary</a>@endif
              </div>
            </div>

            @if ( $contract->status == Contract::STATUS_CLOSED )
              @if ( $contractFeedback->freelancer_feedback )
            <div class="alert alert-info feedback" role="alert"> 
              <div class="content clearfix">
                <div class="col-md-4 col-sm-3">Score</div>
                <div class="col-md-8 col-sm-9">
                  <input name="rate" type="radio" class="rate" value="1" {{ ($contractFeedback->freelancer_score == 1) ? 'checked' : '' }} /> 
                  <input name="rate" type="radio" class="rate" value="2" {{ ($contractFeedback->freelancer_score == 2) ? 'checked' : '' }} /> 
                  <input name="rate" type="radio" class="rate" value="3" {{ ($contractFeedback->freelancer_score == 3) ? 'checked' : '' }} /> 
                  <input name="rate" type="radio" class="rate" value="4" {{ ($contractFeedback->freelancer_score == 4) ? 'checked' : '' }} /> 
                  <input name="rate" type="radio" class="rate" value="5" {{ ($contractFeedback->freelancer_score == 5) ? 'checked' : '' }} />
                </div>
              </div>

              <div class="content clearfix">
                <div class="col-md-4 col-sm-3">Feedback</div>
                <div class="col-md-8 col-sm-9">{!! nl2br($contractFeedback->freelancer_feedback) !!}</div>
              </div>
            </div>
              @elseif ( !$contractFeedback->freelancer_feedback )
            <div class="alert alert-info" role="alert">
              <div class="content clearfix">
                <span>No Feedback Given.</span>
              </div>
            </div>                  
              @endif
              
              @if ( !$contractFeedback->buyer_feedback )
            <form id="CloseContractForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="close_action" value="1">
              <div class="content clearfix">
                <div class="col-md-8 col-sm-6"><a id="closeContractBtn" class="custom-btn btn btn-primary" href="{{ route('contract.feedback', ['id' => $contract->id]) }}">Give Feedback</a></div>
              </div>
            </form>
              @endif
            @else
            <form id="CloseContractForm" class="form-horizontal" method="post" action="" enctype="multipart/form-data">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="close_action" value="1">
              <div class="content clearfix">
                <div class="col-md-8 col-sm-6"><a id="closeContractBtn" class="custom-btn btn btn-primary" href="{{ route('contract.feedback', ['id' => $contract->id]) }}">Close Contract</a></div>
              </div>
            </form>
            @endif
          </div>

          <div class="col-md-6 col-sm-6">
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">Job Type</div>
              <div class="col-md-8 col-sm-9">{{ ($contract->type == 1) ? 'Hourly Job' : 'Fixed Job' }}</div>
            </div>                
            @if ($contract->type == 1)
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">Price</div>
              <div class="col-md-8 col-sm-9">$<strong>{{ formatCurrency($contract->freelancerPrice(60)) }}</strong>/hr</div>
            </div>
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">Weekly Limit</div>
              <div class="col-md-8 col-sm-9"><strong>{{ $contract->limit == 0 ? 'No Limit' : $contract->limit . ' hours' }}</strong></div>
            </div>

            @if ($contract->status == Contract::STATUS_OPEN)
            <div class="content clearfix margin-bottom-30">
              <div class="small-font col-md-12 col-sm-12">{{ ($contract->is_allowed_manual_time == 1) ? 'Allowed' : 'Not allowed' }} log manual time </div>
            </div>
            <div class="content clearfix this-week">
              <div class="col-md-4 col-sm-3">This Week</div>
              <div class="col-md-4 col-sm-4"><strong>{{ formatMinuteInterval($contract->this_week_mins) }}</strong> hrs</div>
              <div class="col-md-4 col-sm-5">$<strong>{{ formatCurrency($contract->freelancerPrice($contract->this_week_mins)) }}</strong></div>
            </div>
            <div class="content clearfix last-week">
              <div class="col-md-4 col-sm-3">Last Week</div>
              <div class="col-md-4 col-sm-4"><strong>{{ formatMinuteInterval($contract->last_week_mins) }}</strong> hrs</div>
              <div class="col-md-4 col-sm-5">$<strong>{{ formatCurrency($contract->freelancerPrice($contract->last_week_mins)) }}</strong></div>
            </div>
            <div class="content clearfix total-week">
              <div class="col-md-4 col-sm-3">Total</div>
              <div class="col-md-4 col-sm-4"><strong>{{ formatMinuteInterval($contract->all_week_mins) }}</strong> hrs</div>
              <div class="col-md-4 col-sm-5">$<strong>{{ formatCurrency($contract->freelancerPrice($contract->all_week_mins)) }}</strong></div>
            </div>
            @endif
            @else
            <div class="content clearfix margin-bottom-30">
              <div class="col-md-4 col-sm-3">Price</div>
              <div class="col-md-8 col-sm-9">$<strong>{{ formatCurrency($contract->price) }}</strong></div>
            </div>
            <div class="content clearfix">
              <div class="col-md-4 col-sm-3">Paid Amount</div>
              <div class="col-md-4 col-sm-5">$<strong>{{ formatCurrency($contract->meter->total_amount) }}</strong></div>
            </div>  
            @endif
            <div class="content clearfix">
              <div class="col-xs-12">
                <a class="links" href="#modalPayment" data-type="Refund" data-toggle="modal" data-backdrop="static">Refund</a>
              </div>
            </div>

            <div class="content">
              <div class="paid-transactions">
                @if (count($paid_transactions))
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#ID</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th class="text-right">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($paid_transactions as $t)
                      <tr class="status-{{ strtolower($t->status_string()) }}">
                        <td>
                          {{ $t->id }}
                        </td>
                        <td>
                           {{ $t->status==Transaction::STATUS_AVAILABLE? 
                                            date_format(date_create($t->done_at), "M d, Y"):
                                            date_format(date_create($t->created_at), "M d, Y") }}
                        </th>
                        <td>
                           {{ $t->type_string() }}
                        </td>
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
@include('pages.freelancer.contract.section.payment')
@endsection