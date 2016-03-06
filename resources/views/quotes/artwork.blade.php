@extends('app')
@section('title')
    Artwork
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>

    <div class="modal fade" id="error_supplier" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Error</h4>
                </div>
                <div class="modal-body">
                    <p>Supplier is not chosen or selected incorrectly. Please select a proper supplier.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>


    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    {!! Form::open(['method' => 'post', 'class' => 'form-horizontal', 'id' => 'artwork_form']) !!}

    <button type="button" class="btn btn-primary" id="add_new_row">Add New Artwork Charge</button>

    <table class="table col-md-12" id="table">
        <thead>
        <tr>
            <th width="22%">Description *</th>
            <th width="20%">Supplier *</th>
            <th width="8%">Hours</th>
            <th width="8%">Rate</th>
            <th width="9%">Total Cost *</th>
            <th width="9%">Markup % *</th>
            <th width="11%">Markup Amount</th>
            <th width="10%">Total</th>
            <th width="3%"></th>
        </tr>
        </thead>
        <tbody>
        <?php $row = 0; $total_cost = 0; $total_amount = 0; $total_charge = 0; ?>
        @foreach($artwork_charges as $artwork)
        <?php $row++; $total_cost += $artwork->total_cost; $total_amount += $artwork->markup * $artwork->total_cost / 100; $total_charge += $artwork->total; ?>
        <tr class="lines" id="artwork-{{ $row }}">
            <td>
                <input style="width: 100%" type="text" class="form-control" name="artwork[{{ $row }}][description]" value="{{ $artwork->description }}" required></td>
                <input type="hidden" name="artwork[{{ $row }}][id]" value="{{ $artwork->id }}">
            <td>
                <input style="width: 100%" type="text" class="form-control line-supplier" name="artwork[{{ $row }}][supplier]" @if($artwork->supplier_id > 0) value="{{ $artwork->supplier->supplier_name }}" @endif required>
                <input type="hidden" class="line-supplier-id" name="artwork[{{ $row }}][supplier_id]" value="{{ $artwork->supplier_id }}">
            </td>
            <td><input style="width: 100%" type="text" class="form-control line-hours" name="artwork[{{ $row }}][hours]" value="{{ $artwork->hours }}"></td>
            <td><input style="width: 100%" type="text" class="form-control line-rate" name="artwork[{{ $row }}][rate]" value="{{ $artwork->rate }}"></td>
            <td><input style="width: 100%" type="text" class="form-control line-total-cost" name="artwork[{{ $row }}][total_cost]" value="{{ $artwork->total_cost }}" required></td>
            <td><input style="width: 100%" type="text" class="form-control line-markup" name="artwork[{{ $row }}][markup]" value="{{ $artwork->markup }}" required></td>
            <td><input style="width: 100%" type="text" class="form-control line-markup-amount" name="artwork[{{ $row }}][markup_amount]" value="{{ number_format($artwork->markup * $artwork->total_cost / 100, 2) }}" readonly></td>
            <td><input style="width: 100%" type="text" class="form-control line-total-charge" name="artwork[{{ $row }}][total]" value="{{ $artwork->total }}" readonly></td>
            <td><button class="btn btn-sm btn-danger btn-delete-row pull-right" type="button" data-target="#artwork-{{ $row }}"><span class="fa fa-trash-o"></span></button></td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <div class="col-md-12">
        <strong>Notes:</strong> For fixed cost enter <strong>Total Cost</strong> and leave empty <strong>Hours</strong> and <strong>Rate</strong>
    </div>

    <div class="col-md-4 col-md-offset-8" style="margin-top: 40px;">
        <div class="col-md-7 form-group">
            {!! Form::label('total_artwork_cost', 'Total Artwork Cost', ['class' => 'control-label']) !!}
        </div>
        <div class="col-md-5 form-group">
            {!! Form::text('total_artwork_cost', number_format($total_cost, 2), ['class' => 'form-control total_cost', 'readonly' => 'readonly']) !!}
        </div>

        <div class="col-md-7 form-group">
            {!! Form::label('markup_amount', 'Markup Amount', ['class' => 'control-label']) !!}
        </div>
        <div class="col-md-5 form-group">
            {!! Form::text('markup_amount', number_format($total_amount, 2), ['class' => 'form-control total_markup', 'readonly' => 'readonly']) !!}
        </div>

        <div class="col-md-7 form-group">
            {!! Form::label('artwork_charge', 'Sell Artwork (Ex GST)', ['class' => 'control-label']) !!}
        </div>
        <div class="col-md-5 form-group">
            {!! Form::text('artwork_charge', number_format($total_charge, 2), ['class' => 'form-control total_charge', 'readonly' => 'readonly']) !!}
        </div>
    </div>

    <p style="float:right">
        <input type="submit" class="btn btn-primary" id="submit" value="Save and Include in Quote" />
    </p>

    {!! Form::close() !!}

    <script>
        $(document).ready(function() {

            var row = {{ $row }};

            if(row == 0) add_line();

            // Deleting a row
            $('#table').on( 'click', '.btn-delete-row', function () {
                $(this).parents('tr').remove();
                recount_table();
            });

            $('#add_new_row').on('click', function(){
               add_line();
            });

            function add_line(){
                row++;
                $('#table').find('tr:last').after('<tr class="lines" id="artwork-' + row + '">\
                        <td><input style="width: 100%" type="text" class="form-control" name="artwork[' + row + '][description]" required></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-supplier" name="artwork[' + row + '][supplier]" required>\
                        <input type="hidden" class="line-supplier-id" name="artwork[' + row + '][supplier_id]"></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-hours" name="artwork[' + row + '][hours]"></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-rate" name="artwork[' + row + '][rate]"></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-total-cost" name="artwork[' + row + '][total_cost]" required></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-markup" name="artwork[' + row + '][markup]" required></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-markup-amount" name="artwork[' + row + '][markup_amount]" readonly></td>\
                        <td><input style="width: 100%" type="text" class="form-control line-total-charge" name="artwork[' + row + '][total]" readonly></td>\
                        <td><button class="btn btn-sm btn-danger btn-delete-row pull-right" type="button" data-target="#artwork-' + row + '"><span class="fa fa-trash-o"></span></button></td></tr>');
            }

            function recount_table() {
                var total_cost = 0;
                var total_markup = 0;
                var total_charge = 0;

                $('#table').find('tr.lines').each(function() {
                    var cost = parseFloat($(this).find('.line-total-cost').val());
                    var markup = parseFloat($(this).find('.line-markup-amount').val());

                    if(isNaN(cost)) cost = 0;
                    if(isNaN(markup)) markup = 0;

                    var charge = cost + markup;

                    $(this).find('.line-total-charge').val(charge.toFixed(2));

                    total_cost += cost;
                    total_markup += markup;
                    total_charge += charge;
                });

                $('.total_cost').val(total_cost.toFixed(2));
                $('.total_markup').val(total_markup.toFixed(2));
                $('.total_charge').val(total_charge.toFixed(2));
            }

            function recount_markup(line, total_cost, markup) {
                if(isNaN(markup)) markup = 0;
                if(isNaN(total_cost)) total_cost = 0;

                var markup_amount = markup * total_cost / 100;
                $(line).parents('tr').find('.line-markup-amount').val(markup_amount.toFixed(2));
                recount_table();
            }

            // Recount data if inputs are changed
            $('#table').on('change', '.line-markup', function() {
                var markup = parseFloat($(this).val());
                var total_cost = parseFloat($(this).parents('tr').find('.line-total-cost').val());

                recount_markup(this, total_cost, markup);
            });

            $('#table').on('change', '.line-total-cost', function() {
                var total_cost = parseFloat($(this).val());
                var markup = parseFloat($(this).parents('tr').find('.line-markup').val());

                recount_markup(this, total_cost, markup);
            });

            $('#table').on('change', '.line-rate', function() {
                var rate = parseFloat($(this).val());
                var hours = parseFloat($(this).parents('tr').find('.line-hours').val());

                if(isNaN(rate)) $(this).val('');

                if(!isNaN(rate) && !isNaN(hours)) {
                    var total_cost = rate * hours;
                    $(this).parents('tr').find('.line-total-cost').val(total_cost.toFixed(2));

                    var markup = parseFloat($(this).parents('tr').find('.line-markup').val());
                    recount_markup(this, total_cost, markup);
                }
            });

            $('#table').on('change', '.line-hours', function() {
                var hours = parseFloat($(this).val());
                var rate = parseFloat($(this).parents('tr').find('.line-rate').val());

                if(isNaN(hours)) $(this).val('');

                if(!isNaN(rate) && !isNaN(hours)) {
                    var total_cost = rate * hours;
                    $(this).parents('tr').find('.line-total-cost').val(total_cost.toFixed(2));

                    var markup = parseFloat($(this).parents('tr').find('.line-markup').val());
                    recount_markup(this, total_cost, markup);
                }
            });

            $('#table').on('keyup', '.line-supplier', function(){
                $(this).autocomplete({
                    source:'/json/suppliers',
                    select: function (event, ui) {
                        $(this).val(ui.item.label);
                        $(this).parents('tr').find(".line-supplier-id").val(ui.item.value);
                        return false;
                    },
                    change: function (event, ui) {
                        $(this).parents('tr').find(".line-supplier-id").val( ui.item ? ui.item.value : '' );
                    }
                });
            });

            // Validation of the form
            $('#artwork_form').validate();

            $('#submit').on('click', function() {
                var errors = false;

                $('#table').find('.line-supplier-id').each(function() {
                   if($(this).val() < 1) {
                       $('#error_supplier').modal('show');
                       errors = true;
                       $(this).parents('tr').find('.line-supplier').val('');
                   }
                });

                if(!errors) {
                    $('#artwork_form').submit();
                }
                else {
                    return false;
                }
            });
        });
    </script>

@endsection
