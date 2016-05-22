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

    @if (count($errors) > 0)
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Error</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Tick needed categories for this job:<br />
        </div>

        <div class="form-group">
            <div class="col-md-4">
            <div class="col-md-2">
                @if(isset($job))
                    {!! Form::checkbox('outside_work', 1, $job->outside_work) !!}
                @else
                    {!! Form::checkbox('outside_work', 1, null) !!}
                @endif
            </div>
            <div class="col-md-10">
                {!! Form::label('outside_work', 'Outside Work', array('class' => 'control-label')) !!}
            </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-2">
                    @if(isset($job))
                        {!! Form::checkbox('design', 1, $job->design) !!}
                    @else
                        {!! Form::checkbox('design', 1, null) !!}
                    @endif
                </div>
                <div class="col-md-10">
                    {!! Form::label('design', 'Design', 1, array('class' => 'control-label')) !!}
                </div>
            </div>

            <div class="col-md-4">
                <div class="col-md-2">
                    @if(isset($job))
                        {!! Form::checkbox('on_proof', 1, $job->on_proof) !!}
                    @else
                        {!! Form::checkbox('on_proof', 1, null) !!}
                    @endif
                </div>
                <div class="col-md-10">
                    {!! Form::label('on_proof', 'On Proof', 1, array('class' => 'control-label')) !!}
                </div>
            </div>
        </div>

        <p style="float:right">
            <input type="submit" class="btn btn-primary" value="@if(isset($job))Edit @else Create @endif Job" />
        </p>

        {!! Form::close() !!}
    @endif

@endsection