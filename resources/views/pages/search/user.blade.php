<?php use Illuminate\Support\Str;
/**
   * Show all freelancers.
   *
   * @author Jin
   * @since Jan 22, 2016
   * @version 1.0 Show all freelancers.
   * @return Response
   */
?>
@extends('layouts/search/index')

@section('content')

  <div class="title-section">
    <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  </div>
  
  <div class="page-content-section freelancer-user-page">
    <form id="search_form" method="post" action="" role="form">
      <div class="form-group">
        <div class="input-group">
          <div class="input-cont">
            <input class="form-control" type="text" placeholder="{{ trans('common.search') }}..." id="search_title" value="{{ $searchTitle }}">
          </div>
          <span class="input-group-btn">
            <a id="search_btn" class="btn btn-primary">{{ trans('common.search') }}</a>
          </span>
        </div>
      </div>
    </form>

    <div class="row margin-bottom-10px" style="display: none;">   
      <div class="col-md-3">
        <select class="form-control" name="category">
            <option value="">CT1</option>
            <option value="">CT2</option>
            <option value="">CT3</option>
        </select> 
      </div>
      <div class="col-md-3">
        <select class="form-control" name="stars">
            <option value="">ST1</option>
            <option value="">ST2</option>
            <option value="">ST3</option>
        </select> 
      </div>
      <div class="col-md-2">
        <select class="form-control" name="experience">
            <option value="">EXP1</option>
            <option value="">EXP2</option>
            <option value="">EXP3</option>
        </select> 
      </div>
      <div class="col-md-2">
        <select class="form-control" name="options">
            <option value="">OP1</option>
            <option value="">OP2</option>
            <option value="">OP3</option>
        </select> 
      </div> 
      <div class="col-md-2">
        <div class="valign-middle">
            <a class="">Clear All Filters</a>
        </div>
      </div> 
    </div>

    <div class="text-right" id="pagination_wrapper">
      {!! $users->render() !!}
    </div>

    <div id="result">
    @if ( !$users->isEmpty() ) 
      @include ('pages.search.userResult')  
    @else
      <div class="col-sm-12">
          @include ('pages.search.noUserFound') 
      </div> 
    @endif
    </div>

    <div id="msg-wrap">
    </div>   

    <div class="result-footer" style="display:none;">
      <p>This Fifth Edition is completely revised and expanded to cover JavaScript as it is used in today's Web 2.0 applications. This book is both an example-driven programmer's guide and a keep-on-your-desk reference, with new chapters that explain everything you need to know to get the most out of JavaScript, including:</p>
    </div>      
</div>

@endsection