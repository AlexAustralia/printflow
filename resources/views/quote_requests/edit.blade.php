
@extends('app')

@section('title')
    Enter Quote Request
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            var rows = {{count($q->qris)}};   // Number of rows in the table

            if(rows == 0){
                rows = 1;
            }

            // Validation of the form
            $('#quote_form').validate(
                    {
                        rules: {
                            customer: {
                                required: true,
                            },
                            request_date: {
                                required: true,
                            },
                            title: {
                                required: true,
                            },
                        },
                    }
            );

            // Submitting the form
            $('#submit_quote').click(function(){

                // If Customer is not valid, clear the customer field
                if($('#customer_id').val() == 0){
                    $('#customer').val('');
                }

                $('#quote_form').submit();
            });

            // Delete row button
            $('#qri_items tbody').on('click', '#delete_row', function(){
                $($(this).parents("tr").get(0)).remove();
                rows--;
                if(rows == 1) {
                    $('#qri_items tbody').find('button').attr('disabled', true);
                }
            });

            $( "#request_date" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

            $( "#expiry_date" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

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

            $('#addRow').click(function(e) {

                rows++;
                $('#qri_items tbody').find('button').attr('disabled', false);

                var tbody = $('#qri_items').find("tbody"),
                        tr = tbody.find("tr:last"),
                        tr_new = tr.clone();
                tr_new.find("input[name='qri_id[]']").val('');
                // leave qri_quote_request_id alone..
                tr_new.find("input[name='qri_quantity[]']").val('');
                tr_new.find("input[name='qri_description[]']").val('');
                tr_new.find("input[name='qri_price[]']").val('');
                tr_new.find("input[name='qri_gst[]']").val('');
                tr_new.find("input[name='qri_total[]']").val('');
                tr_new.find("input[name='qri_unit_price[]']").val('');
                tr_new.find(':checkbox').prop("checked", false);

                tr.after(tr_new);
                $('#quote_form').validate();
            });

            var updateTotal = function() {
                var index = 0;
                var qtys = $('input[name="qri_quantity[]"]')

                qtys.each(function() {
                    qty = parseInt($(this).val()) || 1;

                    price = parseFloat($('input[name="qri_price[]"]').eq(index).val()) || 0;

                    var gst = price * 0.1;
                    $('input[name="qri_gst[]"]').eq(index).val(gst.toFixed(2));

                    var total = price + gst;
                    $('input[name="qri_total[]"]').eq(index).val(total.toFixed(2));

                    var unit_price = total/qty || 0;
                    if (unit_price == "Infinity"){
                        unit_price = 0;
                    }
                    $('input[name="qri_unit_price[]"]').eq(index).val(unit_price.toFixed(2));

                    index++;
                });
            };

            $(function () {
                $('#qri_items').delegate('input[name="qri_quantity[]"]', 'input', updateTotal)
                $('#qri_items').delegate('input[name="qri_price[]"]', 'input', updateTotal)
                $('#qri_items').delegate('input[name="title"]', 'input', updateTotal)
            });
        });
    </script>

    {!! Form::open(array('url' => 'quote_requests/'.$q->id, 'method' => 'put', 'id' => 'quote_form', 'class' => 'form-horizontal')) !!}

            <!-- https://jqueryui.com/autocomplete/#custom-data -->
    <!-- Customer list to be modified jquery autocomplete dropdown
         with hidden id field -->
    <div class="form-group">
        <div class="col-md-2">
            {!! Form::label('customer', 'Customer', array('class' => 'control-label')) !!}
            {!! Form::text('customer', $q->customer["customer_name"], array('id' => 'customer', 'class' => 'form-control')) !!}
            {!! Form::hidden('customer_id', $q->customer_id, array('id' => 'customer_id')) !!}

        </div>

        <div class="col-md-2">
            {!! Form::label('request_date', 'Request Date', array('class' => 'control-label')) !!}
            {!! Form::text('request_date', $q->request_date, array('id' => 'request_date', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('expiry_date', 'Expiry Date', array('class' => 'control-label')) !!}
            {!! Form::text('expiry_date', $q->expiry_date, array('id' => 'expiry_date', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('id', 'Quote Number', array('class' => 'control-label')) !!}
            {!! Form::text('id', $q->id, array('disabled' => 'disabled', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('ref', 'Ref', array('class' => 'control-label')) !!}
            {!! Form::text('ref', $q->ref, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('title', 'Title', array('class' => 'control-label')) !!}
            {!! Form::text('title', $q->title, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('summary', 'Summary', array('class' => 'control-label')) !!}
            {!! Form::textarea('summary', $q->summary, array('rows' => '4', 'class' => 'form-control')) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label('terms', 'Terms', array('class' => 'control-label')) !!}
            {!! Form::textarea('terms', $q->terms, array('rows' => '4', 'class' => 'form-control')) !!}
        </div>
    </div>
    <div class="form-group">
        <table id="qri_items" width="100%" class="table">
            <thead>
            <tr>
                <th width="8%">Quantity</th>
                <th width="35%">Description</th>
                <th width="8%">Price</th>
                <th width="8%">GST</th>
                <th width="8%">Total</th>
                <th width="8%">Unit Price</th>
                <th width="5%"></th>
            </tr>
            </thead>
            @if(count($q->qris) > 0)
                @foreach ($q->qris as $qri):
                <tr>
                    <td>{!! Form::hidden('qri_id[]', $qri->id, ['id' => 'qri_id']) !!}
                        {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                        {!! Form::text('qri_quantity[]', $qri->quantity, ['style' => 'width: 100%', 'required' => 'required', 'number' => 'number']) !!}</td>
                    <td>{!! Form::text('qri_description[]', $qri->description, ['style' => 'width: 100%']) !!}</td>
                    <td>{!! Form::text('qri_price[]', $qri->price, ['style' => 'width: 100%', 'required' => 'required', 'number' => 'number']) !!}</td>
                    <td>{!! Form::text('qri_gst[]', $qri->gst, ['style' => 'width: 100%']) !!}</td>
                    <td>{!! Form::text('qri_total[]', $qri->total, ['style' => 'width: 100%']) !!}</td>
                    <td>{!! Form::text('qri_unit_price[]', $qri->unit_price, ['style' => 'width: 100%']) !!}</td>
                    <td><button type="button" class="btn btn-sm btn-danger" id="delete_row" @if(count($q->qris) == 1) disabled @endif><span class="glyphicon glyphicon-remove"></span></button></td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td>{!! Form::hidden('qri_id[]', null, ['id' => 'qri_id']) !!}
                        {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                        {!! Form::text('qri_quantity[]', null, ['style' => 'width: 100%', 'required' => 'required', 'number' => 'number']) !!}</td>
                    <td>{!! Form::text('qri_description[]', null, ['style' => 'width: 100%']) !!}</td>
                    <td>{!! Form::text('qri_price[]', null, ['style' => 'width: 100%', 'required' => 'required', 'number' => 'number']) !!}</td>
                    <td>{!! Form::text('qri_gst[]', null, ['style' => 'width: 100%']) !!}</td>
                    <td>{!! Form::text('qri_total[]', null, ['style' => 'width: 100%']) !!}</td>
                    <td>{!! Form::text('qri_unit_price[]', null, ['style' => 'width: 100%']) !!}</td>
                    <td><button type="button" class="btn btn-sm btn-danger" id="delete_row" disabled><span class="glyphicon glyphicon-remove"></span></button></td>
                </tr>
            @endif
        </table>
        <div class="pull-right">
            <a id="addRow" class="btn btn-primary" role="button">Add another row</a>
        </div>
    </div>

    <div class="form-group">
        <div class="pull-right">
            <button type="button" class="btn btn-primary" id="submit_quote">Save</button>
            <a href="{{URL::previous()}}" class="btn btn-danger" role="button">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection
