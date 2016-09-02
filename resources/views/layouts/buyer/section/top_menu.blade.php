<?php

/**
* @desc     - Buyer Top Menu
* @author   - nada
* @updated  - 2015/12/19
*/
?>
<div class="top-menu buyer-top-menu">
<ul>
  @if ($main_menu)
  @foreach ($main_menu as $key => $root)
    @if ($key == 'messages' && $unread_msg_count > 0) 
      <li class="menu-item {{ $root['pos'] }}{{ $root['active'] ? ' active' : '' }} message">
        <span class="badge badge-default msg-notification">{{ $unread_msg_count }}</span>
        <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
          {{ trans('menu.buyer_main_menu.' . $key . '.title') }}
        </a>
      </li>
    @else
      <li class="menu-item {{ $root['pos'] }}{{ $root['active'] ? ' active' : '' }}">
        <a href="{{ $root['route'] ? route($root['route']) : 'javascript:;' }}">
          {{ trans('menu.buyer_main_menu.' . $key . '.title') }}
        </a>
      </li>
    @endif
  @endforeach
  @endif
</ul>
</div>