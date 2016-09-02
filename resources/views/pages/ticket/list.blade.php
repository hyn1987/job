<?php
/**
 * Retrieve Ticket list 
 *
 * @author  - so gwang
 */

use Wawjob\Ticket;
?>


@extends('layouts/ticket/index')

@section('content')

<div class="title-section">
  <div class="row">
    <div class="col-sm-6">                                                                   
      <span class="title">{{ trans('page.' . $page . '.title') }}</span>
    </div>
    <div class="col-sm-6 create-btn">      
      <a class="btn btn-primary pull-right" data-toggle="modal" data-target="#createModal" >
      	{{ trans('ticket.Create') }}
      	<span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
      </a>
    </div>
  </div>  
</div>

<div class="page-content-section ticket-page">

@if (!$ticketList->isEmpty())
  <div class="row">
  	<div class="col-sm-12">
	  	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	  		@foreach ($ticketList as $key=>$ticket)
				<div class="panel panel-default">
					<div class="panel-heading" role="tab" id="heading-{{ $ticket->id }}">
				  	<div class="subject-section">
							<div class="row">
								<div class="col-sm-9">
									<span class="subject h-color">{{ $ticket->subject }}</span>
								</div>
								<div class="col-sm-3">
									<span class="status">[{{ trans('ticket.' . Ticket::toString('status', $ticket->status)) }}]</span>
									<div class="input-group pull-right">
										<a id="replyBtn" class="btn btn-warning" data-toggle="modal" data-target="#replyModal" data-ticket="{{ $ticket->id }}">
								    {{ trans('ticket.Reply') }}
								    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								    </a>
								    <a id="closeBtn" class="btn btn-info" data-toggle="modal" data-target="#closeModal" data-ticket-id="{{ $ticket->id }}">
								    {{ trans('ticket.Close') }}
								    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								    </a>
								  </div>
						    </div>
							</div>
						</div>
						<div class="content-section">
					  	<div class="row">
					  		<div class="col-sm-10">
					  			<ul class="ticket-section">
						  			<li class="panel-title h-color">
									      {{ $ticket->content }}
									  </li>		
									  <li>
									  	{!! $ticket->renderAttachmentHtml() !!}
									  </li>							  
								  </ul>
							  </div>
							  <div class="col-sm-1">
							    {{ date('m/d/Y', strtotime($ticket->created_at)) }}
							  </div>
							  <div class="col-sm-1">
							    <a class="toggle-section" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $ticket->id }}" aria-expanded="true" aria-controls="collapse-{{ $ticket->id }}">
							      <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
							    </a>
							  </div>
					    </div>
					  </div>
				  </div>
				  <div id="collapse-{{ $ticket->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ $ticket->id }}">
				    @if ($ticket->comments->isEmpty())
				    <div class="panel-body">
					    <div class="alert alert-danger no-bottom-margin" role="alert">
					    	{{ trans('ticket.No_ticket_Comments_Found') }}
					    </div>
				    </div>
				    @else
				    <div class="panel-body">
				    	@foreach ($ticket->comments->sortByDesc('created_at') as $ckey=>$comment)
				      	@include('pages.ticket.comment')   
				      @endforeach
				    </div>
				    @endif
				  </div>
				</div>
				@endforeach
			</div>
		</div>
  </div>
@endif

</div>

@include('pages.ticket.modal.create')
@include('pages.ticket.modal.reply')
@include('pages.ticket.modal.edit')
@include('pages.ticket.modal.close')

@endsection