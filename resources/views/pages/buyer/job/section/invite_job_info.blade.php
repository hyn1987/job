<?php
/**
 * 
 *
 * @author  - nada
 */
use Wawjob\Project;
?>

<div class="view-section job-content-section">
  <div class="section clearfix">
    <div class="project-category pull-left">
      Ecommerce Development
    </div>
    <div class="past-time pull-left">
      {{ trans('common.posted') }} {{ ago($job->created_at) }}
    </div>
  </div>
  <div class="section clearfix">
    <div class="project-type-info">
      @if ($job->type == Project::TYPE_HOURLY)
      <div class="project-type"><strong>{{ trans('common.hourly_job') }}</strong></div>
      <div class="workload">{{ $job->workload_string() }}</div>
      <div class="duration">{{ $job->duration_string() }}</div>
      @else
      <div class="project-type"><strong>{{ trans('common.fixed_price_job') }}</strong> - <span class="price">${{ $job->price }}</span></div>
      @endif
    </div>
  </div>
  <div class="section clearfix">
    <div class="sub-section">

      <div class="description margin-bottom-30">
        <div class="skill-label"><strong>{{ trans('common.cover_letter') }}:</strong></div>
        {!! nl2br(str_limit($job->desc, 200)) !!} &nbsp;&nbsp;
        <a href="{{ route('job.view', ['id'=>$job->id]) }}" target="_blank">{{ trans('contract.view_original_job_posting') }}</a>
      </div>
      <div class="project-skills margin-bottom-10 clearfix">
        <div class="skill-label pull-left"><strong>{{ trans('common.skills') }}:</strong></div>
        <div class="skill pull-left">
          Ecommerce Development
        </div>
      </div>
    </div>
    <!-- <div class="divider"></div>  -->
  </div>
  @if ($msg_status)
  <div class="status-msg-section section clearfix">
    {!! $msg_status !!}
  </div>
  @endif
</div><!-- END OF .job-content-section -->