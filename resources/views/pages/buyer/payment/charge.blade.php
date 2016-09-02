<?php
/**
 * Payment Charging Page (payment/charge)
 *
 * @author  - nada
 */
?>
@extends('layouts/buyer/payment')

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  <div class="balance pull-right">
    Balance : {{ $balance<0? '($'.formatCurrency(abs($balance)).')' : '$'.formatCurrency($balance) }}
  </div>
</div>
<div class="page-content-section buyer-payment-charge-page">
  <form id="form_payment_charge" method="post" class="" action="{{ route('payment.charge')}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    {{ show_messages() }}
    
    <div class="form-label">Please input charge amount.</div>
    <div class="form-group charge-amount-field">
      <div class="input-group form-line-wrapper">
        <span class="input-group-addon">$</span>
        <input type="text" class="form-control" id="charge_amount" name="charge_amount" placeholder="" data-rule-required="true" data-rule-number="true" 
          value="{{ old('charge_amount') ? old('charge_amount') : '' }}">
      </div>
    </div>
    <div class="form-group">
      <div class="form-line-wrapper checkbox">
        <input type="checkbox" name="confirm_payment" id="confirm_payment" class="checkbox" data-rule-required="true" value="1">
        <label for="confirm_payment">Please confirm this payment</label>
      </div>
    </div>
    <div class="form-group">
      <button" class="charge-submit btn btn-primary" value="charge"> Submit </button>
    </div>
  </form>  
</div>
@endsection