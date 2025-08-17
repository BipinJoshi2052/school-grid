<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToSeatPlanDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Add the foreign key constraint
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['student_id']);
        });
    }
}
