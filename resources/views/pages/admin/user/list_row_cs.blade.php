

  
  @if ($u->userType() == 'user_buyer')
  <a href="{{ route('admin.contract.list') }}?status={{ $__inner_v_type }}&buyer={{ $u->username }}">
    <span class="{{ $__inner_v_class }}">
      <span class="label">{!! $__inner_v_label !!}</span>
      <span class="value">{{ $u->getContractCount($__inner_v_type) }}</span></a>
    </span>
  </a>
  @elseif ($u->userType() == 'user_freelancer')
    <a href="{{ route('admin.contract.list') }}?status={{ $__inner_v_type }}&lancer={{ $u->username }}">
      <span class="{{ $__inner_v_class }}">
        <span class="label">{!! $__inner_v_label !!}</span>
        <span class="value">{{ $u->getContractCount($__inner_v_type) }}</span></a>
      </span>
    </a>
  @endif

</span>

