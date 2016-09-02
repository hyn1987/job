<?php
/**
 * Affiliate Page (user/affiliate)
 *
 * @author  - brice
 */
?>
@extends('layouts/buyer/user')

@section('content')

<div class="title-section">
  <span class="title">Affiliate Invite</span>
</div>
<div class="page-content-section buyer-user-page">
  <div class="info-section form-horizontal">
    
  </div>

  <div class="form-section">
    <form id="affiliatefrm" class="form-horizontal" method="post" action="/user/affiliate">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <fieldset>
        {{-- Affiliate URL --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.affiliate.url') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">{{siteUrl()}}/user/signup/freelancer?token={{ $url}}</div>
            </div>
            <div class="clear-div"></div>
        </div>
        {{-- Affiliate Emails --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.affiliate.emails') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class=" form-line-wrapper">
                  <input type="text" class="form-control" id="emails" name="emails" autocomplete="off">
                </div>
                <label class="label-field">&nbsp;</label>
            </div>
            <div class="clear-div"></div>
        </div>   
        <div class="row form-group action-group">
          <div class="col-md-12 text-right">
            <button type="submit" class="btn btn-primary">{{ trans('user.affiliate.send') }}</button>
          </div>
        </div>
      </fieldset>     
    </form>
  </div>
</div>
@endsection