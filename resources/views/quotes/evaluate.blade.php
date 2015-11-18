
@extends('app')

@section('title')
Evaluate Prices
@endsection

@section('content')

{!! Form::open(array('method' => 'post', 'class' => 'form-horizontal')) !!}

@if (count($quantities) == 0)
    No quotes found
@else

<p style="margin: 30px 0">
    Choose the supplier you want to base your quote on. <br /> 
    These prices are the total net cost to the customer.
</p>


<table width="100%" class="table">

    <tr>
        <td>Supplier:</td>
        @foreach($quantities as $q)
        <td>{!! $q !!}</td>
        @endforeach
        <td>&nbsp;</td>
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
