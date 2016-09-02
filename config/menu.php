<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Freelancer Main Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'freelancer_main_menu' => [
    'find_work' => [
      'route' => 'search.job',
      'children' => [
        'find_jobs' => [
          'route' => 'search.job',
        ],
        'proposals' => [
          'route' => 'job.my_proposals',
          'children' => [
            'archived' => [
              'route' => 'job.my_archived', 
              'hidden' => true, 
            ], 
          ], 
        ], 

      ],
    ],
    'my_jobs' => [
      'route' => 'job.my_jobs',
      'children' => [
        'my_jobs' => [
          'route' => 'job.my_jobs',
        ],
        'contracts' => [
          'route' => 'contract.all_contracts',
        ],
        'work_diary' => [
          'route' => 'workdiary.view_first',
        ]
      ],
    ],
    'report' => [
      'route' => 'report.timelogs',
      'children' => [
        'timelogs' => [
          'route' => 'report.timelogs',
        ],
        'timesheet' => [
          'route' => 'report.timesheet',
        ],
        'transaction_history' => [
          'route' => 'report.transactions',
        ],
        'weekly_summary' => [
          'route' => 'report.weekly_summary',
        ]
      ],
    ],
    'payment' => [
      'route' => 'payment.withdraw',
      'children' => [
      ],
    ],
    'messages' => [
      'route' => 'message.list',
      'children' => [
      ],
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Freelancer Right Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'freelancer_right_menu' => [
    'user_settings' => [
      'route' => 'user.my_info',
      'icon' => 'icon-user',
    ],
    'divider' => [
      'route' => '#'
    ],
    'logout' => [
      'route' => 'user.logout',
      'icon' => 'icon-key',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Freelancer User Settings Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'freelancer_user_settings_menu' => [
    'my_info' => [
      'route' => 'user.my_info',
    ],
    'contact_info' => [
      'route' => 'user.contact_info',
    ],
    'my_profile' => [
      'route' => 'user.my_profile',
    ],
    'account' => [
      'route' => 'user.account',
    ],
    'affiliate' => [
      'route' => 'user.affiliate',
    ],
    'finance' => [
      'route' => '',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Freelancer Report Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'freelancer_report_menu' => [
    'timelogs' => [
      'route' => 'report.timelogs',
    ],    
    'timesheet' => [
      'route' => 'report.timesheet',
    ],
    'transaction_history' => [
      'route' => 'report.transactions',
    ],
    'weekly_summary' => [
      'route' => 'report.weekly_summary',
    ],
  ],


  /*
  |--------------------------------------------------------------------------
  | Buyer Main Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'buyer_main_menu' => [
    'jobs' => [
      'route' => 'job.my_jobs',
      'children' => [
        'my_jobs' => [
          'route' => 'job.my_jobs'
        ],
        'contracts' => [
          'route' => 'contract.all_contracts',
        ],
        'job_create' => [
          'route' => 'job.create',
        ],
        
        'job_all' => [
          'route' => 'job.all',
          'hidden'=> true, 
        ],
        'job_view' => [
          'route' => 'job.view',
          'hidden'=> true, 
        ],
        'job_edit' => [
          'route' => 'job.edit',
          'hidden'=> true, 
        ],
        'job_applicants' => [
          'route' => 'job.applicants',
          'hidden'=> true, 
        ],
        'job_messaged_applicants' => [
          'route' => 'job.messaged_applicants',
          'hidden'=> true, 
        ],
        'job_offer_hired_applicants' => [
          'route' => 'job.offer_hired_applicants',
          'hidden'=> true, 
        ],
        'job_archived_applicants' => [
          'route' => 'job.archived_applicants',
          'hidden'=> true, 
        ],
        'job_application_detail' => [
          'route' => 'job.application_detail',
          'hidden'=> true, 
        ],
        'job_make_offer' => [
          'route' => 'job.make_offer',
          'hidden'=> true, 
        ],
        'contract_view' => [
          'route' => 'contract.contract_view',
          'hidden'=> true,
        ],
      ],
    ],
    'freelancers' => [
      'route' => 'search.user',
      'children' => [
        'my_freelancers' => [
          'route' => 'contract.my_freelancers',
        ],
        'find_freelancers' => [
          'route' => 'search.user',
        ],
        'work_diary' => [
          'route' => 'workdiary.view_first',
          'children' => [
            'individual_work_diary' => [
              'route' => 'workdiary.view',
              'hidden'=> true,
            ],
          ]
        ],
        'job_invite' => [
          'route' => 'job.invite',
          'hidden' => true, 
        ],
      ],
    ],
    'reports' => [
      'route' => 'report.weekly_summary',
      'children' => [
        'weekly_summary' => [
          'route' => 'report.weekly_summary',
          'hidden'=> true, 
        ],
        'budgets' => [
          'route' => 'report.budgets',
          'hidden'=> true, 
        ],
        'transactions' => [
          'route' => 'report.transactions',
          'hidden'=> true, 
        ],
        'timesheet' => [
          'route' => 'report.timesheet',
          'hidden'=> true, 
        ],
      ], 
    ],
    'messages' => [
      'route' => 'message.list',
      'children' => [
      ],
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Buyer Right Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'buyer_right_menu' => [
    'user_settings' => [
      'route' => 'user.my_info',
      'icon' => 'icon-user',
    ],
    'divider' => [
      'route' => '#'
    ],
    'logout' => [
      'route' => 'user.logout',
      'icon' => 'icon-key',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Buyer User Settings Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'buyer_user_settings_menu' => [
    'my_info' => [
      'route' => 'user.my_info',
    ],
    'contact_info' => [
      'route' => 'user.contact_info',
    ],
    'account' => [
      'route' => 'user.account',
    ],
    'affiliate' => [
      'route' => 'user.affiliate',
    ],
    'finance' => [
      'route' => '',
    ],
  ],

  /*
  |--------------------------------------------------------------------------
  | Buyer Report Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'buyer_report_menu' => [
    'weekly_summary' => [
      'route' => 'report.weekly_summary',
    ],
    'budgets' => [
      'route' => 'report.budgets',
    ],
    'transactions' => [
      'route' => 'report.transactions',
    ],
    'timesheet' => [
      'route' => 'report.timesheet',
    ],
  ],


  /*
  |--------------------------------------------------------------------------
  | Admin Sidebar
  |--------------------------------------------------------------------------
  |
  | This option provides the sidebar info.
  |
  */
  'sidebar' => [
    'dashboard' => [
      'route' => 'admin.dashboard',
      'icon' => 'icon-home',
    ],
    'users' => [
      'icon' => 'icon-users',
      'children' => [
        'list' => [
          'route' => 'admin.user.list',
          'icon' => 'icon-list',
          'alternates' => [
            'admin.report.usertransaction'
          ],
        ],
        'add' => [
          'route' => 'admin.user.add',
          'icon' => 'icon-user-follow',
          'alternates' => ['admin.user.edit'],
        ],
      ],
    ],
    'contracts' => [
      'icon' => 'icon-magic-wand',
      'children' => [
        'list' => [
          'route' => 'admin.contract.list',
          'icon' => 'icon-list',
          'alternates' => [
            'admin.workdiary.view',
            'admin.contract.details',
            'admin.report.transaction'
          ],
        ],
      ],
    ],
    'jobs' => [
      'icon' => 'icon-rocket',
      'children' => [
        'list' => [
          'route' => 'admin.job.list',
          'icon' => 'icon-list',
        ]
      ],
    ],
    'tickets' => [
      'icon' => 'icon-eye',
      'children' => [
        'list' => [
          'route' => 'admin.ticket.list',
          'icon' => 'icon-list',
        ],
      ],
    ],
    'notifications' => [
      'icon' => 'icon-bell',
      'children' => [
        'list' => [
          'route' => 'admin.notification.list',
          'icon' => 'icon-list',
        ],
        'send' => [
          'route' => 'admin.notification.send',
          'icon' => 'icon-bubbles',
        ],
      ],
    ],
    'system' => [
      'icon' => 'icon-settings',
      'children' => [
        'category' => [
          'route' => 'admin.category.list',
          'icon' => 'icon-layers',
        ],
        'skill' => [
          'route' => 'admin.skill.list',
          'icon' => 'icon-directions',
        ],
        'faq' => [
          'route' => 'admin.faq.list',
          'icon' => 'icon-question',
        ],
'affiliate' => [
          'route' => 'admin.affiliate.edit',
          'icon' => 'icon-users',
        ],'fee' => [
          'route' => 'admin.fee.settings',
          'icon' => 'icon-pie-chart',
        ],      ],
    ],
  ],
  /*
  |--------------------------------------------------------------------------
  | About Menu
  |--------------------------------------------------------------------------
  |
  |
  */
  'about_menu' => [
    'about_us' => [
      'route' => 'about',
    ],    
    'careers' => [
      'route' => 'about.careers',
    ],
    'team' => [
      'route' => 'about.team',
    ],
    'board' => [
      'route' => 'about.board',
    ],
    'press' => [
      'route' => 'about.press',
    ],
    'contact' => [
      'route' => 'home',
    ],
  ],
];
