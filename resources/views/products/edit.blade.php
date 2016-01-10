@extends('app')

@section('title')
    @if(isset($product)) Edit Product - {{$product->name}}
    @else Create Product
    @endif
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('fancybox/source/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.file-input.js') }}"></script>
    <script>
        $(document).ready(function () {
            // Cancel button
            $('#cancel').on('click', function() {
                location.href="{{URL::to('/products')}}"
            });

            //Modify upload button
            $('input[type=file]').bootstrapFileInput();

            $('#image').fancybox();

            // Autocomplete supplier name
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

            // Validation of the form
            $('#product_form').validate(
                    {
                        rules: {
                            name: {
                                required: true,
                            },
                            supplier: {
                                required: true,
                            },
                            length: {
                                number: true,
                            },
                            height: {
                                number: true,
                            },
                            width: {
                                number: true,
                            },
                            diameter: {
                                number: true,
                            },
                            unit_price_from: {
                                number: true,
                            },
                            unit_price_to: {
                                number: true,
                            },
                            minimum_order_quantity: {
                                number: true,
                            },
                            production_lead_time: {
                                number: true,
                            },
                        },
                    }
            );

            // Submitting the form
            $('#submit_form').click(function(){

                // If Supplier is not valid, clear the supplier field
                if($('#supplier_id').val() == 0){
                    $('#supplier').val('');
                }

                $('#product_form').submit();
            });

        });
    </script>
    {!! Form::open(array('url' => 'products/save', 'method' => 'post', 'id' => 'product_form', 'class' => 'form-horizontal', 'files' => true)) !!}

    @if(isset($product))
        {!! Form::hidden('id', $product->id) !!}
    @else
        {!! Form::hidden('id', 0) !!}
    @endif

    @if(isset($supplier))
        {!! Form::hidden('page', $supplier->id)!!}
    @else
        {!! Form::hidden('page', 0)!!}
    @endif

    <div class="row">
        <div class="col-sm-10">
            @if(isset($product))
                <h2 style="margin:0"> Edit Product - {{$product->name}}</h2>
            @else
                <h2 style="margin:0">Create Product</h2>
            @endif
        </div>

        <div class="col-sm-2 pull-right">
            {!! Form::submit('Save', ['class' => 'btn btn-primary', 'id' => 'submit_form']) !!}
            <div class="btn btn-danger" id="cancel">Cancel</div>
        </div>
    </div>
    <hr>

    @include('partials.errors')

    <div class="form-group">
        <div class="col-md-4">
            {!! Form::label('name', 'Product Name *', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('name', $product->name, array('id' => 'name', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('name', null, array('id' => 'name', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-4">
            {!! Form::label('supplier', 'Supplier *', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('supplier', $product->supplier->supplier_name, array('id' => 'supplier', 'class' => 'form-control')) !!}
                {!! Form::hidden('supplier_id', $product->supplier_id, array('id' => 'supplier_id')) !!}
            @else
                @if(isset($supplier))
                    {!! Form::text('supplier', $supplier->supplier_name, array('id' => 'supplier', 'class' => 'form-control')) !!}
                    {!! Form::hidden('supplier_id', $supplier->id, array('id' => 'supplier_id')) !!}
                @else
                    {!! Form::text('supplier', null, array('id' => 'supplier', 'class' => 'form-control')) !!}
                    {!! Form::hidden('supplier_id', 0, array('id' => 'supplier_id')) !!}
                @endif
            @endif
        </div>

        <div class="col-md-4"
        {!! Form::label('product_image', 'Product Image', array('class' => 'control-label', 'style' => 'margin-top:27px; font-weight:bold;')) !!}
        {!! Form::file('product_image', null) !!}
        @if(isset($product))
            @if(file_exists('uploads/products/'.$product->product_image) && ($product->product_image != null))
                <a id="image" href="/uploads/products/{{$product->product_image}}" class="btn btn-warning">View Product Image</a>
            @endif
        @endif

    </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('description', 'Product Description', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::textarea('description', $product->description, array('rows' => '4', 'id' => 'description', 'class' => 'form-control')) !!}
            @else
                {!! Form::textarea('description', null, array('rows' => '4', 'id' => 'description', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-6">
            {!! Form::label('print_options', 'Print Options', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('print_options', $product->print_options, array('id' => 'print_options', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('print_options', null, array('id' => 'print_options', 'class' => 'form-control')) !!}
            @endif

            {!! Form::label('material', 'Material', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('material', $product->material, array('id' => 'material', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('material', null, array('id' => 'material', 'class' => 'form-control')) !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3">
            {!! Form::label('length', 'Product Length', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('length', $product->length, array('id' => 'length', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('length', null, array('id' => 'length', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('height', 'Product Height', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('height', $product->height, array('id' => 'height', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('height', null, array('id' => 'height', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('width', 'Product Width', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('width', $product->width, array('id' => 'width', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('width', null, array('id' => 'width', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('diameter', 'Product Diameter', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('diameter', $product->diameter, array('id' => 'diameter', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('diameter', null, array('id' => 'diameter', 'class' => 'form-control')) !!}
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 alert alert-warning alert-block">
            {!! Form::label('unit_price_from', 'Unit Price From', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('unit_price_from', $product->unit_price_from, array('id' => 'unit_price_from', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('unit_price_from', null, array('id' => 'unit_price_from', 'class' => 'form-control')) !!}
            @endif

            {!! Form::label('unit_price_to', 'Unit Price To', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('unit_price_to', $product->unit_price_to, array('id' => 'unit_price_to', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('unit_price_to', null, array('id' => 'unit_price_to', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('minimum_order_quantity', 'Minimum Order Quantity', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('minimum_order_quantity', $product->minimum_order_quantity, array('id' => 'minimum_order_quantity', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('minimum_order_quantity', null, array('id' => 'minimum_order_quantity', 'class' => 'form-control')) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('sample_avaiable', 'Sample Available', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::select('sample_available', [0 => 'No', 1 => 'Yes'], $product->sample_available, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
            @else
                {!! Form::select('sample_available', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
            @endif
        </div>

        <div class="col-md-3">
            {!! Form::label('production_lead_time', 'Production Lead Time', array('class' => 'control-label')) !!}
            @if(isset($product))
                {!! Form::text('production_lead_time', $product->production_lead_time, array('id' => 'production_lead_time', 'class' => 'form-control')) !!}
            @else
                {!! Form::text('production_lead_time', null, array('id' => 'production_lead_time', 'class' => 'form-control')) !!}
            @endif
        </div>
    </div>


    {!! Form::close() !!}

@endsection