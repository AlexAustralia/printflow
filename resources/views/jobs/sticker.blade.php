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
@for($i = 1; $i <= $input['cartons']; $i++)
<img src="{{$_SERVER['DOCUMENT_ROOT']}}/images/logo.jpg" width="220px;" style="position:absolute;top:180px;left:270px;">

<div style="position:absolute;top:0px;left:0px;font-size:25px;">
    {{$quote->title}}
</div>

<div style="position:absolute;top:70px;left:0px;font-size:25px;">
    {{$delivery_address->name}}<br>
    {{$delivery_address->address}}<br>
    {{$delivery_address->city}}, {{$delivery_address->state}} {{$delivery_address->postcode}}
</div>

<div style="position:absolute;top:185px;font-size:25px;">
    Carton  Quantity: {{$input['items']}}<br>&nbsp;<br>
    Carton {{$i}} of {{$input['cartons']}}
</div>
@if($i < $input['cartons'])
    <div class="page-break"></div>
@endif
@endfor

</body>
</html>