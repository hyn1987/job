<div class="col-sm-12">
  @if ($groupMessageList->isEmpty())
    <div class="alert alert-danger" role="alert">
      No Message Found
    </div>
  @else
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"> 
    @foreach ($groupMessageList as $key=>$groupMessage)
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="heading-{{$key}}">
        <h5 class="panel-title">
          <a class="message-date" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{$key}}" aria-expanded="false" aria-controls="collapse-{{$key}}">
           {{ date('l, M d, Y', strtotime($key)) }}
          </a>
        </h5>
      </div>
      <div id="collapse-{{$key}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$key}}">
        <div class="panel-body">
          @foreach ($groupMessage as $message)
            @include('pages.message.messageList')  
          @endforeach            
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>
  