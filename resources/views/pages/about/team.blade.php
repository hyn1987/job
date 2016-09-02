@extends('layouts/about/index')

@section('css')
  <link rel="stylesheet" href="{{ url('assets/styles/about/index.css') }}">
@endsection

@section('content')
<div class="row bg-primary">
  <div class="col-md-12">
    <div class="text-center">
      <h2>{{ trans('about.team.our_team') }}</h2>
      <p>{{ trans('about.team.team_desc') }}</p>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    @include('layouts/about/section/about_top_menu')
  </div>
</div>
<div class="row">
  <div class="col-md-10 peoples col-lg-offset-1">
    <div class="row">
      <div class="col-md-6 col-sm-12">
        <div class="row">
          <div class="col-lg-11 team">
            <div class="image center-block img-circle team-img stephane"></div>
            <h2 class="text-center">{{ trans('about.team.stephane') }}</h2>
            <div class="text-center text-uppercase">{{ trans('about.team.ceo') }}</div>
            {!! trans('about.team.stephane_desc') !!}
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="row">
          <div class="col-lg-11 col-lg-offset-1 team">
            <div class="image center-block img-circle team-img brian "></div>
            <h2 class="text-center">{!! trans('about.team.brian') !!}</h2>
            <div class="text-center text-uppercase">{!! trans('about.team.cfo') !!}</div>
            {!! trans('about.team.brian_desc') !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row investors">
  <div class="col-md-12">
    <h2 class="text-center">{{ trans('about.team.investors') }}</h2>
  </div>
</div>
@endsection