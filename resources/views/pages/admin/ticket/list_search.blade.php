<div class="col-md-12">
  <div class="navbar navbar-default" role="navigation">
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <form class="navbar-form navbar-right" role="search" name="frm_search" id="frm_search" method="POST">
        <input type="hidden" name="date_range" value="{{ old('date_range') }}">
        <input type="hidden" name="page" value="">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="input-group">
          <span class="input-group-addon input-circle-left"><i class="fa fa-file-text-o"></i></span>
          <input type="text" class="form-control" name="ckeyword" value="{{ old('ckeyword') }}" placeholder="Keyword">
        </div>

        <div class="input-group">
          <span class="input-group-addon input-circle-left"><i class="fa fa-user"></i></span>
          <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username">
        </div>

        <div class="input-group">
          <select class="form-control" name="status">
            <option value=""@if (old('status') === '') selected @endif>All Status</option>
          @foreach ($options['status'] as $label => $v)
            <option value="{{ $v }}"{{ old('status') != null && $v == old('status') ? ' selected' : '' }}>{{ $label }}</option>
          @endforeach
          </select>
        </div>

        <button class="btn blue btn-search" type="submit">Search</button>
      </form>
    </div>
  </div>
</div>