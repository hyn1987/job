<?php
/**
 * Job Apply Page (apply_offer/{id})
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
              <span class="title">Accept Offer</span>
            </div>
          </div>
          <form id="ApplyOfferForm" class="form-horizontal" method="post" action="{{ route('job.apply_offer', ['id'=>$contract_id]) }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="SubmitAction" name="_action" value="">
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
              <div class="sub-section clearfix">
                <div class="col-sm-3 bold-title">
                  Offer Terms           
                </div>
                <div class="col-sm-9">
                  <!--<div class="box-sub-section margin-bottom-30">-->
                    <div class="clearfix">
                      <!--<div class="col-sm-5">
                        <label>You'll earn</label>
                        <label class="help-comment">Except Fee</label>
                      </div>-->
                      <div class="col-sm-4 no-padding">
                        <div class="input-group form-line-wrapper">
                          <input type="text" class="form-control" id="EarningRate" name="earning_rate" 
                                 data-max="{{ formatCurrency(getEarningRate($contract->price)) }}" value="{{ isset($input_rate) ? formatCurrency($input_rate) : formatCurrency(getEarningRate($contract->price)) }}" data-rule-required="true" data-rule-number="true">
                          <span class="input-group-addon">{{ $job->type===Project::TYPE_HOURLY? "$/hr" : "$" }}</span>
                        </div>
                      </div>
                    <!--</div>-->
                  </div>
                  @if (isset($error))
                    <div class="has-error"><span class="help-block">{{ $error }}</span></div>
                  @endif
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
                <button type="button" id="acceptOffer" class="btn btn-primary">Accept Offer</button>
                <a id="rejectOffer" href="javascript:void(0);">Reject</a>
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