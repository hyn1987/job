<?php 
/**
 * Job Navigation Links in Individual Job Pages
 *
 * @author  - nada
 */
use Wawjob\Project; 
?>

<ul class="job-nav-links nav nav-pills">
  <li class="link-item{{ $page=='buyer.job.applicants'? " active":"" }}">
    <a href="{{ route('job.applicants', array('id'=>$job->id)) }}">
      <i class="icon-users"></i>{{ trans('job.applicants') }} ({{ $job->normalApplicationsCount() }})</a>
  </li>
  <li class="link-item{{ $page=='buyer.job.messaged_applicants'? " active":"" }}">
    <a href="{{ route('job.messaged_applicants', array('id'=>$job->id)) }}">
      <i class="icon-users"></i>{{ trans('job.messaged') }} ({{ $job->messagedApplicationsCount() }})</a>
  </li>
  <li class="link-item{{ $page=='buyer.job.offer_hired_applicants'? " active":"" }}">
    <a href="{{ route('job.offer_hired_applicants', array('id'=>$job->id)) }}">
      <i class="icon-users"></i>{{ trans('job.offer_hired') }} ({{$job->offerHiredContractsCount()}})</a>
  </li>
  <li class="link-item{{ $page=='buyer.job.archived_applicants'? " active":"" }}">
    <a href="{{ route('job.archived_applicants', array('id'=>$job->id)) }}">
      <i class="icon-users"></i>{{ trans('job.archived') }} ({{$job->archivedApplicationsCount()}})</a>
  </li>
</ul>