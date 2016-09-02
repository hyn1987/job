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
          <input type="text" class="form-control" name="client_name" value="{{ old('client_name') }}" placeholder="Client Name">
        </div>

        <div class="input-group">
          <select class="form-control" name="is_public">
            <option value=""@if (old('is_public') === '') selected @endif>Public &amp; Private</option>
          @foreach ($options['is_public'] as $label => $v)
            <option value="{{ $v }}"{{ old('is_public') != null && $v == old('is_public') ? ' selected' : '' }}>{{ $label }}</option>
          @endforeach
          </select>
        </div>

        <div class="input-group">
          <select class="form-control" name="is_open">
            <option value=""@if (old('is_open') === '') selected @endif>Open &amp; Closed</option>
          @foreach ($options['is_open'] as $label => $v)
            <option value="{{ $v }}"{{ old('is_open') != null && $v == old('is_open') ? ' selected' : '' }}>{{ $label }}</option>
          @endforeach
          </select>
        </div>

        <button class="btn blue btn-search" type="submit">Search</button>
      </form>
    </div>
  </div>
</div>  