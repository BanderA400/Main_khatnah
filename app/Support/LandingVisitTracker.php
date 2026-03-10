<?php

namespace App\Support;

use App\Models\LandingVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class LandingVisitTracker
{
    public static function track(Request $request): array
    {
        $visitorId = $request->cookie('khatma_vid');

        if (! is_string($visitorId) || $visitorId === '') {
            $visitorId = (string) Str::uuid();
            Cookie::queue(cookie(
                'khatma_vid',
                $visitorId,
                60 * 24 * 365,
                null,
                null,
                (bool) config('session.secure', false),
                true,
                false,
                (string) config('session.same_site', 'lax'),
            ));
        }

        $today = now()->startOfDay();
        $todayDate = $today->toDateString();
        $fingerprint = hash('sha256', $visitorId);
        $timestamp = now();

        $visit = LandingVisit::query()->incrementOrCreate(
            attributes: [
                'visited_on' => $today,
                'fingerprint' => $fingerprint,
            ],
            column: 'visits_count',
            default: 1,
            step: 1,
            extra: [
                'is_unique' => true,
                'updated_at' => $timestamp,
            ],
        );

        $totalKey = 'landing_visits:total';
        $todayUniqueKey = "landing_visits:today_unique:{$todayDate}";

        $totalVisits = self::incrementOrRememberCachedCounter(
            key: $totalKey,
            shouldIncrement: true,
            ttlSeconds: 86400,
            resolver: fn (): int => (int) LandingVisit::query()->sum('visits_count'),
        );

        $todayUniqueVisitors = self::incrementOrRememberCachedCounter(
            key: $todayUniqueKey,
            shouldIncrement: (bool) $visit->wasRecentlyCreated,
            ttlSeconds: 86400,
            resolver: fn (): int => (int) LandingVisit::query()
                ->where('visited_on', $todayDate)
                ->count(),
        );

        return [
            'totalVisits' => $totalVisits,
            'todayUniqueVisitors' => $todayUniqueVisitors,
        ];
    }

    private static function incrementOrRememberCachedCounter(
        string $key,
        bool $shouldIncrement,
        int $ttlSeconds,
        \Closure $resolver,
    ): int {
        $expiresAt = now()->addSeconds($ttlSeconds);

        if (Cache::has($key)) {
            if ($shouldIncrement) {
                return (int) Cache::increment($key);
            }

            return (int) Cache::get($key, 0);
        }

        $value = (int) $resolver();
        Cache::put($key, $value, $expiresAt);

        return $value;
    }
}
