<?php
/**
 * My Info Page (user/my-info)
 *
 * @author  - nada
 */
?>
@extends('layouts/buyer/user')

@section('additional-css')
<link rel="stylesheet" href="{{ url('assets/plugins/select2/dist/css/select2.min.css') }}">
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
    <form id="form_user_contact_info" class="form-horizontal" method="post" action="{{ route('user.contact_info')}}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      
      {{ show_messages() }}
      <input type="hidden" name="action" id="action" value="update">

      {{-- Country --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.contact.country') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field form-line-wrapper">
            <select class="form-control" id="Country" name="country" placeholder="{{ trans('user.contact.country') }}" data-rule-required="true">
              <option value="">Please select country</option>
              @foreach ($countries as $country)
              <option value="{{ $country->charcode }}"{{ ($user->contact->country_code == $country->charcode)? ' selected' : '' }}>{{ $country->name }}</option>
              @endforeach
            </select>
          </div>
          <label class="label-field">{{ ($user->contact->country_code != null)? $user->contact->country->name : "&nbsp;" }}</label>
        </div>                            
      </div>

      {{-- City --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.contact.city') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field form-line-wrapper">
            <input type="text" class="form-control" id="City" name="city" placeholder="{{ trans('user.contact.city') }}" data-rule-required="true" 
              value="{{ old('city') ? old('city') : (($user->contact->city != null)? $user->contact->city : "") }}">
          </div>
          <label class="label-field">{{ ($user->contact->city != null)? $user->contact->city : "&nbsp;" }}</label>
        </div>                   
      </div>

      {{-- Address --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.contact.address') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field form-line-wrapper">
            <input type="text" class="form-control" id="Address" name="address" placeholder="{{ trans('user.contact.address') }}" data-rule-required="true" 
              value="{{ old('address') ? old('address') : (($user->contact->address != null)? $user->contact->address : "") }}">
          </div>
          <label class="label-field">{{ ($user->contact->address != null)? $user->contact->address : "&nbsp;" }}</label>
        </div>
      </div>

      {{-- Address 2 --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.contact.address2') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field form-line-wrapper">
            <input type="text" class="form-control" id="Address2" name="address2" placeholder="{{ trans('user.contact.address2') }}" 
              value="{{ old('address2') ? old('address2') : (($user->contact->address2 != null)? $user->contact->address2 : "") }}">
          </div>
          <label class="label-field">{{ ($user->contact->address2 != null)? $user->contact->address2 : "&nbsp;" }}</label>
        </div>
      </div>

      {{-- Zip Code --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.contact.zip_code') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field form-line-wrapper">
            <input type="text" class="form-control" id="ZipCode" name="zipcode" placeholder="{{ trans('user.contact.zip_code') }}" data-rule-required="true" 
              value="{{ old('zipcode') ? old('zipcode') : (($user->contact->zipcode != null)? $user->contact->zipcode : "") }}">
          </div>
          <label class="label-field">{{ ($user->contact->zipcode != null)? $user->contact->zipcode : "&nbsp;" }}</label>
        </div>
      </div>

      {{-- Phone --}}
      <div class="row form-group">
        <div class="col-sm-3 col-xs-5 control-label">{{ trans('user.contact.phone') }}:</div>
        <div class="col-sm-9 col-xs-7">
          <div class="input-field form-line-wrapper">
            <input type="text" class="form-control" id="Phone" name="phone" placeholder="{{ trans('user.contact.phone') }}" data-rule-required="true" 
              value="{{ old('phone') ? old('phone') : (($user->contact->phone != null)? $user->contact->phone : "") }}">
          </div>
          <label class="label-field">{{ ($user->contact->phone != null)? $user->contact->phone : "&nbsp;" }}</label>
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