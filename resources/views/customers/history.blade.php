@extends('app')



@section('title')
    Customer History
@endsection

@section('content')
    <link href="{{ asset('fancybox/source/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>

    <div id="customers" class="container-fluid">
        <div class="row">
            <div class="col-sm-10">
                <h2>Customer History: <small>{{$customer->customer_name}}</small>

                        <a class="btn btn-default edit" title="Edit {!! ucwords(strtolower($customer->customer_name)) !!}" href="{!! route('customers.edit', ['id' => $customer->id]) !!}"><i class="fa fa-pencil"></i>Edit Customer Details</a>
                    </h2>
            </div>
        </div>

        <p><a href="{{URL::to('quote_requests/create')}}" class="btn btn-primary" role="button">Add New Quote</a></p>

        <table id="table" class="table table-striped table-hover">
            <thead>
            <tr>
                <th width="7%">Quote Number</th>
                <th width="7%">Job Number</th>
                <th width="7%">Status</th>
                <th>Description</th>
                <th>Artwork</th>
                <th width="10%">Quantity</th>
                <th width="20%">Supplier</th>
                <th width="10%">Job Cost</th>
                <th width="10%">Job Sell</th>
                <th width="10%">Date Last Ordered</th>
                <th width="10%">Expiry Date</th>
                <th width="5%"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($array as $item)
                <tr>
                    <td>{{$item['quote_number']}}</td>
                    <td>{{$item['job_number']}}</td>
                    <td><span class="label label-info">{{$item['status']}}</span></td>
                    <td><a href="{{URL::to('quote_requests/'.$item["quote_number"].'/edit')}}" data-toggle="tooltip" title="{{$item['description']}}">{{$item['title']}}</a></td>
                    <td>@if(isset($item['artwork_image']))<a class="fancybox" href="/uploads/artworks/{{$item['artwork_image']}}"><img src="/uploads/thumbnails/{{$item['artwork_image']}}"></a> @endif</td>
                    <td>{{$item['quantity']}}</td>
                    <td><a href="{{URL::to('/suppliers/'.$item["supplier_id"].'/edit')}}">{{$item['supplier_name']}}<a/></td>
                    <td>{{$item['job_cost']}}</td>
                    <td>{{$item['job_sell']}}</td>
                    <td>{{$item['request_date']}}</td>
                    <td>{{$item['expiry_date']}}</td>
                    <td><div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-expanded="false">
                                ...
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{URL::to('quote_requests/'.$item["quote_number"].'/edit')}}">Edit</a></li>
                                <li><a href="#">Duplicate</i></a></li>
                                <li><a href="#">Delete</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Invoice</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <hr>
    <div class="pull-right">
        <a class="btn btn-warning" href="{{URL::to('customers')}}"><span class="glyphicon glyphicon-backward"></span> Back</a>
    </div>


<script>
    $(document).ready(function(){
        $('#table').DataTable();
        $("[data-toggle='tooltip']").tooltip();
        $('.fancybox').fancybox();
    });
</script>
@endsection