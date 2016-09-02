@extends('layouts/admin/index')

@section('toolbar')
@endsection

@section('content')
<div class="row page-body">
  <div class="col-md-12">
    <div class="navbar navbar-default" role="navigation">
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <form class="navbar-form navbar-left" role="search" name="frm_search" id="frm_search" method="POST">
          <input type="hidden" id="affiliate_id" name="affiliate_id" value="{{$data->id}}"/>
          <div class="input-group">
            <span class="input-group-addon input-circle-left"><i class="fa fa-users"></i> Percent: </span>
            <input type="text" class="form-control" id="percent" name="percent" value="{{$data->percent}}" placeholder="Percent">
          </div>
          <div class="input-group">
            <span class="input-group-addon input-circle-left"><i class="fa fa-calendar"></i> Duration: </span>
            <input type="text" class="form-control" id="duration" name="duration" value="{{$data->duration}}" placeholder="Duration">
          </div>
          <button class="btn-affiliate btn-update btn btn-success btn-sm" type="button">Update</button>
        </form>
      </div>        
    </div>
  </div>
</div>
@endsection

@section('js')
  <script>
    var data = {
      updateUrl: "{{ route('admin.affiliate.update') }}",
    };
  </script>
@endsection