
@extends('app')

@section('title')
Evaluate Prices
@endsection

@section('content')
    <script>
        $(document).ready(function(){
            $("[data-toggle='tooltip']").tooltip();
        });
    </script>


{!! Form::open(array('method' => 'post', 'class' => 'form-horizontal')) !!}

@if (count($quote_request_lines) == 0)
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>Warning</h4>
        You should create a quote request and specify a quantity before evaluating prices
    </div>
@elseif($error)
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>Warning</h4>
        You should enter all supplier prices before evaluating them
    </div>
@else

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

<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    Choose the supplier you want to base your quote on. <br />
    These prices are the total net cost to the customer.
</div>


<table width="100%" class="table table-hover">
    <tr>
        <th>Supplier:</th>
        @foreach($quote_request_lines as $line)
        <th><span data-toggle="tooltip" title="{{$line->description}}">{!! $line->quantity !!}</span></th>
        @endforeach
        <th>&nbsp;</th>
    </tr>

    @foreach($quote_request->quotes as $q)
    <tr>
        <td>{!! $q->supplier->supplier_name !!}</td>
        @foreach($q->quote_items() as $qi)
        <td>{!! $qi['total_inc_gst'] !!}</td>
        @endforeach
        <td>@if($quote_request->quote_id == $q->id)
            {!! Form::radio('quote_id', $q->id, 'checked' ) !!}
            @else
            {!! Form::radio('quote_id', $q->id ) !!}
            @endif
        </td>
    </tr>
    @endforeach

</table>

<p style="float:right">
    <input type="submit" class="btn btn-primary" value="Create Quote" />
</p>

@endif

{!! Form::close() !!}

@endsection
