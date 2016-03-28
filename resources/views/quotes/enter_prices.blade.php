@extends('app')
@section('title')
Enter Supplier Prices
@endsection

@section('content')
    <script>
    $(document).ready(function() {
        var find = function(name, index) {
            return $('input[name="'+name+'[]"]').eq(index);
        }

        var updateTotal = function() {
            var index = 0;
            var qtys = $('input[name="quantity[]"]');

            qtys.each(function() { // For each column

                // Inputs
                var qty = parseInt($(this).val()) || 1; // Default to 1 not zero, to prevent n/0 problems with bad inputs
                var buy_price = parseFloat(find('buy_price', index).val()) || 0;
                var duty = parseFloat(find('duty', index).val())/100 || 0;
                var markup = parseFloat(find('markup', index).val())/100 || 0;
                var artwork = parseFloat(find('artwork', index).val());
                var freight = parseFloat(find('freight', index).val());

                // Outputs
                var buy_price_unit = buy_price / qty;
                find('buy_price_unit', index).val(buy_price_unit.toFixed(2));

                var duty_amount = duty * buy_price;
                find('duty_amount', index).val(duty_amount.toFixed(2));

                var total_buy_cost = buy_price + duty_amount;
                find('total_buy_cost', index).val(total_buy_cost.toFixed(2));

                var markup_amount = buy_price * markup;
                find('markup_amount', index).val(markup_amount.toFixed(2));

                var total_net = total_buy_cost + markup_amount;
                find('total_net', index).val(total_net.toFixed(2));

                var gst = (total_net + artwork + freight) * 0.1;
                find('gst', index).val(gst.toFixed(2));

                var total_inc_gst = total_net + artwork + freight + gst;
                find('total_inc_gst', index).val(total_inc_gst.toFixed(2));

                var unit_price_inc_gst = total_inc_gst / qty;
                find('unit_price_inc_gst', index).val(unit_price_inc_gst.toFixed(2));

                index++;
            });
        };

        $(function () {
            $('input[name="buy_price[]"]').on('input', updateTotal);
            $('input[name="duty[]"]').on('input', updateTotal);
            $('input[name="markup[]"]').on('input', updateTotal);
            updateTotal();
        });

        $('#qid').change(function(e) {
            var quote_request_id = $('#quote_request_id').val();
            var quote_id = this.value;
            url = '/enter_prices/' + quote_request_id + '/' + quote_id;
            console.log(url);
            window.location = url;
        });

        $("[data-toggle='tooltip']").tooltip();

        $('#submit_form').click(function(){
            $('#supplier_prices_form').submit();
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


{!! Form::open(array('method' => 'post', 'class' => 'form-horizontal', 'id' => 'supplier_prices_form')) !!}

    @if (isset($quote))

    <div class="form-group">
        <div class="col-md-4">
            {!! Form::label('choose_supplier', 'Choose a Supplier', array('class' => 'control-label')) !!}
            <input type="hidden" id="quote_request_id" name="quote_request_id" value="{!! $quote_request->id !!}" />

            <select id="qid" name="qid" rows="6" class="form-control">
            @foreach ($quote_request->quotes as $q)
                <option value="{!! $q->id !!}"
                @if ($q->id == $quote->id)
                    selected="selected"
                @endif
                >{!! $q->supplier->supplier_name !!}</option>
            @endforeach
            </select>
        </div>
    </div>

    <table width="100%" class="table table-hover">
        <tr>
            <th width="15%"></th>
            @foreach ($quote_request_lines as $line)
                <th width="{!! intval(85/count($quote_request_lines)) !!}%">
                    <span data-toggle="tooltip" title="{{$line->description}}">{!! $line->quantity !!}</span>
                    <input type="hidden" name="quantity[]" value="{!! $line->quantity !!}" />
                    <input type="hidden" name="qri_id[]" value="{!! $line->id !!}" />
                </th>
            @endforeach
        </tr>

        <tr>
            <td>Buy Price</td>
            @foreach ($quote->quote_items() as $i)
                <td><input type="hidden" name="id[]" value="{!! $i["id"] !!}" />
                    <input type="hidden" name="quote_id[]" value="{!! $quote->id !!}" />
                    <input name="buy_price[]" value="{!! $i["buy_price"] !!}"  /></td>
            @endforeach
        </tr>
        
        <tr class="success">
            <td>Buy Price (Unit)</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="buy_price_unit[]" value="{!! $i["buy_price_unit"] !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr>
            <td>Duty %</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="duty[]" value="{!! $i["duty"] !!}" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Duty Amount</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="duty_amount[]" value="" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Total Buy Cost</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="total_buy_cost[]" value="{!! $i["total_buy_cost"] !!}" readonly="readonly" /></td>
            @endforeach
        </tr>
        
        <tr>
            <td colspan="{!! 1 + count($quote->quote_items()) !!}" >&nbsp;</td>
        </tr>    

        <tr>
            <td>Markup %</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="markup[]" value="{!! $i["markup"] !!}" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Markup Amount</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="markup_amount[]" value="" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Total NET</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="total_net[]" value="{!! $i["total_net"] !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Artwork Charge</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="artwork[]" value="{!! $quote_request->artwork_charge !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Freight Charge</td>
            @foreach ($freight_charge->freight_items as $i)
                <td><input name="freight[]" value="{!! $i->total !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>GST</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="gst[]" value="{!! $i["gst"] !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Total (inc GST)</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="total_inc_gst[]" value="{!! $i["total_inc_gst"] !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

        <tr class="success">
            <td>Unit Price (inc GST)</td>
            @foreach ($quote->quote_items() as $i)
                <td><input name="unit_price_inc_gst[]" value="{!! $i["unit_price_inc_gst"] !!}" readonly="readonly" /></td>
            @endforeach
        </tr>

    </table>


    <div style="margin-top:30px;">
        <p style="float:right;">
            <button type="button" class="btn btn-primary" id="submit_form">Save</button>
        </p>
    </div>

    @else
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Warning</h4>
            No Suppliers chosen. Please choose suppliers and then try again
        </div>
    @endif

{!! Form::close() !!}

@endsection
