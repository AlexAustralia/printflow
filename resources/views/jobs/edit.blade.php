@extends('app')

@section('title')
Create Job
@endsection

@section('content')

    @if(isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    @if($quote_request->quote_id == 0)
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Warning</h4>
            No quotes created. Please create a quote on 'Evaluate Prices'
        </div>
    @else

        {!! Form::open(array('url' => 'job/'.$quote_request->id.'/save', 'method' => 'post', 'class' => 'form-horizontal')) !!}

        <div class="form-group">
            <div class="col-md-4">
                {!! Form::label('customer', 'Customer', array('class' => 'control-label')) !!}
                {!! Form::text('customer', $quote_request->customer["customer_name"], array('id' => 'customer', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
            </div>

            <div class="col-md-4">
                {!! Form::label('job_date', 'Job Date', array('class' => 'control-label')) !!}
                @if(isset($job))
                    {!! Form::text('job_date', $job->updated_at->format('d/m/Y'), array('id' => 'job_date', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
                @else
                    {!! Form::text('job_date', null, array('id' => 'job_date', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
                @endif
            </div>

            <div class="col-md-4">
                {!! Form::label('id', 'Job Number', array('class' => 'control-label')) !!}
                @if(isset($job))
                    {!! Form::text('id', $quote_request->id, array('disabled' => 'disabled', 'class' => 'form-control')) !!}
                @else
                    {!! Form::text('id', null, array('disabled' => 'disabled', 'class' => 'form-control')) !!}
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
                {!! Form::text('title', $quote_request->title, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
            </div>
        </div>

        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Select the quantity that the customer has chosen and create a job based on this<br />
        </div>

        <div class="form-group">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th width="10%">Quantity</th>
                    <th width="50%">Description</th>
                    <th width="10%">Price</th>
                    <th width="10%">GST</th>
                    <th width="10%">Total</th>
                    <th width="10%">Unit Price</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody>
                @foreach($qris as $qri)
                    <tr>
                        <td>{{$qri->quantity}}</td>
                        <td>{{$qri->description}}</td>
                        <td>{{$qri->price}}</td>
                        <td>{{$qri->gst}}</td>
                        <td>{{$qri->total}}</td>
                        <td>{{$qri->unit_price}}</td>
                        <td>@if(isset($job))
                                @if($job->quote_request_items_id == $qri->id)
                                    {!! Form::radio('quote_request_items_id', $qri->id, 'checked' ) !!}
                                @else
                                    {!! Form::radio('quote_request_items_id', $qri->id ) !!}
                                @endif
                            @else
                                {!! Form::radio('quote_request_items_id', $qri->id ) !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <p style="float:right">
            <input type="submit" class="btn btn-primary" value="Create Job" />
        </p>

        {!! Form::close() !!}
    @endif

@endsection