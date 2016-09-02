<?php
/**
 * Job Apply Page (apply/{id})
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
      @if (isset($error))
      <div class="has-error"><span class="help-block">{{ $error }}</span></div>
      @endif
      <div class="view-section job-content-section {{ $job->type===Project::TYPE_HOURLY? "hourly-job" : "fixed-job" }}">
        <div class="col-sm-8 col-sm-offset-2">
          <div class="job-top-section">
            <div class="title-section">
              <span class="title">{{ trans('job.submit_a_proposal') }}</span>
            </div>
          </div>
          <form id="JobDetailForm" class="form-horizontal" method="post" action="{{ route('job.apply', ['id' => $job_id]) }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="row">
              <div class="sub-section">
                <div class="col-sm-3 bold-title">{{ trans('job.job_posting') }}</div>
                <div class="col-sm-9">
                  <div class="description margin-bottom-30">
                    <div class="subject bold-title">{{ $job->subject }}</div>
                    <div class="desc">{!! nl2br($job->desc) !!}</div>
                  </div>
                </div>
              </div>
              <div class="sub-section">
                <div class="col-sm-3 bold-title">{{ trans('job.proposal_terms') }}</div>
                <div class="col-sm-9">
                  <div class="box-sub-section margin-bottom-30">
                    <div class="block-section">
                      <div class="col-sm-5">
                        <label>{{ $job->type === Project::TYPE_HOURLY ? trans('job.billing_rate') : trans('job.billing_price') }}</label>
                        <label class="help-comment">{{ trans('job.this_is_what_the_client_sees') }}</label>
                      </div>
                      <div class="col-sm-7">
                        <span>$</span><input type="text" id="BillingRate" name="billing_rate" value="" /><span>{{ $job->type === Project::TYPE_HOURLY? "/".trans('common.hr') : "" }}</span>
                      </div>
                      <div class="clear-div"></div>
                    </div>
                    <div class="block-section">
                      <div class="col-sm-5">
                        <label>{{ trans('job.you_will_earn') }}</label>
                        <p><label class="help-comment">{{ trans('job.estimated') }}</label></p>
                      </div>
                      <div class="col-sm-7">
                        <span>$</span><input type="text" id="EarningRate" name="earning_rate" value="" /><span>{{ $job->type === Project::TYPE_HOURLY? '/'.trans('common.hr') : "" }}</span>
                      </div>
                      <div class="clear-div"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="sub-section">
                <div class="col-sm-3 bold-title">{{ trans('common.cover_letter') }}</div>
                <div class="col-sm-9">
                  <div class="cover-letter margin-bottom-30">
                    <textarea id="CoverLetter" name="coverletter"></textarea>
                  </div>
                </div>
                 <div class="clear-div"></div>
              </div>
              <div class="action-buttons-section">
                <button type="submit" id="acceptSubmitProposal" class="btn btn-primary">{{ trans('job.accept_and_submit_a_proposal') }}</button>
                <a onclick="cancelSubmit();" id="cancelProposal" href="javascript:void(0);">{{ trans('common.cancel') }}</a>
                <div class="clear-div"></div>
              </div>               
            </div>
          </form>
        </div>
      </div>      
  </div>
</div>
@endsection