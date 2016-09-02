<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);
// About Pages
Route::group(['prefix' => 'about'], function () {
  Route::get('/', ['as' => 'about', 'uses' => 'HomeController@about']);
  Route::match(['get', 'post'], 'careers', ['as' => 'about.careers', 'uses' => 'HomeController@careers']);
  Route::match(['get', 'post'], 'team', ['as' => 'about.team', 'uses' => 'HomeController@team']);
  Route::match(['get', 'post'], 'board', ['as' => 'about.board', 'uses' => 'HomeController@board']);
  Route::match(['get', 'post'], 'press', ['as' => 'about.press', 'uses' => 'HomeController@press']);
});

Route::get('privacy-police', ['as' => 'frontend.privacy_policy', 'uses' => 'HomeController@privacy_policy']);
Route::get('terms-service', ['as' => 'frontend.terms_service', 'uses' => 'HomeController@terms_service']);

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
  /**
   * v1 APIs
   */
  Route::group(['prefix' => 'v1', 'namespace' => 'v1'], function () {
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::group(['middleware' => 'auth.api_v1'], function () {
      Route::get('valid', 'AuthController@valid');
      Route::get('sync', 'AuthController@sync');
      Route::post('timelog', 'ContractController@timelog');
    });
  });
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth.admin'], function () {
  Route::get('/', function () {
    return redirect()->route('admin.dashboard');
  });

  // Dashboard
  Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'DashboardController@index']);
    Route::any('/regional_user_stat', ['as' => 'admin.dashboard.regional_user_stat', 'uses' => 'DashboardController@stat_region_users']);
    Route::any('/recent_posts', ['as' => 'admin.dashboard.recent_posts', 'uses' => 'DashboardController@recent_posts']);
    Route::any('/complete_stats', ['as' => 'admin.dashboard.complete_stats', 'uses' => 'DashboardController@complete_stats']);
    Route::any('/server_stats', ['as' => 'admin.dashboard.server_stats', 'uses' => 'DashboardController@server_stats']);

  });

  // User
  Route::group(['prefix' => 'user'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.user.list');
    });
    Route::match(['get', 'post'], 'list', ['as' => 'admin.user.list', 'uses' => 'UserController@all']);
    Route::get('add', ['as' => 'admin.user.add', 'uses' => 'UserController@add']);
    Route::get('edit/{id}', ['as' => 'admin.user.edit', 'uses' => 'UserController@edit']);
    Route::post('update', ['as' => 'admin.user.update', 'uses' => 'UserController@update']);
    Route::any('ajax_action', ['as' => 'admin.user.ajax_action', 'uses' => 'UserController@ajax_action']);
  });

  // Contract
  Route::group(['prefix' => 'contract'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.contract.list');
    });
    Route::match(['get', 'post'], 'list', ['as' => 'admin.contract.list', 'uses' => 'ContractController@search']);
    Route::match(['get', 'post'], 'update_status', ['as' => 'admin.contract.update_status', 'uses' => 'ContractController@update_status']);
    Route::match(['get'], 'details/{id}', ['as' => 'admin.contract.details', 'uses' => 'ContractController@details']);
  });

  // Job
  Route::group(['prefix' => 'job'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.job.list');
    });
    Route::match(['get', 'post'], 'list', ['as' => 'admin.job.list', 'uses' => 'JobController@search']);

    // ajax call
    Route::post('ajax', ['as' => 'admin.job.ajax', 'uses' => 'JobController@ajaxAction']);
  });

  // Work Diary - paulz - Mar 14, 2016
  Route::group(['prefix' => 'workdiary'], function () {
    Route::get('view/{cid}', ['as' => 'admin.workdiary.view', 'uses' => 'WorkdiaryController@view'])->where(['cid' => '[0-9]+']);
    Route::post('ajax', ['as' => 'admin.workdiary.ajax', 'uses' => 'WorkdiaryController@ajaxAction']);
  });

  // Report - paulz - Mar 25, 2016
  Route::group(['prefix' => 'report'], function () {
    Route::match(['get', 'post'], '/transaction/{cid}', ['as' => 'admin.report.transaction', 'uses' => 'ReportController@transaction'])->where(['cid' => '[0-9]+']);
    Route::match(['get', 'post'], '/usertransaction/{uid}', ['as' => 'admin.report.usertransaction', 'uses' => 'ReportController@user_transaction'])->where(['uid' => '[0-9]+']);
  });


  // Ticket - paulz - Mar 12, 2016
  Route::group(['prefix' => 'ticket'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.ticket.list');
    });
    Route::match(['get', 'post'], 'list', ['as' => 'admin.ticket.list', 'uses' => 'TicketController@search']);
    Route::post('ajax', ['as' => 'admin.ticket.ajax', 'uses' => 'TicketController@ajaxAction']); //paulz
  });

  // Notification
  Route::group(['prefix' => 'notification'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.notification.list');
    });
    Route::get('list', ['as' => 'admin.notification.list', 'uses' => 'NotificationController@all']);
    Route::post('save', ['as' => 'admin.notification.save', 'uses' => 'NotificationController@save']);
    Route::get('send', ['as' => 'admin.notification.send', 'uses' => 'NotificationController@send']);
    Route::post('get/{id}', ['as' => 'admin.notification.get', 'uses' => 'NotificationController@get'])->where(['id' => '[0-9]+']);
    Route::post('multicast', ['as' => 'admin.notification.multicast', 'uses' => 'NotificationController@multicast']);
  });

  /* System */
  // Category
  Route::group(['prefix' => 'category'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.category.list');
    });
    Route::get('list', ['as' => 'admin.category.list', 'uses' => 'CategoryController@all']);
    Route::post('save', ['as' => 'admin.category.save', 'uses' => 'CategoryController@save']);
  });

  // Skill
  Route::group(['prefix' => 'skill'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.skill.list');
    });
    Route::get('list', ['as' => 'admin.skill.list', 'uses' => 'SkillController@all']);
    Route::post('save', ['as' => 'admin.skill.save', 'uses' => 'SkillController@save']);
    Route::post('deactivatable', ['as' => 'admin.skill.deactivatable', 'uses' => 'SkillController@deactivatable']);
  });

  // Faq
  Route::group(['prefix' => 'faq'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.faq.list');
    });
    Route::get('list', ['as' => 'admin.faq.list', 'uses' => 'FaqController@all']);
    Route::post('load', ['as' => 'admin.faq.load', 'uses' => 'FaqController@load']);
    Route::post('save', ['as' => 'admin.faq.save', 'uses' => 'FaqController@save']);
  });
  // Affiliate
  Route::group(['prefix' => 'affiliate'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.affiliate.edit');
    });
    Route::get('edit', ['as' => 'admin.affiliate.edit', 'uses' => 'AffiliateController@edit']);
    Route::post('update', ['as' => 'admin.affiliate.update', 'uses' => 'AffiliateController@update']);
  });
  // Fee
  Route::group(['prefix' => 'fee'], function () {
    Route::get('/', function () {
      return redirect()->route('admin.fee.settings');
    });
    Route::get('settings', ['as' => 'admin.fee.settings', 'uses' => 'FeeController@all']);
  });

});

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/
// Login
Route::group(['prefix' => 'user'], function () {
  Route::match(['get', 'post'], 'login', ['as' => 'user.login', 'uses' => 'AuthController@login']);
  // Signup
  Route::group(['prefix' => 'signup'], function () {
    Route::match(['get', 'post'], '/', ['as' => 'user.signup', 'uses' => 'AuthController@signup']);
    Route::match(['get', 'post'], 'buyer', ['as' => 'user.signup.buyer', 'uses' => 'AuthController@signup_buyer']);
    Route::match(['get', 'post'], 'freelancer', ['as' => 'user.signup.freelancer', 'uses' => 'AuthController@signup_freelancer']);
    Route::match(['get', 'post'], 'checkusername', ['as' => 'user.signup.checkusername', 'uses' => 'AuthController@signup_checkusername']);
    Route::match(['get', 'post'], 'checkemail', ['as' => 'user.signup.checkemail', 'uses' => 'AuthController@signup_checkemail']);
    
    // Apr 19, 2016 - paulz
    Route::get('checkfield', ['as' => 'user.signup.checkfield', 'uses' => 'AuthController@signup_checkfield']);
  });
  // Logout
  Route::get('logout', ['as' => 'user.logout', 'uses' => 'AuthController@logout']);
});

