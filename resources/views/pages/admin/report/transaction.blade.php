@extends('layouts/admin/index')

@section('content')
<div class="row page-body">

  @include('pages.admin.report.transaction_search')

  <div class="col-sm-12">
    <div class="transaction-list">
      <table class="table table-list">
        <thead>
          <th>#ID</th>
          <th>Date</th>
          <th>Type</th>
          <th>Status</th>
          <th>Description</th>
          <th>User Type</th>
          <th>User Name</th>
          <th>Amount</th>
        </tr>          
        </thead>
        <tbody>
        @if ($rows->count() > 0)
        @foreach ($rows as $row)
          <tr class="type-{{ strtolower($row->type_string()) }}{{ $row->user_type ? ' for-'.strtolower($row->user_type) : '' }}{{ $row->isPending() ? ' pending' : ''}}">
            <td>{{ $row->id }}</td>
            <td>{{ $row->created_at->format("M d, Y H:i:s") }}</td>
            <td>{{ $row->type_string() }}</td>
            <td class="status">{{ $row->status_string() }}</td>
            <td class="desc">{!! nl2br($row->desc) !!}</td>
            <td>{{ $row->user_type }}</td>
            <td>{{ $row->user_fullname }}</td>
            <td>{{ $row->amount >= 0 ? "$".formatCurrency($row->amount) : "($".formatCurrency(-$row->amount).")" }}</td>
          </tr>
        @endforeach

        @else
          <tr>
            <td class="no-items" align="center" colspan="8">No Transactions</td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection