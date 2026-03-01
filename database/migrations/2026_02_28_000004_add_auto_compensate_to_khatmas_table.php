<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khatmas', function (Blueprint $table) {
            $table->boolean('auto_compensate_missed_days')
                ->default(false)
                ->after('planning_method');
        });
    }

    public function down(): void
    {
        Schema::table('khatmas', function (Blueprint $table) {
            $table->dropColumn('auto_compensate_missed_days');
        });
    }
};
