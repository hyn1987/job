@extends('layouts/home/index')

@section('css')
  <link rel="stylesheet" href="{{ url('assets/styles/home/home.css') }}">
@endsection

@section('content')
<div class="row">
  <div class="col-md-12 intro">
    <div class="intro-content">
      <h2 class="on-bg">{{ trans('home.best_platform_you_can_find_great_work') }}</h2>
      <div class="desc">{{ trans('home.find_freelancers_to_help_you') }}</div>
      <div class="get-started"><a class="btn btn-success" href="{{ $current_user ? ($current_user->isBuyer() ? route('job.my_jobs') : route('search.job') ) : route('user.signup') }}">{{ trans('home.get_started') }}</a></div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 peoples">
    <div class="row">
      <div class="col-md-7">
        <h2>Great work started with great talent</h2>
        <div class="desc">Get amazing results working with the best programmers, designers, writers and other top online pros. Hire freelancers with confidence, always knowing their work experience and feedback from other clients.</div>
      </div>
      <div class="col-md-5">
        <div class="draw-board">
          <i class="icon-magnifier"></i>
          <i class="icon-user"></i>
          <i class="icon-star star-1"></i>
          <i class="icon-star star-2"></i>
          <i class="icon-star star-3"></i>
          <i class="icon-star star-4"></i>
          <i class="icon-star star-5"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 best-match">
    <div class="row">
      <div class="col-md-5">
        <div class="draw-board">
          <i class="icon-bubbles"></i>
        </div>
      </div>
      <div class="col-md-7">
        <h2>Find the best match fast</h2>
        <div class="desc">Start your job in hours, not weeks. Get a shortlist of skilled freelancers instantly, tapping into our hiring know-how and matching technology. Interview favorites online and hire with the click of a button.</div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 testimonials">
    <h2>Testimonials</h2>
    <ul>
      <li class="active">
        <div class="testimonial">This is the most awesome, full featured, easy, costomizeble theme. It’s extremely responsive and very helpful to all suggestions.</div>
        <h5>Joe Williams</h5>
      </li>
      <li>
        <div class="testimonial">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse.</div>
        <h5>Calson Deton</h5>
      </li>
      <li>
        <div class="testimonial">Williamsburg carles vegan helvetica. Cosby sweater eu banh mi, qui irure terry richardson ex squid Aliquip placeat salvia cillum iphone.</div>
        <h5>Joe Williams</h5>
      </li>
    </ul>
  </div>
</div>
@endsection