<div class="faq-sidebar-menu">
	<ul>
	@foreach ($categories as $category)
		@if ($category['cnt'] > 0)
	  <li class="menu-item @if ($catid == $category['id']) {{'active'}} @endif"><a href="#" cat-id="{{ $category['id'] }}">{{ parse_multilang($category['name'], App::getLocale()) }}</a></li>
	  @endif
	@endforeach
	</ul>
</div>