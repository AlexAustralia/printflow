@extends('app')

@section('title')
Request Supplier Quotes
@endsection

@section('content')

    @if (isset($message))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Error!</h4>
            @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
            @else {{ $message }} @endif
        </div>
    @endif

    @if($quote_request->emails() != '')

    {!! Form::open(array('url' => '/send_rfq_emails/'.$quote_request->id, 'method' => 'post', 'class' => 'form-horizontal')) !!}

    <div class="form-group">
        {!! Form::label('from', 'From:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <input name="from" class="form-control" value="Franklin Direct <{{$user->email}}>" />
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('reply-to', 'Reply-To:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <input name="reply-to" class="form-control" value="Franklin Direct <{{$user->email}}>" />
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('bcc', 'Bcc:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <input name="bcc" class="form-control" value="{!! $quote_request->emails() !!}" />
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('subject', 'Subject:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <input name="subject" class="form-control" value="Please Quote For Project: {!! $quote_request->title !!}" />
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('body', 'Body:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <textarea name="body" rows="15" class="form-control">Dear Supplier,

Please quote on the following job:

{!! $quote_request->summary !!}

Quantities required are:

@foreach ($quote_request->qris as $qri)
{!! $qri->quantity !!}  {!! $qri->description !!}
@endforeach

Kind Regards,
{{$user->name}} at Franklin Direct
</textarea>
        </div>
    </div>

    <div style="margin-top:30px;">
        <p style="float:right;">
            <input type="submit" class="btn btn-primary" value="Send Emails" />
        </p>
        <p style="clear:both;"></p>
    </div>

    {!! Form::close() !!}

    @else
        <div class="alert alert-warning alert-block">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h4>Warning</h4>
            No Suppliers chosen. Please choose suppliers and then try again
        </div>
    @endif

@endsection
