<?php namespace Wawjob\Http\Controllers\Admin;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

use DB;
use Wawjob\User;
use Wawjob\Project;
use Wawjob\Category;
use Wawjob\Contract;
use Wawjob\UserContact;
use Wawjob\Role;
use Wawjob\Country;


class DashboardController extends AdminController {

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Show admin dashboard.
   *
   * @return Response
   */
  public function index()
  {

    $users_weekly_new = intval(User::where('created_at', '>', date('Y-m-d', strtotime('-1 week')) . ' 00:00:00')->count());
    $users_total_active = intval($users_weekly_new = User::where('status', '=', '1')->count());
    $jobs_weekly_new = intval(Project::where('created_at', '>', date('Y-m-d', strtotime('-1 week')) . ' 00:00:00')->count());
    $jobs_total_open = intval(Project::where('status', '=', '0')->count());
    $trans_weekly_new = 0;
    $trans_pending = 0;
    $tickets_weekly_new = 0;
    $tickets_undresolved = 0;

    return view('pages.admin.dashboard', [
      'page' => 'dashboard',
      'css' => 'dashboard',
      'component_css' => [
        '/assets/plugins/jqvmap/jqvmap/jqvmap',
        '/assets/plugins/jquery.uniform/themes/default/css/uniform.default.min',
      ],

      'userStatusList' => User::$userStatusList,
      'userTypeList' => Role::whereNotNull('parent_id')->get(),

      'stat_users' => [
        'weekly' => $users_weekly_new,
        'total_active' => $users_total_active,
      ],

      'stat_jobs' => [
        'weekly' => $jobs_weekly_new,
        'total_open' => $jobs_total_open,
      ],

      'trans' => [
        'weekly' => $trans_weekly_new,
        'pending' => $trans_pending,
      ],

      'tickets' => [
        'weekly' => $tickets_weekly_new,
        'unresolved' => $tickets_undresolved,
      ],

      'pr_cats' => Category::whereNull('parent_id')->get(),
      // 'pr_cats' => Category::byType(),

    ]);

    /*
                @forelse ($pr_cats as $id => $mp)
                  <label>{{ $mp['name'] }}</label>
                  @forelse ($mp['children'] as $pid => $p)
                  <div class="col-xs-3">
                    <label><input type="checkbox" class="pr_cat" ref="{{ $p['id'] }}" /> {{ $p['name'] }}</label>
                  </div>
                  @empty
                  <label>No category available</label>
                  @endforelse
                @empty
                <label>No category available</label>
                @endforelse
    */

  }

  public function stat_region_users(Request $request) {
    if ( !$request->ajax() ) {
      // Only ajax request is expected
      return redirect()->route('admin.dashboard');     

    } else {

      $user_type = $request->input('tv');
      $status = $request->input('st');

      $user_model = User::leftJoin('user_contacts', 'users.id', '=', 'user_contacts.user_id');

      if ($user_type != '') {
        $user_model = $user_model->leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')
                        ->where('users_roles.role_id', '=', $user_type);
      }
      if ($status != '') {
        $user_model->where('users.status','=', $status );
      }

      $user_model->whereNotNull('user_contacts.country_code');

      $stats_raw = $user_model->groupBy('users.id', 'user_contacts.country_code')
              ->select('user_contacts.country_code', DB::raw('COUNT(users.id) as nums'))
              ->get()->toArray();
      
      $stats = array();
      foreach($stats_raw AS $sr) {
        $stats[strtolower($sr['country_code'])] = $sr['nums'];
      }

      return response()->json([
        'data' => $stats
      ]);
      

    }
  }

  private function makeDurReadable($sec, $cut=3, $full=false, $with_now = 0) {

    if (($with_now > 0) && ($sec <= $with_now)) {
      return 'Just Now';
    }

    $diffset = [
    ];

    $diffset_labels = [
      's',
      'm',
      'h',
      'd',
      'm',
      'y',
    ];

    $diffset_labels_full = [
      'secs',
      'mins',
      'hrs',
      'days',
      'months',
      'years',
    ];

    $diffset_rate = [
      60,
      60,
      24,
      30,
      12,
    ];

    $a = 0;
    $b = 0;
    $ind = 0;
    do {
      $a = intval($sec / $diffset_rate[$ind]);
      $b = $sec % $diffset_rate[$ind];
      $diffset[] = $b;
      $sec = $a;
      $ind ++;
    } while (($a > 0) || ($ind < 4));
    if ($a > 0) $diffset[] = $a;

    $tks = [];
    for ($i = count($diffset) - 1; $i >= 0; $i--) {
      if ($diffset[$i] > 0) {
        $tks[] = $diffset[$i] . ' ' . ($full?$diffset_labels_full[$i]:$diffset_labels[$i]);
      }
    }

    $gone = '';
    if ($cut == 0) {
      $gone = implode(' ', $tks);
    } else {
      $gone = implode(' ', array_slice($tks, 0, $cut));   
    }

    return $gone;

  }

  public function recent_posts(Request $request) {
    if ( !$request->ajax()) {
      // Only ajax request is expected
      return redirect()->route('admin.dashboard');     

    } else {

      $cats = $request->input('cats');

      $pr_model = Project::leftJoin('categories', 'categories.id', '=', 'projects.category_id');
      $pr_model = $pr_model->whereIn('categories.parent_id', $cats);
      $prs = $pr_model->get();

      $now = time();

      $rcnt_prs = array();
      foreach($prs AS $pr) {
        $pc = strtotime($pr->created_at);
        $diffs = ($now - $pc);

        $rcnt_prs[] = [
          'title' => $pr->subject,
          'buyer' => [
            'id' => $pr->client->id,
            'avatar' => avatarUrl($pr->client, '32'),
            'username' => $pr->client->username,
          ],
          'created_at' => $this->makeDurReadable($diffs, 1, true, 110),
        ];
      }

      return response()->json([
        'data' => $rcnt_prs
      ]);
      

    }
  }

  public function complete_stats(Request $request) {
    if ( !$request->ajax()) {
      // Only ajax request is expected
      return redirect()->route('admin.dashboard');     

    } else {

      return response()->json([
        'data' => [
          'cp' => [
            'value' => 79,
            'link_label' => 'Buyer',
            'link' => '#',
          ],

          'bp' => [
            'value' => 48,
            'link_label' => 'Freelancer',
            'link' => '#',
          ],

          'tr' => [
            'value' => 52,
            'link_label' => 'Transactions',
            'link' => '#',
          ]
        ]
      ]);
      

    }
  }


  public function server_stats(Request $request) {
    if ( !$request->ajax()) {
      // Only ajax request is expected
      return redirect()->route('admin.dashboard');     

    } else {

      return response()->json([
        'data' => [
          [8, 9, 10, 11, 10, 10, 12, 10, 10, 11, 9, 12, 11, 10, 9, 11, 13, 13, 12],
          [9, 11, 12, 13, 12, 13, 10, 14, 13, 11, 11, 12, 11, 11, 10, 12, 11, 10],
          [9, 10, 9, 10, 10, 11, 12, 10, 10, 11, 11, 12, 11, 10, 12, 11, 10, 12],
        ]
      ]);
      

    }
  }


}