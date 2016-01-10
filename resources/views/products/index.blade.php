@extends('app')

@section('title')
    Product Library
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
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    {!! Form::open(array('url' => 'products/delete', 'method' => 'post')) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete" value="" class="btn btn-danger" id="delete_product">OK</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Main body -->
    <div class="row">
        <div class="col-sm-10">
            <h2>Product Library</h2>
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

    <table id="table" class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="5%">Code</th>
            <th width="5%">Image</th>
            <th width="15%">Name</th>
            <th>Description</th>
            <th width="15%">Supplier Name</th>
            <th width="10%">Minimum Order Quantity</th>
            <th width="10%">Unit Price From</th>
            <th width="10%">Unit Price To</th>
            <th width="5%"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{$product->id}}</td>
                <td>@if(isset($product->product_image))<a class="fancybox" href="/uploads/products/{{$product->product_image}}"><img src="/uploads/products/thumb_{{$product->product_image}}"></a> @endif</td>
                <td><a href="{{URL::to('products/'.$product->id.'/edit')}}">{{$product->name}}</a></td>
                <td>{{$product->description}}</td>
                <td>{{$product->supplier->supplier_name}}</td>
                <td>{{$product->minimum_order_quantity}}</td>
                <td>{{$product->unit_price_from}}</td>
                <td>{{$product->unit_price_to}}</td>
                <td><div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown" aria-expanded="false">
                            ...
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{URL::to('products/'.$product->id.'/edit')}}">Edit</a></li>
                            <li><a href="#" data-toggle="modal" value="{{$product->id}}" data-target="#delete_confirmation" onclick="new_val(this)">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <hr>
    <div class="pull-right">
        <a href="/products/create" class="btn btn-primary">Create New</a>
    </div>

    <script>
        function new_val(t){
            var res = $(t).attr('value');
            $('#delete_product').val(res);
            return false;
        }

        $(document).ready(function(){
            $('#table').DataTable({
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
            });
            $('.fancybox').fancybox();
        });
    </script>
@endsection