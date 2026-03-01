<?php

namespace Tests\Feature\Filament;

use App\Enums\KhatmaDirection;
use App\Enums\KhatmaScope;
use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Enums\PlanningMethod;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\KhatmaResource\Pages\CreateKhatma;
use App\Models\DailyRecord;
use App\Models\Khatma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KhatmaEndToEndFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_user_can_finish_full_flow_create_partial_full_pause_resume_and_complete(): void
    {
        Carbon::setTestNow('2026-03-01 09:00:00');

        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(CreateKhatma::class)
            ->fillForm([
                'name' => 'ختمة شاملة',
                'type' => KhatmaType::Tilawa,
                'scope' => KhatmaScope::Custom,
                'direction' => KhatmaDirection::Forward,
                'start_page' => 1,
                'end_page' => 6,
                'start_date' => '2026-03-01',
                'planning_method' => PlanningMethod::ByWird,
                'daily_pages' => 3,
                'auto_compensate_missed_days' => false,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $khatma = Khatma::query()
            ->where('user_id', $user->id)
            ->where('name', 'ختمة شاملة')
            ->firstOrFail();

        $this->assertSame(6, $khatma->total_pages);
        $this->assertSame(1, $khatma->current_page);
        $this->assertSame(0, $khatma->completed_pages);
        $this->assertSame(KhatmaStatus::Active, $khatma->status);

        Livewire::test(Dashboard::class)
            ->set("partialPages.{$khatma->id}", 1)
            ->call('completePartialWird', $khatma->id);

        $khatma->refresh();
        $this->assertSame(1, $khatma->completed_pages);
        $this->assertSame(2, $khatma->current_page);

        Livewire::test(Dashboard::class)
            ->call('completeWird', $khatma->id);

        $khatma->refresh();
        $this->assertSame(3, $khatma->completed_pages);
        $this->assertSame(4, $khatma->current_page);

        $this->assertSame(
            2,
            DailyRecord::query()->where('khatma_id', $khatma->id)->whereDate('date', '2026-03-01')->count(),
        );
        $this->assertSame(
            3,
            (int) DailyRecord::query()->where('khatma_id', $khatma->id)->whereDate('date', '2026-03-01')->sum('pages_count'),
        );

        Livewire::test(Dashboard::class)
            ->call('pauseKhatma', $khatma->id);
        $this->assertSame(KhatmaStatus::Paused, $khatma->fresh()->status);

        Livewire::test(Dashboard::class)
            ->call('resumeKhatma', $khatma->id);
        $this->assertSame(KhatmaStatus::Active, $khatma->fresh()->status);

        Carbon::setTestNow('2026-03-02 09:00:00');

        Livewire::test(Dashboard::class)
            ->call('completeWird', $khatma->id);

        $khatma->refresh();
        $this->assertSame(6, $khatma->completed_pages);
        $this->assertSame(KhatmaStatus::Completed, $khatma->status);
        $this->assertSame(6, $khatma->current_page);
    }
}
