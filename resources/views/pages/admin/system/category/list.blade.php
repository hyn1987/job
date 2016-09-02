@extends('layouts/admin/index')

@section('actions')
  <li class="dropdown category-types">
    <button class="dropdown-toggler btn btn-info btn-sm" type="button" data-toggle="dropdown" aria-expanded="false">Type <i class="fa fa-angle-down"></i></button>
    <ul class="dropdown-menu" role="menu">
      @foreach ($data as $type => $categories)
      <li>
        <a href=".category-list-{{ $type }}" data-toggle="tab">{{ trans('page.admin.category.list.type.' . $type) }}</a>
      </li>
      @endforeach
    </ul>
  </li>
  <li><button class="btn-add btn btn-success btn-sm" type="button">Add</button></li>
  <li><button class="btn-save btn btn-primary btn-sm" type="button" disabled>Save</button></li>
@endsection

@section('content')
  <div class="row page-body">
    <div class="col-sm-12">
      <div class="tab-content">
        @foreach ($data as $type => $categories)
          @include('pages.admin.system.category.loop', [
            'type' => $type,
            'categories' => $categories,
            'active' => !$type ? true : false,
          ])
        @endforeach
    </div>
  </div>
@endsection

@section('js')
  <script>
    var url = {
      saveCategory: "{{ route('admin.category.save') }}",
    };
  </script>
@endsection