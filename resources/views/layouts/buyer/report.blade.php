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

  <meta name="author" content="">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ url('favicon.ico') }}">
  <!--[if lte IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/plugins/fontawesome/css/font-awesome.css') }}">
  <link rel="stylesheet" href="{{ url('assets/plugins/simple-line-icons/simple-line-icons.min.css') }}">

  <link rel="stylesheet" href="{{ url('assets/styles/common/common.css') }}">
  <link rel="stylesheet" href="{{ url('assets/styles/common/components.css') }}">
  <link rel="stylesheet" href="{{ url('assets/styles/common/fonts.css') }}">
  <link rel="stylesheet" href="{{ url('assets/styles/layouts/buyer/buyer.css') }}">
  
  @yield('additional-css')
  <link rel="stylesheet" href="{{ url('assets/styles/' . str_replace('.', '/', $page) . '.css') }}">
  @include('layouts.section.script')
  
  <script>   
    @if (isset($dates))
    var date_from = '{{ date("Y-m-d", strtotime($dates['from'])) }}',
      date_to   = '{{ date("Y-m-d", strtotime($dates['to'])) }}';
    @endif
  </script>
</head><!-- END HEAD -->

<body class="layout layout-buyer buyer-page buyer-report-page {{ str_replace('.', '-', $page) }}">
  @include('layouts.section.header')

  <!-- BEGIN CONTENT -->
  <div class="page-wrapper page-user">
    <div class="page-section container">

      <div class="row">
        <div class="col-sm-3">
          @include('layouts.buyer.section.report_sidebar_menu')
        </div>
        <div class="page-content col-sm-9">
          @yield('content')
        </div>
      </div>

    </div>
  </div><!-- END CONTENT -->

  @include('layouts.section.footer')

  <script src="{{ url('assets/scripts/config.js') }}"></script>
  <script src="{{ url('assets/plugins/requirejs/require.min.js') }}"></script>
  <script src="{{ url('assets/scripts/app.js') }}"></script>
</body>
</html>
