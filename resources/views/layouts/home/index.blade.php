<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

{{-- Start Head --}}
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
  <link rel="stylesheet" href="{{ url('assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css') }}">
  <link rel="stylesheet" href="{{ url('assets/plugins/simple-line-icons/simple-line-icons.min.css') }}">

  <link rel="stylesheet" href="{{ url('assets/styles/common/common.css') }}">
  <link rel="stylesheet" href="{{ url('assets/styles/common/components.css') }}">
  <link rel="stylesheet" href="{{ url('assets/styles/common/fonts.css') }}">
  <link rel="stylesheet" href="{{ url('assets/styles/layouts/home/home.css') }}">

  @yield('css')
  @include('layouts.section.script')
</head>{{-- End Head --}}

<body class="layout layout-home page page-{{ str_replace('.', '-', $page) }}">
  @include('layouts.home.header')

  {{-- Start Content --}}
  <div class="page-content">
    <div class="container-fluid">
      @yield('content')
    </div>
  </div>{{-- End Content --}}

  @include('layouts.home.footer')

  <script src="{{ url('assets/scripts/config.js') }}"></script>
  <script src="{{ url('assets/plugins/requirejs/require.min.js') }}"></script>
  <script src="{{ url('assets/scripts/app.js') }}"></script>
</body>
</html>
