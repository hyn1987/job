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

	'my_jobs' => '나의 과제',
	'open_jobs' => '제안한 과제',
	'post_job' => '과제발송',
	'contracts' => '계약',

	'view_applicants' => '신청자보기',
	'view_all_jobs' => '발송한 모든 과제 보기',
	'view_all_contracts' => '모든 계약보기',
	'view_work_diary' => '작업리력보기',
	'view_offer' => '제공보기',

	'job_detail' => '과제상세',
	'contract_detail' => '계약상세',
	'send_message' => '통보문전송',
	'make_offer' => '제공하기', 

	'no_open_jobs' => '제안한 과제가 없습니다.',

	// labels in Open Jobs
	'applicants' => '신청자',
	'messaged' => '통신한 과제',
	'offers' => '제공',
	'hires' => '채용',
	'offer_hired' => '제공/채용', 
	'archived' => '처리됨', 

	// Action
	'action' => '조작',
	'view_posting' => '과제 보기',
	'edit_posting' => '과제 편집',
	'close_posting' => '과제 완료',
	'make_public' => '공개 하기',
	'make_private' => '비공개 하기',

	// Status
	'public' => '공개', 
	'private' => '비공개', 
	'open' => '열림', 
	'closed' => '닫기', 
	
	'job_is_closed' => '과제가 완료되였습니다.',
	'job_is_private' => '과제가 비공개되였습니다.',

	// Invite to job
	'invite_sb' => '<span>:fullname</span>을 방문',
	'related_job' => '관련된 과제',
	'please_select_job' => '과제를 선택하십시요',

	// Create job
	'category' => '분류',
	'title' => '제목',
	'description' => '설명',
	'please_select_category' => '분류를 선택하십시요',

	'type' => '류형',
	'price' => '가격',
	'duration' => '기간',
	'workload' => '과제부하',
	'contract_limited' => '계약제한',
	'is_public' => '공개/비공개',
	'cover_letter_required' => '소개서',
	'yes_require_cover_letter' => '예, 소개서가 필요합니다.',
	'yes_make_this_job_public' => '예, 이 과제를 공개합니다.',
	'submit' => '전송',

	// View job
	'details' => 'Details',
	'need_to_hire_freelancers' => ':n 명의 개발자를 채용하고 싶습니다.',
	'client_activity_on_this_job' => '이 과제에 대한 대방 활동',
	'proposals' => '제안',
	'interviewing' => '담화',
	'hired' => '채용됨',
	'proposal_date' => '제안날자',
	'initiated_by' => '가 착수',

	'about_the_client' => '대방에 대하여',
	'payment_not_verified' => '검증안된 지불',
	'n_jobs_posted' => ':n 과제가 발송되였습니다.',
	'member_since' => ':time 까지의 성원',

	// Freelancer
	'billing_rate' => '시간당 지불금액',
	'billing_price' => '고정지불금액',
	'this_is_what_the_client_sees' => '이것은 대방이 아는것입니다.',
	'you_will_earn' => "리익금액",
	'estimated' => '추산금액',
	

	'you_applied_already' => '이과제에 이미 신청하였습니다.',
	'accept_and_submit_a_proposal' => '제안 접수 및 발송',
	'submit_a_proposal' => '제안전송',
	'job_posting' => '과제발송',
	'proposal_terms' => '제안기간',

	'summary_description' => '개요서술',
	'your_cover_letter' => '당신의 소개',
	'messages' => '통보문',
	'send' => '전송',
	'change_term' => '기간변경',
	'withdraw_proposal' => '제안철회',
	'you_applied_this_hourly' => '당신은 이과제를 $:n/hr 에 신청하였습니다.',
	'you_applied_this_fixed_price' => '당신은 이 과제를 $:n 에 신청하였습니다.',
	
	'withdraw' => [
		'reason' => '리유',
		'submit' => '회수'
	],

	'no_applicants' => '과제에 신청자가 없습니다',

	'contract_details' => '계약상세', 
	'contract_title' => '계약제목', 
	'hourly_rate' => '시간당 가격', 
	'sb_rate_is' => ':sb의 프로파일 가격은 $ :rate/hr 입니다.', 

	'per_hour' => '/시', 
	'hour_per_week'	=> '시/주', 
	'n_hours_per_week' => ':n 시/주', 
	'max_week' => '최대 / 주', 
	'allow_freelancer_manual_log' => '개발자기 필요할때 수동으로 시간을 기록할수 있습니다.', 
	'agree_term' => '예, Wawjob 봉사기간에 동의합니다.', 
	'hire_sb' => '<span>:sb</span>를 채용', 

	'sb_applied_to_job' => ':sb가 이미 과제에 신청하였습니다.', 
	'check_sb_proposal' => '<a href=":url">:sb</a>의 제안을 검토하십시요.', 
	'click_here_send_message' => '통보문을 보내려면 여기를 누르십시요', 
	'sent_invitation' => '이미 방문을 보냈습니다.', 
	'sent_offer' => '이미 제의를 보냈습니다..', 
	'hired_freelancer' => '이미 개발자를 채용하였습니다.', 

];
