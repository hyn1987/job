<?php
/**
   * Show all users.
   *
   * @author Ray
   * @since March 2, 2016
   * @version 1.0 show simple list
   * @return Response
   */
?>
@extends('layouts/admin/index')

@section('content')
<!-- User Search Form Part -->

<div class="row page-body">
  <div class="col-md-12">
    <div class="navbar navbar-default" role="navigation">
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        @include('pages.admin.user.list_search')
      </div>
    </div>
  </div>

  <div class="col-sm-12" id="notify_wrapper">
    @include('layouts.admin.common_infos')
    @include('layouts.admin.common_errors')
  </div>

  <script type="text/javascript">

    var nt2show = "";
    var error2show = "";
    @if ($users->total() > 0)
      // nt2show = "{{ $users->total() . ' ' . trans('message.admin.user.found') }}";
    @else
      // error2show = "{{ trans('message.admin.user.notfound') }}";
    @endif

    var ajaxActionLabels = {
      'message': ['Send a message', 'Send', 'Type the message', 'The message could not be blank'],
      'status': ['Are you sure?', 'Update', 'Type the note', 'The note could not be blank'],
    };
  </script>
  <!--
  <div class="col-sm-12">
    <div class="alert alert-success" role="alert">
      <strong>{{ $users->total() . ' ' . trans('message.admin.user.found') }}</strong>
    </div>
  </div>
  -->

  <div class="col-sm-12">
    <div class="pull-right">
      {!! $users->render() !!}
    </div>
    <div style="clear:both;"></div>
  </div>


  <!-- User Listing Part -->
  <div class="col-sm-12">

    <div class="dd user-list-wrap">

      <ul class="list sortable">
        @forelse ($users as $id => $u)

          @include('pages.admin.user.list_row')

        @empty
        <li class="no-items">{{ trans('message.admin.user.notfound') }}</li>
        @endforelse
      </ul>
    </div>
  </div>

  <div class="col-sm-12">
    <div class="pull-right">
      {!! $users->render() !!}
    </div>
    <div style="clear:both;"></div>
  </div>
</div>

{{-- Ajax Action Modal --}}
@include('pages.admin.user.modal_confirm')


@endsection