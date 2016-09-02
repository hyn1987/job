@extends('layouts/admin/index')

@section('actions')
  <li><button class="btn-add btn btn-success btn-sm" type="button">Add</button></li>
  <li><button class="btn-save btn btn-primary btn-sm" type="button" disabled>Save</button></li>
@endsection

@section('content')
  <div class="row page-body">
    <div class="col-sm-3">
      <div class="faq-sidebar-menu">
        <ul>
        @foreach ($data as $category)
          <li class="menu-item "><a href="#" cat-id="{{ $category['id'] }}">{{ parse_multilang($category['name'], "EN") }}</a></li>
        @endforeach
        </ul>
      </div>
    </div>
    <div class="col-sm-9">
      <div class="tab-content">
        <div class="dd faq-list">
          <ol class="dd-list">
            
          </ol>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    var data = {
      loadUrl: "{{ route('admin.faq.load') }}",
      saveUrl: "{{ route('admin.faq.save') }}",
    };
  </script>
@endsection