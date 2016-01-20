@extends('app')

@section('title')
	Edit Supplier - <?php echo ucwords(strtolower(! empty($supplier->supplier_name) ? $supplier->supplier_name : '')); ?>
@endsection

@section('content')
	<link href="{{ asset('css/errors.css') }}" rel="stylesheet" type="text/css">
	<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('js/edit_contacts.js') }}"></script>
	<script>
		$(document).ready(function() {

			// Validation of the form
			$('form').validate(
					{
						rules: {
							supplier_name: {
								required: true,
							},
							common_email: {
								required: true,
							},
						},
						messages: {
							supplier_name: {
								required: "Enter Supplier Name"
							},
							common_email: {
								required: "Enter Email Address"
							}
						}
					}
			);
		});
	</script>

<!-- open form -->
@if(isset($supplier))
    {!! Form::model($supplier, ['route' => ['suppliers.update', $supplier->id], 'method' => 'patch']) !!}
@else
    {!! Form::open(['route' => 'suppliers.store']) !!}
@endif

	<div class="row">

		<div class="col-sm-10">
			<h2 style="margin:0">Edit Supplier: <?php echo ucwords(strtolower(! empty($supplier->supplier_name) ? $supplier->supplier_name : '')); ?></h2>
		</div>
		
		<div class="col-sm-2">
			<div class="btn btn-danger pull-right" data-delete="true" data-toggle="modal" data-target="#deleteModal">Delete</div>
			{!! Form::submit('Save', ['class' => 'btn btn-primary pull-right']) !!}
		</div>

	</div>

	<hr>

	@include('partials.errors')

	<div class="row">
		<div class="contact_details">

			<div class="form-group">
			    {!! Form::label('supplier_name', 'Supplier Name*:', ['class' => 'control-label']) !!}
			    {!! Form::text('supplier_name', Input::old('supplier_name') ?: $supplier->supplier_name, ['class' => 'form-control']) !!}
			</div>

			<div class="row">
				<div class="form-group col-sm-4">
				    {!! Form::label('web_address', 'Website (URL):', ['class' => 'control-label']) !!}
				    {!! Form::text('web_address', Input::old('web_address') ?: $supplier->web_address, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group col-sm-4">
				    {!! Form::label('skype_name', 'Skype Name:', ['class' => 'control-label']) !!}
				    {!! Form::text('skype_name', Input::old('skype_name') ?: $supplier->skype_name, ['class' => 'form-control']) !!}
				</div>

				<div class="form-group col-sm-4">
					{!! Form::label('common_email', 'Common Email*:', ['class' => 'control-label']) !!}
					{!! Form::text('common_email', Input::old('common_email') ?: $supplier->common_email, ['class' => 'form-control email']) !!}
				</div>
			</div>

			<div class="form-group">
			    {!! Form::label('notes', 'Notes:', ['class' => 'control-label']) !!}
			    {!! Form::textarea('notes', Input::old('notes') ?: $supplier->notes, ['class' => 'form-control']) !!}
			</div>
		</div>

		<div class="contact_numbers">
			<div>

				<label class="control-label">Phone number</label>

				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('tel_country', 'Country Code', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('tel_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>

				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('tel_area', 'Area Code:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('tel_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>

				<div class="form-group col-xs-6">
				    {{-- {!! Form::label('tel_number', 'Number:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('tel_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>
			</div>
			
			<div>
				<label class="control-label">Mobile number</label>
				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('mobile_country', 'Country Code', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('mobile_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>

				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('mobile_area', 'Area Code:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('mobile_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>

				<div class="form-group col-xs-6">
				    {{-- {!! Form::label('mobile_number', 'Number:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('mobile_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>
			</div>

			<div>
				<label class="control-label">Fax number</label>
				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('fax_country', 'Country Code', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('fax_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>

				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('fax_area', 'Area Code:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('fax_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>

				<div class="form-group col-xs-6">
				    {{-- {!! Form::label('tel_number', 'Number:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('fax_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>
			</div>
				
			<div>
				<label class="control-label">Direct number</label>
				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('direct_country', 'Country Code', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('direct_country', null, ['class' => 'form-control', 'placeholder' => 'country code']) !!}
				</div>

				<div class="form-group col-xs-3">
				    {{-- {!! Form::label('direct_area', 'Area Code:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('direct_area', null, ['class' => 'form-control', 'placeholder' => 'area code']) !!}
				</div>

				<div class="form-group col-xs-6">
				    {{-- {!! Form::label('direct_number', 'Number:', ['class' => 'control-label']) !!} --}}
				    {!! Form::text('direct_number', null, ['class' => 'form-control', 'placeholder' => 'number']) !!}
				</div>
			</div>
		</div>
	</div> <!-- end .row -->

	<div class="row">
		<div id="postal_address" class="col-xs-12">

			<h3>Postal Address</h3>
			<hr>

			<div class="form-group">
			    {!! Form::label('postal_attention', 'Attention:', ['class' => 'control-label']) !!}
			    {!! Form::text('postal_attention', $supplier->postal_attention ?: Input::old('postal_attention'), ['class' => 'form-control']) !!}
			</div>

			<div class="form-group">
			    {!! Form::label('postal_street', 'Street Address (or PO Box):', ['class' => 'control-label']) !!}
			    {!! Form::text('postal_street', $supplier->postal_street ?: Input::old('postal_street'), ['class' => 'form-control address']) !!}
			</div>

			<div class="row">
				<div class="form-group col-sm-3">
				    {!! Form::label('postal_city', 'City:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_city', $supplier->postal_city ?: Input::old('postal_city'), ['class' => 'form-control']) !!}
				</div>

				<div class="form-group col-sm-3">
				    {!! Form::label('postal_state', 'State:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_state', $supplier->postal_state ?: Input::old('postal_state'), ['class' => 'form-control']) !!}
				</div>

				<div class="form-group col-sm-3">
				    {!! Form::label('postal_postcode', 'Postcode:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_postcode', $supplier->postal_postcode ?: Input::old('postal_postcode'), ['class' => 'form-control']) !!}
				</div>	

				<div class="form-group col-sm-3">
				    {!! Form::label('postal_country', 'Country:', ['class' => 'control-label']) !!}
				    {!! Form::text('postal_country', $supplier->postal_country ?: Input::old('postal_country'), ['class' => 'form-control']) !!}
				</div>	
			</div>
		</div>

		<div id="physical_address" class="col-xs-12 alert alert-warning">
			<h3>Physical Address</h3>
			<hr>

			<div id="physical_same" class="checkbox">
				<label>
					<input type="checkbox" value="" @if(empty($supplier->physical_attention) && empty($supplier->physical_street) && empty($supplier->physical_city) && empty($supplier->physical_state) && empty($supplier->physical_postcode) && empty($supplier->physical_country)) checked @endif> The Physical Address is the same as the Postal Address
				</label>
			</div>

			<div id="physical_block" @if(empty($supplier->physical_attention) && empty($supplier->physical_street) && empty($supplier->physical_city) && empty($supplier->physical_state) && empty($supplier->physical_postcode) && empty($supplier->physical_country)) style="display: none;" @endif>
				<div class="form-group">
					{!! Form::label('physical_attention', 'Attention:', ['class' => 'control-label']) !!}
					{!! Form::text('physical_attention', $supplier->physical_attention ?: Input::old('physical_attention'), ['class' => 'form-control']) !!}
				</div>

				<div class="form-group">
					{!! Form::label('physical_street', 'Street Address (or PO Box):', ['class' => 'control-label']) !!}
					{!! Form::text('physical_street', $supplier->physical_street ?: Input::old('physical_street'), ['class' => 'form-control address']) !!}
				</div>

				<div class="row">
					<div class="form-group col-sm-3">
						{!! Form::label('physical_city', 'City:', ['class' => 'control-label']) !!}
						{!! Form::text('physical_city', $supplier->physical_city ?: Input::old('physical_city'), ['class' => 'form-control']) !!}
					</div>

					<div class="form-group col-sm-3">
						{!! Form::label('physical_state', 'State:', ['class' => 'control-label']) !!}
						{!! Form::text('physical_state', $supplier->physical_state ?: Input::old('physical_state'), ['class' => 'form-control']) !!}
					</div>

					<div class="form-group col-sm-3">
						{!! Form::label('physical_postcode', 'Postcode:', ['class' => 'control-label']) !!}
						{!! Form::text('physical_postcode', $supplier->physical_postcode ?: Input::old('physical_postcode'), ['class' => 'form-control']) !!}
					</div>

					<div class="form-group col-sm-3">
						{!! Form::label('physical_country', 'Country:', ['class' => 'control-label']) !!}
						{!! Form::text('physical_country', $supplier->physical_country ?: Input::old('physical_country'), ['class' => 'form-control']) !!}
					</div>
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
					<div class="col-xs-11">
					    <div class="form-group col-sm-2">
					    	{!! Form::label('first_name', 'First name:', ['class' => 'control-label']) !!}
					    	{!! Form::text('contacts['.$contact->id.'][first_name]', Input::old('first_name') ?: $contact->first_name, ['class' => 'form-control first_name']) !!}
					    </div>

						<div class="form-group col-sm-2">
					    	{!! Form::label('last_name', 'Last name:', ['class' => 'control-label']) !!}
					    	{!! Form::text('contacts['.$contact->id.'][last_name]', Input::old('last_name') ?: $contact->last_name, ['class' => 'form-control last_name']) !!}
					    </div>

						<div class="form-group col-sm-2">
							{!! Form::label('phone', 'Phone:', ['class' => 'control-label']) !!}
						    {!! Form::text('contacts['.$contact->id.'][phone]', Input::old('phone') ?: $contact->phone, ['class' => 'form-control phone']) !!}
						</div>
					
						<div class="form-group col-sm-2">
							{!! Form::label('mobile', 'Mobile:', ['class' => 'control-label']) !!}
					    	{!! Form::text('contacts['.$contact->id.'][mobile]', Input::old('mobile') ?: $contact->mobile, ['class' => 'form-control mobile']) !!}
						</div>

						<div class="form-group col-sm-2">
							{!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
						    {!! Form::text('contacts['.$contact->id.'][email]', Input::old('email') ?: $contact->email, ['class' => 'form-control email']) !!}
						</div>

						<div class="form-group col-sm-2">
							{!! Form::label('primary_person', 'Primary Person:', ['class' => 'control-label']) !!}
					   		{!! Form::select('contacts['.$contact->id.'][primary_person]', [0 => 'No', 1 => 'Yes'], $contact->primary_person, ['class' => 'form-control primary_person', 'autocomplete' => 'off']) !!}
						</div>
					</div>
					<button id="remove_row" style="margin-top:25px;" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
				</div>

	        @endforeach				

		@else

			<div class="row contact">
				<div class="col-xs-11">
				    <div class="form-group col-sm-2">
				    	{!! Form::label('first_name', 'First name:', ['class' => 'control-label']) !!}
				    	{!! Form::text('contacts[::'.time().'][first_name]', null, ['class' => 'form-control first_name']) !!}
				    </div>

				    <div class="form-group col-sm-2">
				    	{!! Form::label('last_name', 'Last name:', ['class' => 'control-label']) !!}
				    	{!! Form::text('contacts[::'.time().'][last_name]', null, ['class' => 'form-control last_name']) !!}
				    </div>

					<div class="form-group col-sm-2">
						{!! Form::label('phone', 'Phone:', ['class' => 'control-label']) !!}
					    {!! Form::text('contacts[::'.time().'][phone]', null, ['class' => 'form-control phone']) !!}
					</div>
				
					<div class="form-group col-sm-2">
						{!! Form::label('mobile', 'Mobile:', ['class' => 'control-label']) !!}
					    {!! Form::text('contacts[::'.time().'][mobile]', null, ['class' => 'form-control mobile']) !!}
					</div>

					<div class="form-group col-sm-2">
						{!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
					    {!! Form::text('contacts[::'.time().'][email]', null, ['class' => 'form-control email']) !!}
					</div>

					<div class="form-group col-sm-2">
						{!! Form::label('primary_person', 'Primary Person:', ['class' => 'control-label']) !!}
					   	{!! Form::select('contacts[::'.time().'][primary_person]', [0 => 'No', 1 => 'Yes'], 0, ['class' => 'form-control primary_person', 'autocomplete' => 'off']) !!}
					</div>
				</div>
				<button id="remove_row" style="margin-top:25px;" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
			</div>
			
		@endif

		<div id="add-contact" class="btn btn-default pull-right">ADD CONTACT</div>

	</div>

{!! Form::close() !!}
<!-- close form -->

@include('partials.delete_supplier')

@endsection