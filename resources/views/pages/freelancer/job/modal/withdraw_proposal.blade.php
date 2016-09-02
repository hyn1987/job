<!-- Withdraw proposal -->
<div class="modal fade" id="WithdrawProposalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('job.withdraw_proposal') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row block-section clearfix">
                  <div class="col-xs-3"><label>{{ trans('job.withdraw.reason') }}</label></div>
                  <div class="col-xs-9"><textarea id="Reason" name="reason"></textarea></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="withdrawProposal" class="btn btn-primary">{{ trans('job.withdraw.submit') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>