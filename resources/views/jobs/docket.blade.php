<html>
<head>
    <style>
        body {
            font-family: "Roboto", Helvetica, Arial, sans-serif;
            font-size: 11px;
            font-weight: 100;
        }

        .page-break {
            page-break-after: always;
        }

    </style>
</head>
<body>
<img src="{{$_SERVER['DOCUMENT_ROOT']}}/images/logo.jpg" width="220px;">

<div id="job_details" style="position:absolute;top:30px;left:400px;width:300px;font-size:15px;">
    <strong>Delivery Docket Number:</strong> {{$quote->id}}<br>
    <strong>PO Number:</strong> (Reference Number)<br>
    <strong>Date:</strong> {{$input['delivery_date']}}<br>
    <strong>Job Quantity:</strong> {{$quote->job->job_item->quantity}}
</div>

<div style="position:absolute;top:150px;left:400px;width:300px;font-size:15px;;">
    {{$delivery_address->name}}<br>
    {{$delivery_address->address}}<br>
    {{$delivery_address->city}}, {{$delivery_address->state}} {{$delivery_address->postcode}}
</div>

<div style="position:absolute;top:250px;left:0px;width:700px;">
    <table width="100%" cellspacing="0" cellpadding="5" border="1">
        <tr>
            <th width="230px">Description</th>
            <th width="70px">Items Per Carton</th>
            <th width="70px">Cartons</th>
            <th width="70px">Quantity Received</th>
        </tr>
        <tr>
            <td>{{$quote->title}}</td>
            <td>{{$input['items']}}</td>
            <td>{{$input['cartons']}}</td>
            <td>{{$input['items'] * $input['cartons']}}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{$input['cartons']}}</strong></td>
            <td><strong>{{$input['items'] * $input['cartons']}}</strong></td>
        </tr>
    </table>
</div>

<div style="position:absolute;top:400px;left:400px;width:300px;font-size:15px;;">
    Sender:<br>
    Franklin Direct<br>
    91 Albert Road, Moonah, Tasmania 7009<br>
    P: (03) 6228 6130<br>
    E: art@franklindirect.com.au
</div>

<div style="position:absolute;top:515px;left:-45px;width:800px; border-top: solid 1px;">
</div>

<img src="{{$_SERVER['DOCUMENT_ROOT']}}/images/logo.jpg" width="220px;" style="position: absolute;top:525px;left:0px;">

<div id="job_details" style="position:absolute;top:555px;left:400px;width:300px;font-size:15px;">
    <strong>Delivery Docket Number:</strong> {{$quote->id}}<br>
    <strong>PO Number:</strong> (Reference Number)<br>
    <strong>Date:</strong> {{$input['delivery_date']}}<br>
    <strong>Job Quantity:</strong> {{$quote->job->job_item->quantity}}
</div>

<div style="position:absolute;top:675px;left:400px;width:300px;font-size:15px;;">
    {{$delivery_address->name}}<br>
    {{$delivery_address->address}}<br>
    {{$delivery_address->city}}, {{$delivery_address->state}} {{$delivery_address->postcode}}
</div>

<div style="position:absolute;top:775px;left:0px;width:700px;">
    <table width="100%" cellspacing="0" cellpadding="5" border="1">
        <tr>
            <th width="230px">Description</th>
            <th width="70px">Items Per Carton</th>
            <th width="70px">Cartons</th>
            <th width="70px">Quantity Received</th>
        </tr>
        <tr>
            <td>{{$quote->title}}</td>
            <td>{{$input['items']}}</td>
            <td>{{$input['cartons']}}</td>
            <td>{{$input['items'] * $input['cartons']}}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{$input['cartons']}}</strong></td>
            <td><strong>{{$input['items'] * $input['cartons']}}</strong></td>
        </tr>
    </table>
</div>

<div style="position:absolute;top:925px;left:400px;width:300px;font-size:15px;">
    Sender:<br>
    Franklin Direct<br>
    91 Albert Road, Moonah, Tasmania 7009<br>
    P: (03) 6228 6130<br>
    E: art@franklindirect.com.au
</div>

<div style="position:absolute;top:1025px;left:0px;width:400px">
    <div style="border-top:solid 1px; width: 175px; text-align: center;">
        Received By
    </div>
</div>

