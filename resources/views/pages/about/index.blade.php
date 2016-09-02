@extends('layouts/about/index')

@section('css')
  <link rel="stylesheet" href="{{ url('assets/styles/about/index.css') }}">
@endsection

@section('content')
<div class="row bg-primary">
  <div class="col-md-12">
    <div class="text-center">
      <h2>{{ trans('about.about.about_us') }}</h2>
      <p>{{ trans('about.about.about_desc') }}</p>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    @include('layouts/about/section/about_top_menu')
  </div>
</div>
<div class="row">
  <div class="col-md-10 text-center col-lg-offset-1">
    <h2>{{ trans('about.about.say_hello') }}</h2>
    {!! trans('about.about.say_hello_desc') !!}
  </div>
</div>
<div class="row">
  <div class="col-md-10 peoples col-lg-offset-1">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="row">
          <div class="col-lg-11 text-center">
            <div class="about-icon">
              <span class="icon-rocket"></span>
            </div>
            <h3>{{ trans('about.about.mission') }}</h3>
            <p>
              {{ trans('about.about.mission_desc') }}
            </p>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="row">
          <div class="col-lg-11 col-lg-offset-1 text-center">
            <div class="about-icon">
              <span class="icon-globe"></span>
            </div>
            <h3>{{ trans('about.about.vision') }}</h3>
            <p>{{ trans('about.about.vision_desc') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row about-bg">
</div>
<div class="row text-center">
  <div class="col-md-10 peoples col-lg-offset-1">
    {!! trans('about.about.story') !!}
  </div>
</div>
<div class="row about-bottom text-center">
  {!! trans('about.about.bottom') !!}
</div>
@endsection