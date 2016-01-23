@extends('app')

@section('title')
    Delivery
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $( "#delivery_date" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

            $.validator.addMethod(
                    "australianDate",
                    function(value, element) {
                        return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
                    },
                    "Please enter a valid date"
            );

            // Validation of the form
            $('form').validate(
                    {
                        rules: {
                            delivery_date: {
                                required: true,
                                australianDate : true
                            },
                            cartons: {
                                required: true,
                                number: true
                            },
                            items: {
                                required: true,
                                number: true
                            },
                            delivery_address: {
                                required: true
                            }
                        },
                        messages: {
                            delivery_date: {
                                required: "You should specify a date"
                            },
                            cartons: {
                                required: "Enter Number of Cartons"
                            },
                            items: {
                                required: "Enter Items per Carton"
                            },
                            delivery_address: {
                                required: "You must choose a valid delivery address"
                            }
                        }
                    }
            );

            // Cancel button
            $('#cancel').on('click', function() {
                location.href="{{URL::to('/')}}"
            });

            // Sticker button
            $('#sticker').on('click', function() {
                $('#act').append('<input type="hidden" name="value" value="sticker">');
                $('#delivery_form').submit();
            });

            // Docket button
            $('#docket').on('click', function() {
                $('#act').append('<input type="hidden" name="value" value="docket">');
                $('#delivery_form').submit();
            });
        });
    </script>

    <div class="row">
        <div class="col-sm-10">
            <h2>Delivery</h2>
        </div>
    </div>
    <hr>

    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    {!! Form::open(array('url' => 'job/delivery', 'method' => 'post', 'id' => 'delivery_form', 'class' => 'form-horizontal')) !!}
    {!! Form::hidden('job_id', $quote->id) !!}

    <div class="form-group">
        <div class="col-md-5">
            {!! Form::label('customer', 'Customer', array('class' => 'control-label')) !!}
            {!! Form::text('customer', $quote->customer->customer_name, array('id' => 'customer', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
        </div>

        <div class="col-md-5">
            {!! Form::label('job_title', 'Job Title', array('class' => 'control-label')) !!}
            {!! Form::text('job_title', $quote->title, array('id' => 'job_title', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('job_qty', 'Job Quantity', array('class' => 'control-label')) !!}
            {!! Form::text('job_qty', $quote->job->job_item->quantity   , array('id' => 'job_qty', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-2">
            {!! Form::label('delivery_date', 'Delivery Date *', array('class' => 'control-label')) !!}
            {!! Form::text('delivery_date', null, array('id' => 'delivery_date', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-6">
            {!! Form::label('delivery_address', 'Delivery Address *', array('class' => 'control-label')) !!}
            <select id="delivery_address" name="delivery_address" class="form-control">
                @foreach ($delivery_addresses as $delivery_address)
                    <option value="{{ $delivery_address->id }}">
                        {{ $delivery_address->name }} {{ $delivery_address->address }} {{ $delivery_address->city }}, {{ $delivery_address->state }} {{ $delivery_address->postcode }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <a href="/customer/{{ $quote->customer->id }}/create_address/{{$quote->id}}" type="button" class="btn btn-primary" style="margin-top:27px;">Create New</a>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4">
            {!! Form::label('cartons', 'Number of Cartons *', array('class' => 'control-label')) !!}
            {!! Form::text('cartons', null, array('id' => 'cartons', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-4">
            {!! Form::label('items', 'Items per Carton *', array('class' => 'control-label')) !!}
            {!! Form::text('items', null, array('id' => 'items', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-4">
            {!! Form::label('method', 'Delivery Method', array('class' => 'control-label')) !!}
            {!! Form::text('method', null, array('id' => 'method', 'class' => 'form-control')) !!}
        </div>
    </div>
    <hr>

    <div class="pull-right">
        <button type="button" class="btn btn-danger" id="cancel">Cancel</button>
        <button type="button" class="btn btn-primary" id="sticker">Create Sticker</button>
        <button type="button" class="btn btn-primary" id="docket">Create Docket</button>
    </div>

    <div id="act"></div>
    {!! Form::close() !!}

@endsection