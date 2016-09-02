<?php
/**
 * Job Search Page (search/job)
 *
 * @author  - so gwang
 */

  use Wawjob\Project;
?>


@extends('layouts/search/index')

@section('content')

<div class="title-section">
  <div class="row">
    <div class="col-sm-3">
      <span class="title">{{ trans('page.' . $page . '.title') }}</span>
    </div>
    <div class="col-sm-9">
      <a href="#" class="rss-link dropdown-toggle">RSS <img src="/assets/images/common/icons/social/rss.png"/></a>
      <div class="rss-box" style="display: none;">
        <div class="input-group">
          <input type="text" placeholder="RSS URL" value="" class="form-control rss-url" name="rssurl" readonly>
          <span class="input-group-btn">
            <button id="copy-btn" class="btn btn-primary" type="button">Copy</button>
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="page-content-section freelancer-page">
  <form id="search_form" class="" action="" method="post" role="form">
    <div class="margin-bottom-35">
      <div class="input-group">
        <input id="search_title" name="search_title"  class="form-control" type="text" placeholder="{{ trans('search.search_jobs') }}..." value="{{ $search_title }}" />
        <span class="input-group-btn">
          <a id="search_btn" class="btn btn-primary">{{ trans('common.search') }}</a>           
        </span>         
      </div>
      <!--<p class="help-block">Advanced Search</p>-->
    </div>

    <div class="row">
      <div class="col-sm-3" id="conditionBox">
        <div class="left-side-box">
          <div class="box-header title">{{ trans('search.category') }}</div>
          <div class="box-content">
            <div>
              <select class="form-control" name="category" id="category">
                @foreach ($categoryTreeList as $category)
                  <option value="{{ $category['id'] }}">{{parse_multilang($category['name'], App::getLocale()) }}</option>
                @endforeach
              </select> 
            </div>
            
            <div id="sub_category_list">
                <div class="checkbox">
                  <label><input type="checkbox" id="all_check">{{ trans('search.all_subcategories') }}</label>
                </div>
                @foreach ($categoryTreeList as $cix => $category)
                <div class="checkbox-list" id="category{{ $category['id'] }}"{!! $cix > 1 ? ' style="display: none"' : '' !!}>
                @foreach ($category['children'] as $subCategory)
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="{{ $subCategory['id'] }}">{{ parse_multilang($subCategory['name'], App::getLocale()) }} ( <span id="category_{{ $subCategory['id'] }}">{{ $subCategory['cnt_projects'] }}</span> )
                    </label>                
                  </div>
                @endforeach
                </div>
                @endforeach
            </div>              
          </div>  
        </div>  

        <div class="left-side-box">
          <div class="box-header title">
            {{ trans('search.job_type') }}
          </div>
          <div class="box-content">
            <div class="checkbox-list">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="type_hr" name="type_hr" value="{{Project::TYPE_HOURLY}}" >
                  {{ trans('common.hourly') }} ( 
                  <span id="hr_cnt">
                  @if ($search_title) 
                     {{ Project::where('status', '=', Project::STATUS_OPEN)
                          ->where('is_public', '=', Project::STATUS_PUBLIC)
                          ->where('subject', 'like', '%' . $search_title . '%')
                          ->where('type', '=', Project::TYPE_HOURLY)->count() }} 
                  @else 
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                          ->where('is_public', '=', Project::STATUS_PUBLIC)
                          ->where('type', '=', Project::TYPE_HOURLY)->count() }} 
                  @endif
                  
                  </span>
                  )
                </label>                
              </div>
              <div class="checkbox" >
                <label>
                  <input type="checkbox" id="type_fx" name="type_fx" value="{{Project::TYPE_FIXED}}">
                  {{ trans('common.fixed_price') }} 
                  (
                  <span id="fx_cnt">
                    @if ($search_title) 
                      {{ Project::where('status', '=', Project::STATUS_OPEN)
                                ->where('is_public', '=', Project::STATUS_PUBLIC)
                                ->where('subject', 'like', '%' . $search_title . '%')
                                ->where('type', '=', Project::TYPE_FIXED)
                                ->count() }} 
                    @else
                      {{ Project::where('status', '=', Project::STATUS_OPEN)
                              ->where('is_public', '=', Project::STATUS_PUBLIC)
                              ->where('type', '=', Project::TYPE_FIXED)
                              ->count() }} 
                    @endif
                  </span>
                  )
                </label>                
              </div>
            </div>
          </div>  
        </div>  

        <div class="left-side-box" style="display:none;">
          <div class="box-header title">
            Experience Level
          </div>
          <div class="box-content">
            <div class="checkbox-list">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="hourly"> Entry Level - $ (24,498)
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="fixed_price"> Intermediate - $$ (43,902)
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="fixed_price"> Expert - $$$ (17,638)
                </label>                
              </div>
            </div>
          </div>  
        </div>

        <div class="left-side-box" style="display:none;">
          <div class="box-header title">
            Client History
          </div>
          <div class="box-content">
            <div class="checkbox-list">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="hourly"> No Hires (32,156)
                </label>
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="fixed_price"> 1 to 9 Hours (29,169)
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="fixed_price"> 10+ Hours (34,784)
                </label>                
              </div>
            </div>
          </div>  
        </div>

        <div class="left-side-box" style="display:none;">
          <div class="box-header title">
            Location
          </div>
          <div class="box-content">
            <select class="form-control" name="location" id="location">
                <option value="0">Project</option>
                <option value="1">QA</option>
                <option value="2">MAINTANANCE</option>
            </select> 
          </div>
        </div>

        <div class="left-side-box">
          <div class="box-header title">
            {{ trans('common.budget') }} ({{ trans('common.fixed_price') }})
          </div>
          <div class="box-content">
            <div id="budget"></div>
            <div class="budget-value-section">
              <span id="budget-value-var">$0 - $5000</span>
              <input type="hidden" id="bgt_amt_min" name="bgt_amt_min" />
              <input type="hidden" id="bgt_amt_max" name="bgt_amt_max" />
            </div>
          </div>  
        </div>

        <div class="left-side-box">
          <div class="box-header title">{{ trans('job.duration') }}</div>
          <div class="box-content">
            <div class="checkbox-list" id="qry_dur_grp">
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::DUR_MT6M}}"> {{ trans('common.mt6m') }} 
                  (
                  <span id="dur_mt6m_cnt">
                  @if ($search_title) 
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                            ->where('is_public', '=', Project::STATUS_PUBLIC)
                            ->where('subject', 'like', '%' . $search_title . '%')
                            ->where('duration', '=', Project::DUR_MT6M)
                            ->count() }}
                  @else
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                          ->where('is_public', '=', Project::STATUS_PUBLIC)
                          ->where('duration', '=', Project::DUR_MT6M)
                          ->count() }}
                  @endif
                  </span>
                  )
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::DUR_3T6M}}"> 
                  {{ trans('common.3t6m') }} 
                  (
                  <span id="dur_3t6m_cnt">
                    @if ($search_title)
                      {{ Project::where('status', '=', Project::STATUS_OPEN)
                              ->where('is_public', '=', Project::STATUS_PUBLIC)
                              ->where('subject', 'like', '%' . $search_title . '%')
                              ->where('duration', '=', Project::DUR_3T6M)
                              ->count() }}
                    @else
                      {{ Project::where('status', '=', Project::STATUS_OPEN)
                                ->where('is_public', '=', Project::STATUS_PUBLIC)
                                ->where('duration', '=', Project::DUR_3T6M)
                                ->count() }}
                    @endif
                  </span>
                  )
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::DUR_1T3M}}">
                  {{ trans('common.1t3m') }} 
                  (
                  <span id="dur_1t3m_cnt">
                    @if ($search_title)
                      {{ Project::where('status', '=', Project::STATUS_OPEN)
                              ->where('is_public', '=', Project::STATUS_PUBLIC)
                              ->where('subject', 'like', '%' . $search_title . '%')
                              ->where('duration', '=', Project::DUR_1T3M)
                              ->count() }}
                    @else
                      {{ Project::where('status', '=', Project::STATUS_OPEN)
                              ->where('is_public', '=', Project::STATUS_PUBLIC)
                              ->where('duration', '=', Project::DUR_1T3M)
                              ->count() }}
                    @endif
                  </span>
                  )
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::DUR_LT1M}}">
                  {{ trans('common.lt1m') }} 
                  (
                  <span id="dur_lt1m_cnt">
                  @if ($search_title)
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                              ->where('is_public', '=', Project::STATUS_PUBLIC)
                              ->where('subject', 'like', '%' . $search_title . '%')
                              ->where('duration', '=', Project::DUR_LT1M)
                              ->count() }}
                  @else
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                            ->where('is_public', '=', Project::STATUS_PUBLIC)
                            ->where('duration', '=', Project::DUR_LT1M)
                            ->count() }}
                  @endif
                  </span>
                  )
                </label>                
              </div>
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::DUR_LT1W}}">
                  {{ trans('common.lt1w') }}
                  (
                  <spna id="dur_lt1w_cnt">
                  @if ( $search_title )
                  {{ Project::where('status', '=', Project::STATUS_OPEN)
                      ->where('is_public', '=', Project::STATUS_PUBLIC)
                      ->where('subject', 'like', '%' . $search_title . '%')
                      ->where('duration', '=', Project::DUR_LT1W)
                      ->count() }}
                  @else
                  {{ Project::where('status', '=', Project::STATUS_OPEN)
                      ->where('is_public', '=', Project::STATUS_PUBLIC)
                      ->where('duration', '=', Project::DUR_LT1W)
                      ->count() }}
                  @endif
                  </spna>
                  )
                </label>                
              </div>
            </div>
          </div>  
        </div>

        <div class="left-side-box">
          <div class="box-header title">{{ trans('job.workload') }}</div>
          <div class="box-content">
            <div class="checkbox-list" id="qry_workload_grp">
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::WL_FULLTIME}}">
                  {{ trans('search.full_time') }} (
                  <span id="ft_cnt">
                  @if ( $search_title )
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                        ->where('is_public', '=', Project::STATUS_PUBLIC)
                        ->where('subject', 'like', '%' . $search_title . '%')
                        ->where('workload', '=', Project::WL_FULLTIME)
                        ->count() }}
                  @else
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                        ->where('is_public', '=', Project::STATUS_PUBLIC)
                        ->where('workload', '=', Project::WL_FULLTIME)
                        ->count() }}
                  @endif
                  </span> )
                </label>                
              </div>

              <div class="checkbox">
                <label>
                  <input type="checkbox" value="{{Project::WL_PARTTIME}}">
                  {{ trans('search.part_time') }} (
                  <span id="pt_cnt">
                  @if ( $search_title )
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                            ->where('is_public', '=', Project::STATUS_PUBLIC)
                            ->where('subject', 'like', '%' . $search_title . '%')
                            ->where('workload', '=', Project::WL_PARTTIME)
                            ->count() }}
                  @else
                    {{ Project::where('status', '=', Project::STATUS_OPEN)
                          ->where('is_public', '=', Project::STATUS_PUBLIC)
                          ->where('workload', '=', Project::WL_PARTTIME)
                          ->count() }}
                  @endif
                  </span> )
                </label>                
              </div>

              <div class="checkbox">
                <label>
                  <input type="checkbox"  value="{{Project::WL_ASNEEDED}}">{{ trans('search.as_needed') }} (
                  <span id="as_cnt">
                  @if ( $search_title )
                  {{ Project::where('status', '=', Project::STATUS_OPEN)
                            ->where('is_public', '=', Project::STATUS_PUBLIC)
                            ->where('subject', 'like', '%' . $search_title . '%')
                            ->where('workload', '=', Project::WL_ASNEEDED)
                            ->count() }}
                  @else
                  {{ Project::where('status', '=', Project::STATUS_OPEN)
                            ->where('is_public', '=', Project::STATUS_PUBLIC)
                            ->where('workload', '=', Project::WL_ASNEEDED)
                            ->count() }}
                  @endif
                  </span> )
                </label>                
              </div>
            </div>
          </div>  
        </div>
      </div>

      <div class="col-sm-9">
        <div class="result-side">
          <div class="content-box">
            <div class="row margin-bottom-10">
              <div class="col-sm-4 form-inline">
                <div class="form-group">
                  <label>{{ trans('search.sort_by') }}:</label>
                  <select class="form-control" name="sort_by" id="sort_sel">
                    <option value="Newest">{{ trans('search.newest') }}</option>
                    <option value="Oldest">{{ trans('search.oldest') }}</option>
                  </select>
                </div>
              </div>

              <div class="col-sm-3">
                <span class="job-count iblock">{{ trans('search.n_jobs_found', ['n' =>  $job_count]) }}</span>
              </div>

              <div class="col-sm-5 text-right" id="pagination_wrapper">{!! $jobs_page->render() !!}</div>
            </div>
          </div>

          <div id="results"> 
            @foreach ($jobs_page as $id => $job) 
              @include ('pages.search.jobInfo')
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection