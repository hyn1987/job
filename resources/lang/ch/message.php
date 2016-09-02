<?php
/**
   * Show messages.
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

  'buyer' => [
    'job' => [
      'post' => [
        'success_create' => 'A new job posting ":job_title" has been created successfully.', 
      ], 
      'status' => [
        'closed_unpossible' => 'Job ":job_title" can not be closed.', 
        'non_exist' => 'Job (#:job_id) does not exist', 
      ], 
      'contract' => [
        'exist' => 'There is :contract_count open contract.', 
        'exist_plural' => 'There are :contract_count open contracts.', 
      ], 
      'invite' => [
        'success' => ':contractor_name has been invited to ":job_title".', 
      ], 
      'make_offer'=> [
        'success' => 'A job offer for ( :job_title ) has been sent to :contractor_name.', 
        'job_not_open'   => "You can not make offer for the non-open job ( :job_title ).", 
        'job_is_private' => "You can not make offer for the private job ( :job_title ).", 
        'user_not_freelancer' => ":contractor isn't a freelancer. You can send job offer only to freelancer.", 
        'existing_offer' => "You already sent a job offer to ( :contractor ).", 
      ], 
    ],

    'application' => [
      'client_decline' => 'You have declined :contractor.', 
    ], 

    'price' => [
      'lt_zero' => 'Please input price correctly. Price should be greater than zero.', 
    ], 
    'payment' => [
      'contract' => [
        "success_paid" => "You have paid $:amount, successfully.", 
      ]
    ], 
  ], 

  'admin' => [
    'user' => [
      'found' => 'user(s) found',
      'notfound' => 'No users found',
    ],
    'contract' => [
      'found' => 'contract(s) found',
      'notfound' => 'No contracts found',
    ],
  ],
];