<?php

/**
* @desc     - Freelancer Top Menu
* @author   - nada
* @updated  - 2015/12/19
*/
?>
<div class="top-menu freelancer-top-menu">
<ul>
  @if ($main_menu)
  @foreach ($main_menu as $key => $root)
  <li class="menu-item {{ $root['pos'] }}{{ $root['active'] ? ' active' : '' }}">
    <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
      {{ trans('menu.freelancer_main_menu.' . $key . '.title') }}
    </a>
  </li>
  @endforeach
  @endif
</ul>
</div>