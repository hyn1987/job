@if(!$notification->read_at)
<div class="notification-item unread nid{{ $notification->id }}" notification-id="{{ $notification->id }}">
@else
<div class="notification-item nid{{ $notification->id }}" notification-id="{{ $notification->id }}">
@endif
	<div class="row">
		<div class="col-sm-10">
		{!! nl2br($notification->notification) !!}
		</div>
		<div class="col-sm-2">
		{{ $notification->notified_at }}
		<i class="fa fa-close notification-close"></i>
		</div>
	</div>
</div>