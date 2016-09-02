<?php
/**
 * My Info Page (user/my-info)
 *
 * @author  - nada
 */
use Wawjob\Project;
use Wawjob\Category;
?>
@extends('layouts/buyer/index')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
<div class="page-content">
  <div class="title-section">
    <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  </div>
  <div class="page-content-section">
    <div class="form-section job-content-section">
    {{ show_messages() }}

    <div class="box-section">
      <form id="form_job_post" class="{{ old('job_type')===(string)Project::TYPE_HOURLY? "hourly-job" : "fixed-job" }}" method="post" action="{{ route('job.create')}}" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="action" id="action" value="update">

        {{-- Category --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.category') }}:</div>
          <div class="col-sm-10 col-xs-12">
            <div class="form-line-wrapper">
              <select class="job-category form-control select2" id="sel_category" name="category" data-rule-required="true">
                <option value="">{{ trans('job.please_select_category') }}</option>
                @foreach(Category::projectCategories() as $id=>$category1)
                <optgroup label="{{ parse_multilang($category1['name']) }}">
                  @if(is_array($category1['children']))
                  @foreach($category1['children'] as $id=>$category2)
                  <option value="{{ $category2['id'] }}" {{ old('category') == $category2['id'] ? "selected" : ""  }} >{{ parse_multilang($category2['name']) }}</option>
                  @endforeach
                  @endif
                </optgroup>
                @endforeach
              </select>
            </div>
          </div>                            
        </div>

        {{-- Title --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.title') }}:</div>
          <div class="col-sm-10 col-xs-12">
            <div class="form-line-wrapper">
              <input type="text" class="form-control" id="job_title" name="title" placeholder="" value="{{ old('title') ? old('title') : "" }}" data-rule-required="true">
            </div>
          </div>                            
        </div>

        {{-- Description --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.description') }}:</div>
          <div class="col-sm-10 col-xs-12">
            <div class="form-line-wrapper">
              <textarea type="text" class="form-control" id="description" name="description" rows="10" data-rule-required="true">{{ old('description') ? old('description') : "" }}</textarea>
            </div>
          </div>
        </div>

        <!--
        {{-- Job Skills --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">Job Skills:</div>
          <div class="col-sm-10 col-xs-12">
            <div class="form-line-wrapper">
              <input type="input" id="job_skills" name="job_skills" class="form-control select2" value="red, blue" multiple>
            </div>
          </div>
        </div>
        -->

        {{-- Type --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.type') }}:</div>
          <div class="col-sm-10 col-xs-12 other-input">
            <div class="job-type-section form-line-wrapper">
              <label class="radio-inline">
                <input type="radio" name="job_type" id="hourly_job" value="{{ Project::TYPE_HOURLY }}" {{ old('job_type')===(string)Project::TYPE_HOURLY? "CHECKED" : "" }}>{{ trans('common.hourly') }}</label>
              <label class="radio-inline">
                <input type="radio" name="job_type" id="fixed_job" value="{{ Project::TYPE_FIXED }}" {{ !old('job_type') || old('job_type')===(string)Project::TYPE_FIXED? "CHECKED" : "" }}>{{ trans('common.fixed_price') }}</label>
            </div>
          </div>
        </div>

        {{-- Hourly-Duration --}}
        <div class="hourly-job-section row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.duration') }}: </div>
          <div class="col-sm-5 col-xs-12">
            <div class="form-line-wrapper">
              <select class="form-control" id="duration" name="duration" placeholder="Duration" >
                <option value="MT6M" {{(old('duration')=="MT6M"? "SELECTED" : "")}}>{{ trans('common.mt6m') }}</option>
                <option value="3T6M" {{(old('duration')=="3T6M"? "SELECTED" : "")}}>{{ trans('common.3t6m') }}</option>
                <option value="1T3M" {{(old('duration')=="1T3M"? "SELECTED" : "")}}>{{ trans('common.1t3m') }}</option>
                <option value="LT1M" {{(old('duration')=="LT1M"? "SELECTED" : "")}}>{{ trans('common.lt1m') }}</option>
                <option value="LT1W" {{(old('duration')=="LT1W"? "SELECTED" : "")}}>{{ trans('common.lt1w') }}</option>
              </select>
            </div>
          </div>  
        </div>

        {{-- Hourly-Workload --}}
        <div class="hourly-job-section row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.workload') }}: </div>
          <div class="col-sm-5 col-xs-12">
            <div class="form-line-wrapper">
              <select class="form-control" id="workload" name="workload" placeholder="Workload">
                <option value="FT" {{(old('workload')=="FT"? "SELECTED" : "")}}>Full Time - 30+ hrs/week</option>
                <option value="PT" {{(old('workload')=="PT"? "SELECTED" : "")}}>Part Time - 10~30 hrs/week</option>
                <option value="AN" {{(old('workload')=="AN"? "SELECTED" : "")}}>As needed - Less than 10 hrs/week</option>
              </select>
            </div>
          </div>
        </div>

        {{-- Fixed-Price --}}
        <div class="row form-group fixed-job-section">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.price') }}:</div>
          <div class="col-sm-5 col-xs-10">
            <div class="input-group have-group-addon form-line-wrapper">
              <span class="input-group-addon">$</span>
              <input type="text" class="form-control" id="price" name="price" placeholder="" 
                value="{{ old('price') ? formatCurrency(priceRaw(old('price'))) : "0.00" }}" data-rule-number="true">
            </div>
          </div>                        
        </div>

        {{-- Cover Letter Required --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.cover_letter_required') }}:</div>
          <div class="col-sm-10 col-xs-12 other-input">
            <div class="form-line-wrapper">
              <label class="check-inline">
                <input type="checkbox" name="cv_required" id="cv_required" value="1" {{  old('cv_required')? "CHECKED" : "" }}> {{ trans('job.yes_require_cover_letter') }}
              </label>
            </div>
          </div>
        </div>

        {{-- Contract Limit --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.contract_limited') }}:</div>
          <div class="col-sm-5 col-xs-12">
            <div class="form-line-wrapper">
              <select class="form-control" id="contract_limit" name="contract_limit">
                @for ($limit=1; $limit<=10; $limit++)
                <option value="{{ $limit }}" {{(old('contract_limit')==$limit? "SELECTED" : "")}}>{{ $limit }}</option>}}
                @endfor
              </select>
            </div>
          </div>
        </div>

        {{-- Is Public --}}
        <div class="row form-group">
          <div class="col-sm-2 col-xs-12 control-label">{{ trans('job.is_public') }}:</div>
          <div class="col-sm-5 col-xs-12 other-input">
            <div class="form-line-wrapper">
              <label class="check-inline">
                <input type="checkbox" name="job_public" id="job_public" value="1" {{  old('job_public')? "CHECKED" : "" }}> {{ trans('job.yes_make_this_job_public') }}</label>
            </div>
          </div>
        </div>

        <div class="row form-group action-group">
          <div class="col-sm-offset-2 col-sm-10 input-field">
            <button type="submit" class="btn btn-primary">{{ trans('job.submit') }}</button>
          </div>
        </div>
      </form>
    </div>
    </div><!-- END OF .job-content-section -->
  </div>
</div>
@endsection