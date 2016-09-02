<?php
  /**
   * Show notification form.
   *
   * @return Response
   */
?>
@extends('layouts/admin/index')

@section('actions')
  <li><button class="btn-add btn btn-success btn-sm" type="button">Add</button></li>
  <li><button class="btn-save btn btn-primary btn-sm" type="button" disabled>Save</button></li>
@endsection

@section('content')
  <div class="row page-body">
    <div class="col-sm-12">
      <ul class="notifications">
        <li class="no-items hidden">No notifications found.</li>
      </ul>
      <div class="language-code">
        <div class="close"></div>
      </div>
    </div>
  </div>
@endsection

@section('js')
  <script>
    var notifications = '{!! $notifications !!}'.replace(/\n/g, '<br>');
    var data = {
      saveUrl: "{{ route('admin.notification.save') }}",
      languages: JSON.parse('{!! $languages !!}'),
      notifications: JSON.parse(notifications),
    };
  </script>
@endsection