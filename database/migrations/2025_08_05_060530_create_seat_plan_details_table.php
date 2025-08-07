<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_plan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seat_plan_id');
            $table->unsignedBigInteger('building_id');
            $table->integer('room');
            $table->string('bench');
            $table->integer('seat');
            $table->unsignedBigInteger('student_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('seat_plan_id')->references('id')->on('seat_plans')->onDelete('cascade');
            $table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seat_plan_details');
    }
}
