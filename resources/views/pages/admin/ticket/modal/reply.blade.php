<div class="modal fade modal-reply" id="modalReplyTicket" tabindex="-1" aria-hidden="false">
  <form name="reply_ticket" id="frm_reply_ticket" method="POST" enctype="multipart/form-data" action="/admin/ticket/ajax">
    <input type="hidden" name="cmd" value="replyTicket">
    <input type="hidden" name="t_id" value="">

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
          <h4 class="modal-title">Reply</h4>
        </div>
        <div class="modal-body">
          {{-- Open | Assigned | Solved | Closed --}}
          <div class="form-group">
            <h5 class="t-subject"></h5>
          </div>
          <div class="text-center form-group">
            <textarea class="t-msg" name="t_msg"></textarea>
          </div>

          <div class="row-attachment">
            <label for="exampleInputFile" class="col-md-3 control-label">Attachment</label>
            <div class="col-md-9">
              <input type="file" id="fileAttachment" name="t_attach">
              <p class="help-block">Your file here please.</p>
            </div>
          </div>

          <div class="clearfix"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn default btn-cancel" data-dismiss="modal">Close</button>
          <button type="submit" class="btn blue btn-save">Send</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </form>
</div>
