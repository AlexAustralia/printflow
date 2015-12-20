@extends('app')

@section('title')
Quote Requests
@endsection

@section('content')
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

    @if (!$quote_requests->count())
        The system has no quote requests.
    @else
        <p>Found {!! $quote_requests->count() !!} quote requests</p>

        <table class="table">
                <tr>
                    <td>Customer Name</td>
                    <td>Quote Number</td>
                    <td>Title</td>
                    <td>Quote Req. Date</td>
                </tr>
            @foreach($quote_requests as $q)
                <tr>
                    @if($q->customer)
                        <td>{!! $q->customer->customer_name !!}</td>
                    @else
                        <td><i>No Customer Selected</i></td>
                    @endif
                    <td>{!! $q->id !!}</td>
                    <td><a href="{!! action('QuoteRequestsController@edit', ['id' => $q->id]) !!}">{!! $q->title !!}</a></td>
                    <td>{!! $q->request_date !!}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" 
                                    data-toggle="dropdown" aria-expanded="false">
                                ...
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{!! action('QuoteRequestsController@edit', ['id' => $q->id]) !!}">Edit</a></li>
                                <li><a href="#">Duplicate</a></li>
                                <li><a href="#" data-toggle="modal" value="{{$q->id}}" data-target="#delete_confirmation" onclick="new_val(this)">Delete</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Send Invoice</a></li>
                            </ul>
                        </div>
                        
                        <!--i>Delete, Dupe, Invoice, etc.</i-->
                    </td>
                </tr>
            @endforeach
        </table>
    @endif

    <p style="float:right">
        <a href="/quote_requests/create" class="btn btn-primary">Create New</a>
    </p>

    <script>
        function new_val(t){
            var res = $(t).attr('value');
            $('#delete_quote').val(res);
            return false;
        }
    </script>
@endsection
