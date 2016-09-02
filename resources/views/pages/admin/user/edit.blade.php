<?php
/**
   * Edit user data.
   *
   * @author Ray
   * @since March 6, 2016
   * @version 1.0 Edit user data page
   */
?>
@extends('layouts/admin/index')

@section('actions')
  
  <li><a href="javascript: history.go(-1)"><button type="button" class="btn-cancel btn btn-info btn-sm"><i class="fa fa-long-arrow-left"></i> Back</button></a></li>
  <li><button class="btn-save btn btn-primary btn-sm" type="button"><i class="fa fa-check"></i> Save</button></li>
  @if ($u && (in_array('user_buyer', $role_slugs) || in_array('user_freelancer', $role_slugs)))   
  <li><span class="dropdown view-more">
    <button class="dropdown-toggler btn btn-danger btn-sm" type="button" data-toggle="dropdown" aria-expanded="false">Go To <i class="fa fa-angle-down"></i></button>
    <ul class="dropdown-menu action-menu" role="menu">
      <li><a href="{{ route('admin.report.usertransaction', ['uid' => $u->id]) }}"><i class="fa fa-info"></i> Public Profile</a></li>
      @if (in_array('user_buyer', $role_slugs))
      <li><a href="{{ route('admin.job.list') }}?un={{ $u->username }}"><i class="fa fa-file-powerpoint-o"></i> Job Postings</a></li>
      <li><a href="{{ route('admin.contract.list') }}?buyer={{ $u->username }}"><i class="fa fa-magic"></i> Contracts</a></li>
      @elseif (in_array('user_freelancer', $role_slugs))
      <li><a href="{{ route('admin.contract.list') }}?lancer={{ $u->username }}"><i class="fa fa-magic"></i> Contracts</a></li>
      @endif
      <li><a href="{{ route('admin.report.usertransaction', ['uid' => $u->id]) }}"><i class="fa fa-dollar"></i> Transactions</a></li>
    </ul>
  </span></li>
  @endif
@endsection

