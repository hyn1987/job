<div class="modal fade" id="modalPayment" tabindex="-1" role="dialog" aria-labelledby="">
  <div class="modal-dialog" role="slot">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalPaymentLabel">Test</h4>
      </div>
      <div class="modal-body text-center">
        <div class="content-section clearfix">
          <form id="form_payment" method="post" class="" action="{{ route('contract.contract_view', ['id'=>$contract->id])}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
            <input type="hidden" name="_action" value="payment" >

            <input type="hidden" name="payment_type" id="payment_type" value="Refund">
            {{ show_messages() }}
            
            <div class="form-label">Please input amount.</div>
            <div class="form-group payment-amount-field">
              <div class="input-field">
                <div class="input-group input-icon right">
                  <input type="text" data-required="1" class="form-control" id="payment_amount" name="payment_amount" 
                         placeholder="" value="{{ old('payment_amount') ? old('payment_amount') : '' }}">
                  <span class="input-group-addon">$</span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="payment_note" id="payment_note" rows="7" placeholder="You can input note"></textarea> 
            </div>
            <div class="form-group">
              <div class="input-icon right checkbox-inline">
                <input type="checkbox" name="confirm_payment" id="confirm_payment" class="checkbox" data-required="1" value="1">
                <label for="confirm_payment">Please confirm this refund</label>
              </div>
            </div>
            <div class="form-group">
              <button class="refund-submit btn btn-primary" value="refund">Submit</button>
            </div>
          </form>  
        </div>
      </div>
    </div>
  </div>
</div>