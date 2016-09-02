<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Auth;
use Storage;
use Config;
use DB;

// Models
use Wawjob\User;
use Wawjob\Contact;
use Wawjob\Role;
use Wawjob\Country;
use Wawjob\Timezone;
use Wawjob\UserProfile;
use Wawjob\UserEducation;
use Wawjob\Language;
use Wawjob\Skill;
use Wawjob\UserEmployment;
use Wawjob\UserPortfolio;
use Wawjob\SimpleImage;
use Wawjob\UserAffiliate;

class UserController extends Controller {

  /**
   * Constructor
   */

  public function __construct()
  {
    view()->share([
      'countries' => Country::all(),
      'languages' => Language::all(),
    ]);

    parent::__construct();
  }

	/**
   * My Info Page (user/my-info)
   *
   * @author nada
   * @since Jan 18, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */

	public function my_info(Request $request)
  {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }

    //
    if ( $request->ajax() ) {
      $avatar = $request->file('avatar');

      $avatar_temp_path = avatarPath($user, '', true);
      $avatar_temp_dir = dirname($avatar_temp_path);
      $filename = pathinfo($avatar_temp_path, PATHINFO_BASENAME);

      $file = $avatar->move($avatar_temp_dir, $filename);

      //get the tempImage size(width, height)
      $tempImage = new SimpleImage($avatar_temp_path);
      $tempImageInfo = $tempImage->original_info;

      return response()->json([
          'success'   => true,
          'imgUrl'    => avatarUrl($user, '', true),
          'imageInfo'  => $tempImageInfo,
      ]);
    }

    // If user input info
    if ($request->isMethod('post')) {
      $user->contact->first_name = $request->input('first_name');
      $user->contact->last_name  = $request->input('last_name');
      
      $user->locale = $request->input('language');

      // Flash data to the session.
      $request->flashOnly('first_name', 'last_name', 'language');

      $avatar = $request->file('avatar');

      if ($avatar) {
        $avatar_temp_path = avatarPath($user, '', true);

        $tempImage = new SimpleImage($avatar_temp_path); 
        $param = [$request->input('width'),
                        $request->input('height'),
                        $request->input('x1'),
                        $request->input('y1')];
        error_log(print_r($param, true));
        
        $tempImage->crop( $request->input('width'),
                        $request->input('height'),
                        $request->input('x1'),
                        $request->input('y1')
                      );

        $tempImage->resize(150,150);
        $tempImage->save(avatarPath($user->id));
        unlink($avatar_temp_path);
      }

      $user->contact->save();
      $user->save();

      return redirect()->route('user.my_info');
    }

