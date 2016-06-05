@extends('app')

@section('title')
    Review Supplier - <?php echo ucwords(strtolower(! empty($supplier->supplier_name) ? $supplier->supplier_name : '')); ?>
@endsection

@section('content')

    {!! Form::open(['url' => 'suppliers/'.$supplier->id.'/review/update', 'method' => 'post', 'id' => 'review_form', 'class' => 'form-horizontal', 'files' => true]) !!}

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0"><img src="/images/edit_supplier.png"> Review Supplier: <?php echo ucwords(strtolower(! empty($supplier->supplier_name) ? $supplier->supplier_name : '')); ?></h2>
        </div>

        <div class="col-sm-2">
            {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right', 'style' => 'margin-top: 30px;']) !!}
        </div>
    </div>

    <hr>

    @include('partials.edit_supplier_menu')

    <div class="form-group">
        {!! Form::label('product', 'Product Supplied', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-4">
            {!! Form::text('product', null, array('class' => 'form-control')) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('date_visited', 'Date Visited', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-4">
            {!! Form::text('date_visited', null, array('class' => 'form-control datepicker')) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('rating', 'Rating', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-4">
            <select name="rating" class="form-control">
                <option value="Excellent">Excellent</option>
                <option value="Good">Good</option>
                <option value="Satisfactory">Satisfactory</option>
                <option value="Fair">Fair</option>
                <option value="Poor">Poor</option>
                <option value="Not Rated">Not Rated</option>
                <option value=">Not Applicable">Not Applicable</option>
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <table class="table">
            <tr class="success" data-toggle="collapse" data-target="#office-tab">
                <th>Office:</th>
            </tr>
            <tr class="collapse" id="office-tab">
                <td>

                    <div class="form-group">
                        {!! Form::label('sales_department', 'Sales Department', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="sales_department" class="form-control">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Satisfactory">Satisfactory</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Not Rated">Not Rated</option>
                                <option value=">Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('sd_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('sd_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('staff_number', 'Number of Staff', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-4">
                            {!! Form::text('staff_number', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('sample_room', 'Sample Room', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="sample_room" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('sr_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('sr_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('photo_office', 'Add Photos', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            {!! Form::file('photo_office[]', array('multiple' => true)) !!}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-12">
        <table class="table">
            <tr class="success" data-toggle="collapse" data-target="#warehouse-tab">
                <th>Warehouse:</th>
            </tr>
            <tr class="collapse" id="warehouse-tab">
                <td>

                    <div class="form-group">
                        {!! Form::label('building_type', 'Building Type', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-4">
                            <select name="building_type" class="form-control">
                                <option value="Outside Building (Freestanding)">Outside Building (Freestanding)</option>
                                <option value="In Complex">In Complex</option>
                                <option value="3">Satisfactory</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('production_staff', 'Number of Production Staff', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-4">
                            {!! Form::text('production_staff', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('well_lit', 'Well Lit', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="well_lit" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('lit_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('lit_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('safety_markings', 'Safety Markings', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="safety_markings" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('safety_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('safety_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('safe_working_conditions', 'Safe Working Conditions', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="safe_working_conditions" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('sf_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('sf_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('conditions', 'Condition', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="conditions" class="form-control">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Satisfactory">Satisfactory</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Not Rated">Not Rated</option>
                                <option value=">Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('conditions_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('conditions_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('export_carton_packing', 'Export Carton Packing', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-2">
                            <select name="export_carton_packing" class="form-control">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Satisfactory">Satisfactory</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Not Rated">Not Rated</option>
                                <option value=">Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('ecp_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-1">
                            {!! Form::text('ecp_notes', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('less15', 'Cartons Packed Less Than 15kgs', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-2">
                            <select name="less15" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('packing', 'Pallet Strapping and Packing', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="packing" class="form-control">
                                <option value="Excellent">Excellent</option>
                                <option value="Good">Good</option>
                                <option value="Satisfactory">Satisfactory</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                                <option value="Not Rated">Not Rated</option>
                                <option value=">Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('packing_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('packing_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('photo_warehouse', 'Add Photos', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            {!! Form::file('photo_warehouse[]', array('multiple' => true)) !!}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-12">
        <table class="table">
            <tr class="success" data-toggle="collapse" data-target="#pre_press-tab">
                <th>Pre Press:</th>
            </tr>
            <tr class="collapse" id="pre_press-tab">
                <td>

                    <div class="form-group">
                        {!! Form::label('pp_staff_number', 'Number of Staff', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-4">
                            {!! Form::text('pp_staff_number', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('age_machine', 'Age of Machines', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="age_machine" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('proofing', 'Proofing', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="proofing" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('proof_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('proof_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('samples', 'Dummies / Samples', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            <select name="samples" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('samples_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-4">
                            {!! Form::text('samples_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group programs">
                        {!! Form::label('programs', 'Programs Used', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-4">
                            {!! Form::text('programs[]', null, array('class' => 'form-control')) !!}
                        </div>

                        <button type="button" class="btn btn-primary" id="add_program">Add Another</button>
                    </div>

                    <div class="form-group">
                        {!! Form::label('ctp', 'CTP', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-2">
                            <select name="ctp" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('ctp_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="ctp_age" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('ctp_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('ctp_model', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('film', 'Film', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-2">
                            <select name="film" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('film_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="film_age" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('film_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('film_model', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('photo_pre_press', 'Add Photos', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            {!! Form::file('photo_pre_press[]', array('multiple' => true)) !!}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-12">
        <table class="table">
            <tr class="success" data-toggle="collapse" data-target="#production-tab">
                <th>Production:</th>
            </tr>
            <tr class="collapse" id="production-tab">
                <td>

                    <div class="form-group machines">
                        {!! Form::label('machines', 'Machines', array('class' => 'control-label col-md-1')) !!}

                        {!! Form::label('brand', 'Brand', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('brand[]', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('colors', 'Number of colours', array('class' => 'control-label col-md-2')) !!}
                        <div class="col-md-2">
                            {!! Form::text('colors[]', null, array('class' => 'form-control')) !!}
                        </div>

                        <div class="col-md-1">
                            <input type="checkbox" value="0" name="uv[]"> UV
                        </div>

                        <div class="col-md-1">
                            <input type="checkbox" value="0" name="coater[]"> Coater
                        </div>

                        <button type="button" class="btn btn-primary" id="add_machine">Add Another</button>
                    </div>

                </td>
            </tr>
        </table>
    </div>

    <div class="col-md-12">
        <table class="table">
            <tr class="success" data-toggle="collapse" data-target="#finishing-tab">
                <th>Finishing:</th>
            </tr>
            <tr class="collapse" id="finishing-tab">
                <td>

                    <div class="form-group">
                        {!! Form::label('folding', 'Folding', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="folding" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('folding_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="folding_age" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('folding_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('folding_model', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('folding_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('folding_notes', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group binding">
                        {!! Form::label('binding', 'Binding', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="binding[]" class="form-control">
                                <option value="Perfect Binding">Perfect Binding</option>
                                <option value="Case Binding">Case Binding</option>
                                <option value="Saddle Stitching">Saddle Stitching</option>
                                <option value="Wire O">Wire O</option>
                                <option value="Section Sewing">Section Sewing</option>
                            </select>
                        </div>

                        {!! Form::label('binding_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="binding_age[]" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('binding_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('binding_model[]', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('binding_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-1">
                            {!! Form::text('binding_notes[]', null, array('class' => 'form-control')) !!}
                        </div>

                        <button type="button" class="btn btn-primary" id="add_binding">Add Line</button>
                    </div>

                    <div class="form-group guilotine">
                        {!! Form::label('guilotine', 'Guilotine', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="guilotine[]" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('guilotine_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="guilotine_age[]" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('guilotine_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('guilotine_model[]', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('guilotine_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-1">
                            {!! Form::text('guilotine_notes[]', null, array('class' => 'form-control')) !!}
                        </div>

                        <button type="button" class="btn btn-primary" id="add_guilotine">Add Line</button>
                    </div>

                    <div class="form-group laminating">
                        {!! Form::label('laminating', 'Laminating', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="laminating[]" class="form-control">
                                <option value="Reel Fed">Reel Fed</option>
                                <option value="Sheet Fed">Sheet Fed</option>
                            </select>
                        </div>

                        {!! Form::label('laminating_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="laminating_age[]" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('laminating_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('laminating_model[]', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('laminating_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-1">
                            {!! Form::text('laminating_notes[]', null, array('class' => 'form-control')) !!}
                        </div>

                        <button type="button" class="btn btn-primary" id="add_laminating">Add Line</button>
                    </div>

                    <div class="form-group cutting">
                        {!! Form::label('cutting', 'Die Cutting', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="cutting[]" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>

                        {!! Form::label('cutting_age', 'Age', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            <select name="cutting_age[]" class="form-control">
                                <option value="1-3 Years Old">1-3 Years Old</option>
                                <option value="4-5 Years Old">4-5 Years Old</option>
                                <option value="6-10 Years Old">6-10 Years Old</option>
                                <option value="11 Years and Older">11 Years and Older</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>

                        {!! Form::label('cutting_model', 'Model', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-2">
                            {!! Form::text('cutting_model[]', null, array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::label('cutting_notes', 'Notes', array('class' => 'control-label col-md-1')) !!}
                        <div class="col-md-1">
                            {!! Form::text('cutting_notes[]', null, array('class' => 'form-control')) !!}
                        </div>

                        <button type="button" class="btn btn-primary" id="add_cutting">Add Line</button>
                    </div>

                    <div class="form-group">
                        {!! Form::label('in_house', 'In House Formes', array('class' => 'control-label col-md-2')) !!}
                        <div class="col-md-2">
                            <select name="in_house[]" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Not Applicable">Not Applicable</option>
                                <option value="No Assessed">Not Assessed</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('other', 'Other Machines', array('class' => 'control-label col-md-2')) !!}
                        <div class="col-md-2">
                            <select name="other[]" class="form-control">
                                <option value="Option 1">Option 1</option>
                                <option value="Option 2">Option 2</option>
                                <option value="Add An Option">Add An Option</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('photo_finishing', 'Add Photos', array('class' => 'control-label col-md-3')) !!}
                        <div class="col-md-3">
                            {!! Form::file('photo_finishing[]', array('multiple' => true)) !!}
                        </div>
                    </div>

                </td>
            </tr>
        </table>
    </div>

    {!! Form::close() !!}

    <script src="{{ asset('js/bootstrap.file-input.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true,
                changeYear: true,
                firstDay: 1
            });

            $('input[type=file]').bootstrapFileInput();

            $('#add_program').on('click', function(){
                $('.programs:last').after('<div class="form-group programs"><div class="col-md-4 col-md-offset-3">' +
                        '<input type="text" class="form-control" name="programs[]"></div>' +
                        '<button type="button" class="btn btn-sm btn-danger remove-program"><span class="fa fa-trash-o"></span></div>');
            });

            $('#pre_press-tab').on('click', '.remove-program', function() {
                $(this).parents('.programs').remove();
            });

            $('#add_machine').on('click', function() {
                var machines = 0;
                $('.machines').each(function(){
                    machines++;
                });

                $('.machines:last').after('<div class="form-group machines"><label class="control-label col-md-1 col-md-offset-1">Brand</label>' +
                                '<div class="col-md-2"><input type="text" name="brand[]" class="form-control"></div>' +
                                '<label class="control-label col-md-2">Number of colours</label><div class="col-md-2">' +
                                '<input type="text" class="form-control" name="colors[]"></div>' +
                                '<div class="col-md-1"><input type="checkbox" value="' + machines + '" name="uv[]"> UV</div>' +
                                '<div class="col-md-1"><input type="checkbox" value="' + machines + '" name="coater[]"> Coater</div>' +
                                '<button type="button" class="btn btn-sm btn-danger remove-machine"><span class="fa fa-trash-o"></span></button></div>');
            });

            $('#production-tab').on('click', '.remove-machine', function() {
                $(this).parents('.machines').remove();
            });

            $('#add_binding').on('click', function() {
                $('.binding:last').after('<div class="form-group binding">' +
                                '<div class="col-md-2 col-md-offset-1"><select name="folding[]" class="form-control"><option value="Perfect Binding">Perfect Binding</option>' +
                                '<option value="Case Binding">Case Binding</option><option value="Saddle Stitching">Saddle Stitching</option>' +
                                '<option value="Wire O">Wire O</option><option value="Section Sewing">Section Sewing</option></select></div>' +
                                '<label class="control-label col-md-1">Age</label><div class="col-md-2"><select name="binding_age[]" class="form-control">' +
                                '<option value="1-3 Years Old">1-3 Years Old</option><option value="4-5 Years Old">4-5 Years Old</option>' +
                                '<option value="6-10 Years Old">6-10 Years Old</option><option value="11 Years and Older">11 Years and Older</option>' +
                                '<option value="Not Applicable">Not Applicable</option></select></div><label class="control-label col-md-1">Model</label>' +
                                '<div class="col-md-2"><input type="text" name="binding_model[]" class="form-control"></div>' +
                                '<label class="control-label col-md-1">Notes</label><div class="col-md-1">' +
                                '<input type="text" name="binding_notes[]" class="form-control"></div>' +
                                '<button type="button" class="btn btn-sm btn-danger remove-binding"><span class="fa fa-trash-o"></span></button></div>');
            });

            $('#finishing-tab').on('click', '.remove-binding', function() {
                $(this).parents('.binding').remove();
            });

            $('#add_guilotine').on('click', function() {
                $('.guilotine:last').after('<div class="form-group guilotine">' +
                        '<div class="col-md-2 col-md-offset-1"><select name="guilotine[]" class="form-control"><option value="Yes">Yes</option>' +
                        '<option value="No">No</option><option value="Not Applicable">Not Applicable</option><option value="No Assessed">Not Assessed</option></select></div>' +
                        '<label class="control-label col-md-1">Age</label><div class="col-md-2"><select name="guilotine_age[]" class="form-control">' +
                        '<option value="1-3 Years Old">1-3 Years Old</option><option value="4-5 Years Old">4-5 Years Old</option>' +
                        '<option value="6-10 Years Old">6-10 Years Old</option><option value="11 Years and Older">11 Years and Older</option>' +
                        '<option value="Not Applicable">Not Applicable</option></select></div><label class="control-label col-md-1">Model</label>' +
                        '<div class="col-md-2"><input type="text" name="guilotine_model[]" class="form-control"></div>' +
                        '<label class="control-label col-md-1">Notes</label><div class="col-md-1">' +
                        '<input type="text" name="guilotine_notes[]" class="form-control"></div>' +
                        '<button type="button" class="btn btn-sm btn-danger remove-guilotine"><span class="fa fa-trash-o"></span></button></div>');
            });

            $('#finishing-tab').on('click', '.remove-guilotine', function() {
                $(this).parents('.guilotine').remove();
            });

            $('#add_laminating').on('click', function() {
                $('.laminating:last').after('<div class="form-group laminating">' +
                        '<div class="col-md-2 col-md-offset-1"><select name="laminating[]" class="form-control"><option value="Reel Fed">Reel Fed</option>' +
                        '<option value="Sheet Fed">Sheet Fed</option></select></div>' +
                        '<label class="control-label col-md-1">Age</label><div class="col-md-2"><select name="laminating_age[]" class="form-control">' +
                        '<option value="1-3 Years Old">1-3 Years Old</option><option value="4-5 Years Old">4-5 Years Old</option>' +
                        '<option value="6-10 Years Old">6-10 Years Old</option><option value="11 Years and Older">11 Years and Older</option>' +
                        '<option value="Not Applicable">Not Applicable</option></select></div><label class="control-label col-md-1">Model</label>' +
                        '<div class="col-md-2"><input type="text" name="laminating_model[]" class="form-control"></div>' +
                        '<label class="control-label col-md-1">Notes</label><div class="col-md-1">' +
                        '<input type="text" name="laminating_notes[]" class="form-control"></div>' +
                        '<button type="button" class="btn btn-sm btn-danger remove-laminating"><span class="fa fa-trash-o"></span></button></div>');
            });

            $('#finishing-tab').on('click', '.remove-laminating', function() {
                $(this).parents('.laminating').remove();
            });

            $('#add_cutting').on('click', function() {
                $('.cutting:last').after('<div class="form-group cutting">' +
                        '<div class="col-md-2 col-md-offset-1"><select name="cutting[]" class="form-control"><option value="Yes">Yes</option>' +
                        '<option value="No">No</option><option value="Not Applicable">Not Applicable</option><option value="No Assessed">Not Assessed</option></select></div>' +
                        '<label class="control-label col-md-1">Age</label><div class="col-md-2"><select name="cutting_age[]" class="form-control">' +
                        '<option value="1-3 Years Old">1-3 Years Old</option><option value="4-5 Years Old">4-5 Years Old</option>' +
                        '<option value="6-10 Years Old">6-10 Years Old</option><option value="11 Years and Older">11 Years and Older</option>' +
                        '<option value="Not Applicable">Not Applicable</option></select></div><label class="control-label col-md-1">Model</label>' +
                        '<div class="col-md-2"><input type="text" name="cutting_model[]" class="form-control"></div>' +
                        '<label class="control-label col-md-1">Notes</label><div class="col-md-1">' +
                        '<input type="text" name="cutting_notes[]" class="form-control"></div>' +
                        '<button type="button" class="btn btn-sm btn-danger remove-cutting"><span class="fa fa-trash-o"></span></button></div>');
            });

            $('#finishing-tab').on('click', '.remove-cutting', function() {
                $(this).parents('.cutting').remove();
            });
        })
    </script>

@endsection