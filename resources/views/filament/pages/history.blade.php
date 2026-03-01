<x-filament-panels::page>

    @php
        $stats = $this->getStatsData();
        $grouped = $this->getRecords();
    @endphp

    {{-- === إحصائيات عامة === --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div style="background: var(--khatma-hifz-bg); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">إجمالي الصفحات</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-hifz-text);">
                {{ $stats['total_pages'] }}
            </div>
        </div>

        <div style="background: var(--khatma-review-bg); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">ختمات مكتملة</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-review-text);">
                {{ $stats['completed_khatmas'] }}
            </div>
        </div>

        <div style="background: var(--khatma-tilawa-bg); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">أفضل يوم</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-tilawa-text);">
                {{ $stats['best_day_pages'] }}
            </div>
            <div style="font-size: 0.75rem; color: var(--khatma-muted-soft);">{{ $stats['best_day_date'] }}</div>
        </div>

        <div style="background: var(--khatma-surface-soft); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">متوسط يومي</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-text);">
                {{ $stats['avg_pages'] }}
            </div>
            <div style="font-size: 0.75rem; color: var(--khatma-muted-soft);">صفحة / يوم</div>
        </div>
    </div>

    {{-- === السجل اليومي === --}}
    <div style="margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.7rem; margin-bottom: 1rem;">
            <h2 style="font-family: 'Amiri', serif; font-size: 1.5rem; font-weight: 700; color: var(--khatma-title); margin: 0;">
                📋 السجل اليومي
            </h2>
            <div style="display: flex; gap: 0.4rem;">
                <button
                    wire:click="setRecordsView('30_days')"
                    style="padding: 0.4rem 0.8rem; border-radius: 999px; border: 1px solid var(--khatma-border); cursor: pointer; font-size: 0.8rem; font-weight: 700; {{ $this->recordsView === '30_days' ? 'background: var(--khatma-title); color: #fff;' : 'background: var(--khatma-surface-soft); color: var(--khatma-muted);' }}"
                >
                    آخر 30 يوم
                </button>
                <button
                    wire:click="setRecordsView('100_records')"
                    style="padding: 0.4rem 0.8rem; border-radius: 999px; border: 1px solid var(--khatma-border); cursor: pointer; font-size: 0.8rem; font-weight: 700; {{ $this->recordsView === '100_records' ? 'background: var(--khatma-title); color: #fff;' : 'background: var(--khatma-surface-soft); color: var(--khatma-muted);' }}"
                >
                    آخر 100 سجل
                </button>
            </div>
        </div>

        @if(count($grouped) === 0)
            <div style="background: var(--khatma-surface); border-radius: 16px; padding: 3rem; text-align: center; box-shadow: var(--khatma-shadow); border: 1px solid var(--khatma-border);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📝</div>
                <div style="font-size: 1.1rem; color: var(--khatma-text); font-weight: 600; margin-bottom: 0.5rem;">لا يوجد سجل بعد</div>
                <div style="color: var(--khatma-muted);">ابدأ بتسجيل ورد اليوم من لوحة التحكم</div>
            </div>
        @else
            @foreach($grouped as $dateKey => $day)
                <div style="margin-bottom: 1.5rem;">
                    {{-- تاريخ اليوم --}}
                    <div style="font-weight: 700; color: var(--khatma-text); font-size: 1rem; margin-bottom: 0.8rem; padding-bottom: 0.5rem; border-bottom: 2px solid var(--khatma-border);">
                        {{ $day['label'] }}
                    </div>

                    {{-- سجلات اليوم --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($day['records'] as $record)
                            @php
                                $typeColors = match($record['khatma_type']) {
                                    \App\Enums\KhatmaType::Hifz => [
                                        'badge_bg' => 'var(--khatma-hifz-bg)', 'badge_text' => 'var(--khatma-hifz-text)',
                                        'border' => 'var(--khatma-hifz-from)', 'label' => '📖 حفظ',
                                    ],
                                    \App\Enums\KhatmaType::Review => [
                                        'badge_bg' => 'var(--khatma-review-bg)', 'badge_text' => 'var(--khatma-review-text)',
                                        'border' => 'var(--khatma-review-from)', 'label' => '🔄 مراجعة',
                                    ],
                                    \App\Enums\KhatmaType::Tilawa => [
                                        'badge_bg' => 'var(--khatma-tilawa-bg)', 'badge_text' => 'var(--khatma-tilawa-text)',
                                        'border' => 'var(--khatma-tilawa-from)', 'label' => '📿 تلاوة',
                                    ],
                                    default => [
                                        'badge_bg' => 'var(--khatma-surface-soft)', 'badge_text' => 'var(--khatma-muted)',
                                        'border' => 'var(--khatma-border)', 'label' => '—',
                                    ],
                                };
                            @endphp

                            <div style="background: var(--khatma-surface); border-radius: 12px; padding: 1rem 1.2rem; box-shadow: var(--khatma-shadow); border-right: 4px solid {{ $typeColors['border'] }}; border-top: 1px solid var(--khatma-border); border-bottom: 1px solid var(--khatma-border); border-left: 1px solid var(--khatma-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.8rem; flex-wrap: wrap;">
                                    {{-- بادج النوع --}}
                                    <span style="background: {{ $typeColors['badge_bg'] }}; color: {{ $typeColors['badge_text'] }}; padding: 0.2rem 0.7rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">
                                        {{ $typeColors['label'] }}
                                    </span>

                                    {{-- اسم الختمة --}}
                                    <span style="font-weight: 600; color: var(--khatma-text);">{{ $record['khatma_name'] }}</span>

                                    {{-- السورة --}}
                                    <span style="color: var(--khatma-muted); font-size: 0.9rem;">{{ $record['surah_name'] }}</span>
                                </div>

                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    {{-- الصفحات --}}
                                    <span style="font-family: 'Amiri', serif; font-weight: 700; color: {{ $typeColors['badge_text'] }};">
                                        ص{{ $record['from_page'] }}–{{ $record['to_page'] }}
                                    </span>

                                    {{-- عدد الصفحات --}}
                                    <span style="background: var(--khatma-surface-soft); color: var(--khatma-muted); padding: 0.2rem 0.6rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; border: 1px solid var(--khatma-border);">
                                        {{ $record['pages_count'] }} صفحة
                                    </span>

                                    {{-- الوقت --}}
                                    <span style="color: var(--khatma-muted-soft); font-size: 0.8rem;">
                                        {{ $record['completed_at'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</x-filament-panels::page>
