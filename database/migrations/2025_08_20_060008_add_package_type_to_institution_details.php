<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackageTypeToInstitutionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institution_details', function (Blueprint $table) {
            $table->string('package_type')->nullable()->after('expiration_date'); // Add package_type column after expiration_date
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institution_details', function (Blueprint $table) {
            $table->dropColumn('package_type'); // Remove the column if rolling back
        });
    }
}
