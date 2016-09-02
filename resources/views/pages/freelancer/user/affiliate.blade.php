<?php
/**
 * Affiliate Page (user/affiliate)
 *
 * @author  - brice
 */
?>
@extends('layouts/freelancer/user')

@section('content')
<div class="title-section">
  <span class="title">My Affiliate Info</span>
</div>
<div class="page-content-section freelancer-user-page">
  <div class="info-section form-horizontal">
    
  </div>
  <div class="form-section">
    <fieldset class="form-horizontal">
        {{-- Affiliate URL --}}
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.affiliate.my_affiliate_name') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">{{ $affiliate_name }}</div>
            </div>
            <div class="clear-div"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.affiliate.percent') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">
                    @if ($affiliate_percent != "")
                    {{ $affiliate_percent }}({{ trans('user.affiliate.pro') }})
                    @endif
                </div>
            </div>
            <div class="clear-div"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.affiliate.duration') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">
                @if ($affiliate_duration != "")
                    {{ $affiliate_duration }} 
                    @if ($affiliate_duration > 1) 
                    ({{ trans('user.affiliate.years') }})
                    @else
                    ({{ trans('user.affiliate.year') }})
                    @endif
                @endif
                </div>
            </div>
            <div class="clear-div"></div>
        </div>
        <div class="form-group">
            <div class="col-sm-3 control-label">
                <div class="pre-summary">{{ trans('user.affiliate.created_at') }}:</div>
            </div>
            <div class="col-sm-9">
                <div class="info-div">{{ $affiliate_created_at }}</div>
            </div>
            <div class="clear-div"></div>
        </div>
    </fieldset>
  </div>
</div>
<div class="title-section">
  <span class="title">Affiliate Invite</span>
</div>
<div class="page-content-section freelancer-user-page">
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