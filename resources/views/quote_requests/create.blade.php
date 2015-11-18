@extends('app')

@section('content')

<script>
  $(function() {
    $( "#request_date" ).datepicker();
    $( "#expiry_date" ).datepicker();
    $( "#customer" ).autocomplete({
        source:'/json/customers',
        select: function (event, ui) {
            $("#customer").val(ui.item.label);
            $("#customer_id").val(ui.item.value);
            return false;
        },
        change: function (event, ui) {
            $("#customer_id").val( ui.item ? ui.item.value : '' );
        }
    });
  });
</script>

    <h2>Create a new quote request</h2>

    <div style="width:80%; margin-left:auto; margin-right:auto;">

{!! Form::open(array('action' => 'QuoteRequestsController@store')) !!}

    <!-- https://jqueryui.com/autocomplete/#custom-data -->
    <!-- Customer list to be modified jquery autocomplete dropdown 
         with hidden id field -->
    <p style="float:left">
    {!! Form::label('customer', 'Customer') !!}<br />
    {!! Form::text('customer', null, array('id' => 'customer')) !!}
    {!! Form::hidden('customer_id', null, array('id' => 'customer_id')) !!}
    <p>
    
    <p style="float:left">
    {!! Form::label('request_date', 'Request Date') !!}<br />
    {!! Form::text('request_date', null, array('id' => 'request_date')) !!}
    </p>

    <p style="float:left">
    {!! Form::label('expiry_date', 'Expiry Date') !!}<br />
    {!! Form::text('expiry_date', null, array('id' => 'expiry_date')) !!}
    </p>

    <p style="float:left">
    {!! Form::label('id', 'Quote Number') !!}<br />
    {!! Form::text('id') !!}
    <p>

    <p style="float:left">
    {!! Form::label('ref', 'Ref') !!}<br />
    {!! Form::text('ref', null, array()) !!}
    </p>

    <hr style="clear:both; visibility:hidden">

    <p style="float:left">
    {!! Form::label('title', 'Title') !!}<br />
    {!! Form::text('title') !!}
    </p>

    <hr style="clear:both; visibility:hidden">

    <p>
    {!! Form::label('summary', 'Summary') !!}<br />
    {!! Form::textarea('summary', null,
                array('rows' => '5')) !!}
    </p>

    <hr style="clear:both; visibility:hidden">

    <p>Quote Request Items go here</p>
    
    <p>
    {!! Form::label('terms', 'Terms') !!}<br />
    {!! Form::textarea('terms', 'Default Terms', 
                array('rows' => '5')) !!}
    </p>
    
    <p>
    {!! Form::submit('Save') !!}
    {!! Form::submit('Send RFQ') !!}
    {!! Form::submit('Cancel') !!}
    </p>

{!! Form::close() !!}

    </div>

@endsection
