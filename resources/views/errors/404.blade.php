@extends('layouts.error.index')

@section('content')
  <div class="number">404</div>
  <div class="details">
    <h3>Oops! You're lost.</h3>
    <p>
      You seem to have upset the delicate internal balance of my housekeeper.<br/>
      Return <a href="{{ url() }}">Home</a> or contact with <a href="mailto:info@wawjob.com">Administrator</a>.
    </p>
  </div>
@endsection