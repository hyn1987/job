@if ($message->sender_id == $userId || date('Y-m-d h:m:s', strtotime($message->received_at)) > '1970-01-01 12:01:00')
<div class="row message-row ">
@elseif ( date('y-m-d h:m:s', strtotime($message->received_at)) == '70-01-01 12:01:00' )
<div class="row message-row unread" data-message-id="{{ $message->id }}">
@endif
  <div class="message">
    <div class="col-sm-1">
      <img alt="" class="img-circle hide1" src="{{ avatarUrl($message->sender) }}" width="48" height="48">
    </div>
    <div class="col-sm-11">
      <div class="row">
        <div class="col-sm-9 sender">{{ $message->sender->fullname() }}</div>
        <div class="col-sm-3 time">
          <span class="pull-right">{{ ago($message->created_at) }}{{-- date('h:m:s', strtotime($message->created_at)) --}} </span>
        </div>
      </div>
      <div class="message">
        {!! nl2br($message->message) !!}
      </div>
    </div>
  </div>
</div>