
@extends('app')

@section('title')
    Workflow
@endsection

@section('content')
    <link href="{{ asset('fancybox/source/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
    <style>
        #table_info{
            display:none;
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
            <h2 style="margin:0">Workflow</h2>
        </div>
        <div class="col-sm-2">
            <div class="pull-right">
                <a href="/quote_requests/create" class="btn btn-primary">Create Quote Request</a>
            </div>
        </div>
    </div>
    <hr>

    @if (!isset($array[0]))
        <div class="alert alert-warning alert-block">
            Nothing to Display: There are no Active Quotes and Jobs
        </div>
    @else
        <ul class="nav nav-tabs" id="workflow_tab" style="margin-bottom: 20px;">
            <li class="active"><a href="#" id="all_jobs">All Jobs</a></li>
            <li><a href="#" id="quote_in" onclick="filter_table(this)">Quote In</a></li>
            <li><a href="#" id="supplier_quote" onclick="filter_table(this)">Supplier Quote</a></li>
            <li><a href="#" id="quote_out" onclick="filter_table(this)">Quote Out</a></li>
            <li><a href="#" id="new_job" onclick="filter_table(this)">New Jobs</a></li>
            <li><a href="#" id="production" onclick="filter_table(this)">Production</a></li>
            <li><a href="#" id="incoming" onclick="filter_table(this)">Incoming</a></li>
            <li><a href="#" id="delivery" onclick="filter_table(this)">Delivery</a></li>
            <li><a href="#" id="invoice" onclick="filter_table(this)">Invoice</a></li>
        </ul>

        <form>
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
            <table class="table table-striped table-hover" id="table" style="display: none;">
                <thead>
                <tr>
                    <th width="7%">Quote Num</th>
                    <th width="7%">Job Num</th>
                    <th width="20%">Status</th>
                    <th>Job Stage</th>
                    <th>Title</th>
                    <th width="55">Artwork</th>
                    <th width="10%">Quantity</th>
                    <th>Customer</th>
                    <th>Supplier</th>
                    <th width="5%"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($array as $item)
                    <tr class="{{ strtolower(str_replace(' ', '_', $item['status'])) }}">
                        <td class="quote_number">{{$item['quote_number']}}</td>
                        <td>{{$item['job_number']}}</td>
                        <td><select class="form-control" style="width:145px;">
                                @foreach($statuses as $status)
                                    @if($status->id >= $item['status_id'] && $status->id != 9)
                                        <option value="{{$status->id}}" @if($status->id == $item['status_id']) selected="selected" @endif >
                                            {{$status->value}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>{!! $item['stage'] !!}</td>
                        <td><a href="{{URL::to('quote_requests/'.$item["quote_number"].'/edit')}}" data-toggle="tooltip" title="{{$item['description']}}">{{$item['title']}}</a></td>
                        <td>@if(isset($item['artwork_image']))<a class="fancybox" href="/uploads/artworks/{{$item['artwork_image']}}"><img src="/uploads/thumbnails/{{$item['artwork_image']}}"></a> @endif</td>
                        <td>{{$item['quantity']}}</td>
                        <td><a href="{{URL::to('/customers/'.$item["customer_id"].'/edit')}}">{{$item['customer_name']}}<a/></td>
                        <td><a href="{{URL::to('/suppliers/'.$item["supplier_id"].'/edit')}}">{{$item['supplier_name']}}<a/></td>
                        <td><div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle"
                                        data-toggle="dropdown" aria-expanded="false">
                                    ...
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
                                        <li><a href="#">Invoice</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>
    @endif
    <hr>

    <script>
        function new_val(t){
            var res = $(t).attr('value');
            $('#delete_quote').val(res);
            return false;
        }

        function filter_table(el) {
            $(el).tab('show');
            $('tbody').find('tr').each(function() {
                $(this).hide();
            });
            var i = 0;
            $('tbody').find('tr.'+el.id).each(function() {
                $(this).show();
                i++;
            });
            if(i == 0) {
                $('tbody').append('<tr class="inform"><td valign="top" colspan="11" class="dataTables_empty">No Quotes/Jobs with This Status</td></tr>')
            }
            else {
                $('tbody').find('tr.inform').remove();
            }
        }

        $(document).ready(function() {
            $('#table').DataTable( {
                order: [[ 1, "asc" ]],
                paging: false
            });

            $('#table').show();

            $('.fancybox').fancybox();

            $("[data-toggle='tooltip']").tooltip();

            $('#all_jobs').click(function(e){
                e.preventDefault();
                $(this).tab('show');
                $('tbody').find('tr').each(function() {
                    $(this).show();
                });
                $('tbody').find('tr.inform').remove();
            });

            // Status is changed
            $('select').on('change', function(){
                var line = $($(this).parents('tr').get(0));

                var new_status = this.value;
                var quote_id = $($(this).parents('tr').get(0)).find('td.quote_number').text();

                $(line).hide();
                $(line).after('<tr class="loader"><td colspan="5"></td><td colspan="6"><img src="{{asset('images/loading-lg.gif')}}" height="30px"></td></tr>');

                $.ajax({
                    //headers     : { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    type        : 'GET',
                    url         : '/change_status/' + quote_id + '/' + new_status,
                    //dataType    : 'json',
                    //encode      : true,
                    success: function(response) {
                        $('#table').find('tr.loader').remove();
                        $(line).show();
                        //alert(response);
                    },
                    error: function() {
                        alert('Some error occurred while storing status');
                    }
                });

                //.done(function(data) {
                //    $(line).html(line_data);
                //    alert(data);
                //});

                /*
                $.post('/change_status',
                        {
                            id: quote_id,
                            status: new_status
                        },
                        function(data)
                        {
                            alert(data);
                        }
                );
                */
            });
        });

    </script>
@endsection

