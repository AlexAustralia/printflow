
@extends('app')

@section('title')
    Enter Quote Request
@endsection

@section('content')
    <link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            // Validation of the form
            $('#quote_form').validate(
                    {
                        rules: {
                            customer: {
                                required: true,
                            },
                            request_date: {
                                required: true,
                            },
                            title: {
                                required: true,
                            },
                        },
                    }
            );

            // Submitting the form
            $('#submit_quote').click(function(){

                // If Customer is not valid, clear the customer field
                if($('#customer_id').val() == 0){
                    $('#customer').val('');
                }

                $('#quote_form').submit();
            });

            $( "#request_date" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

            $( "#expiry_date" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

            $( "#customer" ).autocomplete({
                source:'/json/customers',
                select: function (event, ui) {
                    $("#customer").val(ui.item.label);
                    $("#customer_id").val(ui.item.value);
                    return false;
                },
                change: function (event, ui) {
                    $("#customer_id").val( ui.item ? ui.item.value : '' );
                }
            });
        });
    </script>

    @if (isset($message))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Success</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    {!! Form::open(array('url' => 'quote_requests/'.$q->id, 'method' => 'put', 'id' => 'quote_form', 'class' => 'form-horizontal')) !!}

            <!-- https://jqueryui.com/autocomplete/#custom-data -->
    <!-- Customer list to be modified jquery autocomplete dropdown
         with hidden id field -->
    <div class="form-group">
        <div class="col-md-2">
            {!! Form::label('customer', 'Customer *', array('class' => 'control-label')) !!}
            {!! Form::text('customer', $q->customer["customer_name"], array('id' => 'customer', 'class' => 'form-control')) !!}
            {!! Form::hidden('customer_id', $q->customer_id, array('id' => 'customer_id')) !!}

        </div>

        <div class="col-md-2">
            {!! Form::label('request_date', 'Request Date *', array('class' => 'control-label')) !!}
            {!! Form::text('request_date', $q->request_date, array('id' => 'request_date', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('expiry_date', 'Expiry Date', array('class' => 'control-label')) !!}
            {!! Form::text('expiry_date', $q->expiry_date, array('id' => 'expiry_date', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('id', 'Quote Number', array('class' => 'control-label')) !!}
            {!! Form::text('id', $q->id, array('disabled' => 'disabled', 'class' => 'form-control')) !!}
        </div>

        <div class="col-md-2">
            {!! Form::label('ref', 'Ref', array('class' => 'control-label')) !!}
            {!! Form::text('ref', $q->ref, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-12">
            {!! Form::label('title', 'Title *', array('class' => 'control-label')) !!}
            {!! Form::text('title', $q->title, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6">
            {!! Form::label('summary', 'Summary', array('class' => 'control-label')) !!}
            {!! Form::textarea('summary', $q->summary, array('rows' => '4', 'class' => 'form-control')) !!}
        </div>
        <div class="col-md-6">
            {!! Form::label('terms', 'Terms', array('class' => 'control-label')) !!}
            {!! Form::textarea('terms', $q->terms, array('rows' => '4', 'class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        @if(count($q->qris) > 0)
            <?php $i = 0;?>
            @foreach ($q->qris as $qri)
                <?php $i++;?>
                <div class="col-md-3">
                    @if($i == 1)
                        {!! Form::label('qri_quantity[]', 'Quantity'.$i.' *', array('class' => 'control-label')) !!}
                        {!! Form::hidden('qri_id[]', $qri->id, ['id' => 'qri_id']) !!}
                        {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                        {!! Form::text('qri_quantity[]', $qri->quantity, ['style' => 'width: 100%', 'required' => 'required', 'number' => 'number', 'min' => '1', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::label('qri_quantity[]', 'Quantity'.$i, array('class' => 'control-label')) !!}
                        {!! Form::hidden('qri_id[]', $qri->id, ['id' => 'qri_id']) !!}
                        {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                        {!! Form::text('qri_quantity[]', $qri->quantity, ['style' => 'width: 100%', 'number' => 'number', 'min' => '1', 'class' => 'form-control']) !!}
                    @endif
                </div>
            @endforeach
        @endif
        @for($i = count($q->qris); $i < 4; $i++)
                <div class="col-md-3">
                    @if($i == 0)
                        {!! Form::label('qri_quantity[]', 'Quantity'.($i+1).' *', array('class' => 'control-label')) !!}
                        {!! Form::hidden('qri_id[]', null, ['id' => 'qri_id']) !!}
                        {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                        {!! Form::text('qri_quantity[]', null, ['style' => 'width: 100%', 'required' => 'required', 'number' => 'number', 'min' => '1', 'class' => 'form-control']) !!}
                    @else
                        {!! Form::label('qri_quantity[]', 'Quantity'.($i+1), array('class' => 'control-label')) !!}
                        {!! Form::hidden('qri_id[]', null, ['id' => 'qri_id']) !!}
                        {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                        {!! Form::text('qri_quantity[]', null, ['style' => 'width: 100%', 'number' => 'number', 'min' => '1', 'class' => 'form-control']) !!}
                    @endif
                </div>
        @endfor
    </div>


    <div class="form-group">
        <div class="pull-right">
            <button type="button" class="btn btn-primary" id="submit_quote">Save</button>
            <a href="{{URL::to('/')}}" class="btn btn-danger" role="button">Cancel</a>
        </div>
    </div>

    {!! Form::close() !!}

@endsection
