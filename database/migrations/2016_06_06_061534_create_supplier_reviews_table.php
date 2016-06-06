<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_reviews', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('supplier_id')->unsigned();
            $table->string('product')->nullable();
            $table->date('date_visited')->nullable();
            $table->string('rating')->nullable();
            $table->string('sales_department')->nullable();
            $table->string('sd_notes')->nullable();
            $table->integer('staff_number')->nullable();
            $table->string('sample_room')->nullable();
            $table->string('sr_notes')->nullable();
            $table->string('building_type')->nullable();
            $table->integer('production_staff')->nullable();
            $table->string('well_lit')->nullable();
            $table->string('lit_notes')->nullable();
            $table->string('safety_markings')->nullable();
            $table->string('safety_notes')->nullable();
            $table->string('safe_working_conditions')->nullable();
            $table->string('sf_notes')->nullable();
            $table->string('conditions')->nullable();
            $table->string('conditions_notes')->nullable();
            $table->string('export_carton_packing')->nullable();
            $table->string('ecp_notes')->nullable();
            $table->string('less15')->nullable();
            $table->string('packing')->nullable();
            $table->string('packing_notes')->nullable();
            $table->integer('pp_staff_number')->nullable();
            $table->string('age_machine')->nullable();
            $table->string('proofing')->nullable();
            $table->string('proof_notes')->nullable();
            $table->string('samples')->nullable();
            $table->string('samples_notes')->nullable();

            // array
            $table->text('programs')->nullable();

            $table->string('ctp')->nullable();
            $table->string('ctp_age')->nullable();
            $table->string('ctp_model')->nullable();
            $table->string('film')->nullable();
            $table->string('film_age')->nullable();
            $table->string('film_model')->nullable();

            //arrays
            $table->text('brand')->nullable();
            $table->text('colors')->nullable();
            $table->text('uv')->nullable();
            $table->text('coater')->nullable();

            $table->string('folding')->nullable();
            $table->string('folding_age')->nullable();
            $table->string('folding_model')->nullable();
            $table->string('folding_notes')->nullable();

            // arrays
            $table->text('binding')->nullable();
            $table->text('binding_age')->nullable();
            $table->text('binding_model')->nullable();
            $table->text('binding_notes')->nullable();
            $table->text('guilotine')->nullable();
            $table->text('guilotine_age')->nullable();
            $table->text('guilotine_model')->nullable();
            $table->text('guilotine_notes')->nullable();
            $table->text('laminating')->nullable();
            $table->text('laminating_age')->nullable();
            $table->text('laminating_model')->nullable();
            $table->text('laminating_notes')->nullable();
            $table->text('cutting')->nullable();
            $table->text('cutting_age')->nullable();
            $table->text('cutting_model')->nullable();
            $table->text('cutting_notes')->nullable();

            $table->string('in_house')->nullable();

            // arrays
            $table->text('other')->nullable();
            $table->text('other_age')->nullable();
            $table->text('other_model')->nullable();
            $table->text('other_notes')->nullable();

            // photos
            $table->text('photo_office')->nullable();
            $table->text('photo_warehouse')->nullable();
            $table->text('photo_pre_press')->nullable();
            $table->text('photo_finishing')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('supplier_reviews');
    }
}