    if ($user->isFreelancer()) {
      return view('pages.freelancer.user.my_info', [
        'page' => 'freelancer.user.my_info',
        'user' => $user,
        'error' => isset($error) ? $error : null,
      ]);
    }
    else if ($user->isBuyer()) {
      return view('pages.buyer.user.my_info', [
        'page' => 'buyer.user.my_info',
        'user' => $user,
        'error' => isset($error) ? $error : null,
      ]);
    }
    else {
      return redirect()->route('user.login');
    }

  }

  /**
   * user/my-profile
   *
   * @author Ri Chol Min
   * @param  Request $request
   * @return Response
   */

  public function my_profile(Request $request)
  {
    $user = Auth::user();

    if (!$user) {
      return redirect()->route('user.login');
    }

    // If user input info
    if ($request->isMethod('post')) {
      $skills       = json_decode($request->input('profile_skill'));
      $languages    = json_decode($request->input('profile_language'));
      $edu_from     = json_decode($request->input('pro_edu_from'));
      $edu_to       = json_decode($request->input('pro_edu_to'));
      $edu_school   = json_decode($request->input('pro_edu_school'));
      $edu_degree   = json_decode($request->input('pro_edu_degree'));
      $edu_major    = json_decode($request->input('pro_edu_major'));
      $edu_minor    = json_decode($request->input('pro_edu_minor'));
      $edu_desc     = json_decode($request->input('pro_edu_desc'));
      $emp_from     = json_decode($request->input('pro_emp_from'));
      $emp_to       = json_decode($request->input('pro_emp_to'));
      $emp_company  = json_decode($request->input('pro_emp_company'));
      $emp_position = json_decode($request->input('pro_emp_position'));
      $emp_desc     = json_decode($request->input('pro_emp_desc'));

      // truncate user's related profile database
      DB::table('user_skills')->where('user_id', $user->id)->delete();
      DB::table('users_languages')->where('user_id', $user->id)->delete();
      DB::table('user_educations')->where('user_id', $user->id)->delete();
      DB::table('user_employments')->where('user_id', $user->id)->delete();

      // update user's related profile database
      foreach ( $skills as $skill ){
        DB::table('user_skills')->insert(
          ['user_id' => $user->id, 'skill_id' => $skill]
        );
      }
      for ( $i = 0; $i < count($edu_from); $i++ ){
        $user_education = new UserEducation;
        $user_education->user_id  = $user->id;
        $user_education->from     = date('Y-m-d', strtotime($edu_from[$i] . '/01'));
        $user_education->to       = date('Y-m-d', strtotime($edu_to[$i] . '/01'));
        $user_education->school   = $edu_school[$i];
        $user_education->degree   = $edu_degree[$i];
        $user_education->major    = $edu_major[$i];  
        $user_education->minor    = $edu_minor[$i];
        $user_education->desc     = $edu_desc[$i];
        $user_education->save();
      }
      for ( $i = 0; $i < count($emp_from); $i++ ){
        $user_employment = new UserEmployment;
        $user_employment->user_id  = $user->id;
        $user_employment->from     = date('Y-m-d', strtotime($emp_from[$i] . '/01'));
        $user_employment->to       = date('Y-m-d', strtotime($emp_to[$i] . '/01'));
        $user_employment->company   = $emp_company[$i];
        $user_employment->position   = $emp_position[$i];
        $user_employment->desc    = $emp_desc[$i];  
        $user_employment->save();
      }
      foreach ( $languages as $language ){
        DB::table('users_languages')->insert(
          ['user_id' => $user->id, 'lang_id' => Language::where('name', $language)->first()->id]
        );
      }
      $user->profile->title = $request->input('pro_title');
      $user->profile->rate = $request->input('pro_rate');
      $user->profile->desc = $request->input('pro_description');
      $user->profile->share = intval($request->input('profile_history'));
      $user->profile->save();
    }
    $selected_skills = [];
    foreach ($user->skills as $skill) {
      $selected_skills[] = $skill->id;
    }
    $selectedLanguages = array();

    for ( $i = 0; $i < count($user->languages); $i++ ){
      $selectedLanguages[] = $user->languages[$i]->name;
    }


    
    if ($user->isFreelancer()) {
      return view('pages.freelancer.user.my_profile', [
        'page'              => 'freelancer.user.my_profile',
        'user'              => $user,
        'skills'            => Skill::all(),
        'selectedLanguages' => $selectedLanguages,
        'selected_skills'   => $selected_skills,
        'error'             => isset($error) ? $error : null,
      ]);
    }
    else if ($user->isBuyer()) {
      return view('pages.buyer.user.my_profile', [
        'page' => 'buyer.user.my_profile',
        'user' => $user,
        'selectedLanguages' => $selectedLanguages,
        'error' => isset($error) ? $error : null,
      ]);
    }
  }

  /**
   * user/my-portfolio
   *
   * @author Brice
   * @param  Request $request
   * @return Response
   */
  public function my_portfolio(Request $request)
  {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }

    //
    $action = $request->input("portfolio_action");

    if ( $action == "0" ) {
      //portfolio image upload
      $portfolio_img = $request->file('portfolio_img_src');
      $portfolio_temp_path = portfolioPath($user->id, 0, '');
      $portfolio_temp_dir = dirname($portfolio_temp_path);
      $filename = pathinfo($portfolio_temp_path, PATHINFO_BASENAME);

      $file = $portfolio_img->move($portfolio_temp_dir, $filename);
      //get the tempImage size(width, height)
      $tempImage = new SimpleImage($portfolio_temp_path);
      $tempImage->resizeToWidth(400);
      $tempImage->save($portfolio_temp_path);
      $tempImageInfo = $tempImage->original_info;

      return response()->json([
          'success'   => true,
          'imgUrl'    => portfolioUrl($user, '', true),
      ]);
    } elseif ($action == "1" || $action == "3") {
      //portfolio add or edit : 1, if with uploaded file, then 3. 
      $user_portfolio = new UserPortfolio;
      
      $portfolio_id = $request->input("portfolio_id");
      if ($portfolio_id != 0) {
        $user_portfolio->id = $portfolio_id;
      }
      
      $user_portfolio->user_id  = $user->id;
      $user_portfolio->title    = $request->input("portfolio_title");
      $user_portfolio->url      = $request->input("portfolio_url");
      $user_portfolio->cat_id   = $request->input("portfolio_category");
      $user_portfolio->save();

      if ($action == "3") {
        $portfolio_temp_path = portfolioPath($user->id, 0, '');
        $portfolio_temp_dir = dirname($portfolio_temp_path);
        $tempImage = new SimpleImage($portfolio_temp_path);
        $portfolio_path = portfolioPath($user->id, $user_portfolio->id, '');
        $tempImage->save($portfolio_path);
      }
      
      return response()->json([
          'success'   => true,
          'portfolio_id'    => $portfolio_id,
          'imgUrl'    => portfolioUrl($user, $user_portfolio->id),
      ]);
    } elseif ($action == "2") {
      //portfolio remove
      try {
        $portfolio_id = $request->input("portfolio_id");
        UserPortfolio::where("id", $portfolio_id)
                     ->delete();
        $portfolio_path = portfolioPath($user->id, $portfolio_id, '');
        if (file_exists($portfolio_path)){
          unlink($portfolio_path);
        }
        return response()->json([
            'success'   => true,
            'portfolio_id' => $portfolio_id,
        ]);  
      } catch (Exception $e){
        return response()->json([
            'success'   => false
        ]);
      }
      
    }

    
  }
  /**
   * user/account
   *
   * @author Ri Chol Min
   * @param  Request $request
   * @return Response
   */

  public function account(Request $request)
  {
    $user = Auth::user();

    if (!$user) {
      return redirect()->route('user.login');
    }

    // If user input info
    if ($request->isMethod('post')) {
      $credential = [
        'email' => $user->email,
        'password' => $request->input('old_password')
      ];

      if (Auth::validate($credential)) {
        $user->password = bcrypt($request->input('new_password'));
        $user->save();
      }else{
        return view('pages.freelancer.user.account', [
          'page' => 'freelancer.user.account',
          'user' => $user,
          'error' => 'Old password is mismatch. Retry again.',
        ]);
      }
    }
    
    if ($user->isFreelancer()) {
      return view('pages.freelancer.user.account', [
        'page' => 'freelancer.user.account',
        'user' => $user,
        'error' => isset($error) ? $error : null,
      ]);
    }
    else if ($user->isBuyer()) {
      return view('pages.buyer.user.account', [
        'page' => 'buyer.user.account',
        'user' => $user,
        'error' => isset($error) ? $error : null,
      ]);
    }
  }

  /**
   * user/affiliate
   *
   * @author brice
   * @param  Request $request
   * @return Response
   */

  public function affiliate(Request $request)
  {
    $user = Auth::user();
    // If user input info
    if ($request->isMethod('post')) {

    }
    
    $affiliate = UserAffiliate::where('user_id', $user->id)->first();
    $affiliate_name = "";
    $affiliate_percent = "";
    $affiliate_duration = "";
    $affiliate_created_at = "";

    if (count($affiliate) > 0) {
      $affiliate_user = User::where('id', $affiliate->affiliate_id)->first();
      $affiliate_name = $affiliate_user->fullname();
      $affiliate_percent = $affiliate->percent;
      $affiliate_duration = $affiliate->duration;
      $affiliate_created_at = $affiliate->created_at;
    }

    $url = urlencode(base64_encode($user->id));
    if ($user->isBuyer()) {
      return view('pages.buyer.user.affiliate', [
        'page' => 'buyer.user.affiliate',
        'user' => $user,
        'url'  => $url,
        'error' => isset($error) ? $error : null,
      ]);
    } else {
      return view('pages.freelancer.user.affiliate', [
        'page' => 'freelancer.user.affiliate',
        'user' => $user,
        'url'  => $url,
        'affiliate_name'  => $affiliate_name,
        'affiliate_percent'    => $affiliate_percent,
        'affiliate_duration'    => $affiliate_duration,
        'affiliate_created_at'    => $affiliate_created_at,
        'error' => isset($error) ? $error : null,
      ]);  
    }
    
  }

  /**
   * user/finance
   *
   * @author Ri Chol Min
   * @param  Request $request
   * @return Response
   */

  public function finance(Request $request)
  {
    // If user input info
    if ($request->isMethod('post')) {

    }

    return view('pages.freelancer.user.finance', [
      'page' => 'freelancer.user.finance',
      'error' => isset($error) ? $error : null,
    ]);
  }


  /**
   * Contact Info Page (user/contact-info)
   *
   * @author nada
   * @since Jan 18, 2016
   * @version 1.0
   * @param  Request $request
   * @return Response
   */
  public function contact_info(Request $request)
  {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }

    // If user input info
    if ($request->isMethod('post')) {
      $user->contact->country_code  = $request->input('country');
      $user->contact->city          = $request->input('city');
      $user->contact->address       = $request->input('address');
      $user->contact->address2      = $request->input('address2');
      $user->contact->zipcode       = $request->input('zipcode');
      $user->contact->phone         = $request->input('phone');

      // Flash data to the session.
      $request->flashOnly('country', 'city', 'address', 'address2', 'zipcode', 'phone');

      $user->contact->save();
    }

    if ($user->isFreelancer()) {
      return view('pages.freelancer.user.contact_info', [
        'page' => 'freelancer.user.contact_info',
        'user' => $user,
        'error' => isset($error) ? $error : null,
      ]);
    }
    else if ($user->isBuyer()) {
      return view('pages.buyer.user.contact_info', [
        'page' => 'buyer.user.contact_info',
        'user' => $user,
        'error' => isset($error) ? $error : null,
      ]);
    }
  }

  /**
   * Show user proifle, it depends to the user's setting to show,
   * refrence to share field on user_profiles table.
   * Note that allow only freelancers.
   *
   * @param  integer $user_id User id
   * @return Response
   */
  public function profile($user_id = 0)
  {
    $user = Auth::user();
    if (!$user) {
      return redirect()->route('user.login');
    }
    
    if ( !$user_id ) {
      abort(404);
    }

    $user = User::find($user_id);
    
    if (!$user) {
      abort(404);
    }

    $user->sc = $user->totalScore();
    $portfolio_list = UserPortfolio::getPortfolio($user->id);
    $categories = UserPortfolio::getCategories($user->id);
    $focus_user = User::find($user->id);
//dd(trans('profile.message.No_Feedback_Yet'));
    $success_percent = $user->successPercent();

    return view('pages.freelancer.user.profile', [
      'page' => 'freelancer.user.profile',
      'user' => $user,
      'success_percent' => $success_percent,
      'portfolio_list'    => $portfolio_list,
      'categories'        => $categories,
      'focus_user'        => $focus_user,
    ]);
  }
}
