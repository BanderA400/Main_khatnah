<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khatmas', function (Blueprint $table): void {
            $table->index(['user_id', 'status'], 'khatmas_user_status_index');
            $table->index(['status', 'expected_end_date'], 'khatmas_status_expected_end_date_index');
            $table->index(['user_id', 'start_date'], 'khatmas_user_start_date_index');
            $table->index(['user_id', 'updated_at'], 'khatmas_user_updated_at_index');
            $table->index('created_at', 'khatmas_created_at_index');
        });

        Schema::table('daily_records', function (Blueprint $table): void {
            $table->index(['is_completed', 'date'], 'daily_records_completed_date_index');
            $table->index(['user_id', 'is_completed', 'date'], 'daily_records_user_completed_date_index');
            $table->index(['is_completed', 'completed_at'], 'daily_records_completed_completed_at_index');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->index('created_at', 'users_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('khatmas', function (Blueprint $table): void {
            $table->dropIndex('khatmas_user_status_index');
            $table->dropIndex('khatmas_status_expected_end_date_index');
            $table->dropIndex('khatmas_user_start_date_index');
            $table->dropIndex('khatmas_user_updated_at_index');
            $table->dropIndex('khatmas_created_at_index');
        });

        Schema::table('daily_records', function (Blueprint $table): void {
            $table->dropIndex('daily_records_completed_date_index');
            $table->dropIndex('daily_records_user_completed_date_index');
            $table->dropIndex('daily_records_completed_completed_at_index');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_created_at_index');
        });
    }
};
