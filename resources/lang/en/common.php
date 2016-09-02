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
    'en' => 'English', 
    'ch' => 'Chinese', 
    'kp' => 'Korean', 
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
      '0' => 'Male',
      '1' => 'Female',
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
      '1' => 'Resum',
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
    'google' => 'Google'
  ],

  // Words & phrases
  'posted' => 'Posted', 
  'ago' => ' ago',
  'balance' => 'Balance',
  'budget' => 'Budget',  
  'cancel' => 'Cancel',
  'save' => 'Save',
  'cover_letter' => 'Cover Letter',
  'faq' => 'FAQ',
  'job' => 'Job',
  'now' => 'Now',
  'ok'     => 'Ok', 
  'present' => 'Present',
  'reports' => 'Reports',
  'search' => 'Search',
  'skills' => 'Skills',
  'submit' => 'Submit',
  'decline'=> 'Decline',
  'ticket' => 'Ticket',
  'timesheet' => 'Timesheet',
  'timezone' => 'Timezone',
  'today' => 'Today',
  'work_diary' => 'Work Diary',
  'invited' => 'Invited', 
  'offer' => 'Offer', 
  'hired' => 'Hired',
  'limit' => 'Limit', 

  // due to conflicts with 'common.user', which is an Array above
  'word' => [
    'contract' => 'Contract',
    'user' => 'User',
  ],

  // Hourly or Fixed
  'hourly' => 'Hourly',
  'fixed'  => 'Fixed', 
  'fixed_price' => 'Fixed-price',
  'hourly_job' => 'Hourly Job',
  'fixed_price_job' => 'Fixed-price Job',
  'bonus' => 'Bonus',
  'charge' => 'Charge',
  'withdrawal' => 'Withdrawal',
  'refund' => 'Refund',

  // Duration
  'mt6m' => 'More than 6 months',
  '3t6m' => '3 to 6 months',
  '1t3m' => '1 to 3 months',
  'lt1m' => 'Less than a month',
  'lt1w' => 'Less than a week',

  // Workload
  'full_time' => 'Full Time - 30+ hrs/week',
  'part_time' => 'Part Time - 10 ~ 30 hrs/week',
  'as_needed' => 'As needed - Less than 10 hrs/week',

  // Availability
  'av_more_than_30' => 'More than 30 hrs / week',
  'av_10_to_30' => '10 ~ 30 hrs / week',
  'av_not_available' => 'Not Available',

  'no_items' => 'No items found.',

  'hr' => 'hr',
  'hour' => 'hour',
  'n_hrs' => ':n hrs',
  'n_hours' => ':n hours',
  'n_max_hours_per_week' => ':n maximum hours/week',

  'unit_month' => 'month', 
  'unit_day'   => 'day', 
  'unit_hour'  => 'hour', 
  'unit_minute'=> 'minute', 
  'unit_plural'=> 's', 
  'unit_timespace'=> ' ',

  'at_time' => 'at :time', 
];