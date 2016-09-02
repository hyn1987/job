@extends('layouts/faq/index')

@section('content')
<div class="tab-content">
  <div class="dd faq-list">
    <ul class="dd-list">
      @foreach ($faq_list as $faq)
        @include('pages.faq.list_row')
      @endforeach
    </ol>
  </div>
</div>
@endsection

@section('js')
  <script>
    var data = {
      loadUrl: "{{ route('faq.load') }}",
    };
  </script>
@endsection