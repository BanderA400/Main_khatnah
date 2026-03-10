<?php

namespace Tests\Feature;

use App\Models\LandingVisit;
use App\Support\AppSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingVisitCounterTest extends TestCase
{
    use RefreshDatabase;

    public function test_repeated_requests_from_same_visitor_increment_total_without_increasing_unique_count(): void
    {
        $firstResponse = $this->get('/');
        $firstResponse->assertOk();

        $visitorCookie = $firstResponse->getCookie('khatma_vid');

        $this->assertNotNull($visitorCookie);

        $this->withCookie('khatma_vid', (string) $visitorCookie?->getValue())
            ->get('/')
            ->assertOk();

        $this->assertDatabaseCount('landing_visits', 1);
        $visit = LandingVisit::query()->first();

        $this->assertNotNull($visit);
        $this->assertSame(2, (int) $visit?->visits_count);
        $this->assertTrue((bool) $visit?->is_unique);
        $this->assertTrue($visit?->visited_on?->isToday() ?? false);

        $this->assertSame(2, (int) LandingVisit::query()->sum('visits_count'));
        $this->assertSame(
            1,
            (int) LandingVisit::query()
                ->whereDate('visited_on', now()->toDateString())
                ->count(),
        );
    }

    public function test_counter_can_be_disabled_from_settings(): void
    {
        AppSettings::setMany([
            AppSettings::KEY_LANDING_SHOW_VISIT_COUNTER => false,
        ]);

        $this->get('/')->assertOk();

        $this->assertDatabaseCount('landing_visits', 0);
    }
}
