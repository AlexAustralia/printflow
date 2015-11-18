<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
        @section('title')
        Printflow v2
        @show
    </title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script>
       $(function() {
          $('.nav a').each(function() {
            if ($(this).attr('href')  ===  window.location.pathname) {
              $(this).addClass('disabled');
            }
          });
        });

		jQuery(document).ready(function($){

			$('#add-contact').on('click', function() {
				var clone = $('.contacts').find('.row').first().clone();
				var uniqueId = Date.now();
				clone.find('.first_name').val('').attr('name', 'contacts[::'+uniqueId+'][first_name]');
				clone.find('.last_name').val('').attr('name', 'contacts[::'+uniqueId+'][first_name]');
				clone.find('.phone').val('').attr('name', 'contacts[::'+uniqueId+'][last_name]');
				clone.find('.mobile').val('').attr('name', 'contacts[::'+uniqueId+'][mobile]');
				clone.find('.email').val('').attr('name', 'contacts[::'+uniqueId+'][email]');
				$(clone).insertBefore('#add-contact');
			});

			$('select.primary_person').on('change', function() {
				var context = $(this);
				if ( $(this).val() === "1" ) {
					$('select.primary_person').each(function(){
						$(this).not(context).val("0");
					});
				}

			});

			$('.delete-object').click(function(event) {
				$('#deleteContact').submit();
			});
		});

    </script>
   
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#"> Printflow v2 </a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				
				<ul class="nav navbar-nav">

					<li><a href="{{ url('/') }}">All Quotes</a></li>
					
			        <li class="dropdown">
			          <a href="{{ url('/customers') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Customers <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			            <li><a href="{{ url('/customers') }}">All Customers</a></li>
			            <li><a href="{{ url('/customers/create') }}">Create Customer</a></li>
			          </ul>
			        </li>	
			        				
			        <li class="dropdown">
			          <a href="{{ url('/suppliers') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Suppliers <span class="caret"></span></a>
			          <ul class="dropdown-menu">
			            <li><a href="{{ url('/suppliers') }}">All Suppliers</a></li>
			            <li><a href="{{ url('/suppliers/create') }}">Create Supplier</a></li>
			          </ul>
			        </li>	

				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<!--li><a href="{{ url('/auth/register') }}">Register</a></li-->
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

    <div style="width:80%; margin-left:auto; margin-right:auto;">

{{-- 	@section('page-title')
        <h2 style="margin-bottom:20px">Printflow v2</h2>
    @show --}}


    @if (isset($quote_request))
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                {!! "Quote #".$quote_request->id.": ".$quote_request->title !!}
                @if (isset($quote_request->customer->customer_name))
                |
                Customer: {!! $quote_request->customer->customer_name !!}
                @endif
            </h3>
        </div>
    </div>

    <div class="btn-group nav" >
        <a class="btn btn-primary" href="/quote_requests/{!! $quote_request->id !!}/edit">Enter Quote Request</a>
        <a class="btn btn-primary" href="/choose_suppliers/{!! $quote_request->id !!}">Choose Suppliers</a>
        <a class="btn btn-primary" href="/send_rfq_emails/{!! $quote_request->id !!}">Request Supplier Quotes</a>
        <a class="btn btn-primary" href="/enter_prices/{!! $quote_request->id !!}">Enter Supplier Prices</a>
        <a class="btn btn-primary" href="/evaluate/{!! $quote_request->id !!}">Evaluate Prices</a>
        <a class="btn btn-primary" href="/send_customer_quote/{!! $quote_request->id !!}">Send Customer Quote</a>
    </div>
    <p style="clear:both; margin-bottom:40px;"></p>
    @endif

	@yield('content')

    </div>

    @include('partials.footer')

<script  type="text/javascript" src="<?php echo asset('js/list.min.js');?>"></script>

<script>

// var customerList = new List(
//     'customers', 
//     {
//         searchClass : 'search',
//         valueNames  : [ 'customer' ]
//     }
// );

</script> 
	<!-- Scripts -->
</body>
</html>