@section('content')
<div class="row page-body">
  <div class="col-md-12">
    <div class="portlet block light with-bottom-padding">
      
      <div class="portlet-title">
        <div class="caption">
          <i class="fa fa-user"></i>{{ trans('page.admin.user.add.user_info') }}
        </div>
        <div class="tools">
          <a href="javascript:;" class="collapse"></a>
        </div>
      </div>

      <div class="portlet-body form">
        
        @include('layouts.admin.common_infos')
        @include('layouts.admin.common_errors')

        <!-- BEGIN FORM-->
        <form id="frm_user_edit" class="form-horizontal clearfix" action="{{ route('admin.user.update') }}" method="post" role="form">
          
          <!--
          <div class="navbar-form navbar-right">
              <a href="{{ route('admin.user.list') }}"><button type="button" class="btn-cancel btn btn-default">Back</button></a>
              <button type="submit" class="btn-save btn btn-primary"><i class="fa fa-check"></i> Save</button>
          </div>
          -->

          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="user_id" value="{{ $u ? $u->id : 0 }}">
          
          <div class="form-body clearfix">
            <!-- Credential Part -->
            <h3 class="form-section"><i class="fa fa-key"></i> {{ trans('page.admin.user.add.credential') }}</h3>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">{{ trans('page.admin.user.add.username') }}</label>
                  <div class="col-md-9">

                    <!-- USER NAME -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                      <input type="text" class="form-control" name="username" placeholder="{{ trans('page.admin.user.add.username') }}" value="{{ old('username') ? old('username') : ($u ? $u->username : '') }}" @if ($page == 'admin.user.edit') readonly @endif>
                    </div>

                  </div>
                </div>
              </div>
              <!--/span-->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">{{ trans('page.admin.user.add.email') }}</label>
                  <div class="col-md-9">  

                    <!-- USER EMAIL -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      <input type="text" class="form-control" name="email" placeholder="{{ trans('page.admin.user.add.email') }}" value="{{ old('email') ? old('email') : ($u ? $u->email : '') }}" @if ($page == 'admin.user.edit') readonly @endif>
                    </div>  

                  </div>
                </div>
              </div>
              <!--/span-->
            </div>
            <!--/row-->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">{{ trans('page.admin.user.add.password') }}</label>
                  <div class="col-md-9">

                    <!-- USER PASSWORD -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                      <!-- <span class="input-group-addon right"><i class="fa fa-check tooltips" data-original-title="You look OK!" data-container="body"></i></span> -->
                      <input type="password" class="form-control" name="password" placeholder="{{ trans('page.admin.user.add.password') }}">
                    </div>

                  </div>
                </div>
              </div>
              <!--/span-->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">{{ trans('page.admin.user.add.password_cfm') }}</label>
                  <div class="col-md-9">

                    <!-- CONFIRM PASSWORD -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-eye"></i></span>
                      <input type="password" class="form-control" name="password_cfm" placeholder="{{ trans('page.admin.user.add.password_cfm') }}">
                    </div>

                  </div>
                </div>
              </div>
              <!--/span-->
            </div>
            <!--/row-->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group hidden">
                  <label class="col-md-3 control-label">State</label>
                  <div class="col-md-9 {{ $edit_cell_class }}">    

                    <!-- USER STATUS -->                
                    <select class="form-control" name="status">
                      @foreach ($userStatusList as $status)
                        <option value="{{ $status }}"{{ $u && $u->status == $status ? ' selected' : '' }}>{{ trans('common.user.status.' . $status) }}</option>
                      @endforeach
                    </select>           

                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">User type</label> 
                  <div class="col-md-9 {{ $edit_cell_class }}">

                    <!-- USER TYPE -->
                    <select class="form-control" name="user_type">
                      @foreach ($userTypeList as $type)
                        <option value="{{ $type->id }}"{{ in_array($type->id, $role_ids) ? ' selected' : '' }}>{{ trans('common.user.types.' . $type->slug) }}</option>
                      @endforeach
                    </select>         

                  </div>
                </div>
              </div>
            </div>

            <h3 class="form-section"><i class="fa fa-edit"></i> {{ trans('page.admin.user.add.contact_info') }}</h3>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">First Name</label>
                  <div class="col-md-9">

                    <!-- FIRST NAME -->
                    <div class="{{ $edit_cell_class }}">
                      <input type="text" class="form-control" placeholder="First Name" value="{{ $u && $u->contact ? $u->contact->first_name : '' }}" name="first_name"></input>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Last Name</label>
                  <div class="col-md-9">

                    <!-- LAST NAME -->
                    <div class="{{ $edit_cell_class }}">
                      <input type="text" class="form-control" placeholder="Last Name" value="{{ $u && $u->contact ? $u->contact->last_name : '' }}" name="last_name"></input>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            
            <div class="row"> 
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Birthday</label>
                  <div class="col-md-9">

                    <!-- BIRTHDAY -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" class="form-control date-picker" placeholder="Birthday" value="{{ $u && $u->contact ? $u->contact->birthday : '' }}" name="birthday"></input>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="form-group">
                  <label class="col-md-3 control-label">Gender</label>
                  <div class="col-md-9 {{ $edit_cell_class }}">

                    <!-- GENDER -->
                    <select class="form-control" name="gender">
                      <option value="0">Male</option>
                      <option value="1">Female</option>
                    </select>                  

                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Country</label>
                  <div class="col-md-9 {{ $edit_cell_class }}">

                    <!-- COUNTRY -->
                    <select class="form-control" name="country">
                      @foreach ($countries as $country)
                        <option value="{{ $country->charcode }}"{{ $u && $u->contact && $u->contact->country_code == $country->charcode ? ' selected' : '' }} >{{ $country->name }}</option>
                      @endforeach
                    </select>       

                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">City</label>
                  <div class="col-md-9">

                    <!-- CITY -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-building"></i></span>
                      <input type="text" class="form-control" placeholder="City" value="{{ $u && $u->contact ? $u->contact->city : '' }}" name="city"></input>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Address1</label>
                  <div class="col-md-9">

                    <!-- ADDRESS1 -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-book"></i></span>
                      <input type="text" class="form-control" placeholder="Address1" value="{{ $u && $u->contact ? $u->contact->address : '' }}" name="address"></input>
                    </div>

                  </div>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Address2</label>
                  <div class="col-md-9">

                    <!-- ADDRESS2 -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-book"></i></span>
                      <input type="text" class="form-control" placeholder="Address2" value="{{ $u && $u->contact ? $u->contact->address2 : '' }}" name="address2"></input>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Zip Code</label>
                  <div class="col-md-9">

                    <!-- ZIPCODE -->
                    <div class="{{ $edit_cell_class }}">
                      <input type="text" class="form-control" placeholder="Zip Code" value="{{ $u && $u->contact ? $u->contact->zipcode : '' }}" name="zipcode"></input>
                    </div>

                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Phone</label>
                  <div class="col-md-9">

                    <!-- PHONE -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                      <input type="text" class="form-control" placeholder="Phone" value="{{ $u && $u->contact ? $u->contact->phone : '' }}" name="phone"></input>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Fax</label>
                  <div class="col-md-9">

                    <!-- FAX -->
                    <div class="{{ $edit_cell_class }}">
                      <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                      <input type="text" class="form-control" placeholder="Fax" value="{{ $u && $u->contact ? $u->contact->fax : '' }}" name="fax"></input>
                    </div>

                  </div>
                </div>                
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="col-md-3 control-label">Time zone</label>
                  <div class="col-md-9 {{ $edit_cell_class }}">

                    <!-- TIMEZONE -->
                    <select class="form-control select2" name="timezone">
                      @foreach ($timezones as $timezone)                    
                        <option value="{{ $timezone->id }}" {{ $u && $u->contact && $u->contact->timezone_id == $timezone->id ? ' selected' : '' }} >{{ $timezone->label }}</option>
                      @endforeach
                    </select>        

                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!--
          <div class="navbar-form navbar-right">
              <a href="{{ route('admin.user.list') }}"><button type="button" class="btn-cancel btn btn-default">Back</button></a>
              <button type="submit" class="btn-save btn btn-primary"><i class="fa fa-check"></i> Save</button>
          </div>
          -->

          <input type="submit" class="hidden" />
          

        </form>
        <!-- END FORM-->
      
    </div>
  </div>
</div>
@endsection