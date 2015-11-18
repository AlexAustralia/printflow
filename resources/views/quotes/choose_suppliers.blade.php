
@extends('app')

@section('title')
Choose Suppliers
@endsection

@section('content')

<script>
  $(function() {
    $( "#supplier" ).autocomplete({
        source:'/json/suppliers',
        select: function (event, ui) {
            $("#supplier").val(ui.item.label);
            $("#supplier_id").val(ui.item.value);
            return false;
        },
        change: function (event, ui) {
            $("#supplier_id").val( ui.item ? ui.item.value : '' );
        }
    });
  });
</script>


{!! Form::open(array('url' => '/choose_suppliers/'.$quote_request->id, 'method' => 'post')) !!}

    <p>
        {!! Form::label('supplier', 'Choose Suppliers') !!} <br />
        <div class="input-group">
            {!! Form::text('supplier', null, array('id' => 'supplier', 'class' => 'form-control', 'placeholder' => 'Start typing a supplier name...')) !!}
            <span class="input-group-btn">
                <input name="submit" type="submit" value="Add" class="btn btn-primary" />
            </span>
        </div>
        {!! Form::hidden('supplier_id', null, array('id' => 'supplier_id')) !!}
    </p>

    <p>
        {!! Form::label('suppliers', 'Selected Suppliers') !!}<br />

        <div class="input-group">
            <select name="suppliers" class="form-control" size="10">:
                @foreach ($quote_request->quotes as $quote)
                    <option value="{!! $quote->supplier_id !!}">{!! $quote->supplier->supplier_name !!}</option>
                @endforeach
            </select>
            <span class="input-group-btn" style="vertical-align:bottom;">
                <input name="submit" type="submit" value="Remove" class="btn btn-primary" />
            </span>
        </div>
    </p>

{!! Form::close() !!}

@endsection
