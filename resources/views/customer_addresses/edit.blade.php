@extends('app')

@section('title')
    Edit Delivery Address
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            // Validation of the form
            $('form').validate(
                    {
                        rules: {
                            name: {
                                required: true
                            },
                            address: {
                                required: true
                            },
                            city: {
                                required: true
                            },
                            state: {
                                required: true
                            },
                            postcode: {
                                required: true
                            }
                        },
                        messages: {
                            name: {
                                required: "Enter Full Name"
                            },
                            address: {
                                required: "Enter Address"
                            },
                            city: {
                                required: "Enter City"
                            },
                            state: {
                                required: "Enter State"
                            },
                            postcode: {
                                required: "Enter Postcode"
                            }
                        }
                    }
            );

            // Cancel button
            $('#cancel').on('click', function() {
                location.href="{{URL::to('/job/'.$job.'/delivery')}}"
            });
        });
    </script>

    <div class="row">
        <div class="col-sm-10">
            <h2><img src="/images/edit_customer.png"> Edit Delivery Address</h2>
        </div>
    </div>
    <hr>

    @include('partials.errors')

    {!! Form::open(array('url' => 'customer_address/save', 'method' => 'post', 'id' => 'customer_address_form', 'class' => 'form-horizontal')) !!}
    {!! Form::hidden('id', $customer_address->id) !!}

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('customer', 'Customer', array('class' => 'control-label')) !!}
            {!! Form::text('customer', $customer->customer_name, array('id' => 'customer', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
            {!! Form::hidden('customer_id', $customer->id) !!}
        </div>

        <div class="col-md-6">
            {!! Form::label('name', 'Full Name *', array('class' => 'control-label')) !!}
            {!! Form::text('name', $customer_address->name, array('id' => 'name', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('address', 'Address *', array('class' => 'control-label')) !!}
            {!! Form::text('address', $customer_address->address, array('id' => 'address', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3">
            {!! Form::label('city', 'City *', array('class' => 'control-label')) !!}
            {!! Form::text('city', $customer_address->city, array('id' => 'city', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-3">
            {!! Form::label('state', 'State *', array('class' => 'control-label')) !!}
            {!! Form::text('state', $customer_address->state, array('id' => 'state', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-3">
            {!! Form::label('postcode', 'Postcode *', array('class' => 'control-label')) !!}
            {!! Form::text('postcode', $customer_address->postcode, array('id' => 'postcode', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-3">
            {!! Form::label('country', 'Country', array('class' => 'control-label')) !!}
            {!! Form::text('country', $customer_address->country, array('id' => 'county', 'class' => 'form-control')) !!}
        </div>
    </div>
    <hr>

    <div class="pull-right">
        <button type="button" class="btn btn-danger" id="cancel">Cancel</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>

    {!! Form::hidden('job', $job) !!}
    {!! Form::close() !!}

@endsection