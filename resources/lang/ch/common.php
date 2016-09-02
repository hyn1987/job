<?php
/**
   * Show common data.
   *
   * @author Jin
   * @since Jan 14, 2016
   * @version 1.0 show common data
   * @return Response
   */
return [

  /*
  |--------------------------------------------------------------------------
  | Page Language Lines
  |--------------------------------------------------------------------------
  |
  | The following language lines are used in common.
  |
  */

  'language' => [
    'en' => '英语', 
    'ch' => '中文', 
    'kp' => '朝鲜语', 
  ], 

  'user' => [
    'status' => [
      '0' => 'Inactive',
      '1' => 'Active',
      '2' => 'Block',
      '3' => 'Financial Suspend',
      '4' => 'Account Suspend',
      '9' => 'Account Closed',
    ],
    'status-do' => [
      '0' => 'Inactive',
      '1' => 'Active',
      '2' => 'Block',
      '3' => 'Financial Suspend',
      '4' => 'Suspend',
      '9' => 'Close',
    ],
    'status-do-icon' => [
      '0' => '',
      '1' => 'fa fa-link',
      '2' => 'fa fa-lock',
      '3' => 'fa fa-unlink',
      '4' => 'fa fa-bolt',
      '9' => 'fa fa-times',
    ],
    'types' => [
      'user_guest' => '',
      'user_freelancer' => 'Freelancer',
      'user_buyer' => 'Buyer',
      'user_admin' => 'Admin',
      'user_sadmin' => 'Super Admin',
    ],
    'gender' => [
      '0' => '男',
      '1' => '女',
    ],
  ],

  'contract' => [
    'status' => [
      '0' => 'Offer',
      '1' => 'Started',
      '2' => 'Paused',
      '3' => 'Suspend',
      '4' => 'Finished(wait for feedback)',
      '5' => 'Completed',
      '6' => 'Offer Declined',
    ],
    'status-icon' => [
      '0' => 'fa fa-chain',
      '1' => 'fa fa-play-circle-o',
      '2' => 'fa fa-warning',
      '3' => 'fa fa-bolt',
      '4' => 'fa fa-check',
      '5' => 'fa fa-check-square',
      '6' => 'fa fa-chain-broken',
    ],
    'status-do' => [
      '4' => 'Finish',
      '1' => 'Resume',
      '2' => 'Pause',
      '3' => 'Suspend',
    ],
    'status-do-icon' => [
      '4' => 'fa fa-check',
      '1' => 'fa fa-play-circle-o',
      '2' => 'fa fa-warning',
      '3' => 'fa fa-bolt',
    ],
    'is_hourly' => [
      '0' => 'Hourly',
      '1' => 'Fixed',
      '2' => 'Weekly',
      '3' => 'Annual',
    ],
    'is_hourly-icon' => [
      '0' => 'fa fa-clock-o',
      '1' => 'fa fa-thumb-tack',
      '2' => 'fa fa-calendar',
      '3' => 'fa fa-calendar',
    ],
  ],

  'social' => [
    'facebook' => 'Facebook',
    'linkedin' => 'LinkedIn',
    'google' => '谷歌'
  ],


  // Words & phrases
  'posted' => '创造时间:', 
  'ago' => '前',
  'balance' => '下存',
  'budget' => '预算',
  'cancel' => '取消',
  'save' => 'Save',
  'cover_letter' => '申请书',
  'faq' => '常见问题',
  'job' => '项目',
  'now' => '现在', 
  'ok'     => '确认', 
  'present' => '现在',
  'reports' => '报告',
  'search' => '搜索',
  'skills' => '技术',
  'submit' => '提出',
  'decline'=> '拒绝',
  'ticket' => '票',
  'timezone' => '时间带',
  'timesheet' => '时间表',
  'today' => '今天',
  'work_diary' => '工作日记',
  'hired' => '雇佣', 
  'invited' => '邀请', 
  'offer' => '提供',  
  'limit' => '限制', 

  // due to conflicts with 'common.user', which is an Array above
  'word' => [
    'contract' => '契约',
    'user' => '用户',
  ],

  // Transaction type
  'hourly' => '计时',
  'fixed'  => '固定价格', 
  'fixed_price' => '固定价格',
  'hourly_job' => '计时项目',
  'fixed_price_job' => '固定价格项目',
  'bonus' => '奖金',
  'charge' => '存款',
  'withdrawal' => '取款',
  'refund' => '偿还',

  // Duration
  'mt6m' => '半年以上',
  '3t6m' => '三到六个月',
  '1t3m' => '一到三个月',
  'lt1m' => '一个月以下',
  'lt1w' => '一周以下',

  // Workload
  'full_time' => '专任 - 30+小时/周',
  'part_time' => '小时制 - 10 ~ 30小时/周',
  'as_needed' => '按照要求 - 10小时以下/周',

  // Availability
  'av_more_than_30' => '30小时以上/周',
  'av_10_to_30' => '10到30小时/周',
  'av_not_available' => '不可用',

  'no_items' => '无项目。',	

  'hr' => '小时',
  'hour' => '小时',
  'n_hrs' => ':n小时',
  'n_hours' => ':n小时',
  'n_max_hours_per_week' => '最大:n小时/周',

  'unit_month' => '个月', 
  'unit_day'   => '天', 
  'unit_hour'  => '小时', 
  'unit_minute'=> '分', 
  'unit_plural'=> '',
  'unit_timespace'=> '',

  'at_time' => '于:time', 
];