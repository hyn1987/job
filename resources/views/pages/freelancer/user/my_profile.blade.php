<?php
/**
 * My Profile Page (user/my-profile)
 *
 * @author  - Ri Chol Min
 */
?>
@extends('layouts/freelancer/user')

@section('content')
<div class="title-section">
  <span class="title">{{ trans('page.' . $page . '.title') }}</span>
  <div class="right-action-link">
    <a href="#" class="edit-action">{{ trans('user.action.edit') }}</a>
    <a href="#" class="cancel-action">{{ trans('user.action.cancel') }}</a>
  </div>
</div>
<div class="page-content-section freelancer-user-page">
    <form id="MyProfileForm" class="form-horizontal" method="post" action="{{ route('user.my_profile')}}" enctype="multipart/form-data">
        <input type="hidden" name="profile_skill" id="profile_skill" value="">

        {{ show_messages() }}

        <fieldset>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.title') }}:</div>
                </div>
                <div class="col-sm-9">
                    <div class="input-field">
                        <div class="input-icon right">
                            <input type="text" data-required="1" class="form-control" id="Profile_Title" name="pro_title" placeholder="Title" value="{{ old('pro_title') ? old('pro_title') : (($user->profile && $user->profile->title != null)? $user->profile->title : "") }}">
                        </div>
                    </div>
                    <label class="label-field">{{ ($user->profile && $user->profile->title != null)? $user->profile->title : "&nbsp;" }}</label>
                </div>
                <div class="clear-div"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.work_rate') }}:</div>
                </div>
                <div class="col-sm-9">
                    <div class="input-field">
                        <div class="input-icon right">
                            <input type="text" data-required="1" class="form-control" id="Profile_Rate" name="pro_rate" placeholder="0.00" value="{{ old('pro_rate') ? old('pro_rate') :  (($user->profile && $user->profile->rate != null)? $user->profile->rate : "&nbsp;") }}"><span class="rate-unit">$/{{ trans('common.hr') }}</span>                            
                        </div>
                        <div class="clear-div"></div>
                        <div class="help-block">e.g: 0.01~999.99 </div>
                    </div>
                    <label class="label-field profile-rate">{{ ($user->profile && $user->profile->rate != null)? $user->profile->rate : "&nbsp;" }}</label><span class="label-field rate-unit">$/{{ trans('common.hr') }}</span>
                </div>
                <div class="clear-div"></div>                          
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.description') }}:</div>
                </div>
                <div class="col-sm-9">
                    <div class="hide">
                        <div class="input-icon right">
                            <textarea data-required="1" class="form-control" id="Profile_Description" name="pro_description" placeholder="Description">{{ old('pro_description') ? old('pro_description') : (($user->profile && $user->profile->desc != null)? $user->profile->desc : "") }}</textarea>
                        </div>
                    </div>
                    <label class="pro-description-label" contenteditable="false">{!! ($user->profile && $user->profile->desc != null)? str_replace(array("\r\n", "\r", "\n"), "<br />", $user->profile->desc) : "&nbsp;" !!}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.skill') }}:</div>
                </div>
                <div class="col-sm-8">
                    <div class="input-icon right skill-list">
                        @if ($user->skills)
                        @foreach ($user->skills as $skill)
                            <label class="divided" for="Skill"><span class="title" skill-id="{{$skill->id}}">{{ parse_multilang($skill->name, App::getLocale()) }}</span><span class="input-field icon icon-close"></span></label>
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#skill_select_dlg" href="#skill_select_dlg">+{{ trans('profile.action.add') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.language') }}:</div>
                </div>
                <div class="col-sm-8">
                    <div class="input-icon right language-list">
                        @if ($user->languages)
                        @foreach ($user->languages as $language)
                            <label class="divided" for="Language"><span class="title">{{ $language->name }}</span><span class="input-field icon icon-close"></span></label>
                        @endforeach
                        @endif
                        <input type="hidden" value="" id="Profile_Language" name="profile_language">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#LanguageSelect" href="#LanguageSelect">+{{ trans('profile.action.add') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.education') }}:</div>
                </div>
                <div class="col-sm-8">
                    <div class="input-icon right">
                        @foreach ($user->educations as $education)
                            <?php $num = 1; ?>
                            <div class="education-block" block-number="{{$num}}">
                                <label for="EduFromYear">{{ ($education && $education->from != null)? date("Y", strtotime($education->from)) : "&nbsp;" }}</label>/<label for="EduFromMonth">{{ ($education && $education->from != null)? date("m", strtotime($education->from)) : "&nbsp;" }}</label>~
                                <label for="EduToYear">{{ ($education && $education->to != null)? date("Y", strtotime($education->to)) : "&nbsp;" }}</label>/<label for="EduToMonth">{{ ($education && $education->to != null)? date("m", strtotime($education->to)) : "&nbsp;" }}</label><br>
                                <label for="EduSchool">{{ ($education && $education->school != null)? $education->school : "&nbsp;" }}</label><br>
                                <label for="EduDegree">{{ ($education && $education->degree != null)? $education->degree : "&nbsp;" }}</label><br>
                                <label for="EduMajor">{{ ($education && $education->major != null)? $education->major : "&nbsp;" }}</label><br>
                                <label for="EduMinor">{{ ($education && $education->minor != null)? $education->minor : "&nbsp;" }}</label><br>
                                <label for="EduDesc">{{ ($education && $education->desc != null)? $education->desc : "&nbsp;" }}</label><br>
                                <div class="action-buttons">
                                    <a class="remove-button btn btn-primary action-btn" href="javascript:void(0);" onclick="removeEducation(this);">{{ trans('profile.action.remove') }}</a>
                                    <a class="edit-button btn btn-primary action-btn" href="javascript:void(0);" onclick="editEducation(this);">{{ trans('profile.action.edit') }}</a>
                                    <div class="clear-div"></div>
                                </div>
                            </div>
                            <?php $num++; ?>
                        @endforeach
                        <input type="hidden" value="" id="Profile_Edu_School" name="pro_edu_school">
                        <input type="hidden" value="" id="Profile_Edu_Degree" name="pro_edu_degree">
                        <input type="hidden" value="" id="Profile_Edu_Major" name="pro_edu_major">
                        <input type="hidden" value="" id="Profile_Edu_Minor" name="pro_edu_minor">
                        <input type="hidden" value="" id="Profile_Edu_Desc" name="pro_edu_desc">
                        <input type="hidden" value="" id="Profile_Edu_From" name="pro_edu_from">
                        <input type="hidden" value="" id="Profile_Edu_To" name="pro_edu_to">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#EducationSelect" onclick="addEducation();" href="#EducationSelect">+{{ trans('profile.action.add') }}</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.employment_history') }}:</div>
                </div>
                <div class="col-sm-8">
                    <div class="input-icon right">
                        @foreach ($user->employments as $employment)
                        <?php $num = 1; ?>
                        <div class="employee-history-block" block-number="{{$num}}">
                            <label for="EmpFromYear">{{ ($employment && $employment->from != null)? date("Y", strtotime($employment->from)) : "&nbsp;" }}</label>/<label for="EmpFromMonth">{{ ($employment && $employment->from != null)? date("m", strtotime($employment->from)) : "&nbsp;" }}</label>~
                            <label for="EmpToYear">{{ ($employment && $employment->to != null)? date("Y", strtotime($employment->to)) : "&nbsp;" }}</label>/<label for="EmpToMonth">{{ ($employment && $employment->to != null)? date("m", strtotime($employment->to)) : "&nbsp;" }}</label><br>
                            <label for="EmpCompany">{{ ($employment && $employment->company != null)? $employment->company : "&nbsp;" }}</label><br>
                            <label for="EmpPosition">{{ ($employment && $employment->position != null)? $employment->position : "&nbsp;" }}</label><br>
                            <label for="EmpDesc">{{ ($employment && $employment->desc != null)? $employment->desc : "&nbsp;" }}</label><br>
                            <div class="action-buttons">
                                <a class="remove-button btn btn-primary action-btn" href="javascript:void(0);" onclick="removeEmployment(this);">{{ trans('profile.action.remove') }}</a>
                                <a class="edit-button btn btn-primary action-btn" href="javascript:void(0);" onclick="editEmployment(this);">{{ trans('profile.action.edit') }}</a>
                                <div class="clear-div"></div>
                            </div>
                        </div>
                        <?php $num++; ?>
                        @endforeach
                        <input type="hidden" value="" id="Profile_Emp_Company" name="pro_emp_company">
                        <input type="hidden" value="" id="Profile_Emp_Position" name="pro_emp_position">
                        <input type="hidden" value="" id="Profile_Emp_Desc" name="pro_emp_desc">
                        <input type="hidden" value="" id="Profile_Emp_From" name="pro_emp_from">
                        <input type="hidden" value="" id="Profile_Emp_To" name="pro_emp_to">
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#EmployeeHistorySelect" href="#EmployeeHistorySelect" onclick="addEmployeeHistory();">+{{ trans('profile.action.add') }}</a>
                    </div>
                </div>                           
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.sharing_profile') }}:</div>
                </div>
                <div class="col-sm-9">                    
                    <div class="input-field">
                        <div class="group clear-div">
                            <div class="col-sm-12">
                                <input type="radio" data-required="1" class="form-control" value="0" id="Profile_History_1" name="profile_history" {{ ( $user->profile && $user->profile->share !== null && $user->profile->share == 0 ) ? "checked" : "" }}>
                                <label for="Profile_History_1">{{ trans('profile.sharing.public') }}</label>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                        <div class="group clear-div">
                            <div class="col-sm-12">
                                <input type="radio" data-required="1" class="form-control" value="1" id="Profile_History_2" name="profile_history" {{ ( $user->profile && $user->profile->share !== null && $user->profile->share == 1 ) ? "checked" : "" }}>
                                <label for="Profile_History_2">{{ trans('profile.sharing.protected') }}</label>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                        <div class="group clear-div">
                            <div class="col-sm-12">
                                <input type="radio" data-required="1" class="form-control" value="2" id="Profile_History_3" name="profile_history" {{ ( $user->profile && $user->profile->share !== null && $user->profile->share == 2 ) ? "checked" : "" }}>
                                <label for="Profile_History_3">{{ trans('profile.sharing.private') }}</label>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                    </div>
                    <label class="label-field">
                    @if ( $user->profile && $user->profile->share != null && $user->profile->share == 1 )
                        {{ trans('profile.sharing.protected') }}
                    @elseif ( $user->profile && $user->profile->share != null && $user->profile->share == 2 )
                        {{ trans('profile.sharing.private') }}
                    @else
                        {{ trans('profile.sharing.public') }}
                    @endif
                    </label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">{{ trans('profile.portfolio') }}:</div>
                </div>
                <div class="col-sm-8">
                    <div class="portfolio-list">
                        @forelse ($user->portfolios as $portfolio)
                          @include('pages.freelancer.user.my_portfolio')
                        @empty
                        <div class="no-portfolio">{{ trans('profile.portfolio_add_desc') }}</div>
                        @endforelse
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#Portfolio" href="#Portfolio" class="portfolio_add" portfolio-id="0">+{{ trans('profile.action.add') }}</a>
                    </div>
                </div>                           
            </div>
            
            <div class="alert alert-danger display-hide">
              You have some form errors. Please check above.
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group form-actions action-group">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary center-align input-field">{{ trans('user.action.save') }}</button>&nbsp;&nbsp;
                    <a href="{{ route('user.profile', $user->id) }}" class="label-field" target="_blank">{{ trans('profile.view_as_others_see') }}</a>
                </div>
            </div>
        </fieldset>                                        
    </form>

    @include('pages.freelancer.user.my_profile_modal')
</div>
@endsection