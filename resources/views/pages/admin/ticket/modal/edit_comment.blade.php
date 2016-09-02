<div class="modal fade modal-edit-comment" id="modalEditComment" tabindex="-1" aria-hidden="false">
  <form name="edit_comment" id="frm_edit_comment" method="POST" action="/admin/ticket/ajax">
    <input type="hidden" name="cmd" value="updateComment">
    <input type="hidden" name="tc_id" value="">

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
          <h4 class="modal-title">Edit Comment</h4>
        </div>

        <div class="modal-body">
          <div class="text-center form-group">
            <textarea id="txt_ticket_comment" class="t-comment form-control" name="t_comment" rows="10"></textarea>
          </div>
          <div class="clearfix"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn default btn-cancel" data-dismiss="modal">Close</button>
          <button type="submit" class="btn blue btn-save">Save Changes</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </form>
</div>
