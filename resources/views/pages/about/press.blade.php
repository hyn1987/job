@extends('layouts/about/index')

@section('css')
  <link rel="stylesheet" href="{{ url('assets/styles/about/index.css') }}">
@endsection

@section('content')
<div class="row bg-primary">
  <div class="col-md-12">
    <div class="text-center">
      <h2>{{ trans('about.press.word') }}</h2>
      <p>{{ trans('about.press.word_desc') }}</p>
      <a class="block-link" href="mailto:press@wawjob.com">press@wawjob.com</a>
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
    <h2>{{ trans('about.press.releases') }}</h2>
  </div>
</div>
<div class="row press-releases">
  <div class="col-md-10 peoples col-lg-offset-1">
    <div class="row">
      <div class="col-sm-6 col-md-4 col-lg-3">
        <article class="item-entry">
          <a href="#"><h4>{{ trans('about.press.relaunches') }}</h4></a>
        </article>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <article class="item-entry">
          <a href="#"><h4>{{ trans('about.press.wawjob_name') }}</h4></a>
        </article>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <article class="item-entry">
          <a href="#"><h4>{{ trans('about.press.work') }}</h4></a>
        </article>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <article class="item-entry">
          <a href="#"><h4>{{ trans('about.press.more') }}</h4><br><small>{{ trans('about.press.all') }}</small></a>
        </article>
      </div>
    </div>
  </div>
</div>
<div class="row media-resource">
  <div class="col-md-12">
    <h2 class="text-center">{{ trans('about.press.media') }}</h2>

  </div>
  <div class="row">
    <div class="col-md-10 peoples col-lg-offset-1">
      <div class="row">
        <div class="col-md-4 col-sm-12">
          <div class="row">
            <div class="col-lg-11 text-center">
              <h3>{{ trans('about.press.logos') }}</h3>
              <p>{{ trans('about.press.logos') }}(PNG)</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="row">
            <div class="col-lg-11 text-center">
              <h3>{{ trans('about.press.headshots') }}</h3>
              <p>{{ trans('about.press.download') }}(ZIP)</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="row">
            <div class="col-lg-11 col-lg-offset-1 text-center">
              <h3>{{ trans('about.press.industry') }}</h3>
              <p>{{ trans('about.press.download') }}(PDF)</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row about-bottom text-center">
  <div class="col-md-12">
    <div class="text-center">
      <h2>{{ trans('about.press.inquiries') }}</h2>
      <a class="block-link" href="mailto:press@wawjob.com">press@wawjob.com</a>
    </div>
  </div>
</div>
@endsection