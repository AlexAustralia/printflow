
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
                  },
              }
      );

      // Submitting the form
      $('#submit_quote').click(function(){

          //If Customer is not valid, clear the customer field
          if($('#customer_id').val() == 0){
              $('#customer').val('');
          }
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
    $('#addRow').click(function(e) {
        var tbody = $('#qri_items').find("tbody"),
        tr = tbody.find("tr:last"),
        tr_new = tr.clone();
        tr_new.find("input[name='qri_id[]']").val('');
        // leave qri_quote_request_id alone..
        tr_new.find("input[name='qri_quantity[]']").val('');
        tr_new.find("input[name='qri_price[]']").val('');
        tr_new.find("input[name='qri_gst[]']").val('');
        tr_new.find("input[name='qri_total[]']").val('');
        tr_new.find("input[name='qri_unit_price[]']").val('');
        tr_new.find(':checkbox').prop("checked", false);

        tr.after(tr_new);
    });

    ///

    var updateTotal = function() {
        var index = 0;
        var qtys = $('input[name="qri_quantity[]"]')

        qtys.each(function() {
            qty = parseInt($(this).val()) || 1;

            price = parseFloat($('input[name="qri_price[]"]').eq(index).val()) || 0;

            var gst = price * 0.1;
            $('input[name="qri_gst[]"]').eq(index).val(gst.toFixed(2));

            var total = price + gst;
            $('input[name="qri_total[]"]').eq(index).val(total.toFixed(2));

            var unit_price = total/qty || 0;
            if (unit_price == "Infinity"){
                unit_price = 0;
            }
            $('input[name="qri_unit_price[]"]').eq(index).val(unit_price.toFixed(2));

            index++;
        });
    };

    $(function () {
        $('#qri_items').delegate('input[name="qri_quantity[]"]', 'input', updateTotal)
        $('#qri_items').delegate('input[name="qri_price[]"]', 'input', updateTotal)
        $('#qri_items').delegate('input[name="title"]', 'input', updateTotal)
    });


  });
</script>

{!! Form::open(array('url' => 'quote_requests/'.$q->id, 'method' => 'put', 'id' => 'quote_form')) !!}

    <!-- https://jqueryui.com/autocomplete/#custom-data -->
    <!-- Customer list to be modified jquery autocomplete dropdown
         with hidden id field -->
    <p style="float:left">
    {!! Form::label('customer', 'Customer') !!}<br />

    {!! Form::text('customer', $q->customer["customer_name"], array('id' => 'customer')) !!}
    {!! Form::hidden('customer_id', $q->customer_id, array('id' => 'customer_id')) !!}
    <p>

    <p style="float:left">
    {!! Form::label('request_date', 'Request Date') !!}<br />
    {!! Form::text('request_date', $q->request_date, array('id' => 'request_date')) !!}
    </p>

    <p style="float:left">
    {!! Form::label('expiry_date', 'Expiry Date') !!}<br />
    {!! Form::text('expiry_date', $q->expiry_date, array('id' => 'expiry_date')) !!}
    </p>

    <p style="float:left">
    {!! Form::label('id', 'Quote Number') !!}<br />
    {!! Form::text('id', $q->id, array('disabled' => 'disabled')) !!}
    <p>

    <p style="float:left">
    {!! Form::label('ref', 'Ref') !!}<br />
    {!! Form::text('ref', $q->ref, array()) !!}
    </p>

    <hr style="clear:both; visibility:hidden">

    <p style="float:left">
    {!! Form::label('title', 'Title') !!}<br />
    {!! Form::text('title', $q->title, array('style' => 'width:300px;')) !!}
    </p>

    <hr style="clear:both; visibility:hidden">

    <p style="float:left;">
    {!! Form::label('summary', 'Summary') !!}<br />
    {!! Form::textarea('summary', $q->summary,
                array('rows' => '4')) !!}
    </p>

    <p style="float: right; ">
    {!! Form::label('terms', 'Terms') !!}<br />
    {!! Form::textarea('terms', $q->terms,
                array('rows' => '4')) !!}
    </p>

    <hr style="clear:both; visibility:hidden">

    <table id="qri_items" width="100%">
        <tr>
            <td>Quantity</td>
            <td>Description</td>
            <td>Price</td>
            <td>GST</td>
            <td>Total</td>
            <td>Unit Price</td>
            <td>Delete</td>
        </tr>
        @foreach ($q->qris as $qri):
        <tr>
            <td>{!! Form::hidden('qri_id[]', $qri->id, ['id' => 'qri_id']) !!}
                {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                {!! Form::text('qri_quantity[]', $qri->quantity, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_description[]', $qri->description, ['style'=>'width:350px']) !!}</td>
            <td>{!! Form::text('qri_price[]', $qri->price, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_gst[]', $qri->gst, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_total[]', $qri->total, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_unit_price[]', $qri->unit_price, ['style'=>'width:140px']) !!}</td>
            <td style="text-align:right">{!! Form::checkbox('qri_delete[]', $qri->id) !!}</td>
        </tr>
        @endforeach
        <tr>
            <td>{!! Form::hidden('qri_id[]', null, ['id' => 'qri_id']) !!}
                {!! Form::hidden('qri_quote_request_id[]', $q->id) !!}
                {!! Form::text('qri_quantity[]', null, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_description[]', $q->title, ['style'=>'width:350px']) !!}</td>
            <td>{!! Form::text('qri_price[]', null, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_gst[]', null, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_total[]', null, ['style'=>'width:140px']) !!}</td>
            <td>{!! Form::text('qri_unit_price[]', null, ['style'=>'width:140px']) !!}</td>
            <td></td>
        </tr>
    </table>

    <p style="margin-top:10px; text-align:right"><a id="addRow" class="btn btn-primary" role="button">Add another row</a></p>

    <p style="float:right; margin-top:30px">
        {!! Form::submit('Save', array('class' => 'btn btn-primary', 'id' => 'submit_quote')) !!}
        <a href="{{URL::previous()}}" class="btn btn-danger" role="button">Cancel</a>
    </p>
    <p style="clear:both;"></p>

{!! Form::close() !!}

@endsection