<div style="position:absolute;top:1025px;left:200px;width:400px">
    <div style="border-top:solid 1px; width: 175px; text-align: center;">
        Date
    </div>
</div>


<div class="page-break"></div>


<img src="{{$_SERVER['DOCUMENT_ROOT']}}/images/logo.jpg" width="220px;">

<div id="job_details" style="position:absolute;top:30px;left:400px;width:300px;font-size:15px;">
    <strong>Delivery Docket Number:</strong> {{$quote->id}}<br>
    <strong>PO Number:</strong> (Reference Number)<br>
    <strong>Date:</strong> {{$input['delivery_date']}}<br>
    <strong>Job Quantity:</strong> {{$quote->job->job_item->quantity}}
</div>

<div style="position:absolute;top:150px;left:400px;width:300px;font-size:15px;;">
    {{$delivery_address->name}}<br>
    {{$delivery_address->address}}<br>
    {{$delivery_address->city}}, {{$delivery_address->state}} {{$delivery_address->postcode}}
</div>

<div style="position:absolute;top:250px;left:0px;width:700px;">
    <table width="100%" cellspacing="0" cellpadding="5" border="1">
        <tr>
            <th width="230px">Description</th>
            <th width="70px">Items Per Carton</th>
            <th width="70px">Cartons</th>
            <th width="70px">Quantity Received</th>
        </tr>
        <tr>
            <td>{{$quote->title}}</td>
            <td>{{$input['items']}}</td>
            <td>{{$input['cartons']}}</td>
            <td>{{$input['items'] * $input['cartons']}}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{$input['cartons']}}</strong></td>
            <td><strong>{{$input['items'] * $input['cartons']}}</strong></td>
        </tr>
    </table>
</div>

<div style="position:absolute;top:400px;left:400px;width:300px;font-size:15px;;">
    Sender:<br>
    Franklin Direct<br>
    91 Albert Road, Moonah, Tasmania 7009<br>
    P: (03) 6228 6130<br>
    E: art@franklindirect.com.au
</div>

<div style="position:absolute;top:515px;left:-45px;width:800px; border-top: solid 1px;">
</div>

<img src="{{$_SERVER['DOCUMENT_ROOT']}}/images/logo.jpg" width="220px;" style="position: absolute;top:525px;left:0px;">

<div id="job_details" style="position:absolute;top:555px;left:400px;width:300px;font-size:15px;">
    <strong>Delivery Docket Number:</strong> {{$quote->id}}<br>
    <strong>PO Number:</strong> (Reference Number)<br>
    <strong>Date:</strong> {{$input['delivery_date']}}<br>
    <strong>Job Quantity:</strong> {{$quote->job->job_item->quantity}}
</div>

<div style="position:absolute;top:675px;left:400px;width:300px;font-size:15px;;">
    {{$delivery_address->name}}<br>
    {{$delivery_address->address}}<br>
    {{$delivery_address->city}}, {{$delivery_address->state}} {{$delivery_address->postcode}}
</div>

<div style="position:absolute;top:775px;left:0px;width:700px;">
    <table width="100%" cellspacing="0" cellpadding="5" border="1">
        <tr>
            <th width="230px">Description</th>
            <th width="70px">Items Per Carton</th>
            <th width="70px">Cartons</th>
            <th width="70px">Quantity Received</th>
        </tr>
        <tr>
            <td>{{$quote->title}}</td>
            <td>{{$input['items']}}</td>
            <td>{{$input['cartons']}}</td>
            <td>{{$input['items'] * $input['cartons']}}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Total</strong></td>
            <td><strong>{{$input['cartons']}}</strong></td>
            <td><strong>{{$input['items'] * $input['cartons']}}</strong></td>
        </tr>
    </table>
</div>

<div style="position:absolute;top:925px;left:400px;width:300px;font-size:15px;">
    Sender:<br>
    Franklin Direct<br>
    91 Albert Road, Moonah, Tasmania 7009<br>
    P: (03) 6228 6130<br>
    E: art@franklindirect.com.au
</div>

<div style="position:absolute;top:1025px;left:0px;width:400px">
    <div style="border-top:solid 1px; width: 175px; text-align: center;">
        Received By
    </div>
</div>

<div style="position:absolute;top:1025px;left:200px;width:400px">
    <div style="border-top:solid 1px; width: 175px; text-align: center;">
        Date
    </div>
</div>

</body>
</html>