<form class="navbar-form navbar-right" role="search" name="contract_list" method="get" action="{{ route('admin.contract.list') }}">

  <div class="input-group">
    <span class="input-group-addon input-circle-left"><i class="fa fa-book"></i></span>
    <input type="text" class="form-control" name="id" value="{{ old('id') }}" placeholder="No" size="5">
  </div>

  <div class="input-group">
    <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Title"  size="12">
  </div>

  <div class="input-group">
    <select class="form-control" name="type">
      <option value="">All Type</option>
      <option value="0" {{ old('type') != null && old('type') == Project::TYPE_HOURLY ? ' selected' : '' }}>Hourly</option>
      <option value="1" {{ old('type') != null && old('type') == Project::TYPE_FIXED ? ' selected' : '' }}>Fixed</option>
    </select>
  </div>

  <div class="input-group">
    <select class="form-control" name="status">
      <option value="">All Status</option>
      @for ($i = 0; $i < 7; $i++)
      <option value="{{ $i }}">{{ trans('common.contract.status.' . $i) }}</option>
      @endfor
    </select>
  </div>

  <div class="input-group">
    <span class="input-group-addon input-circle-left"><i class="fa fa-user"></i></span>
    <input type="text" class="form-control" name="buyer" value="{{ old('buyer') }}" placeholder="Buyer"   size="12">
  </div>

  <div class="input-group">
    <span class="input-group-addon input-circle-left"><i class="fa fa-users"></i></span>
    <input type="text" class="form-control" name="lancer" value="{{ old('lancer') }}" placeholder="Contractor" size="12">
  </div>
  

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <button class="btn blue btn-search" type="submit">Search</button>

</form>