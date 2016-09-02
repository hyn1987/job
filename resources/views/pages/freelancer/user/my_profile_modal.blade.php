<?php
/**
 * My Profile Modal Page (user/my-profile)
 *
 * @author  - Brice
 */
use Wawjob\Project;
use Wawjob\Category;
?>
<!-- Modal -->
    <div class="modal fade" id="skill_select_dlg" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('profile.modal.select_skills') }}</h4>
                </div>
                <div class="modal-body">
                    <select name="language" multiple="true" id="select_skill" class="form-control">
                        @foreach ($skills as $skill)
                            <option value="{{$skill->id}}" <?php if(in_array($skill->id, $selected_skills)){?> selected <?php } ?> >{{ parse_multilang($skill->name, App::getLocale())}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="add_skill" class="btn btn-primary">{{ trans('common.ok') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="LanguageSelect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('profile.modal.select_languages') }}</h4>
                </div>
                <div class="modal-body">
                    <select name="language" multiple="true" id="SelectLanguage" class="form-control">
                        @foreach ($languages as $language)
                            <option value="{{$language->name}}" <?php if(in_array($language->name, $selectedLanguages)){?> selected <?php } ?> >{{$language->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" id="addLanguage" class="btn btn-primary">{{ trans('common.ok') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EducationSelect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('profile.modal.add_education') }}</h4>
                </div>              
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.from') }}:</div>
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
                            <div class="pre-summary">{{ trans('profile.modal.to') }}:</div>
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
                            <div class="pre-summary">{{ trans('profile.modal.school') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduSchool" name="edu_school" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.degree') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduDegree" name="edu_degree" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.major') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduMajor" name="edu_major" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.minor') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduMinor" name="edu_minor" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.description') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <textarea data-required="1" has-valuable-data="yes" class="form-control full-option" id="EduDesc" name="edu_desc" ></textarea>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                </div>
                <div class="alert alert-danger display-hide">
                    You must input all fields.
                </div>                
                <div class="modal-footer">
                    <button type="button" id="addEducation" onclick="addEducationHistory();" class="btn btn-primary">{{ trans('common.ok') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EmployeeHistorySelect" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('profile.modal.add_employee_history') }}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.from') }}:</div>
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
                            <div class="pre-summary">{{ trans('profile.modal.to') }}:</div>
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
                            <div class="pre-summary">{{ trans('profile.modal.company') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EmpCompany" name="emp_company" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>                
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.position') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="EmpPosition" name="emp_position" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.description') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                                <textarea data-required="1" has-valuable-data="yes" class="form-control full-option" id="EmpDesc" name="emp_desc" ></textarea>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>                              
                </div>
                <div class="alert alert-danger display-hide">
                    You must input all fields.
                </div>                
                <div class="modal-footer">
                    <button type="button" id="addEducation" class="btn btn-primary">{{ trans('common.ok') }}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Portfolio" tabindex="-1" role="dialog" aria-labelledby="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="">{{ trans('profile.modal.my_portfolio') }}</h4>
                </div>
                <form id="form_user_portfolio" name="form_user_portfolio" class="form-horizontal" method="post" action="{{ route('user.my_portfolio')}}" enctype="multipart/form-data">
     						<input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="portfolio_id" id="portfolio_id" value="" />
                <input type="hidden" name="portfolio_action" id="portfolio_action" value="0" />
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.title') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                            		<i class="fa tooltips" data-original-title="please write the title for portfolio."></i>
                                <input type="text" data-required="1" has-valuable-data="yes" class="form-control full-option" id="portfolio_title" name="portfolio_title" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.category') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                            		<i class="fa tooltips" data-original-title="please select the category for portfolio."></i>
                                <select class="job-category bs-select form-control full-option" id="portfolio_category" name="portfolio_category" data-required="1" aria-required="true">
                                  <option value="">{{ trans('profile.modal.select_category_ph') }}</option>
                                  @foreach(Category::projectCategories() as $id=>$category1)
                                  <optgroup label="{{ parse_multilang($category1['name'], App::getLocale())}}">
                                    @if(is_array($category1['children']))
                                    @foreach($category1['children'] as $id=>$category2)
                                    <option value="{{ $category2['id'] }}" {{ old('category')==$category2['id']? "selected" : ""  }} >{{ parse_multilang($category2['name'], App::getLocale())}}</option>
                                    @endforeach
                                    @endif
                                  </optgroup>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.url') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                            		<input type="text" data-required="1" data-rule-url="true" has-valuable-data="yes" class="form-control full-option" id="portfolio_url" name="portfolio_url" value="">
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <div class="pre-summary">{{ trans('profile.modal.image') }}:</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="input-icon right">
                            		<i class="fa tooltips" data-original-title="please upload the image for portfolio."></i>
                                <input type="file" data-required="1" has-valuable-data="yes" class="form-control full-option" id="portfolio_img_src" name="portfolio_img_src" title="Please upload the JPEG image for portfolio (400 x 300 pixels)." value="">
                            </div>
                            <div class="portfolio-img">
                            	<img src="/assets/images/common/no-image.png" alt="image for portfolio"/>
                            </div>
                        </div>
                        <div class="clear-div"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="addLanguage" class="btn btn-primary">{{ trans('profile.action.save') }}</button>
                    <button type="button" class="btn btn-default portfolio-close" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
