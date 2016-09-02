<div class="portfolio-item col-sm-6" portfolio-id="{{$portfolio->id}}" category="{{$portfolio->cat_id}}">
	<div class="img">
		@if ($portfolio->url != "")<a href="{{$portfolio->url}}">@endif
			@if (portfolioUrl($user, $portfolio->id) != "")<img src="{{portfolioUrl($user, $portfolio->id)}}"/>@endif
		@if ($portfolio->url != "")</a>@endif
	</div>
	<div class="title"> {{$portfolio->title}}</div>
	<div class="action-buttons">
		<a href="javascript:void(0);" class="portfolio-remove btn btn-primary action-btn">Remove</a>
		<a href="javascript:void(0);" data-toggle="modal" data-target="#Portfolio" class="portfolio-edit btn btn-primary action-btn">Edit</a>
	</div>
</div>