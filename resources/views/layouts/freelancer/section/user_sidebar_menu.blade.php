<?php

/**
   * Freelancer User Sidebar Menu
   *
   * @author nada
   * @since Dec 24, 2015
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
?>

<div class="user-sidebar-menu freelancer-sidebar-menu">
<div class="title">{{ trans('user.user_settings') }}</div>
<ul>
  @foreach ($user_settings_menu as $key=>$root)
  <li class="menu-item{{ $root['active'] ? ' active' : ''}}">  
    @if ($root['route'])
    <a href="{{ route($root['route']) }}">
      {{ trans('menu.freelancer_user_settings_menu.' . $key . '.title') }} 
    </a>
    @else
      {{ trans('menu.freelancer_user_settings_menu.' . $key . '.title') }} 
    @endif
  </li>
  @endforeach
</ul>
</div>