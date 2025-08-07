<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvigilatorPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invigilator_plan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seat_plan_id');
            $table->unsignedBigInteger('building_id');
            $table->integer('room');
            $table->unsignedBigInteger('staff_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('seat_plan_id')->references('id')->on('seat_plans')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invigilator_plan_details');
    }
}
