<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Admin Settings
  |--------------------------------------------------------------------------
  |
  |
  */
  'admin' => [
    'per_page' => 10,
    'avatar_size' => 32
  ],

  'fee' => [
    ['limit'=>500,    'fee_rate'=>0.8],     // 0~500
    ['limit'=>10000,  'fee_rate'=>0.9],     // 501~10000
    ['limit'=>-1,     'fee_rate'=>0.95],    // 10000+
  ], 

  /*
  |--------------------------------------------------------------------------
  | FrontEnd Settings
  |--------------------------------------------------------------------------
  |
  |
  */
  'frontend' => [
    'per_page' => 10
  ],
  /*
  |--------------------------------------------------------------------------
  | Frontend Settings
  |--------------------------------------------------------------------------
  |
  |
  */
  'freelancer' => [
    'per_page' => 5
  ],

  'hourly_log_unit' => 10
];