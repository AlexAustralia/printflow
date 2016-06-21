@extends('app')

@section('title')
    Edit Access to Review Supplier - <?php echo ucwords(strtolower(! empty($supplier->supplier_name) ? $supplier->supplier_name : '')); ?>
@endsection

@section('content')

    {!! Form::open(['url' => 'suppliers/'.$supplier->id.'/access/update', 'method' => 'post', 'id' => 'access_form', 'class' => 'form-horizontal']) !!}
    <div id="submit_data"></div>

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0"><img src="/images/edit_supplier.png"> Access to Review Supplier: <?php echo ucwords(strtolower(! empty($supplier->supplier_name) ? $supplier->supplier_name : '')); ?></h2>
        </div>

        <div class="col-sm-2">
            <button type="button" class="btn btn-primary pull-right" style="margin-top: 30px;" id="submit_form">Save</button>
        </div>
    </div>

    <hr>

    @include('partials.edit_supplier_menu')

    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    <div class="col-md-5">
        <div class="form-group">
            {!! Form::label('all_users', 'Choose User and press OPEN ACCESS to add to the allowed users list', array('class' => 'control-label')) !!}

            <select size="5" id="all_users" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <button type="button" class="btn btn-primary" style="margin-top: 27px;" id="add_user"><span class="fa fa-arrow-circle-right"></span> Open Access</button>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            {!! Form::label('allowed_user', 'Allowed Users', array('class' => 'control-label')) !!}

            <select size="5" id="allowed_users" class="form-control"">
                @foreach($allowed_users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger" id="remove_user"><span class="fa fa-trash-o"></span> Delete</button>

        </div>
    </div>

    {!! Form::close() !!}

    <script>
        $(document).ready(function() {
            $('#add_user').on('click', function() {
                var id = $('#all_users option:selected').val();
                var name = $('#all_users option:selected').text();
                if (!isNaN(id)) {
                    $('#allowed_users').append('<option value="' + id + '">' + name + '</option>');
                    $("#all_users :selected").remove();
                }
            });

            $('#remove_user').on('click', function() {
                var id = $('#allowed_users option:selected').val();
                var name = $('#allowed_users option:selected').text();
                if (!isNaN(id)) {
                    $('#all_users').append('<option value="' + id + '">' + name + '</option>');
                    $("#allowed_users :selected").remove();
                }
            });

            $('#submit_form').on('click', function() {
                var allowed_users = [];
                $('#allowed_users option').each(function() {
                    $('#submit_data').append('<input type="hidden" name="allowed_users[]" value="' + $(this).val() +'">')
                });
                $('#access_form').submit();
            });
        });
    </script>

@endsection