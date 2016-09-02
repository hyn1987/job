<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
  <div class="container-fluid">
    <!-- BEGIN LOGO -->
    <div class="page-logo">
      <a class="logo" href="{{ url('/') }}">{{ trans('page.logo') }}<span>{{ trans('page.sub_logo') }}</span></a>
    </div><!-- END LOGO -->
    <!-- BEGIN RESPONSIVE MENU TOGGLER -->
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <!-- BEGIN TOP NAVIGATION MENU -->
    <div class="top-nav">
      <ul class="nav navbar-nav pull-right">
        <!-- BEGIN USER LOGIN DROPDOWN -->
        <li><a href="{{ route('user.logout') }}"><i class="icon-key"></i> {{ trans('header.logout') }}</a></li>
        <!-- END USER LOGIN DROPDOWN -->
      </ul>
    </div><!-- END TOP NAVIGATION MENU -->
  </div>
</div><!-- END HEADER -->