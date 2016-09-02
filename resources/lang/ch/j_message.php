<?php
/**
   * Show messages.
   *
   * @author nada
   * @since June 14, 2016
   * @version 1.0 show common data
   * @return Response
   */
return [

  /*
  |--------------------------------------------------------------------------
  | Javascript - Message
  |--------------------------------------------------------------------------
  |
  | The following language lines are used in common.
  |
  */
  'btn_ok' => 'OK', 
  'btn_cancel' => 'Cancel', 
  'btn_yes' => 'Yes', 
  'btn_no' => 'No', 
  'btn_delete' => 'Delete', 

  'buyer' => [  
    'job' => [
      'status' => [
        'close_job' => 'Are you sure to close this job?',
        'change_public' => 'Are you sure to make this job :status?', 
        'app_declined' => 'Are you sure to decline this application?', 
      ]
    ], 
    'payment' => [
      'charge' => 'Are you sure to charge $:amount?'
    ]
  ], 

  'freelancer' => [
    'job' => [
      'reject_offer' => 'Are you sure to reject this offer?', 
      'accept_offer' => 'Are you sure to accept this offer?', 
    ], 
    'payment' => [
      'withdraw' => 'Are you sure to withdraw $:amount?', 
    ], 
    'workdiary' => [
      'delete_screenshot' => 'Are you sure to delete all seleted screenshots?', 
      'select_screenshot' => 'You have to select some screenshots.', 
    ], 
  ],

  'admin' => [
    'category' => [
      'remove_category' => 'Are you sure to remove this category?', 
    ], 
    'faq' => [
      'remove_faq' => 'Are you sure to remove this FAQ item?', 
    ],
    'affiliate' => [
      'update' => 'Are you sure to update the Affiliate values?', 
      'saved'  => 'The Affiliate values have been saved successfully.',
    ], 
    'notification' => [
      'remove_notification' => 'Are you sure to remove this notification item?', 
      'title_send_notification' => 'Send Notification', 
      'title_add_cronjob' => 'Add CronJob',
      'send_notification' => 'Are you sure to send the notification', 
      'add_cronjob' => 'Are you sure to add the notification in CronJob?', 
    ], 
    'skill' => [
      'remove_skill' => 'Are you sure to remove this skill item?', 
      'deactivate_skill' => 'Are you sure to deactivate this skill item?', 
    ], 
    'ticket' => [
      'remove_comment' => 'Are you sure that you want to remove this comment?', 
    ], 
  ]
];