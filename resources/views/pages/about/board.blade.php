@extends('layouts/about/index')

@section('css')
  <link rel="stylesheet" href="{{ url('assets/styles/about/index.css') }}">
@endsection

@section('content')
<div class="row bg-primary">
  <div class="col-md-12">
    <div class="text-center">
      <h2>{{ trans('about.board.our_board') }}</h2>
      <p>{{ trans('about.board.board_desc') }}</p>
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
            <div class="image center-block img-circle team-img thomas"></div>
            <h2 class="text-center">{{ trans('about.board.thomas') }}</h2>
            <div class="text-center text-uppercase">{{ trans('about.board.chairman') }}</div>
            {!! trans('about.board.thomas_desc') !!}
          </div>
        </div>
      </div>
      <div class="col-md-6 col-sm-12">
        <div class="row">
          <div class="col-lg-11 col-lg-offset-1 team">
            <div class="image center-block img-circle team-img betsey"></div>
            <h2 class="text-center">{{ trans('about.board.betsey') }}</h2>
            <div class="text-center text-uppercase">{{ trans('about.board.board_member') }}</div>
            {!! trans('about.board.betsey_desc') !!}
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