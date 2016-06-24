@extends('app')

@section('title')
    Project Checklist
@endsection

@section('content')

    {!! Form::open(['url' => '#', 'method' => 'post', 'id' => 'brief_form', 'class' => 'form-horizontal']) !!}

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0"><img src="/images/projects.png"> Project Checklist</h2>
        </div>
    </div>

    <hr>

    @include('partials.projects_menu')



    {!! Form::close() !!}

@endsection