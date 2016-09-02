<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use Storage;
use Config;
use Cache;

// Models
use Wawjob\User;
use Wawjob\UserContact;
use Wawjob\Role;
use Wawjob\Country;
use Wawjob\Timezone;
use Wawjob\Project;
use Wawjob\Category;

//DB
use DB;

class SearchController extends Controller {

  public $searchTitle;
  public $arryCountryCode = [];
  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Search the user-informations.
   *
   * @param  Request $request
   * @return Response
   */
  public function user(Request $request)
  {
    // Get the settings from config.
    $per_page = Config::get('settings.freelancer.per_page');
    // Get the parameters.
    $search_title = $request->input('search_title');
    
    if ($request->ajax()) {

      $users = $this->searchUser($search_title, $per_page);
      $str_html = "";
      $msg = "";

      if( !$users->isEmpty() ) {
        $str_html = view('pages.search.userResult', [
                         "users" => $users
                      ])->render();
        
      } else {
        $msg = view('pages.search.noUserFound', [])->render();
      }

      return  response()->json([
        'success'         => true,
        'str_html'        => $str_html,
        'per_page'        => $per_page,
        'pgn_rndr_html'   => $users->render(),
        'page_total'      => $users->total(),
        'cur_page'        => $users->currentPage(),
        'page_count'      => $users->count(),
        'last_page'       => $users->lastPage(),
        'page_first_item' => $users->firstItem(),   
        'page_last_item'  => $users->lastItem(),
        'msg'             => $msg,        
      ]);
    } else {
      //dd($search_title);
      $users = $this->searchUser($search_title, $per_page);     

      return view('pages.search.user', [
        'page' => 'search.user',
        'users' => $users,
        'per_page' => $per_page,
        'searchTitle' => $search_title,
      ]);
    }
  }

  /**
   * Search users.
   *
   * @param  Request $request
   * @return Response
   */

  public function searchUser($searchTitle, $per_page) {
    // retrieve database data.
      try {
        if ($searchTitle) {

          //search by name
          $this->searchTitle = '%' . $searchTitle . '%';
 
         //search by country name
          $countryList = Country::where('name', 'like', $this->searchTitle)->get();

          if ( !$countryList->isEmpty() ) {

            foreach ( $countryList as $country ) {
              $this->arryCountryCode[] = $country->charcode ;
            }

            //$userInfos = $userInfos->OrWhereIn('country_code', $arryCountryCode);
          }

          $users = User::join('users_roles', 'users.id', '=', 'users_roles.id')
                        ->join('user_contacts', 'users.id', '=', 'user_contacts.user_id')
                        ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                        ->whereIn('role_id', [User::ROLE_USER_FREELANCER])
                        ->where('users.status', '=', '1');

          
          if (empty($this->arryCountryCode)) {
            //dd($this->searchTitle);
            $users =  $users->where(function ($query) {
                                  $query ->where('first_name', 'like',  $this->searchTitle)
                                     ->orWhere('last_name', 'like',  $this->searchTitle)
                                     ->orWhere('user_profiles.title', 'like',  $this->searchTitle)
                                     ->orWhere('user_profiles.desc', 'like', $this->searchTitle);
                                });
          }
          else {
            $users =  $users->where(function ($query) {
                                  $query ->where('first_name', 'like',  $this->searchTitle)
                                     ->orWhere('last_name', 'like',  $this->searchTitle)
                                     ->orWhere('user_profiles.title', 'like',  $this->searchTitle)
                                     ->orWhere('user_profiles.desc', 'like', $this->searchTitle)
                                     ->orWhereIn('country_code', $this->arryCountryCode);
                                });
          }

          $users = $users->select('users.*')->paginate($per_page); 

        } else {
          $users = User::join('users_roles', 'users.id', '=', 'users_roles.id')
                        ->whereIn('role_id', [User::ROLE_USER_FREELANCER])
                        ->where('users.status', '=', '1') 
                        ->paginate($per_page);  
        }
      } catch (Exception $e) {
        return; 
      }

      return $users;
  }

