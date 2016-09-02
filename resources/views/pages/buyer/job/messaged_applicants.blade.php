<?php
/**
 * My Info Page (job/{id}/messaged-applicants)
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
      <form id="form_job_applicants" method="post" action="{{ route('job.messaged_applicants')}}">
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
                    <div class="applicant-info ">
                      <div class="row">
                        <div class="col-sm-8 col-xs-12">
                          <div class="user-info">
                            <div class="user-name">
                              <a href="{{ route('job.application_detail', ['id'=>$applicant->id]) }}">{{$applicant->user->fullname()}}</a></div>
                            <div class="user-title">{{$applicant->user->profile? $applicant->user->profile->title:""}}</div>
                            <div class="user-location">
                              <span>{{ $applicant->user->contact->country->name }}</span>
                            </div>
                          </div>

                          <div class="application-info">
                            @if ($applicant->status == ProjectApplication::STATUS_INVITED)
                              <div class="application-status status-invited">
                                <span class="status">{{ strtoupper(trans('common.invited')) }}</span> - {{ ago($applicant->created_at) }}
                              </div>
                            @else 
                              @if ($applicant->cv)
                              <div class="cover-letter">
                                {{ trans('common.cover_letter') }} - {{$applicant->cv}}
                              </div>
                              @endif
                              <div class="ago-time">
                                <span>{{ ago($applicant->created_at) }}</span>
                              </div>
                            @endif
                          </div>
                          <div class="user-skill clearfix">
                            @foreach ($applicant->user->skills as $skill)
                              <div class="skill pull-left">
                                {{ $skill->name }}
                              </div>
                            @endforeach
                          </div>

                        </div>
                        <div class="col-sm-4 col-xs-12 action-section">
                          <div class="action-links">
                            <a class="btn btn-default" href="{{ route('job.application_detail', ['id'=>$applicant->id]) }}?_action=message">{{ trans('job.send_message') }}</a>
                            <a class="btn btn-default decline-link status-client-declined" href="{{ route('job.application.change_status.ajax', ['id'=>$applicant->id, 'status'=>ProjectApplication::STATUS_CLIENT_DCLINED]) }}" data-application="{{$applicant->id}}">X</a>
                          </div>
                          @if (floatval($applicant->price))
                            @if ($applicant->type == Project::TYPE_HOURLY)
                            <div class="price">
                              ${{ formatCurrency($applicant->price) }} / hr
                            </div>
                            @else
                            <div class="price">
                              ${{ formatCurrency($applicant->price) }}
                            </div>
                            @endif
                          @endif
                          <div class="make-offer-action">
                            <a class="btn btn-primary" href="{{ route('job.make_offer', ['id'=>$job->id, 'uid'=>$applicant->user_id]) }}">{{ trans('job.make_offer') }}</a>
                          </div>
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