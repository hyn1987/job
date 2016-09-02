@extends('layouts/admin/index')

@section('actions')
  <li>
    <button class="btn-search btn btn-info btn-sm" type="button" data-toggle="collapse" data-target=".search-box">Search <i class="fa fa-search"></i></button>
    <div class="search-box box collapse">
      <div class="form">
        <div class="form-group">
          <input class="search-name search-item" type="text" placeholder="Name" max-length="64">
        </div>
        <button class="search-active search-item btn-ticker active btn btn-primary btn-xs" type="button"><i class="fa fa-check-square-o"></i>Active</button>
        <button class="search-inactive search-item btn-ticker active btn btn-info btn-xs" type="button"><i class="fa fa-check-square-o"></i>Inactive</button>
      </div>
    </div>
  </li>
  <li><button class="btn-add btn btn-success btn-sm" type="button">Add</button></li>
  <li><button class="btn-save btn btn-primary btn-sm" type="button" disabled>Save</button></li>
@endsection

@section('content')
  <div class="row page-body">
    <div class="col-sm-12">
      <ul class="skills" data-deactivatable-url="{{ route('admin.skill.deactivatable') }}" data-save-url="{{ route('admin.skill.save') }}" data-skills="{{ $skills }}">
        <li class="no-items hidden">No skills found.</li>
      </ul>
    </div>
  </div>
@endsection