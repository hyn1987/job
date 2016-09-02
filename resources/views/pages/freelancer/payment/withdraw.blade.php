<?php
/**
 * Payment Withdraw Page (payment/withdraw)
 *
 * @author  - Ri Chol Min
 */
?>
@extends('layouts/freelancer/payment')

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  <div class="balance pull-right">
    {{ trans('common.balance') }} : {{ $balance < 0 ? '($'.formatCurrency(abs($balance)).')' : '$'.formatCurrency($balance) }}
  </div>
</div>
<div class="page-content-section freelancer-payment-withdraw-page">
  <form id="form_payment_withdraw" method="post" action="{{ route('payment.withdraw')}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    {{ show_messages() }}
    <div class="form-label">{{ trans('payment.input_withdraw_amount') }}</div>
    <div class="form-group withdraw-amount-field">
      <div class="input-group form-line-wrapper">
        <input type="text" class="form-control" id="withdraw_amount" name="withdraw_amount" 
               placeholder="" value="{{ old('withdraw_amount') ? old('withdraw_amount') : '' }}" data-rule-required="true" data-rule-number="true">
        <span class="input-group-addon">$</span>
      </div>
    </div>
    <div class="form-group">
      <div class="form-line-wrapper checkbox">
        <input type="checkbox" name="confirm_payment" id="confirm_payment" class="checkbox" data-rule-required="true" value="1">
        <label for="confirm_payment">{{ trans('payment.confirm_this_payment') }}</label>
      </div>
    </div>
    <div class="form-group">
      <button class="withdraw-submit btn btn-primary" value="withdraw">{{ trans('payment.withdraw.submit') }}</button>
    </div>
  </form>  
</div>
@endsection