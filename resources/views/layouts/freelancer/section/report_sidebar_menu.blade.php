<?php

/**
   * Freelancer Report Sidebar Menu
   *
   * @author Ri CholMin
   * @since Mar 25, 2016
   * @version 1.0
   */
?>

<div class="report-sidebar-menu freelancer-sidebar-menu">
<ul>
  @foreach ($report_sidebar_menu as $key=>$root)
  <li class="menu-item{{ $root['active'] ? ' active' : ''}}">  
    <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
      {{ trans('menu.freelancer_report_sidebar_menu.' . $key . '.title') }}
    </a>
  </li>
  @endforeach
</ul>
</div>