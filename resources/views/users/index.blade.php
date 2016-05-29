@extends('app')

@section('title')
    Users
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="delete_confirmation" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user from the database?</p>
                    <p>This process cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    {!! Form::open(array('url' => 'users/delete', 'method' => 'post')) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="id" value="" class="btn btn-danger" id="delete_user">OK</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

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
        <th width="10%"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
    <tr>
        <td><a href="/users/{{$user->id}}/edit">{{ $user->name }}</a></td>
        <td><span class="label @if($user->admin == true) label-danger">Admin @else label-success">User @endif</span> </td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->created_at->format('d/m/Y') }}
        <td><button class="btn btn-sm btn-danger" type="button" data-toggle="modal" value="{{ $user->id }}" data-target="#delete_confirmation" onclick="new_val(this)" @if(Auth::user()->name == $user->name) disabled @endif><span class="fa fa-trash-o"></span></button></td>
    </tr>
    @endforeach
    </tbody>
    </table>

    <script>
        function new_val(t){
            var res = $(t).attr('value');
            $('#delete_user').val(res);
            return false;
        }
    </script>

@endsection