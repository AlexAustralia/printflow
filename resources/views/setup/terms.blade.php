@extends('app')

@section('title')
    Terms
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">

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
            <h2 style="margin:0;">Terms</h2>
        </div>
    </div>
    <hr>

    {!! Form::open(['url' => 'terms', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'terms_form']) !!}

    <div class="form-group col-md-12">
        <div class="col-md-3 col-md-offset-3">
            <select class="form-control" id="term-name" name="id">
                <option value="0">New Customer Terms</option>
                @foreach($terms as $term)
                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group col-md-12">
        {!! Form::label('name', 'Name', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-6">
            {!! Form::text('name', null, ['class' => 'form-control col', 'id' => 'name']) !!}
        </div>
    </div>

    <div class="form-group col-md-12">
        {!! Form::label('description', 'Terms', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-6">
            {!! Form::textarea('description', null, array('rows' => '4', 'class' => 'form-control', 'id' => 'description')) !!}
        </div>
    </div>

    <div class="col-md-3 col-md-offset-3">
        <button name="submit" type="submit" value="delete" class="btn btn-danger" id="delete" disabled>Delete</button>
        <button name="submit" type="submit" value="save" class="btn btn-primary">Save</button>
    </div>

    {!! Form::close() !!}

    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            var terms = {
                @foreach($terms as $term)
                    "{{ $term->id }}": {"name": "{{ $term->name }}", "description": "{{ str_replace(["\r\n", "\n", "\r"], "\\n", $term->description) }}"},
                @endforeach
            };

            // Validation of the form
            $('#terms_form').validate(
                    {
                        rules: {
                            name: {
                                required: true,
                            },
                            description: {
                                required: true,
                            }
                        },
                        messages: {
                            name: {
                                required: "Enter Name of Terms"
                            },
                            email: {
                                required: "Enter Terms Description"
                            }
                        }
                    }
            );

            $('#term-name').on('change', function() {
                if (this.value > 0) {
                    $('#name').val(terms[this.value].name);
                    $('#description').val(terms[this.value].description);
                    $('#delete').removeAttr('disabled');
                } else {
                    $('#name').val('');
                    $('#description').val('');
                    $('#delete').attr('disabled', true);
                }

            });
        });
    </script>

@endsection