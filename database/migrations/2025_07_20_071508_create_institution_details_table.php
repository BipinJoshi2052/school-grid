<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // foreign key relation to users table
            $table->string('client_id', 100);
            $table->string('institution_name');
            $table->foreignId('registration_id')->nullable()->constrained('registrations'); // foreign key relation to registrations table
            $table->date('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_details');
    }
}