Route::get('login', function () {
  return redirect()->route('user.login');
});

// Signup
Route::group(['prefix' => 'signup'], function () {
  Route::get('/', function () {
    return redirect()->route('user.signup');
  });
  Route::get('buyer', function () {
    return redirect()->route('user.signup.buyer');
  });
  Route::get('freelancer', function () {
    return redirect()->route('user.signup.freelancer');
  });
});
Route::get('logout', function () {
  return redirect()->route('user.logout');
});

// Password
Route::group(['prefix' => 'password'], function () {
  Route::get('/', function () {
    return redirect()->route('password.reset.email');
  });

  // Password reset link request routes...
  Route::get('email', ['as' => 'password.reset.email', 'uses' => 'PasswordController@getEmail']);
  Route::post('email', 'PasswordController@postEmail');

  // Password reset routes...
  Route::get('reset/{token}', 'PasswordController@getReset');
  Route::post('reset', ['as' => 'password.reset', 'uses' => 'PasswordController@postReset']);
});

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'search'], function () {
  Route::match(['get', 'post'], 'user', ['as' => 'search.user', 'uses' => 'SearchController@user']);
  Route::match(['get', 'post'], 'job', ['as' => 'search.job', 'uses' => 'SearchController@job']);
  Route::match(['get', 'post'], 'rssjob', ['as' => 'search.rssjob', 'uses' => 'SearchController@rssjob']);

});



