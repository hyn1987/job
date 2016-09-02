<?php
/**
 * Feedback (my_all_jobs)
 *
 * @author  - So Gwang
 */

use Wawjob\Contract;

?>
@extends('layouts/freelancer/index')

@section('content')
<div class="page-content">
  <div class="page-content-section no-padding">
  	<div class="title-section">
      <span class="title">{{ trans('page.' . $page . '.title') }}</span>
    </div>
  	<div class="row">
  		<div class="col-sm-12">
		 		<form class="form-horizontal feedback-form" role="form" method="post" id="feedback-form">
		 			<div class="form-body">
			 			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			 			<input type="hidden" name="contractId" value="{{ $contractId }}">
			 			
			 			 @if ($status != Contract::STATUS_CLOSED)
			 			<div class="form-group">
			 				<label class="col-sm-2 control-label">
			 					<span><strong>{{ trans('contract.reason') }}:&nbsp</strong></span>
			 				</label>
			 				<div class="col-sm-10">
					 			<div class="radio-list">
									<label class="radio-inline">
										<input type="radio" name="reason" value="Finished" checked>
										{{ trans('contract.job_finished') }}
									</label>

									<label class="radio-inline">
										<input type="radio" name="reason" value="Cancelled">
										{{ trans('contract.job_cancelled') }}
									</label>

									<label class="radio-inline">
										<input type="radio" name="reason" value="Other Reason">
										{{ trans('contract.other_reason') }}
									</label>
								</div>
							</div>
						</div>
						@endif

						<div class="form-group">
					  	<div class="col-sm-2">
					  		<span class="pull-right">
					  			<label><strong>{{ trans('contract.rate') }}:&nbsp</strong><label>
					  		</span>
					  	</div>
					  	<div class="col-sm-10 ">
					  		<div class="form-group" id="pulsate-regular" style="padding:5px; margin:0 5px;">
					  			<input name="rate" type="radio" class="rate required" value="1" {{ $rate == 1 ? 'checked="checked"' : ''}} /> 
                  <input name="rate" type="radio" class="rate" value="2" {{ $rate == 2 ? 'checked="checked"' : ''}} /> 
                  <input name="rate" type="radio" class="rate" value="3" {{ $rate == 3 ? 'checked="checked"' : ''}} /> 
                  <input name="rate" type="radio" class="rate" value="4" {{ $rate == 4 ? 'checked="checked"' : ''}} /> 
                  <input name="rate" type="radio" class="rate" value="5" {{ $rate == 5 ? 'checked="checked"' : ''}} /> 
					    	</div>
					    </div>
					  </div>

					  <div class="form-group">
					  	<label class="col-sm-2 ">
					  		<span class="pull-right"><strong>{{ trans('contract.feedback') }}:&nbsp</strong></span>
					  	</label>
					  	<div class="col-sm-10 ">
					    	<textarea class="form-control" name="feedback" rows="5"></textarea> 
					    </div>
					  </div>
				  </div>
				  
				  <div class="btn-secion pull-right">
        		<button type="submit" class="btn btn-primary">
        		@if ($status != Contract::STATUS_CLOSED)
            {{ trans('contract.close_contract_and_send_feedback') }}
            @else
            {{ trans('contract.send_feedback') }}
            @endif
        		</button>
        		<a href="{{ route('contract.contract_view', ['id' => $contractId]) }}" class="btn btn-warning">{{ trans('common.cancel')}}</a>
          </div>
				</form>
			</div>
		</div>
  </div>
</div>
@endsection