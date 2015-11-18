
@extends('app')

@section('title')
Request Supplier Quotes
@endsection

@section('content')


{!! Form::open(array('url' => '/send_rfq_emails/'.$quote_request->id, 'method' => 'post', 'class' => 'form-horizontal')) !!}

    <div class="form-group">
        {!! Form::label('from', 'From:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <input name="from" class="form-control" value="Franklin Direct <art@franklindirect.com.au>" />
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('reply-to', 'Reply-To:', array('class' => 'control-label col-sm-2')) !!}
        <div class="col-sm-10">
            <input name="reply-to" class="form-control" value="Franklin Direct <art@franklindirect.com.au>" />
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
{!! $qri->quantity !!}
@endforeach

Kind Regards,
Staff at Franklin Direct
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

@endsection
