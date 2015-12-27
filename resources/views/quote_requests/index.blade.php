@extends('app')

@section('title')
Quote Requests
@endsection

@section('content')
    <link href="{{ asset('fancybox/source/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>

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
            <h2>Quote Requests:</h2>
        </div>
    </div>

    @if (!$quote_requests->count())
        <div class="alert alert-warning alert-block">
            The system has no quote requests.
        </div>
    @else
        <table class="table table-striped table-hover" id="table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Quote Number</th>
                    <th>Artwork</th>
                    <th>Title</th>
                    <th>Quote Request Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($quote_requests as $q)
                <tr>
                    @if($q->customer)
                        <td>{!! $q->customer->customer_name !!}</td>
                    @else
                        <td><i>No Customer Selected</i></td>
                    @endif
                    <td>{!! $q->id !!}</td>
                    <td>@if(isset($q->artwork_image))<a class="fancybox" href="/uploads/artworks/{{$q->artwork_image}}"><img src="/uploads/thumbnails/{{$q->artwork_image}}"></a> @endif</td>
                    <td><a href="{!! action('QuoteRequestsController@edit', ['id' => $q->id]) !!}" data-toggle="tooltip" title="{{$q->summary}}">{!! $q->title !!}</a></td>
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
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <hr>
    <div class="pull-right">
        <a href="/quote_requests/create" class="btn btn-primary">Create New</a>
    </div>

    <script>
        function new_val(t){
            var res = $(t).attr('value');
            $('#delete_quote').val(res);
            return false;
        }

        $(document).ready(function() {
            $('#table').DataTable( {
                "order": [[ 1, "asc" ]]
            });
            $('.fancybox').fancybox();
            $("[data-toggle='tooltip']").tooltip();
        })
    </script>
@endsection
