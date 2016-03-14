@extends('app')

@section('title')
    Users
@endsection

@section('content')

    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0;">Users</h2>
        </div>
        <div class="col-sm-2">
            <div class="pull-right">
                <a href="/users/create" class="btn btn-primary">Create New User</a>
            </div>
        </div>
    </div>
    <hr>

    <table class="table table-striped table-hover" id="table">
    <thead>
    <tr>
        <th>User Name</th>
        <th>Role</th>
        <th>Email</th>
        <th>Date Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
    <tr>
        <td><a href="/users/{{$user->id}}/edit">{{ $user->name }}</a></td>
        <td><span class="label @if($user->admin == true) label-danger">Admin @else label-success">User @endif</span> </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->created_at->format('d/m/Y') }}
    </tr>
    @endforeach
    </tbody>
    </table>

@endsection