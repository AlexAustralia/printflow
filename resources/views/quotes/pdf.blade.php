<html>
<head>
    <style>
        body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            font-size: 11px;
            font-weight: 100;
        }

        .left-column{

        }

    </style>
</head>
<body>
    <img src="{{$_SERVER['DOCUMENT_ROOT']}}/images/logo.jpg" width="220px;">

    <div style="font-size: 28px; position: relative; top:10px;">QUOTE</div>

    <div id="client_address" style="position:relative;top:25px;left:100px;width:200px;">
        {{$customer->customer_name}}<br>
        Attention: {{$customer->postal_attention}}<br>
        {{$customer->customer_name}}<br>
        {{$customer->postal_street}}<br>
        {{$customer->postal_city}} {{$customer->postal_state}} {{$customer->postal_postcode}}<br>
        {{$customer->postal_country}}
    </div>

    <div id="quote_main_attributes" style="position:absolute;top:120px;left:400px;width:200px;">
        <strong>Date</strong><br>
        {{$qr->request_date}}<br>&nbsp;<br>
        <strong>Expiry</strong><br>
        {{$qr->expiry_date}}<br>&nbsp;<br>
        <strong>Qoute Number</strong><br>
        {{$qr->id}}<br>&nbsp;<br>
        <strong>ABN</strong><br>
        82 009 574 387
    </div>

    <div id="contacts" style="position:absolute;top:120px;left:550px;width:180px;">
        FRANKLIN DIRECT<br>
        91 ALBERT ROAD<br>
        MOONAH TAS 7009<br>
        AUSTRALIA<br>
        P: (03) 6228 6130<br>
        E: sales@franklindirect.com.au<br>
    </div>

    <div id="title" style="position:absolute;top:320px;left:0px;width:700px;font-size:14px;">
        <strong>{{$qr->title}}</strong>
    </div>

    <div id="summary" style="position:absolute;top:350px;left:0px;width:700px;">
        {!! nl2br($qr->summary) !!}
    </div>

    <div id="quote_details" style="position:absolute;top:550px;left:0px;width:700px;">
        <table width="100%" cellspacing="0" cellpadding="10">
            <tr style="border-bottom: solid 3px;">
                <th width="280px" style="text-align: left;border-bottom: solid 2px;">Description</th>
                <th width="50px" style="text-align: left;border-bottom: solid 2px;">Quantity</th>
                <th width="50px" style="text-align: left;border-bottom: solid 2px;">Artwork</th>
                <th width="50px" style="text-align: left;border-bottom: solid 2px;">Net Price</th>
                <th width="50px" style="text-align: left;border-bottom: solid 2px;">GST Amount</th>
                <th width="50px" style="text-align: left;border-bottom: solid 2px;">TOTAL AUD</th>
            </tr>
            @foreach($qris as $qri)
            <tr style="border-bottom: solid 1px;">
                <td style="border-bottom: solid 1px;">{{$qr->title}}</td>
                <td style="border-bottom: solid 1px;">{{$qri->quantity}}</td>
                <td style="border-bottom: solid 1px;">{{$qr->artwork_charge}}</td>
                <td style="border-bottom: solid 1px;">{{$qri->total_net}}</td>
                <td style="border-bottom: solid 1px;">{{$qri->gst}}</td>
                <td style="border-bottom: solid 1px;">{{$qri->total_inc_gst}}</td>
            </tr>
            @endforeach
        </table>
    </div>

    <div id="terms" style="position:absolute;top:800px;left:0px;width:700px;">
        <table width="100%" cellspacing="0" cellpadding="10">
            <tr>
                <th style="text-align: left;border-bottom: solid 1px;">Terms</th>
            </tr>
            <tr>
                <td>Price is valid for 7 days from date of quotation and is subject to viewing files. Price is subject to no heavy fluctuations in currency or material cost.
                Adjustments in price would only be made if material costs increase by over 3% between date of quotation and final materials being ordered.
                    <br>70% deposit required on acceptance of quote. Balance invoiced upon delivery of order.</td>
            </tr>
        </table>
    </div>

</body>
</html>