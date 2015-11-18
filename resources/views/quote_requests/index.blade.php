@extends('app')

@section('title')
Quote Requests
@endsection

@section('content')

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
                                <li><a href="{!! action('QuoteRequestsController@edit', ['id' => $q->id]) !!}">Open</a></li>
                                <li class="divider"></li>
                                <li><a href="#"><i>Delete</i></a></li>
                                <li><a href="#"><i>Duplicate</i></a></li>
                                <li class="divider"></li>
                                <li><a href="#"><i>Send Invoice</i></a></li>
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


@endsection
