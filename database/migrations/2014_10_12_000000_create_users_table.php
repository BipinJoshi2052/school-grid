<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('avatar')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            // $table->foreignId('user_type_id')->default(2)->constrained('user_types');
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('otp')->nullable();
            $table->integer('suspend')->default(0);
            $table->dateTime('last_access_date')->nullable();
            $table->text('last_access_from')->nullable();
            $table->timestamp('created_at')->useCurrent();  // Set default value to current timestamp
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();  // Set default and auto-update
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
