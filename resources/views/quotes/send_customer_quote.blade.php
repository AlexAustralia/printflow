
@extends('app')

@section('title')
Send Customer Quote
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

    @if (isset($error))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Error</h4>
            @if(is_array($error)) @foreach ($error as $m) {{ $m }} @endforeach
            @else {{ $error }} @endif
        </div>
    @endif

    @if ($quote_request->quote_id == 0)
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Warning</h4>
            No quotes created. Please create a quote on 'Evaluate Prices'
        </div>
    @else

{!! Form::open(array('url' => '/send_customer_quote/'.$quote_request->id, 'method' => 'post', 'class' => 'form-horizontal')) !!}

<div class="form-group">
    <div class="col-md-3">
        {!! Form::label('customer', 'Customer *', array('class' => 'control-label')) !!}
        {!! Form::text('customer', $quote_request->customer["customer_name"], array('id' => 'customer', 'class' => 'form-control', 'disabled' => 'disabled')) !!}

    </div>

    <div class="col-md-3">
        {!! Form::label('request_date', 'Request Date *', array('class' => 'control-label')) !!}
        {!! Form::text('request_date', $quote_request->request_date, array('id' => 'request_date', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
    </div>

    <div class="col-md-3">
        {!! Form::label('expiry_date', 'Expiry Date', array('class' => 'control-label')) !!}
        {!! Form::text('expiry_date', $quote_request->expiry_date, array('id' => 'expiry_date', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
    </div>

    <div class="col-md-3">
        {!! Form::label('id', 'Quote Number', array('class' => 'control-label')) !!}
        {!! Form::text('id', $quote_request->id, array('disabled' => 'disabled', 'class' => 'form-control')) !!}
    </div>

</div>

<div class="form-group">
    <div class="col-md-12">
        {!! Form::label('title', 'Title *', array('class' => 'control-label')) !!}
        {!! Form::text('title', $quote_request->title, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-6">
        {!! Form::label('summary', 'Summary', array('class' => 'control-label')) !!}
        {!! Form::textarea('summary', $quote_request->summary, array('rows' => '4', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
    </div>

    <div class="col-md-6">
        {!! Form::label('terms', 'Terms', array('class' => 'control-label')) !!}
        {!! Form::textarea('terms', $quote_request->terms, array('rows' => '4', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
    </div>
</div>

<div class="form-group">
    <table class="table">
        <thead>
        <tr>
            <th width="10%">Quantity</th>
            <th width="40%">Description</th>
            <th width="10%">Price</th>
            <th width="10%">Artwork</th>
            <th width="10%">GST</th>
            <th width="10%">Total</th>
            <th width="10%">Unit Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach($qris as $key => $qri)
        <tr>
            <td><input style="width: 100%" type="text" value="{{$qri->quantity}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$quote_request->title}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->total_net + $quote_request->qris[$key]->freight_charge}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$quote_request->artwork_charge}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->gst}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->total_inc_gst}}" readonly></td>
            <td><input style="width: 100%" type="text" value="{{$qri->unit_price_inc_gst}}" readonly></td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

    <div style="margin-top:30px;">
        <p style="float:right;">
            @if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/quotes/'. $quote_request->id . '.pdf'))
                <a target='_blank' href="/quotes/{{$quote_request->id}}.pdf" class="btn btn-warning">View PDF Quote</a>
                <input name="submit" type="submit" class="btn btn-primary" value="Send Email" />
            @else
                <input name="submit" type="submit" class="btn btn-primary" value="Create PDF" />
                <input name="submit" type="submit" class="btn btn-primary" value="Send Email" disabled/>
            @endif
        </p>
        <p style="clear:both;"></p>
    </div>

{!! Form::close() !!}
    @endif

@endsection
