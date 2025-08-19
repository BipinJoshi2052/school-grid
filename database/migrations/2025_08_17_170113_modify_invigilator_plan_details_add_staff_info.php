<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyInvigilatorPlanDetailsAddStaffInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invigilator_plan_details', function (Blueprint $table) {
            // Add the staff_id column and its foreign key
            $table->unsignedBigInteger('staff_id')->nullable()->after('room');
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('set null');
            
            // Add the staff_name and staff_department columns
            $table->string('staff_name')->nullable()->after('staff_id');
            $table->string('staff_department')->nullable()->after('staff_name');
            $table->string('staff_position')->nullable()->after('staff_department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invigilator_plan_details', function (Blueprint $table) {
            // Drop the new columns and foreign key
            // $table->dropForeign(['staff_id']);
            $table->dropColumn(['staff_id', 'staff_name', 'staff_department', 'staff_position']);
        });
    }
}
