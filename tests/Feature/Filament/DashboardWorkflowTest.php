<?php

namespace Tests\Feature\Filament;

use App\Enums\KhatmaDirection;
use App\Enums\KhatmaScope;
use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Enums\PlanningMethod;
use App\Filament\Pages\Dashboard;
use App\Models\DailyRecord;
use App\Models\Khatma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_progress_can_be_recorded_multiple_times_in_same_day(): void
    {
        $user = User::factory()->create();

        $khatma = Khatma::create([
            'user_id' => $user->id,
            'name' => 'ختمة يومية',
            'type' => KhatmaType::Tilawa,
            'scope' => KhatmaScope::Full,
            'direction' => KhatmaDirection::Forward,
            'start_page' => 1,
            'end_page' => 20,
            'total_pages' => 20,
            'planning_method' => PlanningMethod::ByWird,
            'auto_compensate_missed_days' => false,
            'daily_pages' => 5,
            'start_date' => Carbon::today(),
            'expected_end_date' => Carbon::today()->addDays(3),
            'status' => KhatmaStatus::Active,
            'current_page' => 1,
            'completed_pages' => 0,
        ]);

        $this->actingAs($user);

        Livewire::test(Dashboard::class)
            ->set("partialPages.{$khatma->id}", 2)
            ->call('completePartialWird', $khatma->id)
            ->set("partialPages.{$khatma->id}", 3)
            ->call('completePartialWird', $khatma->id);

        $this->assertSame(
            2,
            DailyRecord::where('khatma_id', $khatma->id)->whereDate('date', Carbon::today())->count(),
        );
        $this->assertSame(
            5,
            (int) DailyRecord::where('khatma_id', $khatma->id)->whereDate('date', Carbon::today())->sum('pages_count'),
        );

        $khatma->refresh();

        $this->assertSame(5, $khatma->completed_pages);
        $this->assertSame(6, $khatma->current_page);
    }

    public function test_commitment_rate_is_calculated_from_first_khatma_start_date(): void
    {
        $user = User::factory()->create();
        $startDate = Carbon::today()->subDays(9);

        $khatma = Khatma::create([
            'user_id' => $user->id,
            'name' => 'ختمة التزام',
            'type' => KhatmaType::Review,
            'scope' => KhatmaScope::Full,
            'direction' => KhatmaDirection::Forward,
            'start_page' => 1,
            'end_page' => 30,
            'total_pages' => 30,
            'planning_method' => PlanningMethod::ByWird,
            'auto_compensate_missed_days' => false,
            'daily_pages' => 3,
            'start_date' => $startDate,
            'expected_end_date' => Carbon::today(),
            'status' => KhatmaStatus::Active,
            'current_page' => 1,
            'completed_pages' => 0,
        ]);

        DailyRecord::create([
            'khatma_id' => $khatma->id,
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'from_page' => 1,
            'to_page' => 3,
            'pages_count' => 3,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        DailyRecord::create([
            'khatma_id' => $khatma->id,
            'user_id' => $user->id,
            'date' => Carbon::yesterday(),
            'from_page' => 4,
            'to_page' => 6,
            'pages_count' => 3,
            'is_completed' => true,
            'completed_at' => now()->subDay(),
        ]);

        $this->actingAs($user);

        $widgets = Livewire::test(Dashboard::class)->instance()->getWidgetsData();

        $this->assertSame(20.0, $widgets['commitment_rate']);
    }

    public function test_user_can_pause_and_resume_khatma_from_dashboard(): void
    {
        $user = User::factory()->create();

        $khatma = Khatma::create([
            'user_id' => $user->id,
            'name' => 'ختمة قابلة للإيقاف',
            'type' => KhatmaType::Tilawa,
            'scope' => KhatmaScope::Full,
            'direction' => KhatmaDirection::Forward,
            'start_page' => 1,
            'end_page' => 40,
            'total_pages' => 40,
            'planning_method' => PlanningMethod::ByWird,
            'auto_compensate_missed_days' => false,
            'daily_pages' => 4,
            'start_date' => Carbon::today(),
            'expected_end_date' => Carbon::today()->addDays(9),
            'status' => KhatmaStatus::Active,
            'current_page' => 1,
            'completed_pages' => 0,
        ]);

        $this->actingAs($user);

        Livewire::test(Dashboard::class)
            ->call('pauseKhatma', $khatma->id);

        $this->assertSame(KhatmaStatus::Paused, $khatma->fresh()->status);

        Livewire::test(Dashboard::class)
            ->call('resumeKhatma', $khatma->id);

        $this->assertSame(KhatmaStatus::Active, $khatma->fresh()->status);
    }
}
