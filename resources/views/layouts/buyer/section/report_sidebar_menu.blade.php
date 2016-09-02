<?php

/**
   * Buyer Report Sidebar Menu
   *
   * @author nada
   * @since Mar 21, 2016
   * @version 1.0
   */
?>

<div class="report-sidebar-menu buyer-sidebar-menu">
<ul>
  @foreach ($report_sidebar_menu as $key=>$root)
  <li class="menu-item{{ $root['active'] ? ' active' : ''}}">  
    <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
      {{ trans('menu.buyer_report_sidebar_menu.' . $key . '.title') }} 
    </a>
  </li>
  @endforeach
</ul>
</div>