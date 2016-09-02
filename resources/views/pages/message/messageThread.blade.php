@if ($messageThread->id == $threadId)
<a href="#" class="list-group-item active2" data-thread="{{$messageThread->id}}"> 
@else
<a href="#" class="list-group-item" data-thread="{{$messageThread->id}}"> 
@endif
  <div class="row">
    <div class="col-sm-2">
      <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
    </div>
    <div class="col-sm-10">
      <div class="client-name">
         <span>@if ($current_user->isFreelancer())
         {{ $messageThread->sender->fullname() }}
         @else
         {{ $messageThread->receiver->fullname() }}
         @endif
         </span>
         <span class="date">@if(!$messageThread->messages->isEmpty())
         {{ date('m/d/y', strtotime($messageThread->messages->last()->created_at)) }}
         @endif
         </span>
      </div>
      <div class="subject">{{ str_limit($messageThread->subject, 30)}}</div>
      <div class="message-desc">@if(!$messageThread->messages->isEmpty())
        {{ str_limit($messageThread->messages->last()->message, 30)}}
        @endif
      </div>
    </div>
  </div>
</a>