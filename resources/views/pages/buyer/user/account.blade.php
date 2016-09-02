<?php
/**
 * My Info Page (user/my-info)
 *
 * @author  - nada
 */
?>
@extends('layouts/buyer/user')

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
    <form id="MyAccountForm" class="form-horizontal" method="post" action="{{ route('user.account')}}" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      
      {{ show_messages() }}
      
      <fieldset>
        {{-- Email --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.account.email') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">{{ ($user->email != null)? $user->email : "" }}</div>
            </div>
            <div class="clear-div"></div>
        </div>

        {{-- Username --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.account.username') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">{{ ($user->username != null)? $user->username : "" }}</div>
            </div>
            <div class="clear-div"></div>
        </div>

        {{-- Old Password --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.account.old') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="input-field form-line-wrapper">
                    <input type="password" class="form-control" id="OldPassword" name="old_password" autocomplete="off" data-rule-required="true" 
                        value="{{ old('old_password') ? old('old_password') : "" }}">
                </div>
                <label class="label-field">****</label>
            </div>
            <div class="clear-div"></div>
        </div>

        {{-- New Password --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.account.new') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="input-field form-line-wrapper">
                    <input type="password" class="form-control" id="NewPassword" name="new_password" autocomplete="off" data-rule-required="true">
                </div>
                <label class="label-field">&nbsp;</label>
            </div>
            <div class="clear-div"></div>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.account.confirm') }}:</div> 
            </div>
            <div class="col-sm-9">
                <div class="input-field form-line-wrapper">
                    <input type="password" class="form-control" id="NewPassword2" name="new_password2" autocomplete="off" data-rule-required="true" data-rule-equalto="#NewPassword">
                </div>
                <label class="label-field">&nbsp;</label>
            </div>
            <div class="clear-div"></div>
        </div>   
        <div class="row form-group action-group">
          <div class="col-md-10 text-right input-field">
            <button type="submit" class="btn btn-primary">{{ trans('user.action.save') }}</button>
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>
@endsection