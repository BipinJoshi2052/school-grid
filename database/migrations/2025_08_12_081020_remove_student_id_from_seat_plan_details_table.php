<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStudentIdFromSeatPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Drop the foreign key constraint if it exists
            $table->dropForeign(['student_id']);  // Drop foreign key constraint
            
            // Drop the 'student_id' column
            $table->dropColumn('student_id');
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
            // Re-add the 'student_id' column as unsigned integer
            $table->unsignedBigInteger('student_id');
            
            // Re-add the foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }
}
