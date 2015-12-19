@extends('app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">

<style type="text/css" media="screen">

    #customers ul { margin: 0; padding: 0; overfow: hidden; clear: both;}
    #customers li { list-style: none; }

    #customers input.search { 
        border: 1px solid #aaa;
        float: right;
        font-size: 15px;
        margin-right: -15px;
        /*margin-top: -50px;*/
        padding: 5px;        
    }

    #customers .customer-row {
        border: 1px solid #f2f2f2;
        margin-bottom: 20px;
    }

    #customers .customer {
        background: #F9F9F9;
        padding: 10px;
        font-weight: bold;
        border-bottom: 1px solid #eee;
    }

    #customers .customer .btn.edit {
        margin: -4px;
    }

    #customers .contacts .heading {
        width: 100%;
        clear: both;
        color: #aaa;
        padding: 15px;
        font-family: Helvetica, Arial;
    }
    
    #customers .contacts .fa {
        margin-right: 10px;
    }

    #customers .contact {
        overflow: hidden;
    }

    #customers .contact:last-child {
        padding-bottom: 20px;
    }

    #customers .unavailable {
        color: #aaa;
    }

</style>

@section('title')
Customers
@endsection

@section('content')

<div id="customers" class="container-fluid">

    <div class="row">

        <div class="col-sm-10">
            <h2 style="margin:0">Customers</h2>
        </div>
        
        <div class="col-sm-2">
            <input class="search" placeholder="search..."/>
        </div>

    </div>

    <hr>

    <ul class="list">

    @foreach ($customers as $c)

    <li class="row customer-row">

        <div class="customer">
            <a href="customers/{!! $c->id !!}/history">{!! $c->customer_name !!}</a>
            <div class="pull-right"> 
                <a class="btn btn-default edit" title="Edit {!! ucwords(strtolower($c->customer_name)) !!}" href="{!! route('customers.edit', ['id' => $c->id]) !!}"><i class="fa fa-pencil"></i></a> 
            </div>
        </div>

        <div class="contacts">

            <?php if ( ! $c->customer_contacts->isEmpty() ) : ?>
                <div class="heading"><i class="fa fa-user"></i>Contacts</div>
            <?php else : ?>
                <div class="heading"><i class="fa fa-user"></i>No contacts found</div>
            <?php endif; ?>

            @foreach ($c->customer_contacts as $cc)

            <div class="contact">

                <div class="col-sm-4">
                    {!! $cc->first_name !!} {!! $cc->last_name !!}
                </div>

                <div class="col-sm-4 email">
                    <i class="fa fa-envelope"></i>
                    <?php if ( $cc->email ) : ?>
                        <a href="mailto:{!! $cc->email !!}" title="{!! $cc->email !!}">{{ $cc->email }}</a>
                    <?php else : ?>
                        <span class="unavailable">Not available</span>
                    <?php endif; ?>
                </div>

                <div class="col-sm-2">
                    <i class="fa fa-phone"></i>
                    <?php if ( $cc->phone ) : ?>
                        <a href="tel:{!! $cc->phone !!}">{{ $cc->phone }}</a>
                    <?php else : ?>
                        <span class="unavailable">Not available</span>
                    <?php endif; ?>
                </div>
                
                <div class="col-sm-2">
                    <i class="fa fa-mobile"></i>
                    <?php if ( $cc->mobile ) : ?>
                        <a href="tel:{!! $cc->mobile !!}">{{ $cc->mobile }}</a>
                    <?php else : ?>
                        <span class="unavailable">Not available</span>
                    <?php endif; ?>
                </div>       

            </div>         

            @endforeach

        </div>

    </li>

    @endforeach

    </ul>

</div>

<script  type="text/javascript" src="{{ asset('js/list.min.js') }}"></script>

<script>
    var options = {
        valueNames: ['customer']
    };
    var customerList = new List('customers', options);
</script>

@endsection