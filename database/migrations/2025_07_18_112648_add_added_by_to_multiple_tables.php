<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAddedByToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adding 'added_by' to batches table
        Schema::table('batches', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE batches MODIFY added_by BIGINT UNSIGNED AFTER faculty_id');

        // Adding 'added_by' to buildings table
        Schema::table('buildings', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE buildings MODIFY added_by BIGINT UNSIGNED AFTER user_id');

        // Adding 'added_by' to classes table
        Schema::table('classes', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE classes MODIFY added_by BIGINT UNSIGNED AFTER batch_id');

        // Adding 'added_by' to departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE departments MODIFY added_by BIGINT UNSIGNED AFTER title');

        // Adding 'added_by' to faculties table
        Schema::table('faculties', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE faculties MODIFY added_by BIGINT UNSIGNED AFTER title');

        // Adding 'added_by' to positions table
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE positions MODIFY added_by BIGINT UNSIGNED AFTER title');

        // Adding 'added_by' to sections table
        Schema::table('sections', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE sections MODIFY added_by BIGINT UNSIGNED AFTER class_id');

        // Adding 'added_by' to staffs table
        Schema::table('staffs', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE staffs MODIFY added_by BIGINT UNSIGNED AFTER address');

        // Adding 'added_by' to students table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE students MODIFY added_by BIGINT UNSIGNED AFTER section_id');

        // Adding 'added_by' to suggestions table
        Schema::table('suggestions', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE suggestions MODIFY added_by BIGINT UNSIGNED AFTER user_id');

        // Adding 'added_by' to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
        });
        // Reorder columns with raw SQL after creating the 'added_by' column
        DB::statement('ALTER TABLE users MODIFY added_by BIGINT UNSIGNED AFTER parent_id');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Dropping 'added_by' column from batches table
        Schema::table('batches', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from buildings table
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from classes table
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from faculties table
        Schema::table('faculties', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from positions table
        Schema::table('positions', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from sections table
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from staffs table
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from suggestions table
        Schema::table('suggestions', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });

        // Dropping 'added_by' column from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['added_by']);
            $table->dropColumn('added_by');
        });
    }
}
