<div class="top-sub-menu">
  <ul class="sub-menu">
  @if ($main_sub_menu)
  @foreach ($main_sub_menu['sub_menu'] as $key => $menu)
    <li class="menu-item {{ $menu['pos'] }}{{ $menu['active'] ? ' active' : '' }}">
      <a href="{{ $menu['route'] ? route($menu['route']) : 'javascript:;' }}">
        {{ trans('menu.freelancer_main_menu.' . $main_sub_menu['root_key'] . '.' . $key . '.title') }}
      </a>
    </li>
  @endforeach
  @endif
  </ul>
</div>