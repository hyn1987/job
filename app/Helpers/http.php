<?php
/**
* HTTP services
*
* Mar 11, 2016 - sunlignt
*/

if ( !function_exists('send_jwt') ) {
  function send_jwt($url, $jwt, $method = 'GET', $opts = [])
  {
    $defaults = array(
      CURLOPT_HEADER => false,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 4,
      CURLOPT_URL => $url,
      CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS
      CURLOPT_HTTPHEADER => [
        "X-CSRF-TOKEN:" . csrf_token(),
        "JWT:" . $jwt
      ],
    );

    $ch = curl_init();
    curl_setopt_array($ch, ($opts + $defaults));
    if (!$result = curl_exec($ch)) {
      return [
        'error' => 'Failed to get data, please try again.',
      ];
    }
    curl_close($ch);

    return json_decode($result, true);
  }
}