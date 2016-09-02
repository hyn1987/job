<?php
/**
 * JobInfo Page (search/job)
 *
 * @author  - so gwang
 */

  use Wawjob\Project;
?>

<div class="content-box">  
  <div class="box-header">
    <div class="title">
      <a href="{{ route('job.view', ['id'=>$job->id]) }}">{{ $job->subject }}</a>
    </div>
  </div>
  <div class="summary">  
    @if ( $job->type == Project::TYPE_HOURLY ) 
      <span class="type"> {{ $job->type_string() }} </span>- {{ trans('job.duration') }}:&nbsp;
      <span class="duration">{{ $job->duration_string() }}</span>
    @elseif ($job->type == Project::TYPE_FIXED )
      <span class="type">{{ $job->type_string() }}</span>&nbsp;-&nbsp;{{ trans('job.price') }}:&nbsp;<span class="price"> {{ $job->price }} </span>($)
    @endif
    &nbsp;-&nbsp;
    <span class="workload">{{ $job->workload_string() }}</span>&nbsp;-&nbsp;{{ trans('common.posted' )}}&nbsp; 
    <span class="posted-time">{{ ago($job->created_at) }} </span>
  </div>
  
  <div class="desc">{{ $job->desc }}</div> 

  <div class="skills">
    @foreach ($job->skills as $skill)
    <span class="label label-default">{{ $skill->name }}&nbsp;&nbsp;
      <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
    </span>&nbsp;&nbsp;
    @endforeach 
  </div> 

</div>