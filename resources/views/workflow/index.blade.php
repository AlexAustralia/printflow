
@extends('app')

@section('title')
    Workflow
@endsection

@section('content')
    <style>
        #table_info{
            display:none;
        }
        table {
            font-size: 13px;
        }
        #table tr.lines td {
            border-left: 1px solid #dddddd;
        }
        #table tr.lines td:last-child {
            border-right: 1px solid #dddddd;
        }
    </style>

    <!-- Modal -->
    <div class="modal fade" id="delete_confirmation" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this quote / job?</p>
                </div>
                <div class="modal-footer">
                    {!! Form::open(array('url' => 'quote_requests/delete', 'method' => 'post')) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete" value="" class="btn btn-danger" id="delete_quote">OK</button>
                    {!! Form::close() !!}
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

    <!-- Main body-->
    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0;"><img src="/images/workflow.png"> Workflow</h2>
        </div>
        <div class="col-sm-2">
            <div class="pull-right">
                <a href="/quote_requests/create" class="btn btn-primary" style="margin-top: 30px;">Create Quote Request</a>
            </div>
        </div>
    </div>
    <hr>

    @if (!isset($array[0]))
        <div class="alert alert-warning alert-block">
            Nothing to Display: There are no Active Quotes and Jobs
        </div>
    @else
        <form class="form-horizontal">
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}">

            <div class="form-group col-md-4">
                {!! Form::label('filter', 'Filter Status', array('class' => 'control-label col-md-6')) !!}
                <div class="col-md-6">
                    <select class="form-control" id="filter">
                        <option value="all_jobs">All Jobs</option>
                        <option value="quote_in">Quote In</option>
                        <option value="supplier_quote">Supplier Quote</option>
                        <option value="quote_out">Quote Out</option>
                        <option value="new_job">New Jobs</option>
                        <option value="production">Production</option>
                        <option value="incoming">Incoming</option>
                        <option value="delivery">Delivery</option>
                        <option value="invoice">Invoice</option>
                    </select>
                </div>
            </div>

            <table class="table table-striped table-hover" id="table" style="display: none;">
                <thead>
                <tr>
                    <th width="7%">Quote Num</th>
                    <th width="7%">Job Num</th>
                    <th>Customer</th>
                    <th>Title</th>
                    <th width="10%">Quantity</th>
                    <th width="10%">Job Value</th>
                    <th>Due Date</th>
                    <th width="20%">Status</th>
                    <th width="5%"></th>
                </tr>
                </thead>
                <tbody>
                <?php $total = 0; ?>
                @foreach($array as $item)
                    <?php $total += $item['total']; ?>
                    <tr class="{{ strtolower(str_replace(' ', '_', $item['status'])) }} lines">
                        <td class="quote_number">{{$item['quote_number']}}</td>
                        <td>{{$item['job_number']}}</td>
                        <td><a href="{{URL::to('/customers/'.$item["customer_id"].'/edit')}}">{{$item['customer_name']}}<a/></td>
                        <td><a href="{{URL::to('quote_requests/'.$item["quote_number"].'/edit')}}" data-toggle="tooltip" title="{{$item['description']}}">{{$item['title']}}</a></td>
                        <td>{{$item['quantity']}}</td>
                        <td>{{$item['total']}}</td>
                        <td><span style="color: @if(Carbon\Carbon::createFromFormat('d/m/Y', $item['expiry_date']) > Carbon\Carbon::now()) #00aa00 @else #dd0000 @endif">{{$item['expiry_date']}}</span></td>
                        <td><select class="select" style="width:145px;">
                                @foreach($statuses as $status)
                                    @if($status->id != 9)
                                        <option value="{{$status->id}}" @if($status->id == $item['status_id']) selected="selected" @endif >
                                            {{$status->value}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle btn-sm"
                                        data-toggle="dropdown" aria-expanded="false">

                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{URL::to('quote_requests/'.$item["quote_number"].'/edit')}}">Edit</a></li>
                                    <li><a href="#">Duplicate</i></a></li>
                                    <li><a href="#" data-toggle="modal" value="{{$item['quote_number']}}" data-target="#delete_confirmation" onclick="new_val(this)">Delete</a></li>
                                    @if(!empty($item['job_number']) || $item['status'] == 'Invoice')
                                        <li class="divider"></li>
                                    @endif
                                    <li>@if(!empty($item['job_number']))
                                            <a href="{{URL::to('job/'.$item['job_number'].'/delivery')}}">Delivery</a>
                                        @endif
                                    </li>
                                    @if($item['status'] == 'Invoice')
                                        <li><a href="#" value="{{$item['job_number']}}" onclick="invoice_jobs(this);">Invoice</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5"><strong>Total</strong></td>
                    <td><strong>{{ $total }}</strong></td>
                    <td colspan="3"></td>
                </tr>
                </tfoot>
            </table>
        </form>
    @endif
    <hr>

    <script>
        function invoice_jobs(arg){
            var job_number = $(arg).attr('value');
            var line = $($(arg).parents('tr').get(0));

            //window.open("{{{ URL::to('/send_invoice') }}}", "_blank");

            $(line).hide();
            $(line).after('<tr class="loader"><td colspan="3"></td><td colspan="5"><img src="{{asset('images/loading-lg.gif')}}" height="30px"></td></tr>');

            $.ajax({
                type        : 'GET',
                url         : '/send_invoice/' + job_number,
                success: function(response) {
                    $('#table').find('tr.loader').remove();

                    if(response == 'OK'){
                        $('#ajax_error_text').html('<p>The Invoice has been sent to Xero successfully</p>');
                        $('#ajax_error').find('h4').html('Success');
                        $('#ajax_error').modal('show');

                        // Delete completed jobs from the table
                        $(line).addClass('selected');
                        oTable.row('.selected').remove().draw(false);
                    }
                    else {
                        $('#ajax_error_text').html('<p>Some errors occurred while sending the Invoice to Xero</p>');
                        $('#ajax_error').find('h4').html('Error!');
                        $('#ajax_error').modal('show');
                        $(line).show();
                    }
                },
                error: function() {
                    $('#ajax_error_text').html('<p>Some errors occurred while sending the Invoice to Xero</p>');
                    $('#ajax_error').find('h4').html('Error!');
                    $('#ajax_error').modal('show');
                    $('#table').find('tr.loader').remove();
                    $(line).show();
                }
            });
        }

        function new_val(t){
            var res = $(t).attr('value');
            $('#delete_quote').val(res);
            return false;
        }

        function filter_table(el) {
            $('tbody').find('tr').each(function() {
                $(this).hide();
            });
            var i = 0;
            $('tbody').find('tr.'+el).each(function() {
                $(this).show();
                i++;
            });
            if(i == 0) {
                $('tbody').append('<tr class="inform"><td valign="top" colspan="8" class="dataTables_empty">No Quotes/Jobs with This Status</td></tr>')
            }
            else {
                $('tbody').find('tr.inform').remove();
            }
        }


        var statuses_id = [
                @foreach($statuses as $status)
                    {{ strtolower(str_replace(' ', '_', $status->id)) }},
                @endforeach
        ];
        var statuses_value = [
            @foreach($statuses as $status)
                "{{ strtolower(str_replace(' ', '_', $status->value)) }}",
            @endforeach
        ];

        $(document).ready(function() {
            oTable = $('#table').DataTable( {
                order: [[ 0, "asc" ]],
                paging: false
            });

            $('#table').show();

            $('#filter').on('change', function(){
                if (this.value == 'all_jobs') {
                    $('tbody').find('tr').each(function() {
                        $(this).show();
                    });
                    $('tbody').find('tr.inform').remove();
                } else {
                    filter_table(this.value);
                }
            });

            $("[data-toggle='tooltip']").tooltip();

            // Status is changed
            $('.select').on('change', function(){
                var line = $($(this).parents('tr').get(0));

                var new_status = this.value;
                var quote_id = $($(this).parents('tr').get(0)).find('td.quote_number').text();

                $(line).hide();
                $(line).after('<tr class="loader"><td colspan="3"></td><td colspan="5"><img src="{{asset('images/loading-lg.gif')}}" height="30px"></td></tr>');

                $.ajax({
                    type        : 'GET',
                    url         : '/change_status/' + quote_id + '/' + new_status,
                    success: function(response) {
                        $('#table').find('tr.loader').remove();
                        $(line).removeClass(statuses_value[response[0]-1]).addClass(statuses_value[response[1]-1]).show();

                        // If New Job status changed, delete redundant rows
                        if(response[0] == 4){
                            $('#table').find('td.quote_number').each(function(){
                                if($(this).text() == response[2]) {
                                    if(($($(this).parents('tr').get(0)).find('select').val()) != response[1]){
                                        $($(this).parents('tr').get(0)).addClass('selected');
                                        oTable.row('.selected').remove().draw(false);
                                    }
                                    else{
                                        $($(this).parents('tr').get(0)).find('td.stage').html('');
                                    }
                                }
                            });
                        }

                        // If new status is 'Cancelled', delete this row
                        if(response[1] == 10){
                            $(line).addClass('selected');
                            oTable.row('.selected').remove().draw(false);
                        }

                        // If new status 'Invoice', add menu item
                        if(response[1] == 8){
                            if(!$(line).find('li.divider').length){
                                $(line).find('ul').append('<li class="divider"></li>');
                            }
                        }
                    },
                    error: function() {
                        $('#ajax_error_text').html('<p>Some errors occurred while storing the new status</p>');
                        $('#ajax_error').modal('show');
                        $('#table').find('tr.loader').remove();
                        $(line).show();
                    }
                });
            });
        });

    </script>
@endsection

