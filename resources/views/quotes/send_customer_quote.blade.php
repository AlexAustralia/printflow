
@extends('app')

@section('title')
Send Customer Quote
@endsection

@section('content')


{!! Form::open(array('url' => '/send_customer_quote/'.$quote_request->id, 'method' => 'post', 'class' => 'form-horizontal')) !!}


    <pre>{{ $quote_request }}</pre>

    <div style="margin-top:30px;">
        <p style="float:right;">
            <input type="submit" class="btn btn-primary" value="Send Emails" />
        </p>
        <p style="clear:both;"></p>
    </div>

{!! Form::close() !!}

@endsection
