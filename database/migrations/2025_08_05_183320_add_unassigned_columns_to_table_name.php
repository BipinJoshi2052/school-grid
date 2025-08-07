<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnassignedColumnsToTableName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seat_plans', function (Blueprint $table) {
            $table->text('unassigned_students')->nullable()->after('added_by');
            $table->text('unassigned_staffs')->nullable()->after('unassigned_students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seat_plans', function (Blueprint $table) {
            $table->dropColumn('unassigned_students');
            $table->dropColumn('unassigned_staffs');
        });
    }
}
