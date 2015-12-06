
@extends('app')

@section('title')
Send Customer Quote
@endsection

@section('content')


{!! Form::open(array('url' => '/send_customer_quote/'.$quote_request->id, 'method' => 'post', 'class' => 'form-horizontal')) !!}


<div class="form-group">
    <table class="table">
        <thead>
        <tr>
            <th width="10%">Quantity</th>
            <th width="50%">Description</th>
            <th width="10%">Price</th>
            <th width="10%">GST</th>
            <th width="10%">Total</th>
            <th width="10%">Unit Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($qris as $qri)
        <tr>
            <td><input style="width: 100%" type="text" value="{{$qri->quantity}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->description}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->price}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->gst}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->total}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->unit_price}}" readonly></td>
        </tr>
        @endforeach
        </tbody>
    </table>






    <div style="margin-top:30px;">
        <p style="float:right;">
            <input type="submit" class="btn btn-primary" value="Send Emails" />
        </p>
        <p style="clear:both;"></p>
    </div>

{!! Form::close() !!}

@endsection
