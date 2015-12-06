
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
        <h4>Message</h4>
        No Quotes Found
    </div>
@else

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
        <td>{!! Form::radio('quote_id', $q->id) !!}</td>
    </tr>
    @endforeach

</table>

<p style="float:right">
    <input type="submit" class="btn btn-primary" value="Create Quote" />
</p>
<p style="clear:both:></p>
@endif

{!! Form::close() !!}

@endsection
