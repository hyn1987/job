<?php
/**
 * Job Apply Page (accept_invite/{id})
 *
 * @author  - Ri Chol Min
 */
use Wawjob\Project;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">      
      <div class="view-section job-content-section {{ $job->type===Project::TYPE_HOURLY? "hourly-job" : "fixed-job" }}">
        <div class="col-sm-8 col-sm-offset-2">
          <div class="job-top-section">
            <div class="title-section">
              <span class="title">Accept Invitation</span>
            </div>
          </div>
          <form id="AcceptInviteForm" class="form-horizontal" method="post" action="{{ route('job.accept_invite', ['id'=>$application_id]) }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
              <div class="sub-section">
                <div class="col-sm-3 bold-title">
                  Job Posting
                </div>
                <div class="col-sm-9">
                  <div class="description margin-bottom-30">
                    <div class="subject bold-title">{{ $job->subject }}</div>
                    <div class="desc">{!! nl2br($job->desc) !!}</div>
                  </div>
                </div>
              </div>
              <div class="sub-section">
                <div class="col-sm-3 bold-title">
                  Accept Terms
                </div>
                <div class="col-sm-9">
                  <div class="margin-bottom-30">
                    <div class="block-term-section clearfix">                      
                       <div class="input-group form-line-wrapper price-field">
                          <span class="input-group-addon">$</span><input type="text" id="BillingRate" name="billing_rate" value="" data-rule-required="true" data-rule-number="true" />
                          @if ( $job->type===Project::TYPE_HOURLY )
                          <span class="input-group-addon">/hr</span>
                          @endif
                        </div>
                        <label class="help-comment"><i>This is what the client sees</i></label>                      
                      </div>
                    <div class="block-term-section no-margin clearfix">
                        <div class="input-group form-line-wrapper price-field">
                          <span class="input-group-addon">$</span><input type="text" id="EarningRate" name="earning_rate" value="" data-rule-required="true" data-rule-number="true" />
                          @if ( $job->type===Project::TYPE_HOURLY )
                          <span class="input-group-addon">/hr</span>
                          @endif
                        </div>
                        <!--<label>You'll earn</label>-->
                        <label class="help-comment"><i>Except fee</i></label>
                    </div>                    
                  </div>
                </div>
              </div>
               <div class="sub-section">
                <div class="col-sm-3 bold-title">
                  Message
                </div>
                <div class="col-sm-9">
                  <div class="message-div margin-bottom-30">
                    <textarea class="message" name="message"></textarea>
                  </div>
                </div>
              </div>
              <div class="action-buttons-section">
                <button type="submit" id="acceptInvite" class="btn btn-primary">Accept Invite</button>
                <a onclick="cancelSubmit();" id="rejectInvite" href="javascript:void(0);">Reject</a>
                <div class="clear-div"></div>
              </div>               
            </div>
          </form>
        </div>
      </div>      
  </div>
</div>
<script>
  var _error = '{{ isset($errorflag) ? $errorflag : "" }}';
</script>
@endsection