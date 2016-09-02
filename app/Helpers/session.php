<?php
/* Mar 6, 2016 - Sunlight */
if ( !function_exists('add_message') )
{
  function add_message($msg, $type)
  {
    $msgs = Session::get('msgs');

    $msgs[] = [
      'msg' => $msg,
      'type' => $type
    ];

    Session::set('msgs', $msgs);
  }
}

if ( !function_exists('show_messages') )
{
  function show_messages()
  {
    $html = '<div class="messages">';
    $groups = [
      'success' => [],
      'info' => [],
      'warning' => [],
      'danger' => [],
    ];

    $msgs = Session::get('msgs');
    Session::remove('msgs');

    if (empty($msgs)) {
      return '';
    }

    foreach ($msgs as $msg) {
      if (!isset($groups[$msg['type']])) {
        $groups[$msg['type']] = [];
      }
      $groups[$msg['type']][] = $msg['msg'];
    }

    foreach ($groups as $type => $group) {
      if ($group) {
        $html .= '<div class="alert alert-' . $type . '"><ul>';
        foreach ($group as $msg) {
          $html .= '<li>' . $msg . '</li>';
        }
        $html .= '</ul></div>';
      }
    }
    $html .= '</div>';

    echo $html;
  }
}