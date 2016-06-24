@extends('app')

@section('title')
    Project Discussion
@endsection

@section('content')

    <link href="{{ asset('fancybox/source/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet" type="text/css">

    {!! Form::open(['url' => '/projects/discussion/save', 'method' => 'post', 'id' => 'discussion_form', 'class' => 'form-horizontal', 'files' => true]) !!}

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0"><img src="/images/projects.png"> Project Discussion</h2>
        </div>
    </div>

    <hr>

    @include('partials.projects_menu')

    <div class="well">
        @foreach($messages as $message)
            <div class="row">
                <div class="col-sm-2"><small>{{ $message->created_at->format('d/m/Y H:m:s') }}</small></div>
                <div class="col-sm-2"><strong>{{ $message->user->name }}</strong></div>
                <div class="col-sm-1">@if(!is_null($message->attachment))<a class="review_image" target="_blank" href="/uploads/projects/{{ $message->attachment }}">
                        <img src="/uploads/projects/thumbnails/{{ $message->attachment }}">
                    </a>@endif</div>
                <div class="col-sm-6">{{ $message->body }}</div>
                <div class="col-sm-1">@if(Auth::user()->id == $message->user_id)<button type="button" class="btn btn-sm btn-danger delete-message pull-right"><span class="fa fa-trash-o"></span></button>@endif</div>
            </div>
            <hr>
        @endforeach
    </div>

    <div class="form-group">
        <div class="col-md-2">
            {!! Form::label('message', 'Enter Message', array('class' => 'control-label')) !!}
        </div>
        <div class="col-md-10">
            {!! Form::textarea('message', null, array('rows' => '4', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="row"><div class="col-md-12">
    <div class="pull-right">
        You can attach images
        {!! Form::file('image') !!}
        <button type="submit" class="btn btn-primary">Send</button>
    </div>
    </div>
    </div>

    {!! Form::close() !!}

    <script src="{{ asset('js/bootstrap.file-input.js') }}"></script>
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>

    <script>
        $(document).ready(function() {
            $('input[type=file]').bootstrapFileInput();

            $('.review_image').fancybox();
        });
    </script>

@endsection