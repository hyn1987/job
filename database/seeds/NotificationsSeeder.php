<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class NotificationsSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // Add Notifications for Constant.
    $notifications = [
      [
        'id' => 1,
        'slug' => 'ACCOUNT_SUSPENDED',
        'content' => '{\"EN\":\"Your account has been suspended.\"}',
        'is_const' => 1,
        'type' => 1,
      ],
      [
        'id' => 2,
        'slug' => 'ACCOUNT_REACTIVATED',
        'content' => '{\"EN\":\"Your account has been re-activated.\",\"KP\":\"\\ub2f9\\uc2e0\\uc758 \\uad6c\\uc88c\\ub294 \\ub2e4\\uc2dc \\ud65c\\uc131\\ud654\\ub418\\uc600\\uc2b5\\ub2c8\\ub2e4.\",\"CH\":\"\\u60a8\\u53ef\\u4ee5\\u518d\\u4f7f\\u7528\\u60a8\\u7684\\u8d26\\u6237\\u3002\"}',
        'is_const' => 1,
        'type' => 1,
      ],
      [
        'id' => 3,
        'slug' => 'FINANCIAL_ACCOUNT_SUSPENDED',
        'content' => '{\"EN\":\"Your financial account has been suspended.\"}',
        'is_const' => 1,
        'type' => 1,
      ],
      [
        'id' => 4,
        'slug' => 'FINANCIAL_ACCOUNT_REACTIVATED',
        'content' => '{\"EN\":\"Your financial account has been re-activated.\"}',
        'is_const' => 1,
        'type' => 1,
      ],
      [
        'id' => 5,
        'slug' => 'RECEIVED_JOB_OFFER',
        'content' => '{\"EN\":\"You have received a job offer from @#buyer_fullname# for the job @#job_title#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 6,
        'slug' => 'RECEIVED_INVITATION',
        'content' => '{\"EN\":\"You have received an invitation from @#buyer_fullname#  for the job @#job_title#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 7,
        'slug' => 'BUYER_JOB_CANCELED',
        'content' => '{\"EN\":\"Your job @#job_title# has been canceled.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 8,
        'slug' => 'APPLICATION_DECLINED',
        'content' => '{\"EN\":\"Your application have been declined.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 9,
        'slug' => 'PAY_BONUS',
        'content' => '{\"EN\":\"@#buyer_name# sent you bonus $@#amount#.\",\"CH\":\"@#buyer_name#\\u7ed9\\u4f60\\u652f\\u4ed8\\u4e86\\u5956\\u91d1$@#amount#\\u3002\",\"KP\":\"\\uace0\\uac1d \\\"@#buyer_name#\\\"\\uc5d0\\uc11c \\ub2f9\\uc2e0\\uc5d0\\uac8c $@#amount#\\ub97c \\uc0c1\\uae08\\uc73c\\ub85c \\uc9c0\\ubd88\\ud558\\uc600\\uc2b5\\ub2c8\\ub2e4.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 10,
        'slug' => 'PAY_FIXED',
        'content' => '{\"EN\":\"@#buyer_name# sent you $@#amount#.\",\"CH\":\"@#buyer_name#\\u7ed9\\u4f60\\u652f\\u4ed8\\u4e86$@#amount#\\u3002\",\"KP\":\"\\uace0\\uac1d \\\"@#buyer_name#\\\"\\uc5d0\\uc11c \\ub2f9\\uc2e0\\uc5d0\\uac8c $@#amount#\\ub97c \\uc9c0\\ubd88\\ud558\\uc600\\uc2b5\\ub2c8\\ub2e4.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 11,
        'slug' => 'REFUND',
        'content' => '{\"EN\":\"You have refunded $@#amount# to @#buyer_name#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 12,
        'slug' => 'TIMELOG_REVIEW',
        'content' => '{\"EN\":\"A week has been ended. Please review weeklog for your contract @#contract_title#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 13,
        'slug' => 'USER_CHARGE',
        'content' => '{\"EN\":\"You have charged $@#amount# to your account.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 14,
        'slug' => 'USER_WITHDRAWAL',
        'content' => '{\"EN\":\"You have withdrawn $@#amount# from your account.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 15,
        'slug' => 'TICKET_OPENED',
        'content' => '{\"EN\":\"Your ticket #@#ticket_id# has been created.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 16,
        'slug' => 'TICKET_CLOSED',
        'content' => '{\"EN\":\"Your ticket #@#ticket_id# has been closed.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 17,
        'slug' => 'TICKET_SOLVED',
        'content' => '{\"EN\":\"Your ticket #@#ticket_id# has been solved and closed.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 18,
        'slug' => 'BUYER_CONTRACT_PAUSED',
        'content' => '{\"EN\":\"Your contract @#contract_title# has been paused. (for reason @todo later)\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 19,
        'slug' => 'FREELANCER_CONTRACT_PAUSED',
        'content' => '{\"EN\":\"Your contract @#contract_title# has been paused. Please contact your client.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 20,
        'slug' => 'CONTRACT_STARTED',
        'content' => '{\"EN\":\"Your contract @#contract_title# has been started.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 21,
        'slug' => 'CONTRACT_CLOSED',
        'content' => '{\"EN\":\"Your contract @#contract_title# has been closed. Please leave your feedback.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 22,
        'slug' => 'FREELANCER_ENABLED_CHANGE_FEEDBACK',
        'content' => '{\"EN\":\"Your contractor has enabled you change the feedback for the contract @#contract_title#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 23,
        'slug' => 'BUYER_CHANGED_FEEDBACK',
        'content' => '{\"EN\":\"Your client has changed the feedback for the contract  @#contract_title#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 24,
        'slug' => 'SEND_MESSAGE',
        'content' => '{\"EN\":\"@#sender_name# sent you a message.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 25,
        'slug' => 'BUYER_PAY_BONUS',
        'content' => '{\"EN\":\"You have sent @#freelancer_name# bonus $@#amount#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 26,
        'slug' => 'BUYER_PAY_FIXED',
        'content' => '{\"EN\":\"You have sent @#freelancer_name# $@#amount#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 27,
        'slug' => 'BUYER_REFUND',
        'content' => '{\"EN\":\"@#freelancer_name# refunded you $@#amount#.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
      [
        'id' => 28,
        'slug' => 'AFFILIATE_USER',
        'content' => '{\"EN\":\"Hello, @#affiliate_user#.\\nI would like to sign up <a href=\\\"\\\"@#signup#\\\"\\\">Wawjob<\\/a> and enjoy your business.\\nThanks.\"}',
        'is_const' => 1,
        'type' => 0,
      ],
    ];
    DB::table('notifications')->insert($notifications);
  }
}