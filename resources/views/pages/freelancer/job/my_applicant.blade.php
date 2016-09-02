<?php
/**
 * Job Applicant Page (applicant/{id})
 *
 * @author  - Ri Chol Min
 */
use Wawjob\Project;
use Wawjob\ProjectApplication;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <div class="job-top-section">
        <div class="title-section">
          <span class="title">{{ $job->subject }}</span>
        </div>
      </div>

      {{ show_messages() }}

      <div class="view-section job-content-section {{ $job->type===Project::TYPE_HOURLY ? 'hourly-job' : 'fixed-job' }}">
        <form id="ApplicantDetailForm" class="form-horizontal" method="post" action="{{ route('job.application_detail', ['id' => $application_id]) }}" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="type" value="M">
          <input type="hidden" name="rate" value="">
          <input type="hidden" name="reason" value="">
          <div class="row">
            <div class="col-md-9 col-sm-8 clearfix">
              <div class="small-box-section">
                <div class="sub-section">
                  <div class="title">{{ trans('job.summary_description') }}</div>               
                  <div class="description">
                    <div class="desc">{{ str_limit($job->desc, 50) }}<span class="detail-page-link"><a href="{{ route('job.view', ['id'=>$application->project_id]) }}">{{ trans('contract.view_original_job_posting') }}</a></span></div>
                  </div>                
                </div>
                <div class="sub-section">
                  <div class="title">{{ trans('job.your_cover_letter') }}</div>               
                  <div class="description">
                    <div class="cvletter">{!! nl2br($application->cv) !!}</div>
                  </div>
                </div>                  
              </div>

              @if (!empty($messages))
              <div class="box-section">
                <div class="section-title">{{ trans('job.messages') }}</div>
                <div class="sub-section message-section">
                  <div class="content">
                    @foreach ($messages as $key=>$groupMessage)
                    <div class="panel panel-default">
                      <div class="panel-heading" role="tab" id="heading-{{$key}}">
                        <h5 class="panel-title">
                          <a class="message-date" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$key}}" aria-expanded="false" aria-controls="collapse-{{$key}}">
                           {{ date('l, M d, Y', strtotime($key)) }}
                          </a>
                        </h5>
                      </div>
                      <div id="collapse-{{$key}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$key}}">
                        <div class="panel-body">
                          @foreach ($groupMessage as $message)
                          <div class="message-{{$message->id}} message-item clearfix">
                            <div class="pull-left user-avatar">
                              <img alt="" class="img-circle" src="{{ avatarUrl($message->sender) }}" width="48" height="48">
                            </div>
                            <div class="message-content">
                              <div class="user-name">
                                <strong>{{ $message->sender->fullname() }}</strong>
                              </div>
                              <div class="message-text">
                                {!! nl2br($message->message) !!}
                              </div>
                            </div>
                            <div class="pull-right message-timestamp">
                              {{ date('H:i', strtotime($message->created_at)) }}
                            </div>
                          </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
              </div>
              @endif
              <!-- <div class="divider"></div>  -->

              @if ( $application->status == ProjectApplication::STATUS_ACTIVE )
              <div class="sub-section send-message-section">
                <div class="title">{{ trans('job.send_message') }}</div>
                <div class="content clearfix">                    
                  <textarea id="Message" name="message"></textarea>
                  <div class="action-btns">
                    <button type="submit" id="SendMsg" class="btn btn-primary">{{ trans('job.send') }}</button>
                    <a onclick="cancelSubmit();" id="cancelSendMsg" href="javascript:void(0);">{{ trans('common.cancel') }}</a>
                  </div>
                </div>
              </div>
              @endif
            </div>

            <div class="col-md-3 col-sm-4">
              <div class="client-info">
                <div class="sub-section margin-bottom-30">
                  <label>{{ trans($job->type === Project::TYPE_HOURLY ? 'job.you_applied_this_hourly' : 'job.you_applied_this_fixed_price', ['n' => getEarningRate($application->price)]) }}</label>
                  <a class="btn btn-primary" href="javascript:void(0);" data-target="#ChangeTermModal" data-toggle="modal"><strong>Change Term</strong></a>
                </div>
                <div class="sub-section">
                  <a class="btn btn-primary" href="javascript:void(0);" data-target="#WithdrawProposalModal" data-toggle="modal"><strong>Withdraw Proposal</strong></a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>    
  </div>
</div>

@include ('pages.freelancer.job.modal.change_term')
@include ('pages.freelancer.job.modal.withdraw_proposal')

<script>
  var _action = '{{ $_action }}';
</script>
@endsection