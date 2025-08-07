<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalRoomTotalBenchTotalSeatsToBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->integer('total_room')->default(0)->after('rooms');
            // $table->integer('total_bench')->default(0)->after('total_room');
            // $table->integer('total_seats')->default(0)->after('total_bench');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn([
                'total_room', 
                // 'total_bench', 
                // 'total_seats'
            ]);
        });
    }
}
