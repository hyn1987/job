<?php

use Wawjob\Contract;
?>

<div class="form-group">
  <div class="input-field">
    <select class="contract-filter bs-select form-control" id="contract_selector" name="contract_selector" data-required="1" aria-required="true">
      <option value="0" data-content='
        <div class="all-filter filter-item">All</div>
        ' {{ old('contract_selector')==0? 'selected' : ''  }}></option>
      @foreach($contracts as $p_contracts)
      <option value="p{{ $p_contracts['project']->id }}" data-content='
        <div class="project-filter filter-item">{{ $p_contracts['project']? $p_contracts['project']->subject : ''}}</div>
                  ' {{ old('contract_selector')=="p".$p_contracts['project']->id? 'selected' : ''  }}></option>

        @if(is_array($p_contracts['contracts']))
        @foreach($p_contracts['contracts'] as $_contract)
        <option value="{{ $_contract->id }}" data-content='
          <div class="contract-filter filter-item">{{ $_contract->contractor->fullname()}} - {{ $_contract->title }}</div>
                  ' {{ $_contract->id == old('contract_selector')? 'selected' : ''  }}></option>
        @endforeach
        @endif
      @endforeach
    </select>
  </div>
</div>