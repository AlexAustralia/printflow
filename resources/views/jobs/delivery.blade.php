@extends('app')

@section('title')
    Delivery
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            var not_equal;

            $( "#delivery_date" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

            $.validator.addMethod(
                    "australianDate",
                    function(value, element) {
                        return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
                    },
                    "Please enter a valid date"
            );

            // Validation of the form
            var validator = $('form').validate(
                    {
                        rules: {
                            delivery_date: {
                                required: true,
                                australianDate : true
                            },
                            cartons: {
                                required: true,
                                number: true
                            },
                            delivery_address: {
                                required: true
                            }
                        },
                        messages: {
                            delivery_date: {
                                required: "You should specify a date"
                            },
                            cartons: {
                                required: "Enter Number of Cartons"
                            },
                            delivery_address: {
                                required: "You must choose a valid delivery address"
                            }
                        }
                    }
            );

            // Cancel button
            $('.cancel').on('click', function() {
                location.href="{{URL::to('/')}}"
            });

            // Edit button
            $('#edit').on('click', function() {
                var id = $('#delivery_address').val();
                location.href='/customer/edit_address/' + id + '/{{ $quote->id }}';
            });

            $('#go-to-page2').on('click', function() {
                $('#delivery_form').valid();
                if (!$('#delivery_date').hasClass('error') && !$('#delivery_address').hasClass('error')) {
                    $('#page-1').hide();
                    $('#page-2').show();

                    $('#delivery_date_confirm').val($('#delivery_date').val());
                    $('#qty_deliver_confirm').val($('#qty_deliver').val());
                    $('#delivery_address_confirm').val($.trim($('#delivery_address option:selected').text()));
                }
            });

            $('#go-to-page3').on('click', function() {
                not_equal = false;
                var number = [];
                $('#act').html('');

                $('#page-2').find('.line').each(function(){
                    if ($(this).find('input').val() == '') {
                        $(this).remove();
                    } else {
                        var item = parseInt($(this).find('input').val());
                        if (item == 0 || isNaN(item)) {
                            $(this).remove();
                        } else {
                            not_equal = true;
                            number.push(item);
                        }
                    }
                });

                var item = parseInt($('#items').val());
                if (!not_equal && (item == 0 || isNaN(item))){
                    $('#no_items').modal('show');
                } else {
                    $('#page-2').hide();
                    $('#page-3').show();

                    var html = '';

                    if (not_equal) {
                        html = add_line('Number of Cartons', number.length);
                        $('#act').append('<input type="hidden" name="cartons" value="' + number.length + '">');

                        for (var i = 1; i <= number.length; i++) {
                            html += add_line('Carton ' + i, number[i - 1]);
                            $('#act').append('<input type="hidden" name="qtys[]" value="' + number[i - 1] + '">');
                        }
                    } else {
                        html = add_line('Number of Cartons', $('#cartons').val());
                        html += add_line('Number Per Carton', $('#items').val());
                        $('#act').append('<input type="hidden" name="cartons" value="' + $('#cartons').val() + '">');
                    }

                    $('#qty_confirm').html(html);
                 }
            });

            function add_line(label, value) {
                var html = '<div class="form-group col-md-12"><label class="control-label col-md-3">' + label + '</label>' +
                        '<div class="col-md-2"><input type="text" class="form-control" value="' + value + '" disabled></div></div>';
                return html;
            }

            $('#page-2').on('click', '#back-to-page1', function() {
                $('#page-2').hide();
                $('#page-1').show();
            });

            $('#page-3').on('click', '#back-to-page2', function() {
                $('#page-3').hide();
                $('#page-2').show();
            });

            $('#add_carton').on('click', function() {
                var number = $('#page-2').find('.lines:last').find('label').text();
                if (number == '') {
                    number = 1;
                } else {
                    number = parseInt(number.slice(7, number.length)) + 1;
                }
                $('#page-2').find('.lines:last').after('<div class="form-group col-md-12 lines line">' +
                    '<label class="control-label col-md-3">Carton ' + number + '</label>' +
                    '<div class="col-md-2"><input type="text" class="form-control not_equal" name="number[]"></div>' +
                    '<div class="col-md-1"><button class="btn btn-sm btn-danger btn-delete-row" type="button"><span class="fa fa-trash-o"></span></button>' +
                    '</div></div>');
            });

            $('#page-2').on('click', '.btn-delete-row', function() {
                $(this).parents('.lines').remove();
            })

            $('#delivery-button').on('click', function() {
                $('#history-page').hide();
                $('#delivery-page').show();
                $('#delivery-button').attr('disabled', true);
                $('#history-button').removeAttr('disabled');
            });

            $('#history-button').on('click', function() {
                $('#delivery-page').hide();
                $('#history-page').show();
                $('#history-button').attr('disabled', true);
                $('#delivery-button').removeAttr('disabled');
            });

            $('#dispatched').on('click', function() {
                $(this).after('<div class="loader"><img src="{{asset('images/loading-lg.gif')}}" height="30px"></div>');
                $(this).hide();

                $.ajax({
                    type        : 'GET',
                    url         : '/change_status/{{ $quote->id }}/7',
                    success: function(response) {
                        $('#dispatched').attr('disabled', true).show();
                        $('.loader').remove();
                    },
                    error: function() {
                        $('#ajax_error_text').html('<p>Some errors occurred while storing the new status</p>');
                        $('#ajax_error').modal('show');
                        $('#dispatched').show();
                        $('.loader').remove();
                    }
                });

            });
        });
    </script>

    <!-- Modal -->
    <div class="modal fade" id="no_items" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Warning</h4>
                </div>
                <div class="modal-body">
                    <p>You should enter <strong>Number Per Carton</strong> and it must be a <strong>number</strong> greater than 0!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajax_error" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Error!</h4>
                </div>
                <div class="modal-body" id="ajax_error_text">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10">
            <h2><img src="/images/delivery.png"> Delivery</h2>
        </div>
    </div>
    <hr>

    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    <div class="btn-group nav" >
        <a class="btn btn-primary" id="delivery-button" href="javascript:;" @if($page == 'delivery') disabled @endif>Delivery</a>
        <a class="btn btn-primary" id="history-button" href="javascript:;" @if($page == 'history') disabled @endif>History</a>
    </div>
    <div class="pull-right">
        <button class="btn btn-primary" id="dispatched" @if($quote->status == 7) disabled @endif>Dispatched</button>
    </div>
    <p style="clear:both; margin-bottom:40px;"></p>

    <div id="delivery-page" @if($page != 'delivery') style="display: none;" @endif>
        {!! Form::open(array('url' => 'job/delivery', 'method' => 'post', 'id' => 'delivery_form', 'class' => 'form-horizontal')) !!}
        {!! Form::hidden('job_id', $quote->id) !!}

        <div id="page-1">
            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('customer', 'Customer', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-6">
                        {!! Form::text('customer', $quote->customer->customer_name, array('id' => 'customer', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('job_title', 'Job Name', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-6">
                        {!! Form::text('job_title', $quote->title, array('id' => 'job_title', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('job_qty', 'Job Quantity', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-2">
                        {!! Form::text('job_qty', $quote->job->job_item->quantity, array('id' => 'job_qty', 'class' => 'form-control', 'disabled' => 'disabled')) !!}
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::label('delivery_date', 'Delivery Date *', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-2">
                        {!! Form::text('delivery_date', null, array('id' => 'delivery_date', 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('qty_deliver', 'Quantity to be Delivered', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-2">
                        {!! Form::text('qty_deliver', null, array('id' => 'qty_deliver', 'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('delivery_address', 'Delivery Address *', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-6">
                        <select id="delivery_address" name="delivery_address" class="form-control">
                            @foreach ($delivery_addresses as $delivery_address)
                                <option value="{{ $delivery_address->id }}">
                                    {{ $delivery_address->name }} {{ $delivery_address->address }} {{ $delivery_address->city }}, {{ $delivery_address->state }} {{ $delivery_address->postcode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-warning" id="edit">Edit</button>
                    <a href="/customer/{{ $quote->customer->id }}/create_address/{{$quote->id}}" type="button" class="btn btn-primary">Create New</a>
                </div>
            </div>

            <hr>

            <div class="pull-right">
                <button type="button" class="btn btn-danger cancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="go-to-page2">Next</button>
            </div>
        </div>

        <div id="page-2" style="display: none;">
            <div class="row">
                <div class="col-xs-12">
                    <h4 style="font-weight: bold; margin-bottom: 20px;">If Quantities Are Equal:</h4>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('cartons', 'Number of Cartons', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-2">
                        <select class="form-control" id="cartons">
                            @for($i = 1; $i <= 100; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('items', 'Number Per Carton', array('class' => 'control-label col-md-3')) !!}
                    <div class="col-md-2">
                        {!! Form::text('items', null, array('id' => 'items', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-5">
                    <h4 style="font-weight: bold; margin-bottom: 20px;">If Quantities Are Not Equal Specify Below:</h4>
                </div>

                <div class="col-md-7 lines">
                    <button type="button" class="btn btn-primary" id="add_carton">Add a Carton</button>
                </div>
            </div>

            <hr>

            <div class="pull-right">
                <button type="button" class="btn btn-danger cancel">Cancel</button>
                <button type="button" class="btn btn-warning" id="back-to-page1">Back</button>
                <button type="button" class="btn btn-primary" id="go-to-page3">Next</button>
            </div>
        </div>

        <div id="page-3" style="display: none;">
            <div class="row">
                <div class="col-md-5">
                    <h4 style="font-weight: bold; margin-bottom: 20px;">Confirm Details:</h4>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Customer</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="customer_confirm" value="{{ $quote->customer->customer_name }}" disabled>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Job Name</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="job_title_confirm" value="{{ $quote->title }}" disabled>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Job Quantity</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="job_qty_confirm" value="{{ $quote->job->job_item->quantity }}" disabled>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Delivery Date</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="delivery_date_confirm" disabled>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Quantity to be Delivered</label>
                    <div class="col-md-2">
                        <input type="text" class="form-control" id="qty_deliver_confirm" disabled>
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label class="control-label col-md-3">Delivery Address</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="delivery_address_confirm" disabled>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row" id="qty_confirm"></div>

            <hr>

            <div class="pull-right">
                <button type="button" class="btn btn-danger cancel">Cancel</button>
                <button type="button" class="btn btn-warning" id="back-to-page2">Back</button>
                <button type="submit" class="btn btn-primary">Create Sticker and Docket</button>
            </div>
        </div>

        <div id="act"></div>
        {!! Form::close() !!}
    </div>

    <div id="history-page" @if($page != 'history') style="display: none;" @endif>
        <div class="col-xs-6 col-xs-offset-3">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Delivery Date</th>
                    <th>Docket Number</th>
                    <th>Sticker Number</th>
                    <th>Delivered Quantity</th>
                </tr>
                </thead>
                <tbody>
                @if(count($delivery_history) > 0)
                    @foreach($delivery_history as $line)
                        <tr>
                            <td>{{ $line->delivery_date }}</td>
                            <td><a href="/job/delivery/docket/{{ $line->id }}" target="_blank">{{ $line->number }}</a></td>
                            <td><a href="/job/delivery/sticker/{{ $line->id }}" target="_blank">{{ $line->number }}</a></td>
                            <td>{{ unserialize($line->input)['qty_deliver'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No Dockets and Stickers created yet</td>
                    </tr>
                @endif
                </tbody>
            </table>
            </table>
        </div>
    </div>

@endsection