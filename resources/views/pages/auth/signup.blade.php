@extends('layouts/auth/signup')

@section('content')
  <div id="main_body" class="container-fluid">
    <div class="text-center content_1">{{ trans('page.auth.signup.get_started') }}</div>
    <div class="text-center content_1">{{ trans('page.auth.signup.what_you_are_looking_for') }}</div>
    <div id="main_panel">
        <div class="left choose-type">
            <div class="block">
                <div class="icon"><img src="/assets/images/pages/auth/buyer.png"></div>
                <div class="text-center content_2">{{ trans('page.auth.signup.hire_a_freelancer') }}</div>
                <div class="text-center content_3">{!! trans('page.auth.signup.find_collaborate') !!}</div>
                <div class="col-sm-6 col-sm-offset-3">
                    <form id="UserTypeForm" method="post" action="{{ route('user.signup') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" id="UserType" name="UserType" value="B">
                      <button type="submit" class="button btn btn-primary">{{ trans('page.auth.signup.hire') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="div-line"></div>
        <div class="right choose-type">
            <div class="block">
                <div class="icon"><img src="/assets/images/pages/auth/freelancer.png"></div>
                <div class="text-center content_2">{{ trans('page.auth.signup.looking_for_online_work') }}</div>
                <div class="text-center content_3">{!! trans('page.auth.signup.find_freelance_projects') !!}</div>
                <div class="col-sm-6 col-sm-offset-3">
                    <form id="UserTypeForm" method="post" action="{{ route('user.signup') }}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="hidden" id="UserType" name="UserType" value="F">
                      <button type="submit" class="button btn btn-primary">{{ trans('page.auth.signup.work') }}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="clear-div"></div>
    </div>
</div>
@endsection