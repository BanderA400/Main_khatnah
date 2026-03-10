<?php

namespace Tests\Feature\Filament;

use App\Enums\KhatmaDirection;
use App\Enums\KhatmaScope;
use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Enums\PlanningMethod;
use App\Filament\Resources\KhatmaResource\Pages\EditKhatma;
use App\Models\Khatma;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class KhatmaEditSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_ignores_tampered_hidden_fields(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $khatma = Khatma::create([
            'user_id' => $owner->id,
            'name' => 'ختمة آمنة',
            'type' => KhatmaType::Tilawa,
            'scope' => KhatmaScope::Full,
            'direction' => KhatmaDirection::Forward,
            'start_page' => 1,
            'end_page' => 604,
            'total_pages' => 604,
            'planning_method' => PlanningMethod::ByWird,
            'auto_compensate_missed_days' => false,
            'daily_pages' => 10,
            'start_date' => '2026-03-01',
            'expected_end_date' => '2026-04-30',
            'status' => KhatmaStatus::Active,
            'current_page' => 1,
            'completed_pages' => 0,
        ]);

        $this->actingAs($owner);

        Livewire::test(EditKhatma::class, ['record' => $khatma->getRouteKey()])
            ->set('data.user_id', $otherUser->id)
            ->set('data.completed_pages', 250)
            ->set('data.current_page', 250)
            ->call('save')
            ->assertHasNoFormErrors();

        $fresh = $khatma->fresh();

        $this->assertNotNull($fresh);
        $this->assertSame($owner->id, $fresh->user_id);
        $this->assertSame(0, $fresh->completed_pages);
        $this->assertSame(1, $fresh->current_page);
    }
}

