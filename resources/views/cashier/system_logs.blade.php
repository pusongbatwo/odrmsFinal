@extends('layouts.app')

@section('content')
<div class="container">
    <h1>System Logs</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Message</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->type }}</td>
                <td>{{ $log->message }}</td>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
