<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyInvigilatorPlanDetailsRemoveStaffId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invigilator_plan_details', function (Blueprint $table) {
            // Drop the foreign key constraint (you may need to adjust the name if it's different)
            $table->dropForeign(['staff_id']);
            
            // Drop the staff_id column
            $table->dropColumn('staff_id');
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
            // Add staff_id column back with nullable property
            $table->unsignedBigInteger('staff_id')->nullable();

            // Add the foreign key constraint back (assuming 'id' in 'users' table)
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
        });
    }
}
