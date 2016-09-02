<?php
/**
 * Job Detail Page (job/{id})
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

      @if (isset($error))
      <div class="has-error"><span class="help-block">{{ $error }}</span></div>
      @endif

      <div class="view-section job-content-section {{ $job->type===Project::TYPE_HOURLY? "hourly-job" : "fixed-job" }}">
        <form id="JobDetailForm" class="form-horizontal" method="post" action="/job/{{$job_id}}" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <div class="row">
            <div class="col-md-9 col-sm-8">
              <div class="section clearfix">
                <div class="project-category pull-left">{{ parse_multilang($job->category->name, App::getLocale()) }}</div>
                <div class="past-time pull-left">{{ trans('common.posted' )}} {{ago($job->created_at)}}</div>
              </div>
              <div class="section clearfix">
                <div class="project-type-info">
                  @if ($job->type == Project::TYPE_HOURLY)
                  <div class="project-type"><strong>{{ trans('common.hourly_job') }}</strong></div>
                  <div class="workload">{{ $job->workload_string() }}</div>
                  <div class="duration">{{ $job->duration_string() }}</div>
                  @else
                  <div class="project-type"><strong>{{ trans('common.fixed_price_job') }}</strong> - <span class="price">${{ $job->price}}</span></div>
                  @endif
                </div>
              </div>
              <div class="box-section margin-bottom-35">
                <div class="sub-section">
                  <div class="title margin-bottom-35">{{ trans('job.details') }}</div>

                  @if ($job->contract_limit>0)
                  <div class="multi-freelancer-needed margin-bottom-30">
                    <span>{{ trans('job.need_to_hire_freelancers', ['n' => $job->contract_limit]) }}</span>
                  </div> 
                  @endif

                  <div class="description margin-bottom-30">
                    <div class="desc">{!! nl2br($job->desc) !!}</div>
                  </div>
                  <div class="project-skills margin-bottom-30 clearfix">
                    <div class="skill-label pull-left"><strong>{{ trans('common.skills') }}:</strong></div>
                    <div class="skill pull-left">
                      {{ parse_multilang($job->category->name, App::getLocale()) }}
                    </div>
                  </div>
                  <div class="activity">
                    <div class="small-title margin-bottom-10">{{ trans('job.client_activity_on_this_job') }}</div>
                    <div class="display-info">{{ trans('job.proposals') }}: <span>{{$job->allProposalsCount()}}</span></div>
                    <div class="display-info">{{ trans('job.interviewing') }}: <span>{{$job->messagedApplicationsCount()}}</span></div>
                    @if ( $job->hiredContractsCount() > 0 )
                    <div class="display-info">{{ trans('job.hired') }}: <span>{{$job->hiredContractsCount()}}</span></div>
                    @endif
                  </div>
                </div>
              </div>
              @if ( $job->allProposalsCount() > 0 )
              <div class="box-section margin-bottom-35">
                <div class="applicant clearfix">
                  <div class="title col-xs-8">
                    <div role="tab" id="proposalHeading">
                      <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" href="#applicantsList" aria-expanded="true" aria-controls="applicantsList">{{ trans('job.proposals')}} ({{$job->allProposalsCount()}})</a>
                      </h4>
                    </div>
                  </div>
                  <div class="help-content col-xs-4">See current bids for this job</div>
                </div>
                <div id="applicantsList" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="proposalHeading">
                  <div class="applicant-header-item clearfix">
                    <div class="col-xs-5">{{ trans('report.freelancer') }}</div>
                    <div class="col-xs-4">{{ trans('job.proposal_date') }}</div>
                    <div class="col-xs-3">{{ trans('job.initiated_by') }}</div>
                  </div>
                  @foreach ( $job->allProposals() as $applicant )
                  <div class="applicant-item clearfix">
                    <div class="col-xs-5 name"><a href="{{route('user.profile', ['id' => $applicant->user->id])}}">{{$applicant->user->fullname()}}</a></div>
                    <div class="col-xs-4">{{ago($applicant->created_at)}}</div>
                    <div class="col-xs-3">{{ $applicant->status == ProjectApplication::STATUS_INVITED ? 'Invite' : 'Freelancer' }}</div>
                  </div>
                  @endforeach
                </div>
              </div> 
              @endif
            </div>
            <div class="col-md-3 col-sm-4">
              @if (isset($login))              
              @if (!$bid)
              <div class="action-buttons-section">
                <button type="submit" id="submitProposal" class="btn btn-primary">{{ trans('job.submit_a_proposal') }}</button>
              </div>
              @else
              <div class="action-buttons-section">
                <label class="alert-message">{{ trans('job.you_applied_already') }}</label>
              </div>
              @endif
              @endif
              <div class="client-info">
                <div class="sub-section">
                  <div><strong>{{ trans('job.about_the_client') }}</strong></div>
                  <div>{{ trans('job.payment_not_verified') }}</div>
                </div>
                <div class="sub-section">
                  <div><strong>{{ trans('job.n_jobs_posted', ['n' => $job_count]) }}</strong></div>
                </div>
                <div class="sub-section">
                  <div>{{ trans('job.member_since', ['time' => getFormattedDate($client->created_at)]) }}</div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>      
  </div>
</div>
@endsection