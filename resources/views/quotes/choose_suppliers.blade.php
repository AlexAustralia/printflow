@extends('app')

@section('title')
    Choose Suppliers
@endsection

@section('content')
    <script>
        $(document).ready(function() {

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

    @if(count($quote_request->qris) > 0)
        {!! Form::open(array('url' => '/choose_suppliers/'.$quote_request->id, 'method' => 'post', 'id' => 'choose_suppliers')) !!}

        <div class="form-group">
            {!! Form::label('supplier', 'Choose Suppliers', array('class' => 'control-label')) !!}

            <div class="input-group col-md-12">
                {!! Form::text('supplier', null, array('id' => 'supplier', 'class' => 'form-control', 'placeholder' => 'Start typing a supplier name...')) !!}
                <span class="input-group-btn" style="vertical-align: top;">
                <input name="submit" type="submit" value="Add" class="btn btn-primary" />
            </span>
            </div>
        </div>

        {!! Form::hidden('supplier_id', 0, array('id' => 'supplier_id')) !!}

        <div class="form-group">
            {!! Form::label('suppliers', 'Selected Suppliers') !!}

            <div class="input-group">
                <select name="suppliers" class="form-control" size="10">:
                    @foreach ($quote_request->quotes as $quote)
                        <option value="{!! $quote->supplier_id !!}">{!! $quote->supplier->supplier_name !!}</option>
                    @endforeach
                </select>
            <span class="input-group-btn" style="vertical-align: top;">
                <input name="submit" type="submit" value="Remove" class="btn btn-danger" />
            </span>
            </div>
        </div>

        {!! Form::close() !!}
    @else
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Warning</h4>
            You should create a quote request and specify a quantity before choosing suppliers
        </div>
    @endif
@endsection
