<?php

namespace App\Filament\Pages;

use App\Enums\KhatmaStatus;
use App\Enums\KhatmaType;
use App\Models\DailyRecord;
use App\Models\Khatma;
use App\Models\Surah;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class History extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'سجل الإنجاز';

    protected static ?string $title = 'سجل الإنجاز';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.history';

    protected static ?string $slug = 'history';

    public string $recordsView = '30_days';

    /**
     * إحصائيات عامة
     */
    public function getStatsData(): array
    {
        $userId = auth()->id();

        $totalRecords = DailyRecord::where('user_id', $userId)
            ->where('is_completed', true)
            ->count();

        $totalPages = DailyRecord::where('user_id', $userId)
            ->where('is_completed', true)
            ->sum('pages_count');

        $completedKhatmas = Khatma::where('user_id', $userId)
            ->where('status', KhatmaStatus::Completed)
            ->count();

        $activeKhatmas = Khatma::where('user_id', $userId)
            ->where('status', KhatmaStatus::Active)
            ->count();

        // أكثر يوم إنجازاً
        $bestDay = DailyRecord::where('user_id', $userId)
            ->where('is_completed', true)
            ->selectRaw('date, SUM(pages_count) as total')
            ->groupBy('date')
            ->orderByDesc('total')
            ->first();

        // متوسط الصفحات يومياً
        $firstRecord = DailyRecord::where('user_id', $userId)->orderBy('date')->first();
        $avgPages = 0;
        if ($firstRecord) {
            $totalDays = Carbon::parse($firstRecord->date)->diffInDays(Carbon::today()) + 1;
            if ($totalDays > 0) {
                $avgPages = round($totalPages / $totalDays, 1);
            }
        }

        return [
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'completed_khatmas' => $completedKhatmas,
            'active_khatmas' => $activeKhatmas,
            'best_day_pages' => $bestDay?->total ?? 0,
            'best_day_date' => $bestDay?->date ? Carbon::parse($bestDay->date)->translatedFormat('j F Y') : '—',
            'avg_pages' => $avgPages,
        ];
    }

    /**
     * تبديل وضع عرض السجل
     */
    public function setRecordsView(string $view): void
    {
        if (!in_array($view, ['30_days', '100_records'], true)) {
            return;
        }

        $this->recordsView = $view;
    }

    /**
     * سجل الإنجاز اليومي
     */
    public function getRecords(): array
    {
        $userId = auth()->id();

        $query = DailyRecord::where('user_id', $userId)
            ->where('is_completed', true)
            ->with('khatma')
            ->orderByDesc('date')
            ->orderByDesc('completed_at');

        if ($this->recordsView === '30_days') {
            $query->whereDate('date', '>=', Carbon::today()->subDays(29));
        } else {
            $query->limit(100);
        }

        $records = $query->get();
        $surahs = Surah::query()
            ->orderBy('start_page')
            ->get(['start_page', 'end_page', 'name_arabic']);

        $grouped = [];

        foreach ($records as $record) {
            $dateKey = $record->date->format('Y-m-d');
            $dateLabel = $record->date->translatedFormat('l j F Y');

            if ($record->date->isToday()) {
                $dateLabel = 'اليوم — ' . $dateLabel;
            } elseif ($record->date->isYesterday()) {
                $dateLabel = 'أمس — ' . $dateLabel;
            }

            $grouped[$dateKey]['label'] = $dateLabel;
            $grouped[$dateKey]['records'][] = [
                'khatma_name' => $record->khatma?->name ?? '—',
                'khatma_type' => $record->khatma?->type,
                'from_page' => $record->from_page,
                'to_page' => $record->to_page,
                'pages_count' => $record->pages_count,
                'surah_name' => $this->resolveSurahNameFromPage($surahs, (int) $record->from_page),
                'completed_at' => $record->completed_at?->format('H:i') ?? '—',
            ];
        }

        return $grouped;
    }

    private function resolveSurahNameFromPage(Collection $surahs, int $page): string
    {
        foreach ($surahs as $surah) {
            if ($page >= (int) $surah->start_page && $page <= (int) $surah->end_page) {
                return $surah->name_arabic;
            }
        }

        return '—';
    }
}
