<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentIdForeignKeyInSeatPlanDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop the old foreign key relation (if it exists)
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Drop the old foreign key constraint (assuming it's called 'seat_plan_details_student_id_foreign')
            $table->dropForeign(['student_id']);
        });

        // Create a new foreign key relation where student_id references the id in the students table
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Add a new foreign key constraint
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // If we roll back, we need to remove the new foreign key and restore the old one if needed
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['student_id']);
        });

        // Optionally, restore the old foreign key constraint if necessary (you may need to adjust the previous setup)
        // Schema::table('seat_plan_details', function (Blueprint $table) {
        //     $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        // });
    }
}
