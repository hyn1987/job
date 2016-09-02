<script>
  var siteUrl = '{{ url() }}';
  @if (isset($page))
  var pageId = '{{ str_replace('.', '/', $page) }}';
  @endif
  var lang = '{{ App::getLocale() }}';

  var trans = {};
  trans.btn_ok     = '{{ trans('j_message.btn_ok') }}';
  trans.btn_cancel = '{{ trans('j_message.btn_cancel') }}';
  trans.btn_yes    = '{{ trans('j_message.btn_yes') }}';
  trans.btn_no     = '{{ trans('j_message.btn_no') }}';

  @if (isset($j_trans) && is_array($j_trans))
    @foreach ($j_trans as $var_name=>$value)
      @if (is_array($value))
        trans.{{ $var_name }} = {};
        @foreach ($value as $sub_var_name => $sub_value)
          trans.{{ $var_name }}.{{ $sub_var_name }} = '{!! $sub_value  !!}'; 
        @endforeach
      @else
        trans.{{ $var_name }} = '{!! $value !!}'; 
      @endif
    @endforeach
  @endif

</script>