<div class="modal fade" id="modalPayment" tabindex="-1" role="dialog" aria-labelledby="">
  <div class="modal-dialog" role="slot">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPaymentLabel">Test</h4>
      </div>
      <div class="modal-body text-center">
        <div class="content-section clearfix">
          <form id="form_payment" method="post" class="form-horizontal" action="{{ route('contract.contract_view', ['id'=>$contract->id])}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_action" value="payment" >
            <input type="hidden" name="payment_type" id="payment_type" value="">

            {{-- Amount --}}
            <div class="form-group">
              <label class="col-xs-3 col-md-2 control-label text-left">{{ trans('report.amount') }}: </label>
              <div class="col-xs-8 col-md-3">
                <div class="input-group form-line-wrapper">
                  <span class="input-group-addon">$</span>
                  <input type="text" data-required="1" class="form-control" id="payment_amount" name="payment_amount" placeholder="" value="{{ old('payment_amount') ? old('payment_amount') : '' }}" data-rule-required="true" data-rule-number="true">
                </div>
              </div>
            </div>

            {{-- Payment note --}}
            <div class="form-group">
              <div class="col-sm-12">
                <textarea class="form-control" name="payment_note" id="payment_note" rows="7" placeholder="{{ trans('contract.payment.enter_your_note_here') }}"></textarea> 
              </div>
            </div>

            {{-- Checkbox: confirm --}}
            <div class="form-group">
              <div class="col-sm-12">
                <div class="form-line-wrapper checkbox">
                  <input type="checkbox" name="confirm_payment" id="confirm_payment" class="" value="1" data-rule-required="true">
                  <label for="confirm_payment">{{ trans('contract.payment.confirm') }}</label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-12">
                <button data-dismiss="modal" class="btn btn-info">{{ trans('common.cancel') }}</button>
                <button class="charge-submit btn btn-primary" value="charge">{{ trans('contract.payment.submit') }}</button>
              </div>
            </div>
          </form>  
        </div>
      </div>
    </div>
  </div>
</div>