  /**
   * Search the jobs.
   *
   * @param  Request $request
   * @return Response
   */
  public function job(Request $request)
  {
    // Get the settings from config.
    $perPage = Config::get('settings.freelancer.per_page');
    
    try {
      $jobAll = Project::where('status', Project::STATUS_OPEN)
                      ->where('is_public', Project::STATUS_PUBLIC);
    } catch (Exception $e) {
        return;
    }
    
    if ($request->ajax()) { //if the request is ajax
      
      $bgtAmtMin = $request->input('bgt_amt_min');
      $bgtAmtMax = $request->input('bgt_amt_max');

      $jobList = clone $jobAll;

      // Get the parameters.
      $subject = $request->input('search_title');

      if ($subject) {
        $jobList = $jobList->where('subject', 'like', '%' . $subject . '%');
      }


      $jobListTemp = clone $jobList;
      
      //category?
      $subCategory = $request->input('subCategory');
      if (!empty($subCategory)) {
          $jobList = $jobList->whereIn('category_id', $subCategory);   
      }  

      //job type?
      $typeHr = $request->input('type_hr');
      $typeFx = $request->input('type_fx');   
      
      if ( isset($typeHr) && isset($typeFx)) {
        $jobList = $jobList->whereRaw('(type = ? or (type = ? and price between ? and ?))', array($typeHr, $typeFx, $bgtAmtMin,$bgtAmtMax));
      } else {
        if (isset($typeHr)) {
          $jobList = $jobList ->where('type', '=', $typeHr);
        } else if (isset($typeFx)) {      
          $jobList = $jobList ->where('type', '=', $typeFx)
                              ->whereBetween('price', array($bgtAmtMin, $bgtAmtMax));
                              
        }/* else {
          $jobList = $jobList->whereRaw('type = ? or (type = ? and price between ? and ?)', array(Project::TYPE_HOURLY, Project::TYPE_FIXED, $bgtAmtMin,$bgtAmtMax));
        }*/               
      }

      //duration?
      $duration = $request->input('duration');
      if (!empty($duration)) {
          $jobList = $jobList->whereIn('duration', $duration);   
      }

      //workload?
      $workload = $request->input('workload');
      if (!empty($workload)) {
        $jobList = $jobList->whereIn('workload', $workload); 
      }

      //////////////////////////////////////////  
      $sortBy = $request->input('sort_by');
      
      if ($sortBy == 'Newest') {
        $dir = 'desc';
      } else /*if ($sortBy == 'Oldest')*/ {
        $dir = 'asc';
      }
        
      $jobListPage = $jobList->orderBy('created_at', $dir)
        ->paginate($perPage);
     
      $str_html = "";

      if(!$jobListPage->isEmpty()) {
        foreach ($jobListPage as $job ) {
          $str_html  = 
                $str_html .
                view('pages.search.jobInfo', [
                         'job' => $job
                      ])->render();                          
        }
      }
      
      //job count by category list
      $catList = Category::where('type', Category::TYPE_PROJECT)
                          ->where('parent_id', $request->input('category'))
                          ->get();

      $arrayJobCntByCat = [];
      foreach ($catList as $key => $cat) {
        $jobListTemp1 = clone $jobListTemp;
        $arrayJobCntByCat[$cat->id] = $jobListTemp1->where('category_id', '=', $cat->id)->count();
      }

      //job count by the other item lists
      $arrayCnt = array();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['hr_cnt'] = $jobListTemp1->where('type', '=', Project::TYPE_HOURLY)->count();
      
      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['fx_cnt'] = $jobListTemp1->where('type', '=', Project::TYPE_FIXED)
                          ->whereRaw('price between ? and ?', array($bgtAmtMin,$bgtAmtMax))->count(); 

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['dur_mt6m_cnt'] = $jobListTemp1->where('duration', '=', Project::DUR_MT6M)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['dur_3t6m_cnt'] = $jobListTemp1->where('duration', '=', Project::DUR_3T6M)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['dur_1t3m_cnt'] = $jobListTemp1->where('duration', '=', Project::DUR_1T3M)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['dur_lt1m_cnt'] = $jobListTemp1->where('duration', '=', Project::DUR_LT1M)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['dur_lt1w_cnt'] = $jobListTemp1->where('duration', '=', Project::DUR_LT1W)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['pt_cnt'] = $jobListTemp1->where('workload', '=', Project::WL_PARTTIME)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['ft_cnt'] = $jobListTemp1->where('workload', '=', Project::WL_FULLTIME)->count();

      $jobListTemp1 = clone $jobListTemp;
      $arrayCnt['as_cnt'] = $jobListTemp1->where('workload', '=', Project::WL_ASNEEDED)->count();

      return response()->json([
        'success' => true,
        'str_html' => $str_html,
        'per_page' => $perPage, 
        'job_count' => $jobList->count() . " " . str_plural('job', $jobList->count()) . " found",
        'pgn_rndr_html' => $jobListPage->render(),
        'page_total' => $jobListPage->total(),
        'cur_page' => $jobListPage->currentPage(),
        'page_count' => $jobListPage->count(),
        'last_page' => $jobListPage->lastPage(),
        'page_first_item' => $jobListPage->firstItem(),   
        'page_last_item' => $jobListPage->lastItem(),
        'array_cnt' => $arrayCnt,
        'arrayJobCntByCat' => $arrayJobCntByCat,
      ]); 

    } else {
      //if the request is not ajax
      //get the category list 
      $categoryList = Category::all();
      
      foreach ($categoryList as $key => $category) {
        $categoryTreeList = $category->byType(Category::TYPE_PROJECT);
        break;
      }
      
      $jobAllCount = $jobAll->count();

      // Get the parameters.
      $subject = $request->input('search_title');

      if ($subject) {
        $paginated = $jobAll->where('subject', 'like', '%' . $subject . '%');
      }

      try {
        $paginated = $jobAll->orderBy('created_at', 'desc')
                           ->paginate($perPage);      
      } catch (Exception $e) {
        return;
      }
      
      return view('pages.search.job', [
        'page' => 'search.job',
        'jobs_page' => $paginated,
        'per_page' => $perPage, 
        'job_count' => $jobAllCount,
        'categoryTreeList' => $categoryTreeList,
        'search_title' => $subject,
      ]);
    }
  }

