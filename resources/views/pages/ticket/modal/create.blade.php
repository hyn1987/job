<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="ticketCreateModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ticketCreateModalLabel">{{ trans('ticket.modal.Create_A_Ticket') }} </h4>
      </div>
      <div class="modal-body">
        <form id="createForm" class="form-horizontal" method="POST" enctype="multipart/form-data" action="/ticket/create">
        	<input type="hidden" name="_token" value="{{ csrf_token() }}">				  
				  <div class="form-group">
				    <label for="" class="col-sm-2 control-label">{{ trans('ticket.modal.Subject') }}:</label>
				    <div class="col-sm-10">
				    	<input type="text" name="subject" class="form-control" id="" placeholder="">
				    </div>	
				  </div>
				  <div class="form-group">
				    <label for="" class="col-sm-2 control-label">{{ trans('ticket.modal.Category') }}:</label>
				    <div class="col-sm-10">
					    <select class="form-control" name="type">
					    	@foreach ($optionTypeArry as $key=>$optionType)
							  <option value="{{$optionType[$key]}}">{{$key}}</option>
							  @endforeach
							</select>
						</div>
				  </div>
				  <div class="form-group">
				    <label for="" class="col-sm-2 control-label">{{ trans('ticket.modal.Text') }}:</label>
				    <div class="col-sm-10">
				    	<textarea class="form-control" rows="10" name="content"></textarea>
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="" class="col-sm-2 control-label">{{ trans('ticket.modal.Attachment') }}:</label>
				    <div class="col-sm-10">
				    	<input type="file" id="fileAttachment" name="attachFile">
            	<p class="help-block">{{ trans('ticket.modal.Your_file_here_please') }}.</p>
            </div>
          </div>
				</form>
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</a>
        <a class="btn btn-primary" id="saveBtn">{{ trans('common.save') }}</a>
      </div>
    </div>
  </div>
</div>