@extends('app')

@section('title')
    @if(isset($user))
        Edit User - {{ $user->name }}
    @else
        Create User
    @endif
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Validation of the form
            $('form').validate(
                    {
                        rules: {
                            name: {
                                required: true,
                            },
                            email: {
                                required: true,
                            },
                            @if(!isset($user))
                            password: {
                                required: true,
                            }
                            @endif
                        },
                        messages: {
                            name: {
                                required: "Enter User Name"
                            },
                            email: {
                                required: "Enter Email Address"
                            },
                            @if(!isset($user))
                            password: {
                                required: "Enter User Password"
                            }
                            @endif
                        }
                    }
            );
        });
    </script>

    {!! Form::open(['url' => 'users/save', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'user_form']) !!}

    @if(isset($user))
        {!! Form::hidden('id', $user->id) !!}
    @else
        {!! Form::hidden('id', 0) !!}
    @endif

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0">
                @if(isset($user))
                    Edit User: {{ $user->name }}
                @else
                    Create User
                @endif
            </h2>
        </div>

        <div class="col-sm-2">
            @if(isset($user))
                <div class="btn btn-danger pull-right" data-delete="true" data-toggle="modal" data-target="#deleteModal" style="margin-top: 30px;" @if(Auth::user()->name == $user->name) disabled @endif>Delete</div>
            @endif
            {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right', 'style' => 'margin-top: 30px;']) !!}
        </div>
    </div>

    <hr>

    @include('partials.errors')

    <div class="form-group">
        <div class="col-md-3">
            {!! Form::label('name', 'User Name *', array('class' => 'control-label')) !!}
            @if(isset($user))
                {!! Form::text('name', $user->name, array('id' => 'name', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter User Name']) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('email', 'Email *', array('class' => 'control-label')) !!}
            @if(isset($user))
                {!! Form::email('email', $user->email, array('id' => 'email', 'class' => 'form-control')) !!}
            @else
                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email Address']) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('password', 'Password *', array('class' => 'control-label')) !!}
            {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Enter New User Password']) !!}
        </div>

        <div class="col-md-3">
            {!! Form::label('admin', 'Admin', array('class' => 'control-label', 'style' => 'margin-top:25px')) !!}
            @if(isset($user))
                {!! Form::checkbox('admin', 1, $user->admin) !!}
            @else
                {!! Form::checkbox('admin', 1, null) !!}
            @endif
        </div>
    </div>

    {!! Form::close() !!}

    @if(isset($user))
        @include('partials.delete_user')
    @endif

@endsection