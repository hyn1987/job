<div class="top-about-menu">
  <ul>
  @if($about_menu)
  @foreach ($about_menu as $root_key => $root)
    <li class="top-about-item {{ $root['pos'] }}{{ $root['active'] ? ' active' : '' }}">
      <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
        @if ($root['icon'])
        <i class="{{ $root['icon'] }}"></i>
        @endif
        {{ trans('menu.about_menu.' . $root_key . '.title') }} 
      </a>
    </li>
  @endforeach
  @endif
  </ul>
</div>