Route::group(['middleware' => 'auth.customer'], function () {
  // Job
  Route::match(['get', 'post'], 'my-jobs', ['as' => 'job.my_jobs', 'uses' => 'JobController@my_jobs']);

  Route::group(['prefix' => 'job'], function () {
    Route::match(['get', 'post'], '{id}', ['as' => 'job.view', 'uses' => 'JobController@view_job'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], 'application/{id}', ['as' => 'job.application_detail', 'uses' => 'JobController@application_detail'])->where(['id' => '[0-9]+']);
  });

  // Contract
  Route::group(['prefix' => 'contract'], function () {
    Route::match(['get', 'post'], 'all', ['as' => 'contract.all_contracts', 'uses' => 'ContractController@all_contracts']);
    Route::match(['get', 'post'], '{id}', ['as' => 'contract.contract_view', 'uses' => 'ContractController@contract_view'])->where(['id' => '[0-9]+']);
  });

  // Work Diary
  Route::group(['prefix' => 'workdiary'], function () {
    Route::get('view', ['as' => 'workdiary.view_first', 'uses' => 'WorkdiaryController@view_first']);
    Route::get('view/{cid}', ['as' => 'workdiary.view', 'uses' => 'WorkdiaryController@view'])->where(['cid' => '[0-9]+']);
  });

  // Report
  Route::group(['prefix' => 'report'], function () {
    Route::match(['get', 'post'], 'weekly-summary', ['as' => 'report.weekly_summary', 'uses' => 'ReportController@weekly_summary']);
    Route::match(['get', 'post'], 'transactions', ['as' => 'report.transactions', 'uses' => 'ReportController@transactions']);
    Route::match(['get', 'post'], 'timesheet', ['as' => 'report.timesheet', 'uses' => 'ReportController@timesheet']);
  });


});


