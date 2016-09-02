<?php
/**
 * My Freelancers Page (my-freelancers)
 *
 * @author  - nada
 */

use Wawjob\Project;
use Wawjob\Contract;
use Wawjob\User;

?>
@extends('layouts/buyer/index')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ url('assets/plugins/star-rating/jquery.rating.css') }}">
@endsection

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
    <div class="form-section">
      <form id="form_my_freelancers" method="post" action="{{ route('contract.my_freelancers')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        {{ show_messages() }}

        <div class="project-selector-section">
          <select class="project-selection bs-select form-control" id="project" name="project">
            <option value="0" {{ $filter_project==0? 'SELECTED':'' }}>{{ trans('contract.all_projects') }}</option>
            @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ $filter_project==$project->id? 'SELECTED':'' }}>{{ $project->subject }}</option>
            @endforeach
          </select>
        </div>

        <div class="freelancers-section">
          @foreach ($contractors as $c)
          <div class="contractor-item status-{{ strtolower($c->status_string()) }}">
            <div class="contractor-wrapper clearfix">
              <div class='user-avatar pull-left'>
                <img alt="" class="img-circle" src="{{avatarUrl($c->contractor)}}" width="64" height="64" />
              </div>
              <div class="contractor-item-section">     
                <div class="contractor-name">
                  <a href="{{ route('user.profile', ['id'=>$c->contractor->id]) }}">{{ $c->contractor->fullname() }}</a>
                </div>           
                <div class="row">
                  <div class="col-sm-6 margin-bottom-10">
                    <div class="contractor-title">
                    {{ $c->contractor->profile->title }}
                    </div>
                    <div class="contractor-location">
                    {{ $c->contractor->contact->city ? $c->contractor->contact->city . ', ' : ''}}
                    {{ $c->contractor->contact->country->name }}
                    </div>
                    <div class="timezone">
                      {{ $c->contractor->contact->timezone ? $c->contractor->contact->timezone->label : '' }}
                    </div>

                    <div class="contractor-feedback">
                      <span class="freelancer-total-score">{!! $c->user_score > 0 ? $c->user_score : '<i>'. trans('profile.No_Feedback_Yet') .'</i>' !!}</span>
                      @if ($c->user_score > 0)
                      @for ($sc = 0.25; $sc <= 5.00; $sc += 0.25)
                      <input name="rate_{{ $c->contractor_id }}" type="radio" class="rate {split:4}" disabled value="{{ $sc }}"{{ $c->user_score >= $sc && $c->user_score < $sc + 0.25 ? ' checked' : ''}} style="display: none;">
                      @endfor
                      @endif
                    </div>
                    
                    <div class="skill-button">
                      @foreach ($c->contractor->skills as $skill)
                        <span class="label label-default">{{ $skill->name }} &nbsp;&nbsp;
                          <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        </span>&nbsp;&nbsp;
                      @endforeach
                    </div>

                  </div>
                  <div class="col-sm-6">
                    <div class="user-contracts clearfix">
                      @foreach ($c->contracts as $_c)
                      <div class="contract-item status-{{ strtolower($_c->status_string()) }}">
                        <div class="clearfix">
                          <div class="contract-date pull-left">
                            {{ date_format(date_create($_c->started_at), 'M d, Y') }} ~ 
                            @if ($_c->status == Contract::STATUS_OPEN)
                              {{ trans('common.present') }}
                            @else
                              {{ date_format(date_create($_c->ended_at), 'M d, Y') }}
                            @endif
                          </div>
                          <div class="contract-title pull-left">
                            <a href="{{ route('contract.contract_view', ['id'=>$_c->id]) }}">{{ $_c->title }}</a>

                            @if ($_c->status == Contract::STATUS_CLOSED)
                            <span class="contract-status status-closed">[{{ strtoupper(trans('contract.closed')) }}]</span>
                            @elseif ($_c->status == Contract::STATUS_PAUSED)
                             <span class="contract-status status-paused">[{{ strtoupper(trans('contract.paused')) }}]</span>
                            @endif

                          </div>
                        </div>
                        <div class="contract-term">
                          @if ($_c->type == Project::TYPE_HOURLY)
                            {{ trans('common.hourly') }} - <span>${{ $_c->price }}{{ trans('job.per_hour') }}</span> &nbsp;
                            @if ($_c->limit)
                              <span>({{ trans('common.limit') }}: {{ trans('common.n_hours', ['n'=>$_c->limit]) }})</span>
                            @else
                              <span>({{ trans('contract.no_limit') }})</span>
                            @endif
                          @else
                            {{ trans('common.fixed') }} - <span>${{ $_c->price }}</span>
                          @endif
                        </div>
                      </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach

          @if ($filter_project == 0)
          <div class="clearfix">
            <div class="pull-right">
              {!! $contractors->render() !!}
            </div>
          </div>
          @endif

        </div><!-- END OF .freelancers-section -->
      </form>
    </div>
  </div>
</div>
@endsection