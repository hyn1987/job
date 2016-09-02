<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Job
	|--------------------------------------------------------------------------
	|
	| The following language lines are used by the paginator library to build
	| the simple pagination links. You are free to change them to anything
	| you want to customize your views to better match your application.
	|
	*/

	'my_jobs' => '我的项目',
	'open_jobs' => '开着的项目',
	'post_job' => '创建项目',
	'contracts' => '契约',

	'view_applicants' => '查看申请人',
	'view_all_jobs' => '查看全部项目',
	'view_all_contracts' => '查看全部契约',
	'view_work_diary' => '查看工作日记',
	'view_offer' => '查看提供',
	
	'job_detail' => '项目详细',
	'contract_detail' => '契约详细',
	'send_message' => '发送消息',
	'make_offer' => '提供项目', 

	'no_open_jobs' => '您没有开着的项目。',

	// labels in Open Jobs
	'applicants' => '申请人',
	'messaged' => '已联系的',
	'offers' => '提供',
	'hires' => '雇佣',
	'offer_hired' => '提供/雇佣', 
	'archived' => '旧的',

	// Action
	'action' => '行动',
	'view_posting' => '查看项目',
	'edit_posting' => '编辑项目',
	'close_posting' => '关闭项目',
	'make_public' => '换成公开的',
	'make_private' => '换成专用的',

	// Status
	'public' => '公开的', 
	'private' => '专用的', 
	'open' => '开着的', 
	'closed' => '关闭的', 

	'job_is_closed' => '此项目已关闭。',
	'job_is_private' => '此项目为专用的。',

	// Invite to job
	'invite_sb' => '邀请<span>:fullname</span>',
	'related_job' => '有关项目',
	'please_select_job' => '请您选择项目',

	// Create job
	'category' => '项目类',
	'title' => '题目',
	'description' => '描述',
	'please_select_category' => '请您选择项目类',

	'type' => '计时或固定价格',
	'price' => '价格',
	'duration' => '期间',
	'workload' => '工作负荷',
	'contract_limited' => '契约人数限制',
	'is_public' => '公开的',
	'cover_letter_required' => '需要申请书',
	'yes_require_cover_letter' => '是， 需要申请书。',
	'yes_make_this_job_public' => '是， 这是公开项目。',
	'submit' => '提出',

	// View job
	'details' => '详细',
	'need_to_hire_freelancers' => '需要:n个契约者',
	'client_activity_on_this_job' => '顾客为此项目的行为',
	'proposals' => '申请',
	'interviewing' => '会谈中',
	'hired' => '已雇佣',
	'proposal_date' => '申请日期',
	'initiated_by' => '创始者',

	'about_the_client' => '关于顾客',
	'payment_not_verified' => '未认证支付模式',
	'n_jobs_posted' => '已创建:n个项目',
	'member_since' => '注册于:time',

	// Freelancer
	'billing_rate' => '支付价格',
	'billing_price' => '支付价格',
	'this_is_what_the_client_sees' => '这是顾客所看的。',
	'you_will_earn' => "您将获得",
	'estimated' => '预算',
	
	'you_applied_already' => '您已经申请该项目。',
	'accept_and_submit_a_proposal' => '接受并提交申请书',
	'submit_a_proposal' => '提交申请书',
	'job_posting' => '项目内容',
	'proposal_terms' => '申请条目',

	'summary_description' => '摘要',
	'your_cover_letter' => '您的申请书',
	'messages' => '消息',
	'send' => '发送',
	'change_term' => '变更条目',
	'withdraw_proposal' => '收回申请',
	'you_applied_this_hourly' => '您以$:n/小时申请该项目。',
	'you_applied_this_fixed_price' => '您以$:n的数额申请该项目。',
	
	'withdraw' => [
		'reason' => '理由',
		'submit' => '收回'
	], 

	'no_applicants' => '没有人申请此项目。',

	'contract_details' => '契约详细', 
	'contract_title' => '契约题目', 
	'hourly_rate' => '每时价格', 
	'sb_rate_is' => ':sb的默认价格为$:rate/小时。', 

	'per_hour' => '/小时', 
	'hr_per_week'	=> '小时/周', 
	'n_hours_per_week' => ':n小时/周', 
	'max_week' => '最大 / 周', 
	'allow_freelancer_manual_log' => '允许自由契约者记录手动日记。', 
	'agree_term' => '是，我同意Wawjob的服务条目。', 
	'hire_sb' => '雇佣<span>:sb</span>', 

	'sb_applied_to_job' => ':sb已申请您的项目。', 
	'check_sb_proposal' => '请您查看<a href=":url">:sb的申请书</a>。', 
	'click_here_send_message' => '点击这里发送消息', 
	'sent_invitation' => '您已经发出了邀请。', 
	'sent_offer' => '您已经发出了提供。', 
	'hired_freelancer' => '您已经雇佣这个自由契约者。', 
];
