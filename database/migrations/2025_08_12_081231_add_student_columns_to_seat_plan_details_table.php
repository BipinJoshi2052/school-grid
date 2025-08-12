<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentColumnsToSeatPlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seat_plan_details', function (Blueprint $table) {
            // Add the 'student_id' column as nullable
            $table->unsignedBigInteger('student_id')->nullable()->after('seat');  // Adjust 'after' as needed

            // Add other student-related columns after 'student_id'
            $table->string('student_name')->nullable()->after('student_id');
            $table->string('student_class')->nullable()->after('student_name');
            $table->string('student_section')->nullable()->after('student_class');
            $table->string('student_roll_no')->nullable()->after('student_section');
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
            // Drop the newly added columns
            $table->dropColumn('student_id');
            $table->dropColumn('student_name');
            $table->dropColumn('student_class');
            $table->dropColumn('student_section');
            $table->dropColumn('student_roll_no');
        });
    }
}
