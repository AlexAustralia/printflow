@extends('app')

@section('title')
Create Customer
@endsection

@section('content')

<!-- open form -->

{!! Form::open(['route' => 'customers.store']) !!}

	<div class="row">

		<div class="col-sm-10">
			<h2 style="margin:0">Create Customer</h2>
		</div>
		
		<div class="col-sm-2">
			{!! Form::submit('Save', ['class' => 'btn btn-primary pull-right']) !!}
		</div>

	</div>

	<hr>

	@include('partials.errors')

	<div class="row">

		<div class="contact_details">

			<div class="form-group">
			    {!! Form::label('customer_name', 'Customer Name:', ['class' => 'control-label']) !!}
			    {!! Form::text('customer_name', Input::old('customer_name') ?: $customer->customer_name, ['class' => 'form-control']) !!}
			</div>

			<div class="row">

				<div class="form-group col-sm-6">
				    {!! Form::label('web_address', 'Website (URL):', ['class' => 'control-label']) !!}
				    {!! Form::text('web_address', Input::old('web_address') ?: $customer->web_address, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group col-sm-6">
				    {!! Form::label('skype_name', 'Skype Name:', ['class' => 'control-label']) !!}
				    {!! Form::text('skype_name', Input::old('skype_name') ?: $customer->skype_name, ['class' => 'form-control']) !!}
				</div>

			</div>

			<div class="form-group">
			    {!! Form::label('notes', 'Notes:', ['class' => 'control-label']) !!}
			    {!! Form::textarea('notes', Input::old('notes') ?: $customer->notes, ['class' => 'form-control']) !!}
			</div>

		</div>

		<div class="contact_numbers">

			<div class="">

				<label class="control-label">Phone number</label>

				<div class="form-group col-xs-3">
				    {!! Form::text('tel_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>
				<div class="form-group col-xs-3">
				    {!! Form::text('tel_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>
				<div class="form-group col-xs-6">
				    {!! Form::text('tel_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>

			</div>
			
			<div class="">
				
				<label class="control-label">Mobile number</label>

				<div class="form-group col-xs-3">
				    {!! Form::text('mobile_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>
				<div class="form-group col-xs-3">
				    {!! Form::text('mobile_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>
				<div class="form-group col-xs-6">
				    {!! Form::text('mobile_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>

			</div>		

			<div class="">
				
				<label class="control-label">Fax number</label>

				<div class="form-group col-xs-3">
				    {!! Form::text('fax_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>
				<div class="form-group col-xs-3">
				    {!! Form::text('fax_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>
				<div class="form-group col-xs-6">
				    {!! Form::text('tel_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>

			</div>	
				
			<div class="">

				<label class="control-label">Direct number</label>

				<div class="form-group col-xs-3">
				    {!! Form::text('direct_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>
				<div class="form-group col-xs-3">
				    {!! Form::text('direct_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>
				<div class="form-group col-xs-6">
				    {!! Form::text('direct_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>

			</div>
		
		</div>

	</div> <!-- end .row -->

	<div class="address">

		<div class="address__postal">

			<h3>Postal Address</h3>
			<hr>

			<div class="form-group">
			    {!! Form::label('postal_attention', 'Attention:', ['class' => 'control-label']) !!}
			    {!! Form::text('postal_attention', $customer->postal_attention ?: Input::old('postal_attention'), ['class' => 'form-control']) !!}
			</div>

			<div class="form-group">
			    {!! Form::label('postal_street', 'Street Address (or PO Box):', ['class' => 'control-label']) !!}
			    {!! Form::textarea('postal_street', $customer->postal_street ?: Input::old('postal_street'), ['class' => 'form-control address']) !!}
			</div>

			<div class="row">

				<div class="form-group col-sm-6">
				    {!! Form::label('postal_city', 'City:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_city', $customer->postal_city ?: Input::old('postal_city'), ['class' => 'form-control']) !!}
				</div>


				<div class="form-group col-sm-6">
				    {!! Form::label('postal_state', 'State:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_state', $customer->postal_state ?: Input::old('postal_state'), ['class' => 'form-control']) !!}
				</div>


				<div class="form-group col-sm-6">
				    {!! Form::label('postal_postcode', 'Postcode:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_postcode', $customer->postal_postcode ?: Input::old('postal_postcode'), ['class' => 'form-control']) !!}
				</div>	


				<div class="form-group col-sm-6">
				    {!! Form::label('postal_country', 'Country:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_country', $customer->postal_country ?: Input::old('postal_country'), ['class' => 'form-control']) !!}
				</div>	

			</div>

		</div>

		<div class="address__postal">

			<h3>Physical Address</h3>
			<hr>

			<div class="form-group">
			    {!! Form::label('physical_attention', 'Attention:', ['class' => 'control-label']) !!}
			    {!! Form::text('physical_attention', $customer->physical_attention ?: Input::old('physical_attention'), ['class' => 'form-control']) !!}
			</div>

			<div class="form-group">
			    {!! Form::label('physical_street', 'Street Address (or PO Box):', ['class' => 'control-label']) !!}
			    {!! Form::textarea('physical_street', $customer->physical_street ?: Input::old('physical_street'), ['class' => 'form-control address']) !!}
			</div>

			<div class="row">

				<div class="form-group col-sm-6">
				    {!! Form::label('physical_city', 'City:', ['class' => 'control-label']) !!}
				    {!! Form::text('physical_city', $customer->physical_city ?: Input::old('physical_city'), ['class' => 'form-control']) !!}
				</div>


				<div class="form-group col-sm-6">
				    {!! Form::label('physical_state', 'State:', ['class' => 'control-label']) !!}
				    {!! Form::text('physical_state', $customer->physical_state ?: Input::old('physical_state'), ['class' => 'form-control']) !!}
				</div>


				<div class="form-group col-sm-6">
				    {!! Form::label('physical_postcode', 'Postcode:', ['class' => 'control-label']) !!}
				    {!! Form::text('physical_postcode', $customer->physical_postcode ?: Input::old('physical_postcode'), ['class' => 'form-control']) !!}
				</div>	


				<div class="form-group col-sm-6">
				    {!! Form::label('physical_country', 'Country:', ['class' => 'control-label']) !!}
				    {!! Form::text('physical_country', $customer->physical_country ?: Input::old('physical_country'), ['class' => 'form-control']) !!}
				</div>	

			</div>

		</div>

	</div> <!-- end .address -->

	<div class="contacts">

		<h3>Contacts</h3>
		<hr>

		@if ( ! $contacts->isEmpty() )
			
	        @foreach ($contacts as $contact)

				<div class="row contact">

					<div class="form-group col-sm-3">

						<div class="row">

						    <div class="form-group col-sm-6">
						    	{!! Form::label('first_name', 'First name:', ['class' => 'control-label']) !!}
						    	{!! Form::text('contacts['.$contact->id.'][first_name]', Input::old('first_name') ?: $contact->first_name, ['class' => 'form-control first_name']) !!}
						    </div>
						    <div class="form-group col-sm-6">
						    	{!! Form::label('last_name', 'Last name:', ['class' => 'control-label']) !!}
						    	{!! Form::text('contacts['.$contact->id.'][last_name]', Input::old('last_name') ?: $contact->last_name, ['class' => 'form-control last_name']) !!}
						    </div>

						</div>

					</div>
					
					<div class="form-group col-sm-2">
						{!! Form::label('phone', 'Phone:', ['class' => 'control-label']) !!}
					    {!! Form::text('contacts['.$contact->id.'][phone]', Input::old('phone') ?: $contact->phone, ['class' => 'form-control phone']) !!}
					</div>
					
					<div class="form-group col-sm-2">
						{!! Form::label('mobile', 'Mobile:', ['class' => 'control-label']) !!}
					    {!! Form::text('contacts['.$contact->id.'][mobile]', Input::old('mobile') ?: $contact->mobile, ['class' => 'form-control mobile']) !!}
					</div>

					<div class="form-group col-sm-3">
						{!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
					    {!! Form::text('contacts['.$contact->id.'][email]', Input::old('email') ?: $contact->email, ['class' => 'form-control email']) !!}
					</div>

					<div class="form-group col-sm-2">
						{!! Form::label('primary_person', 'Primary Person:', ['class' => 'control-label']) !!}
					   	{!! Form::select('contacts['.$contact->id.'][primary_person]', [0 => 'No', 1 => 'Yes'], $contact->primary_person, ['class' => 'form-control primary_person', 'autocomplete' => 'off']) !!}
					</div>

				</div>

	        @endforeach				

		@else

			<div class="row contact">

				<div class="form-group col-sm-3">

					<div class="row">

					    <div class="form-group col-sm-6">
					    	{!! Form::label('first_name', 'First name:', ['class' => 'control-label']) !!}
					    	{!! Form::text('contacts[::'.time().'][first_name]', null, ['class' => 'form-control first_name']) !!}
					    </div>
					    <div class="form-group col-sm-6">
					    	{!! Form::label('last_name', 'Last name:', ['class' => 'control-label']) !!}
					    	{!! Form::text('contacts[::'.time().'][last_name]', null, ['class' => 'form-control last_name']) !!}
					    </div>

					</div>

				</div>
				
				<div class="form-group col-sm-2">
					{!! Form::label('phone', 'Phone:', ['class' => 'control-label']) !!}
				    {!! Form::text('contacts[::'.time().'][phone]', null, ['class' => 'form-control phone']) !!}
				</div>
				
				<div class="form-group col-sm-2">
					{!! Form::label('mobile', 'Mobile:', ['class' => 'control-label']) !!}
				    {!! Form::text('contacts[::'.time().'][mobile]', null, ['class' => 'form-control mobile']) !!}
				</div>

				<div class="form-group col-sm-3">
					{!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
				    {!! Form::text('contacts[::'.time().'][email]', null, ['class' => 'form-control email']) !!}
				</div>

				<div class="form-group col-sm-2">
					{!! Form::label('primary_person', 'Primary Person:', ['class' => 'control-label']) !!}
				   	{!! Form::select('contacts[::'.time().'][primary_person]', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control primary_person', 'autocomplete' => 'off']) !!}
				</div>

			</div>
					
		@endif

		<div id="add-contact" class="btn btn-default pull-right">ADD CONTACT</div>

	</div>

{!! Form::close() !!}
<!-- close form -->

@endsection