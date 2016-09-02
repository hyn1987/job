<table class="table table-bordered table-slot-act {{$class}}">
  <thead>
    <tr>
      <th>Time</th>
      <th>Keyboard</th>
      <th>Mouse</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($act as $time => $info)
    <tr>
      <td>{{ $time }}</td>
      <td>{{ $info['k'] }}</td>
      <td>{{ $info['m'] }}</td>
    </tr>
    @endforeach
  </tbody>
</table>