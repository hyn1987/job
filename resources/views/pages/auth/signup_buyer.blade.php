@extends('layouts/auth/signup')

@section('content')
<div id="main_body" class="container-fluid">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="summary-block">
                <div class="text-center title_1">{{ trans('page.auth.signup.buyer.title')}}</div>
                <div class="content-block">
                    <div class="text-center content_12">{{ trans('page.auth.signup.buyer.looking_work')}}? <a href="{{ route('user.signup.freelancer') }}">{{ trans('page.auth.signup.buyer.as_freelancer')}}</a></div>
                    <div class="text-center content_12">{{ trans('page.auth.signup.with')}} <a href="javascript:void(0);" onclick="gotoFacebookSignUp();">{{ trans('common.social.facebook')}}</a><span>, </span><a href="javascript:void(0);" onclick="gotoLinkedinSignUp();">{{ trans('common.social.linkedin')}}</a><span>, <span>or <a href="javascript:void(0);" onclick="gotoGoogleSignUp();">{{ trans('common.social.google')}}</a>
                    </div>
                </div>
            </div>
            <div id="register_panel">
                <form id="frm_register" method="post" action="{{ route('user.signup.buyer') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>
                        <input type="hidden" name="UserType" id="ele_usertype" value="2">

                        <div class="row">
                            {{-- First name --}}
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="ele_first_name" name="first_name" autocomplete="off" data-rule-required="true">
                                    <label for="ele_first_name">{{ trans('auth.first_name')}}</label>
                                </div>
                            </div>

                            {{-- Last name --}}
                            <div class="col-sm-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="ele_last_name" name="last_name" autocomplete="off" data-rule-required="true">
                                    <label for="ele_last_name">{{ trans('auth.last_name')}}</label>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" class="form-control" id="ele_email" name="email" autocomplete="off" data-rule-required="true" data-rule-email="true">
                            <label for="ele_email">{{ trans('auth.email')}}</label>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Country --}}
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <select name="country" id="ele_country" class="form-control select2 edited" data-rule-required="true">
                                        <option value="">&nbsp;</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->charcode }}"{{ $country->charcode == $defaults['country'] ? ' selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="ele_country">{{ trans('auth.country')}}</label>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                {{-- Username --}}
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="ele_username" name="username" value="{{ old('username') }}" autocomplete="off">
                                    <label for="ele_username">{{ trans('auth.username')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Password --}}
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="password" class="form-control" id="ele_password" name="password" placeholder="{{ trans('auth.password')}}" autocomplete="off" data-rule-required="true" data-rule-minlength="8">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                {{-- Confirm Password --}}
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="password" class="form-control" id="ele_password2" placeholder="{{ trans('auth.confirm_password')}}" autocomplete="off" data-rule-required="true" data-rule-equalto="#ele_password">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                {{-- Question --}}
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <select name="selectquestion" id="ele_question" class="select2 form-control edited">
                                        @foreach ($how_hear_options as $label => $v)
                                        <option value="{{ $v }}"{{ $v == $defaults['how_hear'] ? ' selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <label for="ele_question">{{ trans('auth.hear_about_wawjob')}}</label>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                {{-- Re-captcha --}}
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" class="form-control" id="ele_captcha" name="captcha" autocomplete="off">
                                    <label for="ele_question">{{ trans('auth.type_letters_below')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-actions">
                            <button type="submit" class="btn btn-primary center-align">{{ trans('auth.get_started')}}</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection