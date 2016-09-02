<?php 
/**
 * Job Action Links in Individual Job Pages
 *
 * @author  - nada
 */
use Wawjob\Project; 
?>

<ul class="job-top-links nav nav-pills">
  @if ($page != 'buyer.job.view')
  <li class="link-item">
    <a href="{{ route('job.view', array('id'=>$job->id)) }}">
      <i class="icon-eye"></i>{{ trans('job.view_posting') }}</a>
  </li>
  @endif

  @if ($page != 'buyer.job.edit')
  <li class="link-item">
    <a href="{{ route('job.edit', array('id'=>$job->id)) }}">
      <i class="icon-pencil"></i>{{ trans('job.edit_posting') }}</a>
  </li>
  @endif

  @if ($job->status == Project::STATUS_OPEN)
  <li class="link-item action-change-status">
    <a href="#" data-url="{{ route('job.change_status.ajax', array('id'=>$job->id, 'status'=>Project::STATUS_CLOSED)) }}" class="status-closed">
      <i class="icon-close"></i>{{ trans('job.close_posting') }}</a>
  </li>

    @if ($job->is_public == Project::STATUS_PRIVATE)
    <li class="link-item action-change-public">
      <a href="#" data-url="{{ route('job.change_public.ajax', array('id'=>$job->id, 'public'=>Project::STATUS_PUBLIC)) }}" data-status="public">
        <i class="icon-magnifier"></i>{{ trans('job.make_public') }}</a>
    </li>
    @elseif ($job->is_public== Project::STATUS_PUBLIC)
    <li class="link-item action-change-public">
      <a href="#" data-url="{{ route('job.change_public.ajax', array('id'=>$job->id, 'public'=>Project::STATUS_PRIVATE)) }}" data-status="private">
        <i class="icon-lock"></i>{{ trans('job.make_private') }}</a>
    </li>
    @endif

  @endif

  @if ($page == 'buyer.job.view' || $page == 'buyer.job.edit')
  <li class="link-item">
    <a href="{{ route('job.applicants', array('id'=>$job->id)) }}">
      <i class="icon-users"></i>{{ trans('job.view_applicants') }}</a>
  </li>
  @endif
</ul>

@if ($job->status == Project::STATUS_CLOSED)
<div class="job-closed-notification alert alert-warning">
  <strong>{{ trans('job.job_is_closed') }}</strong>
</div>
@elseif ($job->is_public == Project::STATUS_PRIVATE)
<div class="job-closed-notification alert alert-warning">
  <strong>{{ trans('job.job_is_private') }}</strong>
</div>
@endif