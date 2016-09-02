<?php namespace Wawjob\Http\Controllers;

use Wawjob\Http\Requests;
use Wawjob\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's front page for users.
	| It is just here to get your app started!
	|
	*/

	/**
	 * Show the application front page to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
    	$user = Auth::user();
    	if ($user && $user->isAdmin()) {
    		return redirect()->route('admin.dashboard');
        }

		return view('pages.home', [
			'page' => 'home',
		]);
	}
	
	/**
	 * Show the about page.
	 *
	 * @return Response
	 */
	public function about()
	{
    	return view('pages.about.index', [
			'page' => 'about.index',
		]);
	}
	/**
	 * Show the about/careers page.
	 *
	 * @return Response
	 */
	public function careers()
	{
    	return view('pages.about.careers', [
				'page' => 'about.careers',
			]);
	}


	/**
	 * Show the about/team page.
	 *
	 * @return Response
	 */
	public function team()
	{
    	return view('pages.about.team', [
			'page' => 'about.team',
		]);
	}
	/**
	 * Show the about/board page.
	 *
	 * @return Response
	 */
	public function board()
	{
    	return view('pages.about.board', [
			'page' => 'about.board',
		]);
	}
/**
	 * Show the about/press page.
	 *
	 * @return Response
	 */
	public function press()
	{
    	return view('pages.about.press', [
			'page' => 'about.press',
		]);
	}
/**
	 * Show the privacy policy page.
	 *
	 * @return Response
	 */
	public function privacy_policy()
	{
    	return view('pages.frontend.privacy_policy', [
				'page' => 'frontend.privacy_policy',
			]);
	}

	/**
	 * Show the terms of service page.
	 *
	 * @return Response
	 */
	public function terms_service()
	{
    	return view('pages.frontend.terms_service', [
				'page' => 'frontend.terms_service',
			]);
	}
}
