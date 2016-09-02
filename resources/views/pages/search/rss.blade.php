<?php
/**
 * RSS Service Page (search/job)
 *
 * @author  - brice
 */
  use Wawjob\Project;
?>
<rss version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
>
<channel>
  <title>Wawjob Recent Posts</title>
  <atom:link href="{{ route('search.rssjob') }}" rel="self" type="application/rss+xml" />
  <link>{{ route('search.rssjob') }}</link>
  <description>Search Result via Wawjob RSS service</description>
  <lastBuildDate>{{$last_build_date}}</lastBuildDate>
  <language>EN-US</language>
  <sy:updatePeriod>minutely</sy:updatePeriod>
  <sy:updateFrequency>1</sy:updateFrequency>
  @foreach ($jobs as $job)
  <?php 
    $excerpt = strip_tags($job->desc);
    $len = strlen($excerpt);
    if ($len > 200) {
      $excerpt = substr($excerpt, 0, 200) . "...";
    }
  ?>
  <item>
    <title>{{ $job->subject }}</title>
    <link>{{ route('job.view', ['id'=>$job->id]) }}</link>
    <pubDate>- Posted {{ ago($job->created_at) }}</pubDate>
    <dc:creator>Wawjob Corporation</dc:creator>
    <guid isPermaLink="false">{{ route('job.view', ['id'=>$job->id]) }}</guid>
    <description>
    <![CDATA[
    @if ( $job->type == Project::TYPE_HOURLY ) 
      {{ $job->type_string() }} 
      - Duration: <b> {{ $job->duration_string() }} </b>
    @elseif ($job->type == Project::TYPE_FIXED )
      {{ $job->type_string() }} - Price:<b> {{ $job->price }} ($) </b>
    @endif
     - <b>{{ $job->workload_string() }} </b>
    ]]>
    </description>
    <content:encoded>
    <![CDATA[
    @if ( $job->type == Project::TYPE_HOURLY ) 
      {{ $job->type_string() }} 
      - Duration: <b> {{ $job->duration_string() }} </b>
    @elseif ($job->type == Project::TYPE_FIXED )
      {{ $job->type_string() }} - Price:<b> {{ $job->price }} ($) </b>
    @endif
     - <b>{{ $job->workload_string() }} </b>
    <br>
    {{ $job->desc }}
    ]]>
    </content:encoded>
  </item>
  @endforeach
</channel>
</rss>