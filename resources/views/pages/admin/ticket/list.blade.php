@extends('layouts/admin/index')

@section('toolbar')
<form name="frm_toolbar" id="frm_toolbar">
  <div class="date-ranger-wrp pull-right">
    <label class="control-label pull-left">Date</label>
    <div class="input-group pull-left section-right" id="date_range">
      <input type="text" class="form-control" name="date_range" value="{{ old('date_range') }}">
      <span class="input-group-btn">
      <button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
      </span>
    </div>
  </div>
</form>
@endsection

@section('content')
<div class="row page-body">

  @include('pages.admin.ticket.list_search')

  <div class="col-sm-12">
    @if (count($tickets))
    <div class="pagi clearfix">
      <div class="pull-left total">
        <strong><span class="jresult-total">{{ $tickets->total() }}</span> results found</strong>
      </div>
      <div class="pull-right">
        {!! $tickets->render() !!}
      </div>
    </div>

    <ul class="list ticket-list">
      @forelse ($tickets as $ticket)
      <li class="item item-{{ $ticket->id }} type-{{ strtolower($ticket->strType) }}" data-id="{{ $ticket->id }}" data-status="{{ $ticket->status }}"data-admin-id="{{ $ticket->admin_id }}" data-type="{{ $ticket->type }}" data-priority="{{ $ticket->priority }}">
        <div class="item-header">
          <div class="row">
            <div class="col-sm-9 col-xs-12"><span class="t-type pull-left">{{ $ticket->strType }}</span><h5 class="t-subject">{{ $ticket->subject }}</h5></div>
            <div class="col-sm-3 col-xs-12">
              <div class="toolbar pull-right">
                <a data-id="{{ $ticket->id }}" href="#modalEditTicket" data-toggle="modal" data-backdrop="static" data-keyboard="" class="btn btn-primary btn-xs btn-edit-ticket">Edit<i class="fa fa-edit"></i></a>
                <a data-id="{{ $ticket->id }}" href="#modalReplyTicket" data-toggle="modal" data-backdrop="static" data-keyboard="" class="btn btn-warning btn-xs btn-reply">Reply<i class="fa fa-reply"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="item-body">
          <div class="summary">
            <img class="avatar img-circle pull-left" src="{{ avatarUrl($ticket->user, 48) }}" width="48" height="48">
            <div class="item-content">
              <span class="tri-arrow left"></span>
              <a class="name" href="{{ route('user.profile', $ticket->user->id) }}" target="_blank">{{ $ticket->user->fullname() }}</a>
              <span class="date">{{ ago($ticket->created_at) }}</span>
              <span class="message">{!! nl2br($ticket->content) !!}</span>
              <a href="javascript:void(0)" id="collapse_toggler{{ $ticket->id }}" class="collapse-toggler"@if ($ticket->numComments == 0) style="display: none;"@endif><i></i></a>
              {!! $ticket->renderAttachmentHtml() !!}
            </div>
          </div>

          <div class="thread" id="thread_{{ $ticket->id }}" style="display: none;">
            <div class="gradient top"></div>
            <div class="gradient bottom"></div>
            <div class="t-comments-wrp">
              <ul class="t-comments">
                @foreach ($ticket->comments as $comment)
                @include('pages.admin.ticket.comment')
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </li>
      @empty
      <li class="no-items">{{ trans('common.no_items') }}</li>
      @endforelse
    </ul>

    @else
    <p>No Matched Tickets Found</p>

    @endif
  </div>
</div>

{{-- Edit Ticket --}}
@include('pages.admin.ticket.modal.edit')

{{-- Reply to the Ticket (aka Add a comment) --}}
@include('pages.admin.ticket.modal.reply')

{{-- Edit Comment --}}
@include('pages.admin.ticket.modal.edit_comment')

@endsection