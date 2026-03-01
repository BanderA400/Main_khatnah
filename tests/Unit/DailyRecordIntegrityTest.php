<?php

namespace Tests\Unit;

use App\Enums\KhatmaDirection;
use App\Enums\KhatmaScope;
use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Enums\PlanningMethod;
use App\Models\DailyRecord;
use App\Models\Khatma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class DailyRecordIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_record_rejects_mismatched_user_and_khatma_owner(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $khatma = Khatma::create([
            'user_id' => $owner->id,
            'name' => 'ختمة التحقق',
            'type' => KhatmaType::Tilawa,
            'scope' => KhatmaScope::Full,
            'direction' => KhatmaDirection::Forward,
            'start_page' => 1,
            'end_page' => 50,
            'total_pages' => 50,
            'planning_method' => PlanningMethod::ByWird,
            'auto_compensate_missed_days' => false,
            'daily_pages' => 5,
            'start_date' => Carbon::today(),
            'expected_end_date' => Carbon::today()->addDays(9),
            'status' => KhatmaStatus::Active,
            'current_page' => 1,
            'completed_pages' => 0,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('المستخدم لا يطابق صاحب الختمة.');

        DailyRecord::create([
            'khatma_id' => $khatma->id,
            'user_id' => $otherUser->id,
            'date' => Carbon::today(),
            'from_page' => 1,
            'to_page' => 5,
            'pages_count' => 5,
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }
}
