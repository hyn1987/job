<?php
/**
 * My Info Page (job/{id}/archived_applicants)
 *
 * @author  - nada
 */
use Wawjob\Project;
use Wawjob\ProjectApplication;
use Wawjob\Contract;
?>
@extends('layouts/buyer/job')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_job_applicants" method="post" action="{{ route('job.archived_applicants')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="job-top-section">
          <div class="title-section">
            <span class="title">
              {{ $job->subject }}
            </span>
          </div>
        </div>

        <div class="page-content-section no-padding ">
          <div class="job-top-section">
            @include('layouts.buyer.section.job_top_links')
            {{ show_messages() }}
          </div>

          <div class="applicants-section job-content-section">
            <div class="job-box-section">
              <div class="job-nav-section">
                @include('layouts.buyer.section.job_nav_links')
              </div>
              <div class="job-box-content">
              @if (!$applicants || !count($applicants))
                <div class="text-center container no-applicants-message">
                  {{ trans('job.no_applicants')}}
                </div>
              @else
                <div class="applicants-wrapper">
                @foreach ($applicants as $applicant)
                  <div class="applicant-row clearfix">
                    <div class="pull-left user-avatar">
                      <img alt="" class="img-circle" src="{{avatarUrl($applicant->user)}}" width="64" height="64" />
                    </div>
                    <div class="pull-left applicant-info">
                      <div class="user-info row">
                        <div class="col-sm-7">
                          <div class="user-name">
                            <a href="{{ route('job.application_detail', ['id'=>$applicant->id]) }}">{{$applicant->user->fullname()}}</a></div>
                          <div class="user-title">{{$applicant->user->profile? $applicant->user->profile->title:""}}</div>
                          <div class="user-location">
                            <span>{{ $applicant->user->contact->country->name }}</span>
                          </div>
                        </div>
                        <div class="col-sm-5 action-section">
                          <div class="application-status">
                            {{$applicant->getArchivedNotification()}}
                          </div>
                          @if ($applicant->type == Project::TYPE_HOURLY)
                          <div class="price">
                            ${{ formatCurrency($applicant->price) }} / hr
                          </div>
                          @else`
                          <div class="price">
                            ${{ formatCurrency($applicant->price) }}
                          </div>
                          @endif

                          <?php $_contract=Contract::getContractFromApplication($applicant->id); ?>

                          @if ($applicant->status==ProjectApplication::STATUS_HIRING_CLOSED &&
                               $_contract && 
                               $_contract->status!=Contract::STATUS_OFFER)
                          <div class="action-links">
                            <a href="{{ route('contract.contract_view', ['id'=>$_contract->id]) }}">{{ trans('job.contract_detail') }}</a>
                          </div>
                          @endif

                        </div>
                      </div>

                      <div class="row">
                        <div class="application-info col-sm-9">
                          <div class="ago-time">
                            <span>{{ ago($applicant->created_at) }}</span>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="user-skill col-sm-9 clearfix">
                          @foreach ($applicant->user->skills as $skill)
                          <div class="skill pull-left">
                            {{ $skill->name }}
                          </div>
                          @endforeach
                        </div>
                      </div>

                    </div>
                  </div><!-- END OF .applicants-wrapper -->
                  @endforeach
                </div>
                {!! $applicants->render() !!}
              @endif
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection