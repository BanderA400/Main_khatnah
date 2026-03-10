<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_visits', function (Blueprint $table): void {
            $table->unsignedInteger('visits_count')
                ->default(1)
                ->after('is_unique');
        });

        $duplicates = DB::table('landing_visits')
            ->select(
                'visited_on',
                'fingerprint',
                DB::raw('COUNT(*) as total'),
            )
            ->groupBy('visited_on', 'fingerprint')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $rows = DB::table('landing_visits')
                ->where('visited_on', $duplicate->visited_on)
                ->where('fingerprint', $duplicate->fingerprint)
                ->orderBy('id')
                ->get(['id']);

            if ($rows->isEmpty()) {
                continue;
            }

            $keeperId = (int) $rows->first()->id;
            $deleteIds = $rows
                ->skip(1)
                ->pluck('id')
                ->all();

            DB::table('landing_visits')
                ->where('id', $keeperId)
                ->update([
                    'is_unique' => true,
                    'visits_count' => (int) $duplicate->total,
                    'updated_at' => now(),
                ]);

            if ($deleteIds !== []) {
                DB::table('landing_visits')
                    ->whereIn('id', $deleteIds)
                    ->delete();
            }
        }

        DB::table('landing_visits')
            ->where(function ($query): void {
                $query->whereNull('visits_count')
                    ->orWhere('visits_count', '<', 1);
            })
            ->update([
                'visits_count' => 1,
                'updated_at' => now(),
            ]);

        DB::table('landing_visits')
            ->where('is_unique', false)
            ->update([
                'is_unique' => true,
                'updated_at' => now(),
            ]);

        Schema::table('landing_visits', function (Blueprint $table): void {
            $table->unique(
                ['visited_on', 'fingerprint'],
                'landing_visits_visited_on_fingerprint_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('landing_visits', function (Blueprint $table): void {
            $table->dropUnique('landing_visits_visited_on_fingerprint_unique');
            $table->dropColumn('visits_count');
        });
    }
};

