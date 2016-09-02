<div class="modal fade" id="modalEditTicket" tabindex="-1" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Edit Ticket</h4>
      </div>
      <div class="modal-body">
        <form name="edit_ticket" class="form-horizontal">
          <div style="margin-top: 20px;"></div>

          {{-- Open | Assigned | Solved | Closed --}}
          <div class="form-group">
            <label class="col-md-3 control-label">Status</label>
            <div class="col-md-5">
              <select id="sel_status" name="t_status" class="form-control">
              @foreach ($options['status'] as $label => $v)
                <option value="{{ $v }}">{{ $label }}</option>
              @endforeach
              </select>
              <span class="help-block">The status of this ticket.</span>
            </div>
          </div>

          {{-- Assignee --}}
          @if ($is_super_admin)
          <div class="form-group">
            <label class="col-md-3 control-label">Assignee</label>
            <div class="col-md-5">
              <select id="sel_admin" name="t_admin" class="form-control">
                <option value="0">Choose assignee</option>
                @foreach ($options['admins'] as $admin)
                <option value="{{ $admin->id }}">{{ $admin->first_name }}@if ($admin->first_name && $admin->last_name)&nbsp;@endif{{ $admin->last_name }}</option>
                @endforeach
              </select>
              <span class="help-block">The administrator who supports this ticket.</span>
            </div>
          </div>
          @endif

          {{-- Priority --}}
          <div class="form-group">
            <label class="col-md-3 control-label">Priority</label>
            <div class="col-md-5">
              <select id="sel_priority" name="t_priority" class="form-control">
              @foreach ($options['priority'] as $label => $v)
                <option value="{{ $v }}">{{ $label }}</option>
              @endforeach
              </select>
              <span class="help-block">The priority of this ticket.</span>
            </div>
          </div>

          {{-- Type (Notify | Normal | Dispute | Question | Suspension | Maintenance) --}}
          <div class="form-group">
            <label class="col-md-3 control-label">Type</label>
            <div class="col-md-5">
              <select id="sel_type" name="t_type" class="form-control">
              @foreach ($options['type'] as $label => $v)
                <option value="{{ $v }}">{{ $label }}</option>
              @endforeach
              </select>
              <span class="help-block">The type of this ticket.</span>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn default btn-cancel" data-dismiss="modal">Close</button>
        <button type="button" class="btn blue btn-save">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>