@extends('app')
@section('title')
    Freight
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('fancybox/source/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.file-input.js') }}"></script>
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>

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

    <div class="modal fade" id="delete_line" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this freight line?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirm">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
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

    {!! Form::open(['method' => 'post', 'class' => 'form-horizontal', 'id' => 'freight_form', 'files' => true]) !!}

    <button type="button" class="btn btn-primary" id="add_new_row">Add New Freight Price</button>
    <hr>

    <?php $row = 0; ?>
    @foreach($freights as $freight)
        <?php $row++; ?>
        <div class="well" id="freight-{{ $row }}"><div class="row">
        <input name="freight[{{ $row }}][freight_id]" type="hidden" value="{{ $freight->id }}">

        <div class="col-md-12">
            <div class="col-md-2 form-group">
                <label for="freight[{{ $row }}][type]" class="control-label">Freight Type</label>
            </div>
            <div class="col-md-3 form-group">
                <select class="form-control freight-type" name="freight[{{ $row }}][type]" disabled>
                    <option value="1" @if($freight->type == 1) selected="selected" @endif>SEA FREIGHT</option>
                    <option value="2" @if($freight->type == 2) selected="selected" @endif>AIR FREIGHT</option>
                    <option value="3" @if($freight->type == 3) selected="selected" @endif>LOCAL DELIVERY</option>
                </select>
                <input type="hidden" name="freight[{{ $row }}][type]" value="{{ $freight->type }}">
            </div>
            <div class="col-md-2 col-md-offset-5">
                <input name="freight[{{ $row }}][freight_id_chosen]" type="checkbox" value="{{ $row }}" @if($freight->include_in_quote) checked @endif>
                <label for="freight_id_chosen" class="control-label">Include In Quote</label>
            </div>

            <button class="btn btn-sm btn-danger btn-delete-row pull-right" type="button" data-target="#freight-{{ $row }}"><span class="fa fa-trash-o"></span></button>
        </div>

        <table class="table col-md-12 table-hover">
            <tr>
                <th width="20%">Quantity</th>
                @foreach ($quote_request->qris as $qri)
                    <th width="{!! intval(80/count($quote_request->qris)) !!}%">
                        <span data-toggle="tooltip" title="{{$qri->description}}">{!! $qri->quantity !!}</span>
                        <input type="hidden" name="freight[{{ $row }}][qri_id][]" value="{{ $qri->id }}" />
                    </th>
                @endforeach
            </tr>

            @if($freight->type == 1)
                <tr>
                    <td>Sea Freight Supplier</td>
                    @foreach ($freight->freight_items as $line)
                        <td><input type="hidden" name="freight[{{ $row }}][id][]" value="{{ $line->id }}" />
                            <input type="hidden" name="freight[{{ $row }}][typex][]" value="1">
                            <input type="text" class="line-supplier" name="freight[{{ $row }}][supplier][]" @if($line->supplier_id > 0) value="{{ $line->supplier->supplier_name }}" @endif required>
                            <input type="hidden" class="line-supplier-id" name="freight[{{ $row }}][supplier_id][]" value="{{ $line->supplier_id }}">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>CBM</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-cbm" name="freight[{{ $row }}][cbm][]" value="{{ $line->cbm }}" required>
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>CBM Rate</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-cbm-rate" name="freight[{{ $row }}][cbm_rate][]" value="{{ $line->cbm_rate }}" required>
                        </td>
                    @endforeach
                </tr>

                <tr class="warning">
                    <td>Total CBM Charge</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-cbm-charge" name="freight[{{ $row }}][cbm_charge][]" value="{{ number_format($line->cbm_rate * $line->cbm, 2) }}" readonly>
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Fixed Freight Cost</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-fixed-cost" name="freight[{{ $row }}][fixed_cost][]" value="{{ $line->fixed_cost }}">
                        </td>
                    @endforeach
                </tr>

                <tr class="warning">
                    <td>Total Sea Freight Cost</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-total-cost" name="freight[{{ $row }}][total_cost][]" value="{{ $line->total_cost }}" readonly>
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Markup %</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-markup" name="freight[{{ $row }}][markup][]" value="{{ $line->markup }}" required>
                        </td>
                    @endforeach
                </tr>

                <tr class="warning">
                    <td>Markup Amount</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-markup-amount" name="freight[{{ $row }}][markup_amount][]" value="{{ number_format($line->total_cost * $line->markup / 100, 2) }}" readonly>
                        </td>
                    @endforeach
                </tr>

                <tr class="warning">
                    <td>Total</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-total" name="freight[{{ $row }}][total][]" value="{{ $line->total }}" readonly>
                        </td>
                    @endforeach
                </tr>

            @else

                <tr>
                    <td>Supplier</td>
                    @foreach ($freight->freight_items as $line)
                        <td><input type="hidden" name="freight[{{ $row }}][id][]" value="{{ $line->id }}" />
                            <input type="hidden" name="freight[{{ $row }}][typex][]" value="2">
                            <input type="text" class="line-supplier" name="freight[{{ $row }}][supplier][]" @if($line->supplier_id > 0) value="{{ $line->supplier->supplier_name }}" @endif required>
                            <input type="hidden" class="line-supplier-id" name="freight[{{ $row }}][supplier_id][]" value="{{ $line->supplier_id }}">
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Number of Items</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-number-items" name="freight[{{ $row }}][number_items][]" value="{{ $line->number_items }}" required>
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Total Cost</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-total-cost" name="freight[{{ $row }}][total_cost][]" value="{{ $line->total_cost }}" required>
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Markup %</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-markup" name="freight[{{ $row }}][markup][]" value="{{ $line->markup }}" required>
                        </td>
                    @endforeach
                </tr>

                <tr class="warning">
                    <td>Markup Amount</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-markup-amount" name="freight[{{ $row }}][markup_amount][]" value="{{ number_format($line->total_cost * $line->markup / 100, 2) }}" readonly>
                        </td>
                    @endforeach
                </tr>

                <tr class="warning">
                    <td>Total</td>
                    @foreach ($freight->freight_items as $line)
                        <td>
                            <input type="text" class="line-total" name="freight[{{ $row }}][total][]" value="{{ $line->total }}" readonly>
                        </td>
                    @endforeach
                </tr>

            @endif
        </table>

        <div class="col-xs-12">
            <div class="col-xs-2 form-group">
                <label class="control-label">Attach Files:</label>
            </div>

            <div class="col-xs-6">
                {!! Form::file('files-'.$row.'[]', array('multiple' => true)) !!}
            </div>

            @if(!is_null($freight->files))
                <div class="col-xs-6">
                    @foreach(unserialize($freight->files) as $key => $file)
                        {!! Form::checkbox('freight['.$row.'][erase_files]['.$key.']', $key, null) !!}
                        <a class="artwork_image" target="_blank" href="/uploads/attachments/{{ $file }}">{{ $file }}</a><br>
                    @endforeach
                    @if(count(unserialize($freight->files)) > 0)
                        <br> Tick to delete
                    @endif
                </div>
            @endif
        </div>

        </div></div>
    @endforeach




    <div class="clearfix"></div>

    <p style="float:right">
        <input type="submit" class="btn btn-primary" id="submit" value="Save" />
    </p>

    {!! Form::close() !!}

    <script>
        $(document).ready(function(){

            var row = {{ $row }};
            var row_to_delete;

            if(row == 0) {
                add_line();
                add_sea_freight($('#freight_form').find('.well:first'));
            }

            $("[data-toggle='tooltip']").tooltip();

            //Modify upload button
            $('input[type=file]').bootstrapFileInput();

            // Fancybox for image viewing
            $('.artwork_image').fancybox();

            // Deleting a freight charge
            $('#freight_form').on( 'click', '.btn-delete-row', function () {
                row_to_delete = this;
                $('#delete_line').modal('show');
            });

            $('#confirm').on('click', function() {
                $(row_to_delete).parents('.well').remove();
                $('#delete_line').modal('hide');
            });

            // Add a new freight charge
            $('#add_new_row').on('click', function(){
                add_line();
                add_sea_freight($('#freight_form').find('.well:first'));
            });

            // Freight type is changed
            $('#freight_form').on('change', '.freight-type', function() {
                // Sea Freight
                if(type = $(this).val() == 1) {
                    add_sea_freight($(this).parents('.well'));
                }
                else {
                    add_air_local_freight($(this).parents('.well'));
                }

            });

            function add_sea_freight(div){
                $(div).find('tbody').html('<tr>\
                        <td>Sea Freight Supplier</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td><input type="hidden" name="freight[' + row + '][typex][]" value="1">\
                            <input type="text" class="line-supplier" name="freight[' + row + '][supplier][]" required>\
                            <input type="hidden" class="line-supplier-id" name="freight[' + row + '][supplier_id][]">\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>CBM</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-cbm" name="freight[' + row + '][cbm][]" required>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>CBM Rate</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-cbm-rate" name="freight[' + row + '][cbm_rate][]" required>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr class="warning">\
                        <td>Total CBM Charge</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" name="freight[' + row + '][cbm_charge][]" class="line-cbm-charge" readonly>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>Fixed Freight Cost</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-fixed-cost" name="freight[' + row + '][fixed_cost][]">\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr class="warning">\
                        <td>Total Sea Freight Cost</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-total-cost" name="freight[' + row + '][total_cost][]" readonly>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>Markup %</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-markup" name="freight[' + row + '][markup][]" required>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr class="warning">\
                        <td>Markup Amount</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" name="freight[' + row + '][markup_amount][]" class="line-markup-amount" readonly>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr class="warning">\
                        <td>Total</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-total" name="freight[' + row + '][total][]" readonly>\
                            </td>\
                        @endforeach
                        </tr>');
            }

            function add_air_local_freight(div){
                $(div).find('tbody').html('<tr>\
                        <td>Supplier</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="hidden" name="freight[' + row + '][typex][]" value="2">\
                            <input type="text" class="line-supplier" name="freight[' + row + '][supplier][]" required>\
                            <input type="hidden" class="line-supplier-id" name="freight[' + row + '][supplier_id][]">\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>Number of Items</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-number-items" name="freight[' + row + '][number_items][]" required>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>Total Cost</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-total-cost" name="freight[' + row + '][total_cost][]" required>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr>\
                        <td>Markup %</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-markup" name="freight[' + row + '][markup][]" required>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr class="warning">\
                        <td>Markup Amount</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" name="freight[' + row + '][markup_amount][]" class="line-markup-amount" readonly>\
                            </td>\
                        @endforeach
                        </tr>\
                        <tr class="warning">\
                        <td>Total</td>\
                        @foreach ($quote_request->qris as $qri)
                            <td>\
                            <input type="text" class="line-total" name="freight[' + row + '][total][]" readonly>\
                            </td>\
                        @endforeach
                        </tr>');
            }

            function add_line(){
                row++;
                $('#freight_form').find('div:first').before('<div class="well" id="freight-' + row + '"><div class="row">\
                        <div class="col-md-12">\
                            <div class="col-md-2 form-group">\
                                <label for="freight[' + row + '][type]" class="control-label">Freight Type</label>\
                            </div>\
                            <div class="col-md-3 form-group">\
                                <select class="form-control freight-type" name="freight[' + row + '][type]">\
                                    <option value="1">SEA FREIGHT</option>\
                                    <option value="2">AIR FREIGHT</option>\
                                    <option value="3">LOCAL DELIVERY</option>\
                                </select>\
                            </div>\
                            <div class="col-md-2 col-md-offset-5">\
                            <input name="freight[' + row + '][freight_id_chosen]" type="checkbox" value="' + row + '">\
                            <label for="freight_id_chosen" class="control-label">Include In Quote</label>\
                        </div>\
                        <button class="btn btn-sm btn-danger btn-delete-row pull-right" type="button" data-target="#freight-' + row + '"><span class="fa fa-trash-o"></span></button>\
                        </div>\
                        <table class="table col-md-12 table-hover"><thead>\
                        <tr>\
                        <th width="20%">Quantity</th>\
                        @foreach ($quote_request->qris as $qri)
                            <th width="{!! intval(80/count($quote_request->qris)) !!}%">\
                        <span data-toggle="tooltip" title="{{$qri->description}}">{!! $qri->quantity !!}</span>\
                        <input type="hidden" name="freight[' + row + '][qri_id][]" value="{{ $qri->id }}" />\
                        </th>\
                        @endforeach
                    </tr></thead><tbody></tbody></table>\
                    <div class="col-xs-12">\
                        <div class="col-xs-2 form-group">\
                        <label class="control-label">Attach Files:</label>\
                    </div>\
                    <div class="col-xs-6">\
                        <input multiple="1" name="files-' + row + '[]" type="file">\
                    </div></div>');
            }

            $('#freight_form').on('keyup', '.line-supplier', function(){
                $(this).autocomplete({
                    source:'/json/suppliers',
                    select: function (event, ui) {
                        $(this).val(ui.item.label);
                        $(this).parents('td').find(".line-supplier-id").val(ui.item.value);
                        return false;
                    },
                    change: function (event, ui) {
                        $(this).parents('td').find(".line-supplier-id").val( ui.item ? ui.item.value : '' );
                    }
                });
            });

            $('#freight_form').on('input', '.line-cbm-rate', function() {
                var row = $(this).parents('.well').attr('id');
                row = row.substr(8, row.length - 8);
                update_total(row);
            }).on('input', '.line-cbm', function() {
                var row = $(this).parents('.well').attr('id');
                row = row.substr(8, row.length - 8);
                update_total(row);
            }).on('input', '.line-fixed-cost', function() {
                var row = $(this).parents('.well').attr('id');
                row = row.substr(8, row.length - 8);
                update_total(row);
            }).on('input', '.line-markup', function() {
                var row = $(this).parents('.well').attr('id');
                row = row.substr(8, row.length - 8);
                update_total(row);
            }).on('input', '.line-total-cost', function() {
                var row = $(this).parents('.well').attr('id');
                row = row.substr(8, row.length - 8);
                update_total(row);
            });

            var find = function(row, name, index) {
                return $('input[name="freight[' + row + '][' + name + '][]"]').eq(index);
            };

            function update_total(row){
                var index = 0;
                var cell = $('input[name="freight[' + row + '][markup][]"]');

                cell.each(function(){
                    var cbm = parseFloat(find(row, 'cbm', index).val()) || 0;
                    var cbm_rate = parseFloat(find(row, 'cbm_rate', index).val()) || 0;
                    var cbm_charge = cbm * cbm_rate;
                    find(row, 'cbm_charge', index).val(cbm_charge.toFixed(2));

                    var fixed_cost = parseFloat(find(row, 'fixed_cost', index).val()) || 0;
                    var total_cost = cbm_charge + fixed_cost;

                    var type = parseFloat(find(row, 'typex', index).val());

                    if(type == 1) {
                        find(row, 'total_cost', index).val(total_cost.toFixed(2));
                    }
                    else {
                        total_cost = parseFloat(find(row, 'total_cost', index).val()) || 0;
                    }

                    var markup = parseFloat(find(row, 'markup', index).val()) || 0;
                    var markup_amount = total_cost * markup / 100;
                    find(row, 'markup_amount', index).val(markup_amount.toFixed(2));

                    var total = total_cost + markup_amount;
                    find(row, 'total', index).val(total.toFixed(2));

                    index++;
                });

            }

            // Validation of the form
            $('#freight_form').validate();

            $('#submit').on('click', function() {
                var errors = false;

                $('#freight_form').find('.line-supplier-id').each(function() {
                    if($(this).val() < 1) {
                        $('#error_supplier').modal('show');
                        errors = true;
                        $(this).parents('td').find('.line-supplier').val('');
                    }
                });

                if(!errors) {
                    $('#freight_form').submit();
                }
                else {
                    return false;
                }
            });
        });
    </script>

@endsection
