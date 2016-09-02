<?php use Wawjob\Project;
/**
   * Show all contracts.
   *
   * @author Ray
   * @since March 2, 2016
   * @version 1.0 show simple list
   * @return Response
   */
?>
@extends('layouts/admin/index')

@section('content')
<!-- contract Search Form Part -->

<div class="row page-body">
  <div class="col-md-12">
    <div class="navbar navbar-default" role="navigation">
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        @include('pages.admin.contract.list_search')
      </div>
    </div>
  </div>

  <div class="col-sm-12">
    @include('layouts.admin.common_errors')
  </div>

  <script type="text/javascript">
    var nt2show = "";
    var error2show = "";
    @if ($contracts->total() > 0)
      // nt2show = "{{ $contracts->total() . ' ' . trans('message.admin.contract.found') }}";
    @else
      // error2show = "{{ trans('message.admin.contract.notfound') }}";
    @endif

    var contractStateLabels = {
      @for ($i = 1; $i < 5; $i++)
      '{{ $i }}': '{{ trans('common.contract.status-do.' . $i) }}',
      @endfor
    };
  </script>
  <!--
  <div class="col-sm-12">
    <div class="alert alert-success" role="alert">
      <strong>{{ $contracts->total() . ' ' . trans('message.admin.contract.found') }}</strong>
    </div>
  </div>
  -->

  <div class="col-sm-12">
    <div class="pull-right">
      {!! $contracts->render() !!}
    </div>
    <div style="clear:both;"></div>
  </div>



  <!-- contract Listing Part -->
  <div class="col-sm-12">

    <div class="dd contract-list-wrap">

      <ul class="list sortable">
        @forelse ($contracts as $ind => $u)
          @include('pages.admin.contract.list_row')
        @empty
          <li class="no-items">{{ trans('message.admin.contract.notfound') }}</li>
        @endforelse
      </ul>

    </div>
  </div>

  <div class="col-sm-12">
    <div class="pull-right">
      {!! $contracts->render() !!}
    </div>
    <div style="clear:both;"></div>
  </div>
</div>


{{-- Status Update --}}
@include('pages.admin.contract.modal_confirm')


@endsection