  /**
   * Service the jobs via RSS.
   *
   * @param  Request $request
   * @return Response
   */
  public function rssjob(Request $request)
  {
    $minutes = 1;
    $jobs = Cache::remember('job', $minutes, function() {
      $param_arr = json_decode(urldecode($_REQUEST['param']), true);
      $date = date('Y-m-d H:i:s');
      //header('Content-Type: application/rss+xml; charset=UTF-8', true);
      //date('Y-m-d', strtotime('-1 minute'));
      $jobList = Project::where('status', Project::STATUS_OPEN)
                        ->where('is_public', Project::STATUS_PUBLIC);
      // Get the parameters.
      $subject = Project::input('search_title', $param_arr);
      if ($subject) {
        $jobList = $jobList->where('subject', 'like', '%' . $subject . '%');
      }

      //category?
      $subCategory = Project::input('subCategory', $param_arr);
      if (!empty($subCategory)) {
          $jobList = $jobList->whereIn('category_id', $subCategory);   
      }  

      //job type?
      $typeHr = Project::input('type_hr', $param_arr);
      $typeFx = Project::input('type_fx', $param_arr);      
      $bgtAmtMin = Project::input('bgt_amt_min', $param_arr);
      $bgtAmtMax = Project::input('bgt_amt_max', $param_arr);
      
      if ( $typeHr && $typeFx) {
        $jobList = $jobList ->where('type', '=', $typeHr)
                            ->orWhereRaw('type = ? and price between ? and ?', array($typeFx,$bgtAmtMin,$bgtAmtMax));
                              
      } else {
        if ($typeHr) {
          $jobList = $jobList ->where('type', '=', $typeHr);
        } else if ($typeFx) {      
          $jobList = $jobList ->where('type', '=', $typeFx)
                              ->WhereBetween('price', array($bgtAmtMin, $bgtAmtMax));
                              
        }               
      }

      //duration?
      $duration = Project::input('duration', $param_arr);
      if (!empty($duration)) {
          $jobList = $jobList->whereIn('duration', $duration);   
      }

      //workload?
      $workload = Project::input('workload', $param_arr);
      if (!empty($workload)) {
        $jobList = $jobList->whereIn('workload', $workload); 
      }

      ////////////////////////////  
      $sortBy = Project::input('sort_by', $param_arr);
      
      if ($sortBy == 'Newest' || !$sortBy) {
        $dir = 'desc';
      } else{
        $dir = 'asc';
      }
      $jobList = $jobList->orderBy('created_at', $dir)->take(50)->get();

      return $jobList;
    });
    return response()->view('pages.search.rss', [
        'page'             => 'search.rss',
        'last_build_date'  => date('Y-m-d H:i:s'),
        'jobs'             => $jobs,
    ])->header('Content-Type', 'text/xml')->header('charset', 'UTF-8');
    
  }
}