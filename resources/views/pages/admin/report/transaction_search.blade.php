<div class="col-md-12">
  <div class="navbar navbar-default" role="navigation">
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <form class="navbar-form navbar-right" role="search" name="frm_search" id="frm_search" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="input-group date-range" id="date_range">
          <input type="text" class="form-control" name="date_range" value="{{ $date_range }}">
          <span class="input-group-btn">
            <button class="btn default date-range-toggle" type="button"><i class="fa fa-calendar"></i></button>
          </span>
        </div>

        {{-- Who performs this action? --}}
        <div class="input-group transaction-for">
          <select class="form-control" name="t_for" id="sel_transaction_for">
            <option value="">All</option>
            @foreach ($options['for'] as $label => $v)
            <option value="{{ $v }}"{{ $v == old('t_for') ? ' selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        {{-- Transaction type --}}
        <div class="input-group transaction-type">
          <select class="form-control" name="t_type" id="sel_transaction_type" placeholder="Transaction Type">
            <option value="all">All Transactions</option>
            @foreach ($options['type'] as $label => $v)
            <option value="{{ $v }}"{{ $v == old('t_type') ? ' selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <button class="btn blue btn-search" type="submit">Search</button>
      </form>
    </div>
  </div>
</div>