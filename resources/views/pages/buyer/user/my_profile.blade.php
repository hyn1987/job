<?php
/**
 * My Profile Page (user/my-profile)
 *
 * @author  - Ri Chol Min
 */
?>
@extends('layouts/buyer/user')

@section('content')
<div class="title-section">
  <span class="title">My Profile</span>
  <div class="right-action-link">
    <a href="#">Edit</a>
  </div>
</div>
<div class="page-content-section buyer-user-page">
    <form id="MyProfileForm" class="form-horizontal" method="post" action="{{ route('user.my_profile')}}" enctype="multipart/form-data">
        @if (isset($error))
            <div class="has-error"><span class="help-block">{{ $error }}</span></div>
        @endif
        <fieldset>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">Title:</div>
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
                    <div class="pre-summary">Work Rate:</div>
                </div>
                <div class="col-sm-9">
                    <div class="input-field">
                        <div class="input-icon right">
                            <input type="text" data-required="1" class="form-control" id="Profile_Rate" name="pro_rate" placeholder="0.00" value="{{ old('pro_rate') ? old('pro_rate') :  (($user->profile && $user->profile->rate != null)? $user->profile->rate : "&nbsp;") }}"><span class="rate-unit">$/hr</span>                            
                        </div>
                        <div class="clear-div"></div>
                        <div class="help-block">e.g: 0.01~999.99 </div>
                    </div>
                    <label class="label-field profile-rate">{{ ($user->profile && $user->profile->rate != null)? $user->profile->rate : "&nbsp;" }}</label><span class="label-field rate-unit">$/hr</span>
                </div>
                <div class="clear-div"></div>                          
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">Description:</div>
                </div>
                <div class="col-sm-9">
                    <div class="input-field">
                        <div class="input-icon right">
                            <textarea data-required="1" class="form-control" id="Profile_Description" name="pro_description" placeholder="Description">{{ old('pro_description') ? old('pro_description') : (($user->profile && $user->profile->desc != null)? $user->profile->desc : "") }}</textarea>
                        </div>
                    </div>
                    <label class="label-field pro-description-label">{{ ($user->profile && $user->profile->desc != null)? $user->profile->desc : "&nbsp;" }}</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">Language:</div>
                </div>
                <div class="col-sm-5">
                    <div class="input-icon right">                        
                        @foreach ($user->languages as $language)
                            <label class="divided" for="Language">{{ $language->name }}</label>
                        @endforeach
                        <input type="hidden" value="" id="Profile_Language" name="profile_language">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#LanguageSelect" href="javascript:void(0);">+Add</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">Education:</div>
                </div>
                <div class="col-sm-5">
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
                                    <a class="remove-button btn btn-primary action-btn" href="javascript:void(0);" onclick="removeEducation(this);">Remove</a>
                                    <a class="edit-button btn btn-primary action-btn" href="javascript:void(0);" onclick="editEducation(this);">Edit</a>
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
                <div class="col-sm-4">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#EducationSelect" onclick="addEducation();" href="javascript:void(0);">+Add</a>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">Employee History:</div>
                </div>
                <div class="col-sm-5">
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
                                <a class="remove-button btn btn-primary action-btn" href="javascript:void(0);" onclick="removeEmployment(this);">Remove</a>
                                <a class="edit-button btn btn-primary action-btn" href="javascript:void(0);" onclick="editEmployment(this);">Edit</a>
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
                <div class="col-sm-4">
                    <div class="input-field">
                        <a data-toggle="modal" data-target="#EmployeeHistorySelect" href="javascript:void(0);" onclick="addEmployeeHistory();">+Add</a>
                    </div>
                </div>                           
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <div class="pre-summary">Sharing Profile:</div>
                </div>
                <div class="col-sm-9">                    
                    <div class="input-field">
                        <div class="group clear-div">
                            <div class="col-sm-2">
                                <input type="radio" data-required="1" class="form-control" value="0" id="Profile_History_1" name="profile_history" {{ ( $user->profile && $user->profile->share !== null && $user->profile->share == 0 ) ? "checked" : "" }}>
                            </div>
                            <div class="col-sm-10">
                                 <label for="Profile_History1">Public(Everyone)</label>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                        <div class="group clear-div">
                            <div class="col-sm-2">
                                <input type="radio" data-required="1" class="form-control" value="1" id="Profile_History_2" name="profile_history" {{ ( $user->profile && $user->profile->share !== null && $user->profile->share == 1 ) ? "checked" : "" }}>
                            </div>
                            <div class="col-sm-10">
                                 <label for="Profile_History2">Protected(Users Only)</label>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                        <div class="group clear-div">
                            <div class="col-sm-2">
                                <input type="radio" data-required="1" class="form-control" value="2" id="Profile_History_3" name="profile_history" {{ ( $user->profile && $user->profile->share !== null && $user->profile->share == 2 ) ? "checked" : "" }}>
                            </div>
                            <div class="col-sm-10">
                                 <label for="Profile_History3">Privated(Buyers)</label>
                            </div>
                            <div class="clear-div"></div>
                        </div>
                    </div>
                    <label class="label-field pro-description-label">
                    @if ( $user->profile && $user->profile->share != null && $user->profile->share == 1 )
                        Protected(Users Only)
                    @elseif ( $user->profile && $user->profile->share != null && $user->profile->share == 2 )
                        Privated(Buyers)
                    @else
                        Public(Everyone)
                    @endif
                    </label>
                </div>
            </div>
            <div class="alert alert-danger display-hide">
              You have some form errors. Please check above.
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group form-actions action-group">
                <div class="col-md-10 text-right">
                    <button type="submit" class="btn btn-primary center-align">Save Changes</button>
                </div>
            </div>
        </fieldset>                                        
    </form>

    <!-- Modal -->
    <div class="modal fade" id="LanguageSelect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Select Language</h4>
                </div>
                <div class="modal-body">
                    <select name="country" multiple="true" id="SelectLanguage" class="form-control">
                        @foreach ($languages as $language)
                            <option value="{{$language->name}}" <?php if(in_array($language->code, $selectedLanguages)){?> selected <?php } ?> >{{$language->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addLanguage" class="btn btn-primary">Ok</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EducationSelect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Education</h4>
                </div>              
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">From:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <select name="fromyear" id="EduFromYear" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1950; $i < date("Y"); $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <select name="frommonth" id="EduFromMonth" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1; $i < 10; $i++)
                                        <option value="0{{$i}}">{{$i}}</option>
                                    @endfor
                                    @for ($i = 10; $i <= 12; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">To:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <select name="toyear" id="EduToYear" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1950; $i < date("Y"); $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <select name="tomonth" id="EduToMonth" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1; $i < 10; $i++)
                                        <option value="0{{$i}}">{{$i}}</option>
                                    @endfor
                                    @for ($i = 10; $i <= 12; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">School:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduSchool" name="edu_school" placeholder="School" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Degree:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduDegree" name="edu_degree" placeholder="Degree" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Major:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduMajor" name="edu_major" placeholder="Major" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Minor:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduMinor" name="edu_minor" placeholder="Minor" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Description:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <textarea data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduDesc" name="edu_desc" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                </div>
                <div class="alert alert-danger display-hide">
                    You must input all fields.
                </div>                
                <div class="modal-footer">
                    <button type="button" id="addEducation" onclick="addEducationHistory();" class="btn btn-primary">Ok</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EmployeeHistorySelect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Employee History</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">From:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <select name="fromyear" id="EmpFromYear" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1950; $i < date("Y"); $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <select name="frommonth" id="EmpFromMonth" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1; $i < 10; $i++)
                                        <option value="0{{$i}}">{{$i}}</option>
                                    @endfor
                                    @for ($i = 10; $i <= 12; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">To:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <select name="toyear" id="EmpToYear" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1950; $i < date("Y"); $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <select name="tomonth" id="EmpToMonth" has-valuable-data="yes" class="form-control date-option">
                                    @for ($i = 1; $i < 10; $i++)
                                        <option value="0{{$i}}">{{$i}}</option>
                                    @endfor
                                    @for ($i = 10; $i <= 12; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Company:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EmpCompany" name="emp_company" placeholder="Company" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>                
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Position:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EmpPosition" name="emp_position" placeholder="Position" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">Description:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <textarea data-required="1" has-valuable-data="yes" class="form-control full-option" id="EmpDesc" name="emp_desc" placeholder="Description"></textarea>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>                              
                </div>
                <div class="alert alert-danger display-hide">
                    You must input all fields.
                </div>                
                <div class="modal-footer">
                    <button type="button" id="addEducation" class="btn btn-primary">Ok</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection