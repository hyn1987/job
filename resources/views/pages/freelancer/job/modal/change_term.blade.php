<?php use Wawjob\Project; ?>

<!-- Change term -->
<div class="modal fade" id="ChangeTermModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">{{ trans('job.change_term') }}</h4>
            </div>
            <div class="modal-body">
              <div class="row block-section clearfix">
                <div class="col-xs-4">
                  <label>{{ $job->type === Project::TYPE_HOURLY ? trans('job.billing_rate') : trans('job.billing_price') }}</label>
                </div>
                <div class="col-xs-8">
                  <span class="col-xs-2">$</span>
                  <input class="col-xs-8" type="text" id="BillingRate" name="billing_rate" value="{{$application->price}}" />
                  <span class="col-xs-2">{{ $job->type===Project::TYPE_HOURLY ? '/'.trans('hr') : '' }}</span>
                </div>
              </div>
              <div class="row block-section clearfix">
                <div class="col-xs-4">
                  <label>{{ trans('job.you_will_earn') }}</label>
                </div>
                <div class="col-xs-8">
                  <span class="col-xs-2">$</span>
                  <input class="col-xs-8" type="text" id="EarningRate" name="earning_rate" value="{{getEarningRate($application->price)}}" />
                  <span class="col-xs-2">{{ $job->type===Project::TYPE_HOURLY ? '/'.trans('hr') : '' }}</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" id="changeTerm" class="btn btn-primary">{{ trans('common.submit') }}</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
