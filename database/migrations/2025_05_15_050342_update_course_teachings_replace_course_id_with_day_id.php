<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_teachings', function (Blueprint $table) {
               $table->foreignId('course_day_id')
                  ->constrained('course_days')
                  ->onDelete('cascade')
                  ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_teachings', function (Blueprint $table) {
            $table->dropForeign(['course_day_id']);
            $table->dropColumn('course_day_id');
        });
    }
};
