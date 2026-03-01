<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_records', function (Blueprint $table) {
            // MySQL قد يعتمد هذا الفهرس لمتطلبات المفتاح الأجنبي على khatma_id.
            $table->index('khatma_id', 'daily_records_khatma_id_support_index');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropUnique('daily_records_khatma_id_date_unique');
        });

        Schema::table('daily_records', function (Blueprint $table) {
            $table->index(['khatma_id', 'date']);
            $table->index(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        $duplicates = DB::table('daily_records')
            ->select('khatma_id', 'date')
            ->groupBy('khatma_id', 'date')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $records = DB::table('daily_records')
                ->where('khatma_id', $duplicate->khatma_id)
                ->where('date', $duplicate->date)
                ->orderBy('id')
                ->get();

            if ($records->count() < 2) {
                continue;
            }

            $keeper = $records->first();
            $last = $records->last();
            $sumPages = (int) $records->sum('pages_count');
            $lastCompletedAt = $records->max('completed_at');
            $deleteIds = $records->skip(1)->pluck('id')->all();

            DB::table('daily_records')
                ->where('id', $keeper->id)
                ->update([
                    'to_page' => $last->to_page,
                    'pages_count' => $sumPages,
                    'completed_at' => $lastCompletedAt,
                    'updated_at' => now(),
                ]);

            DB::table('daily_records')
                ->whereIn('id', $deleteIds)
                ->delete();
        }

        Schema::table('daily_records', function (Blueprint $table) {
            $table->dropIndex('daily_records_khatma_id_date_index');
            $table->dropIndex('daily_records_user_id_date_index');
            $table->dropIndex('daily_records_khatma_id_support_index');
            $table->unique(['khatma_id', 'date']);
        });
    }
};
