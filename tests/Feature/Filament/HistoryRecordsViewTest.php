<?php

namespace Tests\Feature\Filament;

use App\Enums\KhatmaDirection;
use App\Enums\KhatmaScope;
use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Enums\PlanningMethod;
use App\Filament\Pages\History;
use App\Models\DailyRecord;
use App\Models\Khatma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HistoryRecordsViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_defaults_to_last_30_days_and_can_switch_to_100_records(): void
    {
        $user = User::factory()->create();

        $khatma = Khatma::create([
            'user_id' => $user->id,
            'name' => 'ختمة السجل',
            'type' => KhatmaType::Tilawa,
            'scope' => KhatmaScope::Full,
            'direction' => KhatmaDirection::Forward,
            'start_page' => 1,
            'end_page' => 60,
            'total_pages' => 60,
            'planning_method' => PlanningMethod::ByWird,
            'auto_compensate_missed_days' => false,
            'daily_pages' => 6,
            'start_date' => Carbon::today()->subDays(60),
            'expected_end_date' => Carbon::today()->addDays(30),
            'status' => KhatmaStatus::Active,
            'current_page' => 1,
            'completed_pages' => 0,
        ]);

        DailyRecord::create([
            'khatma_id' => $khatma->id,
            'user_id' => $user->id,
            'date' => Carbon::today()->subDays(40),
            'from_page' => 1,
            'to_page' => 2,
            'pages_count' => 2,
            'is_completed' => true,
            'completed_at' => now()->subDays(40),
        ]);

        DailyRecord::create([
            'khatma_id' => $khatma->id,
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'from_page' => 3,
            'to_page' => 5,
            'pages_count' => 3,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        $todayKey = Carbon::today()->format('Y-m-d');
        $oldKey = Carbon::today()->subDays(40)->format('Y-m-d');

        $this->actingAs($user);

        $component = Livewire::test(History::class);
        $recordsIn30Days = $component->instance()->getRecords();

        $this->assertArrayHasKey($todayKey, $recordsIn30Days);
        $this->assertArrayNotHasKey($oldKey, $recordsIn30Days);

        $component->call('setRecordsView', '100_records');
        $recordsIn100 = $component->instance()->getRecords();

        $this->assertArrayHasKey($todayKey, $recordsIn100);
        $this->assertArrayHasKey($oldKey, $recordsIn100);
    }
}
