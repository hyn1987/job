<?php
/**
 * Retrieve Message list 
 *
 * @author  - so gwang
 */

use Wawjob\User;
?>


@extends('layouts/message/index')

@section('content')

<div class="title-section">
  <div class="row">
    <div class="col-sm-12">                                                                   
      <span class="title">{{ trans('page.' . $page . '.title') }}</span>
      @if($messageThreadList->isEmpty())
      <div class="alert alert-warning" role="alert">
        <strong>Attention!</strong> No Message Found.
      </div>
      @endif
    </div>
  </div>  
</div>

@if(!$messageThreadList->isEmpty())
<div class="page-content-section message-page">
  <div class="row">
    {{-- Search Section begin --}}
    <div class="col-sm-3">
      <div class="row" style="display:none">
        <div class="col-sm-12 margin-bottom-15" >        
          <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </div>        
      </div>

      <div class="row">
        <div class="col-sm-12">
          <form id="search_form" method="post" role="form">
            <div class="form-group">
              <input id="search_title" name="search_title"  class="form-control" type="text" placeholder="{{ trans('common.search') }}"/>              
            </div>
          </form>
        </div>
      </div>

      <div class="row margin-bottom-15">
        <div class="col-sm-12">
          <select class="form-control" name="sortSel" id="sortSel">
            <option value="new">{{ trans('msg.threads.newest') }}</option>
            <option value="old">{{ trans('msg.threads.oldest') }}</option>
          </select> 
        </div>
      </div>

      <div class="list-group">
          @foreach ($messageThreadList as $messageThread)
            @include('pages.message.messageThread')  
          @endforeach  
      </div>
    </div>   
    {{-- Search Section end --}}

    {{-- Result Section begin--}}
    <div class="col-sm-9">
      <div id="messageSummary" class="row margin-bottom-15">
      @include('pages.message.messageSummary')
      </div>
      <div id="groupMessageList" class="row">
      @include('pages.message.groupMessageList')
      </div>

      <div id="sendMessageForm" class="row">
        <div class="col-sm-12">
          <form id="send_form" method="post" role="form" data-thread="{{ $threadId }}">
            <div class="margin-bottom-15">
              <textarea id="message_content" class="form-control" rows="10" placeholder="{{ trans('msg.send_form.place_holder.type_messages') }}"></textarea>
            </div>
            <div class="pull-right">
              <span>
                <a id="send_btn" class="btn btn-primary">{{ trans('msg.send_form.button.send') }}</a>
              </span>
            </div>
          </form>
        </div>
      </div>
      
    </div>    
    {{-- Result Section end--}}

  </div>  
</div>
@endif

@endsection