/*
|--------------------------------------------------------------------------
| Buyer
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth.buyer'], function () {
  // Job
  Route::group(['prefix' => 'job'], function () {
    Route::match(['get', 'post'], 'create', ['as' => 'job.create', 'uses' => 'JobController@create']);
    Route::match(['get'       ], 'search_skills', ['as' => 'job.search_skills.ajax', 'uses' => 'JobController@searchJobSkills']);

    Route::match(['get', 'post'], 'all', ['as' => 'job.all', 'uses' => 'JobController@all_jobs']);
    
    Route::match(['get', 'post'], '{id}/edit', ['as' => 'job.edit', 'uses' => 'JobController@edit_job'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], '{id}/applicants', ['as' => 'job.applicants', 'uses' => 'JobController@view_applicants'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], '{id}/messaged-applicants', ['as' => 'job.messaged_applicants', 'uses' => 'JobController@messaged_applicants'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], '{id}/archived-applicants', ['as' => 'job.archived_applicants', 'uses' => 'JobController@archived_applicants'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], '{id}/offer-hired-applicants', ['as' => 'job.offer_hired_applicants', 'uses' => 'JobController@offer_hired_applicants'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], '{id}/change-status/{status}', ['as' => 'job.change_status.ajax', 'uses' => 'JobController@change_status'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], '{id}/change-public/{public}', ['as' => 'job.change_public.ajax', 'uses' => 'JobController@change_public'])->where(['id' => '[0-9]+']);

    Route::match(['get', 'post'], '{id}/make-offer/{uid}', ['as' => 'job.make_offer', 'uses' => 'JobController@make_offer'])->where(['id' => '[0-9]+', 'uid' => '[0-9]+']);
    Route::match(['get', 'post'], 'invite/{uid}', ['as' => 'job.invite', 'uses' => 'JobController@invite'])->where(['uid' => '[0-9]+']);
    Route::match(['get', 'post'], 'invite/job-info', ['as' => 'job.invite.job_info.ajax', 'uses' => 'JobController@invite_job_info']);

    Route::match(['get', 'post'], 'application/{id}/change-status/{status}', ['as' => 'job.application.change_status.ajax', 'uses' => 'JobController@changeAppStatus_buyer'])->where(['id' => '[0-9]+']);
  });

  // My Freelancers
  Route::match(['get', 'post'], 'my-freelancers', ['as' => 'contract.my_freelancers', 'uses' => 'ContractController@my_freelancers_buyer']);

  // Contract
  Route::group(['prefix' => 'contract'], function () {
    Route::post('ajax', ['as' => 'contract.ajax', 'uses' => 'ContractController@ajaxAction']);
  });

  // Work Diary
  Route::group(['prefix' => 'workdiary'], function () {
    Route::post('ajax', ['as' => 'workdiary.ajax', 'uses' => 'WorkdiaryController@ajaxAction']);
  });

  // Report
  Route::group(['prefix' => 'report'], function () {
    Route::match(['get', 'post'], 'budgets', ['as' => 'report.budgets', 'uses' => 'ReportController@budgets']);
  });

  // Payment
  Route::group(['prefix' => 'payment'], function () {
    Route::match(['get', 'post'], 'charge', ['as' => 'payment.charge', 'uses' => 'PaymentController@charge']);
  });
});

/*
|--------------------------------------------------------------------------
| Freelancer
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth.freelancer'], function () {
  // Job
  Route::group(['prefix' => 'job'], function () {
    Route::match(['get', 'post'], '{id}/apply', ['as' => 'job.apply', 'uses' => 'JobController@job_apply'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], 'my-proposals', ['as' => 'job.my_proposals', 'uses' => 'JobController@my_proposals']);
    Route::match(['get', 'post'], 'my-archived', ['as' => 'job.my_archived', 'uses' => 'JobController@my_archived']);
    Route::match(['get', 'post'], 'my-contracts', ['as' => 'job.my_contracts', 'uses' => 'JobController@my_contracts']);
    Route::match(['get', 'post'], 'apply-offer/{id}', ['as' => 'job.apply_offer', 'uses' => 'JobController@apply_offer'])->where(['id' => '[0-9]+']);
    Route::match(['get', 'post'], 'accept-invite/{id}', ['as' => 'job.accept_invite', 'uses' => 'JobController@accept_invite'])->where(['id' => '[0-9]+']);
  });

  // Work Diary
  Route::group(['prefix' => 'workdiary'], function () {
    Route::post('ajaxjob', ['as' => 'workdiary.ajaxjob', 'uses' => 'WorkdiaryController@ajaxjobAction']);
  });

  // Report
  Route::group(['prefix' => 'report'], function () {
    Route::match(['get', 'post'], 'timelogs', ['as' => 'report.timelogs', 'uses' => 'ReportController@timelogs']);
  });

  // Payment
  Route::group(['prefix' => 'payment'], function () {
    Route::match(['get', 'post'], 'withdraw', ['as' => 'payment.withdraw', 'uses' => 'PaymentController@withdraw']);
  });
});

/*
|--------------------------------------------------------------------------
| User
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'user'], function () {
  Route::match(['get', 'post'], 'my-info', ['as' => 'user.my_info', 'uses' => 'UserController@my_info']);
  Route::match(['get', 'post'], 'contact-info', ['as' => 'user.contact_info', 'uses' => 'UserController@contact_info']);
  Route::match(['get', 'post'], 'my-profile', ['as' => 'user.my_profile', 'uses' => 'UserController@my_profile']);
  Route::match(['get', 'post'], 'my-portfolio', ['as' => 'user.my_portfolio', 'uses' => 'UserController@my_portfolio']);
  Route::match(['get', 'post'], 'account', ['as' => 'user.account', 'uses' => 'UserController@account']);
  Route::match(['get', 'post'], 'affiliate', ['as' => 'user.affiliate', 'uses' => 'UserController@affiliate']);
  Route::match(['get', 'post'], 'finance', ['as' => 'user.finance', 'uses' => 'UserController@finance']);
  // Profile
  Route::get('profile/{id}', ['as' => 'user.profile', 'uses' => 'UserController@profile'])->where(['id' => '[0-9]+']);
});

/*
|--------------------------------------------------------------------------
| Message
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'message'], function () {
  Route::match(['get', 'post'], 'list', ['as' => 'message.list', 'uses' => 'MessageController@all']);
});

/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'notification', 'middleware' => 'auth.customer'], function () {
  Route::match(['get', 'post'], 'list', ['as' => 'notification.list', 'uses' => 'NotificationController@all']);
  Route::post('read/{id}', ['as' => 'notification.read', 'uses' => 'NotificationController@read'])->where(['id' => '[0-9]+']);
  Route::post('delete/{id}', ['as' => 'notification.delete', 'uses' => 'NotificationController@del'])->where(['id' => '[0-9]+']);
});


/*
|--------------------------------------------------------------------------
| FAQ
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'faq', 'middleware' => 'auth.customer'], function () {
  Route::match(['get', 'post'], 'list', ['as' => 'faq.list', 'uses' => 'FaqController@all']);
  Route::match(['get', 'post'], 'load', ['as' => 'faq.load', 'uses' => 'FaqController@load']);
});


/*
|--------------------------------------------------------------------------
| Contract Feedback
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'contract', 'middleware' => 'auth.customer'], function () {
  Route::match(['get', 'post'], '{id}/feedback', ['as' => 'contract.feedback', 'uses' => 'ContractController@feedback'])->where(['id' => '[0-9]+']);
});

/*
|--------------------------------------------------------------------------
| ticket
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'ticket'], function () {
  Route::match(['get', 'post'], 'list', ['as' => 'ticket.list', 'uses' => 'TicketController@all']);
  Route::match(['get', 'post'], 'create', ['as' => 'ticket.create', 'uses' => 'TicketController@create']);
});



/*
|--------------------------------------------------------------------------
| Test
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'test'], function () {
  Route::get('/', ['as' => 'test.index', 'uses' => 'TestController@index']);
});

