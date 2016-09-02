@extends('layouts/about/index')

@section('css')
  <link rel="stylesheet" href="{{ url('assets/styles/about/index.css') }}">
@endsection

@section('content')
<div class="row bg-primary">
  <div class="col-md-12">
    <div class="text-center">
      <h2>{{ trans('about.careers.change') }}</h2>
      <p>{{ trans('about.careers.change_desc') }}</p>
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
    <h2>{{ trans('about.careers.work') }}</h2>
  </div>
</div>
<div class="row">
  <div class="col-md-10 peoples col-lg-offset-1">
    <div class="row">
      <div class="col-md-4 col-sm-12">
        <div class="row">
          <div class="col-lg-11 text-center">
            <div class="about-icon">
              <span class="glyphicon glyphicon-time"></span>
            </div>
            <h3>{{ trans('about.careers.history') }}</h3>
            <p>{{ trans('about.careers.history_desc') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="row">
          <div class="col-lg-11 text-center">
            <div class="about-icon">
              <span class="glyphicon glyphicon-globe"></span>
            </div>
            <h3>{{ trans('about.careers.proud') }}</h3>
            <p>{{ trans('about.careers.proud_desc') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="row">
          <div class="col-lg-11 col-lg-offset-1 text-center">
            <div class="about-icon">
              <span class="glyphicon glyphicon-road"></span>
            </div>
            <h3>{{ trans('about.careers.whistle') }}</h3>
            <p>{{ trans('about.careers.whistle_desc') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-10 text-center col-lg-offset-1">
    <h2>{{ trans('about.careers.see') }}</h2>
  </div>
</div>
<div class="row about-bottom text-center">
  <h2>{{ trans('about.careers.join') }}</h2>
</div>
@endsection