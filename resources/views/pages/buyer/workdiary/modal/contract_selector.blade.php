<?php

use Wawjob\Contract;
?>

<div class="form-group">
  <div class="input-field">
    <select class="job-category bs-select form-control" id="contract_selector" name="contract_selector" data-required="1" aria-required="true">
      @foreach($contracts as $p_contracts)
      <optgroup label="{{ $p_contracts['project']? $p_contracts['project']->subject : ''}}">
        @if(is_array($p_contracts['contracts']))
        @foreach($p_contracts['contracts'] as $_contract)
        <option value="{{ $_contract->id }}"{{ $contract&&$_contract->id == $contract->id? ' selected' : ''  }}>{{ $_contract->contractor->fullname()}} - {{ $_contract->title }}@if ($_contract->status == Contract::STATUS_PAUSED)&nbsp;[PAUSED]@endif
        </option>
        @endforeach
        @endif
      </optgroup>
      @endforeach
    </select>
  </div>
</div>