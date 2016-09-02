<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8">
  <title>{{ trans('page.' . $page . '.title') }} - {{ trans('page.title') }}</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <meta name="author" content="Sunlight">
  <meta name="description" content="This site supports to manage patients at the clinic.">
  <meta name="keywords" content="">

  <link rel="shortcut icon" href="{{ url('favicon.ico') }}">
  <!--[if lte IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="{{ url('assets/styles/' . str_replace('.', '/', $page) . '.css') }}">
  @include('layouts.section.script')
</head><!-- END HEAD -->

<body class="layout layout-default page page-{{ str_replace('.', '-', $page) }}">
  @include('layouts.default.header')
  <!-- BEGIN CONTENT -->
  <div class="page-content">
    <div class="container-fluid">
      <!-- BEGIN PAGE HEADER-->
      <h3 class="page-title">{{ trans('page.' . $page . '.title') }} <small>{{ trans('page.' . $page . '.exp') }}</small></h3>
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="#">Home</a>
            <i class="fa fa-angle-right"></i>
          </li>
        </ul>
      </div><!-- END PAGE HEADER-->
      @yield('content')
    </div>
  </div><!-- END CONTENT -->
  @include('layouts.default.footer')

  <script src="{{ url('assets/scripts/config.js') }}"></script>
  <script src="{{ url('assets/plugins/requirejs/require.min.js') }}"></script>
  <script src="{{ url('assets/scripts/app.js') }}"></script>
</body>
</html>
