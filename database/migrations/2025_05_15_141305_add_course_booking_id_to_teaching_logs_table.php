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
        Schema::table('teaching_logs', function (Blueprint $table) {
            $table->foreignId('course_booking_id')->nullable()->constrained('course_bookings')->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teaching_logs', function (Blueprint $table) {
            $table->dropForeign(['course_booking_id']);
            $table->dropColumn('course_booking_id');
        });
    }
};
