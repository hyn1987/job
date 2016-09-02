<?php
/**
 * My Info Page (user/my-info)
 *
 * @author  - nada
 */
?>
@extends('layouts/buyer/user')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  <div class="right-action-link">
    <a href="#" class="edit-action">{{ trans('user.action.edit') }}</a>
    <a href="#" class="cancel-action">{{ trans('user.action.cancel') }}</a>
  </div>
</div>
<div class="page-content-section buyer-user-page">
  <div class="form-section">
    <form id="form_user_my_info" class="form-horizontal" method="post" action="{{ route('user.my_info')}}" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="x1" id="x1">
      <input type="hidden" name="y1" id="y1">
      <input type="hidden" name="width" id="w">
      <input type="hidden" name="height" id="h">
      
      {{ show_messages() }}

      <input type="hidden" name="action" id="action" value="update">

      {{-- First Name --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.my.first_name') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="form-line-wrapper input-field">
            <input type="text" class="form-control" id="FirstName" name="first_name" placeholder="{{ trans('user.my.first_name') }}" data-rule-required="true" 
              value="{{ old('first_name') ? old('first_name') : (($user->contact->first_name != null)? $user->contact->first_name : "") }}" >
          </div>
          <label class="label-field">{{ ($user->contact->first_name != null)? $user->contact->first_name : "&nbsp;" }}</label>
        </div>                            
      </div>

      {{-- Last Name --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.my.last_name') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="form-line-wrapper input-field">
            <input type="text" class="form-control" id="LastName" name="last_name" placeholder="{{ trans('user.my.last_name') }}" data-rule-required="true"
              value="{{ old('last_name') ? old('last_name') : (($user->contact->last_name != null)? $user->contact->last_name : "") }}">
          </div>
          <label class="label-field">{{ ($user->contact->last_name != null)? $user->contact->last_name : "&nbsp;" }}</label>  
        </div>                   
      </div>

      {{-- Language --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.my.language') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="form-line-wrapper input-field language-select">
            <select type="text" class="form-control" id="language" name="language">
              <option value="en" {{ !$user->getLocale() || $user->getLocale()=="en" ? "SELECTED":"" }} data-content="<img class='flag' src='/assets/images/common/lang_flags/en.png'/>&nbsp;&nbsp; {{ trans( "common.language.en") }}">English</option>
              <option value="ch" {{ $user->getLocale()=="ch" ? "SELECTED":"" }} data-content="<img class='flag' src='/assets/images/common/lang_flags/ch.png'/>&nbsp;&nbsp; {{ trans( "common.language.ch") }}">Chinese</option>
              <option value="kp" {{ $user->getLocale()=="kp" ? "SELECTED":"" }} data-content="<img class='flag' src='/assets/images/common/lang_flags/kp.png'/>&nbsp;&nbsp; {{ trans( "common.language.kp") }}">Korean</option>
            </select>
          </div>
          <label class="label-field">
            <img class='flag' src='/assets/images/common/lang_flags/{{ $user->getLocale()? $user->getLocale(): "en" }}.png'/>&nbsp;&nbsp; {{ trans( "common.language.".($user->getLocale()? $user->getLocale(): "en") ) }}
          </label>  
        </div>                   
      </div>

      {{-- Photo --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.my.photo') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field">
            <div class="input-icon right">
              <input type="file" id="avatar" class="form-control" name="avatar">
            </div>
          </div>
          <div id="temp-avatar">              
          </div>
          <label class="label-field">
            <div class="user-avatar">
              <img src="{{ avatarUrl($user) }}" width="150" />
            </div>
          </label>
        </div>
      </div>

      <div class="row form-group action-group">
        <div class="col-md-10 text-right input-field">
          <button type="submit" class="btn btn-primary">{{ trans('user.action.save') }}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection