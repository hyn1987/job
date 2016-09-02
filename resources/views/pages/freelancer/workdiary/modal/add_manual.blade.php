<!-- Add manual time -->
<div class="modal fade" id="addManualModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('workdiary.add_manual_time') }}</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <label>{{ trans('workdiary.from') }}</label>
                </div>
                <div class="col-md-8">
                  <select id="startHour" name="starthour">
                    @for ( $i = 0; $i < 24; $i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                  <span>h</span>
                  <select id="startMinute" name="startminute">
                    @for ( $i = 0; $i < 6; $i++)
                      @if ( $i == 0 )
                      <option value="{{$i}}">00</option>
                      @else
                      <option value="{{$i*10}}">{{$i*10}}</option>
                      @endif
                    @endfor
                  </select>
                  <span>m</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>{{ trans('workdiary.to') }}</label>
                </div>
                <div class="col-md-8">
                  <select id="endHour" name="endhour">
                    @for ( $i = 0; $i < 24; $i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                  <span>h</span>
                  <select id="endMinute" name="endminute">
                    @for ( $i = 0; $i < 6; $i++)
                      @if ( $i == 0 )
                      <option value="{{$i}}">00</option>
                      @else
                      <option value="{{$i*10}}">{{$i*10}}</option>
                      @endif
                    @endfor
                  </select>
                  <span>m</span>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label>{{ trans('workdiary.memo') }}</label>
                </div>
                <div class="col-md-8">
                  <textarea id="manualMemo" class="manualmemo"></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="insertManual" class="btn btn-primary">{{ trans('workdiary.apply') }}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
