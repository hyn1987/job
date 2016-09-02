<div class="row">
	<div id="comment-{{ $comment->id }}" class="clearfix comment-row">
  	<div class="col-sm-1">
  		<img class="avatar img-circle" src="{{avatarUrl($comment->commentor, 48)}}" width="48" height="48">
  	</div>
  	<div class="col-sm-11">
  		<ul class="comment-section">
    		<li> 
    			<span class="h-color font-bold"> {{ $comment->commentor->fullname() }} </span>
    			<span> - {{ ago($comment->created_at) }} </span>
          <span class="pull-right">
            {{ date('l, M d, Y', strtotime($comment->created_at)) }}
          </span>
    		</li>
    		<li> 
    			<span class="comment">{!! nl2br($comment->comment) !!}</span>
    		</li>
        <li>
          {!! $comment->renderAttachmentHtml() !!}
        </li>
  		</ul>
  	</div>
  	<div class="col-sm-1" style="display:none;">
  		<div class="pull-right">
    		<a id="editBtn" class="btn" data-toggle="modal" data-target="#editModal" data-comment-id="{{ $comment->id }}">
	    		<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
	    	</a>
    	</div>
  	</div>
	</div>
</div>