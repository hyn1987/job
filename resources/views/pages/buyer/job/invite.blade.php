<?php
/**
 * Invite Page (job/{id}/invite)
 *
 * @author  - nada
 */

use Wawjob\Project;
?>
@extends('layouts/buyer/index')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_invite" method="post" action="{{ route('job.invite', ['uid'=>$contractor->id])}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="job-top-section">
          <div class="title-section clearfix">
            <div class="user-avatar pull-left margin-right-20">
              <img src="{{ avatarUrl($contractor) }}" alt="" class="img-circle" width="64" height="64">
            </div>
            <div class="title pull-left">{!! trans('job.invite_sb', ['fullname' => $contractor->fullname()]) !!}</div>
          </div>
          {{ show_messages() }}
        </div>

        <div class="job-content-section no-padding row">
          <div class="col-md-9 col-sm-8">
            <div class="box-section">
              <div class="sub-section">
                <div class="item form-group">
                  <div class="item-label"><strong>{{ trans('job.related_job') }}</strong></div>
                  <div class="item-info input-field">
                    <div class="input-icon right">
                      <i class="fa tooltips" data-original-title="please input contract title"></i>
                      <select class="form-control" id="job" name="job" placeholder="Job" data-contractor="{{ $contractor->id }}" data-rule-required="true">
                        <option value="">- {{ trans('job.please_select_job') }} -</option>
                        @foreach($jobs as $job) 
                        <option value="{{$job->id}}">{{ $job->subject }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="job-info"></div>
              </div>

              <div class="sub-section">
                <div class="form-group">
                  <button type="submit" class="invite-btn btn btn-primary disable-submit">{!! trans('job.invite_sb', ['fullname' => $contractor->fullname()]) !!}</button>
                </div>
              </div>
            </div><!-- END OF .box-section -->
          </div>
          <div class="col-md-3 col-sm-4">
            <div class="sub-section">
              <!-- <strong>How do hourly contracts work?</strong> -->
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection