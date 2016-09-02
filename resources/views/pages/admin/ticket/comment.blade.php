<li class="t-comment t-comment-{{ $comment->id }} {{ $comment->commentor->isAdmin() ? 'by-admin' : '' }} clearfix">
  <img class="avatar img-circle" src="{{ avatarUrl($comment->commentor, 48) }}" width="48" height="48">
  <div class="comment-content">
    <span class="tri-arrow {{ $comment->commentor->isAdmin() ? 'right' : 'left' }}"></span>
    <div>
      <a class="name" href="#">{{ $comment->commentor->fullname() }}</a>
      <span class="date">{{ ago($comment->created_at) }}</span>
    </div>
    <div class="message">{!! nl2br($comment->comment) !!}</div>
    {!! $comment->renderAttachmentHtml() !!}
  </div>

  <div class="toolbar">
    <span href="#modalEditComment" data-id="{{ $comment->id }}" data-toggle="modal" data-backdrop="static" class="pointer edit" title="Edit"><i class="fa fa-edit"></i></span>
    <span data-id="{{ $comment->id }}" class="pointer close" title="Remove"></span>
  </div>
</li>
