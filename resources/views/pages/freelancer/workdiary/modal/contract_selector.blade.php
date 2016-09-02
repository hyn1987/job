<?php

use Wawjob\Contract;
?>

<div class="form-group">
  <div class="input-field">
    <select class="job-category bs-select form-control" id="contract_selector" name="contract_selector" data-required="1" aria-required="true">
      @foreach($contracts as $p_contracts)
        @if(is_array($p_contracts['contracts']))
        @foreach($p_contracts['contracts'] as $_contract)
        <option value="{{ $_contract->id }}"{{ $_contract->id == $contract->id? ' selected' : ''  }} >{{ $_contract->buyer->fullname()}} - {{ $_contract->title }}@if ($_contract->status == Contract::STATUS_PAUSED) [PAUSED]@endif</option>
        @endforeach
        @endif
      @endforeach
    </select>
  </div>
</div>