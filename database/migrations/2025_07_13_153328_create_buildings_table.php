<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the building
            $table->json('rooms'); // Store room details in JSON format
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relation to users table
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps(); // created_at and updated_at timestamps
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
