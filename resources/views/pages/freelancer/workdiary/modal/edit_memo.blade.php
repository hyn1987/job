<!-- Edit memo -->
<div class="modal fade" id="EditMemoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('workdiary.edit_memo') }}</h4>
            </div>
            <div class="modal-body">
                <textarea id="newMemo" class="memo"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="updateMemo" class="btn btn-primary">{{ trans('workdiary.apply') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
