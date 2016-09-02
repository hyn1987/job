<?php

/**
   * Buyer Sidebar Menu
   *
   * @author nada
   * @since Dec 24, 2015
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
?>

<div class="user-sidebar-menu buyer-sidebar-menu">
<div class="title">{{ trans('user.user_settings') }}</div>
<ul>
  @foreach ($user_settings_menu as $key=>$root)
  <li class="menu-item{{ $root['active'] ? ' active' : ''}}">  
    <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
      {{ trans('menu.buyer_user_settings_menu.' . $key . '.title') }} 
    </a>
  </li>
  @endforeach
</ul>
</div>