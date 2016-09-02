<?php
/**
 * My Info Page (job/{id}/applicants)
 *
 * @author  - nada
 */
use Wawjob\Project;
use Wawjob\ProjectApplication;
?>
@extends('layouts/buyer/job_application')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
      <div class="page-content-section no-padding row">
        <div class="col-md-9 col-sm-8">
          <div class="job-top-section">
            {{ show_messages() }}
          </div>

          <div class="application-section job-content-section {{ $job->type_string()."-job" }} application-content-section">
            <div class="section clearfix">
              <div class="pull-left user-avatar">
                <img alt="" class="img-circle" src="{{avatarUrl($application->user)}}" width="64" height="64" />
              </div>
              <div class="application-info">
                <div class="user-info">
                    <div class="user-name clearfix">
                      <div class="pull-left">{{$application->user->fullname()}}</div>
                      @if (formatCurrency(floatval($application->price)))
                      <div class="pull-right">${{ formatCurrency($application->price).($application->type==Project::TYPE_HOURLY? " / hr":"") }}</div>
                      @endif
                    </div>
                    <div class="user-title">{{$application->user->profile? $application->user->profile->title:""}}</div>
                    <div class="user-location">
                      <span>{{$application->user->contact->country->name}}</span>
                    </div>
                    @if ($application->status == ProjectApplication::STATUS_INVITED)
                    <div class="application-status status-invited">
                      <span class="status">{{trans('common.invited')}}</span> - {{ ago($application->created_at) }}
                    </div>
                    @else

                    @endif
                </div>
                <div class="row">
                  <div class="user-skill col-sm-9 clearfix">
                    @foreach ($application->user->skills as $skill)
                      <div class="skill pull-left">
                        {{ parse_multilang($skill->name, App::getLocale()) }}
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            
            @if ($application->cv)
            <div class="box-section">
              <div class="section-title">{{ trans('common.cover_letter') }}</div>
              <div class="sub-section cover-leter-section">
                <div class="content">
                  {!! nl2br($application->cv) !!}
                </div>
              </div>
              <!-- <div class="divider"></div>  -->
            </div>
            @endif

            <div class="box-section">
              @if (!empty($messages))
              <div class="section-title">{{trans('job.messages')}}</div>
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
              @endif
              <!-- <div class="divider"></div>  -->

              <div class="sub-section send-message-section">
                <div class="rel-job">{{ trans('common.job') }}: <a href="{{ route('job.view', $application->project->id )}}" target="_blank">{{ $application->project->subject }}</a></div>
                <div class="title">{{ trans('job.send_message') }}</div>
                <div class="content">
                  <form id="form_send_message" method="post" action="{{ route('job.application_detail', ['id'=>$application->id])}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_action" value="send_message" />
                    <div class="form-group">
                      <div class="input-field">
                        <div class="input-icon right">
                          <i class="fa tooltips" data-original-title="please write a valid title"></i>
                          <textarea type="text" data-required="1" class="form-control" id="message" name="message" rows="8">{{ old('message') ? old('message') : "" }}</textarea>
                        </div>
                      </div>                            
                    </div>
                    <div class="form-group">
                      <div class="input-field">
                        <button type="submit" class="btn btn-primary btn-send-message"> {{ trans('common.submit') }} </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div><!-- END OF .job-content-section -->
        </div>
        <div class="col-md-3 col-sm-4">
          <div class="applicant-info">
            @if (empty($_archive_msg))
            <div class="sub-section">
              <a class="btn btn-primary make-offer" href="{{ route('job.make_offer', ['id'=>$application->project->id, 'uid'=>$application->user->id]) }}">{{ trans('job.make_offer') }}</a>
            </div>
            <div class="sub-section">
              <a class="decline-link status-client-declined" href="{{ route('job.application.change_status.ajax', ['id'=>$application->id, 'status'=>ProjectApplication::STATUS_CLIENT_DCLINED]) }}" data-application="{{$application->id}}">
                {{ trans('common.decline') }}
              </a>
            </div>
            @else
            <div class="sub-section">
              <div class="archive-message alert-warning title"> {{ $_archive_msg }} </div>
            </div>
            @endif
            <div class="sub-section">
              <div class="title info-item">{{ trans('profile.work_history')}}</div>
              <div class="hours-worked info-item">{{ $application->user->howManyHours() }} hours worked</div>
              <div class="jobs info-item">{{ $application->user->howManyJobs() }} jobs</div>
            </div>
            <div class="sub-section">
              <div class="">
                <a href="{{ route('user.profile', $application->user->id) }}">{{ trans('profile.view_sb_profile', ['sb'=>$application->user->fullname()])}}</a>
              </div>
            </div>
          </div>
        </div>
      </div><!-- END OF .page-content-section -->
      
  </div>
</div>

<script>
  var _action = '{{ $_action }}';
</script>
@endsection