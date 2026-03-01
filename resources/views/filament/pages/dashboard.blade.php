<x-filament-panels::page>

    {{-- === ويدجتات سريعة === --}}
    @php
        $widgets = $this->getWidgetsData();
        $wirds = $this->getTodayWirds();
        $pausedKhatmas = $this->getPausedKhatmas();
    @endphp

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div style="background: var(--khatma-hifz-bg); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">ختمات نشطة</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-hifz-text);">
                {{ $widgets['active_count'] }}
            </div>
        </div>

        <div style="background: var(--khatma-review-bg); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">صفحة منجزة</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-review-text);">
                {{ $widgets['total_completed'] }}
            </div>
        </div>

        <div style="background: var(--khatma-tilawa-bg); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">أيام متتالية</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-tilawa-text);">
                🔥 {{ $widgets['streak'] }}
            </div>
        </div>

        <div style="background: var(--khatma-surface-soft); border-radius: 16px; padding: 1.5rem; text-align: center; border: 1px solid var(--khatma-border);">
            <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.5rem;">نسبة الالتزام</div>
            <div style="font-family: 'Amiri', serif; font-size: 2rem; font-weight: 700; color: var(--khatma-text);">
                {{ $widgets['commitment_rate'] }}%
            </div>
        </div>
    </div>

    {{-- === ورد اليوم === --}}
    <div style="margin-top: 2rem;">
        <h2 style="font-family: 'Amiri', serif; font-size: 1.5rem; font-weight: 700; color: var(--khatma-title); margin-bottom: 1rem;">
            📖 ورد اليوم
        </h2>

        @if(count($wirds) === 0)
            <div style="background: var(--khatma-surface); border-radius: 16px; padding: 3rem; text-align: center; box-shadow: var(--khatma-shadow); border: 1px solid var(--khatma-border);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
                <div style="font-size: 1.1rem; color: var(--khatma-text); font-weight: 600; margin-bottom: 0.5rem;">لا توجد ختمات نشطة</div>
                <div style="color: var(--khatma-muted); margin-bottom: 1.5rem;">ابدأ رحلتك بإنشاء ختمة جديدة</div>
                <a href="{{ \App\Filament\Resources\KhatmaResource::getUrl('create') }}"
                   style="display: inline-block; background: linear-gradient(135deg, var(--khatma-hifz-from), var(--khatma-hifz-to)); color: white; padding: 0.7rem 1.8rem; border-radius: 12px; font-weight: 600; text-decoration: none;">
                    + ختمة جديدة
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach($wirds as $wird)
                    @php
                        $typeColors = match($wird['type']) {
                            \App\Enums\KhatmaType::Hifz => [
                                'badge_bg' => 'var(--khatma-hifz-bg)', 'badge_text' => 'var(--khatma-hifz-text)',
                                'btn_from' => 'var(--khatma-hifz-from)', 'btn_to' => 'var(--khatma-hifz-to)',
                                'progress_from' => 'var(--khatma-hifz-from)', 'progress_to' => 'var(--khatma-hifz-to)',
                                'pages_color' => 'var(--khatma-hifz-text)', 'label' => '📖 حفظ',
                            ],
                            \App\Enums\KhatmaType::Review => [
                                'badge_bg' => 'var(--khatma-review-bg)', 'badge_text' => 'var(--khatma-review-text)',
                                'btn_from' => 'var(--khatma-review-from)', 'btn_to' => 'var(--khatma-review-to)',
                                'progress_from' => 'var(--khatma-review-from)', 'progress_to' => 'var(--khatma-review-to)',
                                'pages_color' => 'var(--khatma-review-text)', 'label' => '🔄 مراجعة',
                            ],
                            \App\Enums\KhatmaType::Tilawa => [
                                'badge_bg' => 'var(--khatma-tilawa-bg)', 'badge_text' => 'var(--khatma-tilawa-text)',
                                'btn_from' => 'var(--khatma-tilawa-from)', 'btn_to' => 'var(--khatma-tilawa-to)',
                                'progress_from' => 'var(--khatma-tilawa-from)', 'progress_to' => 'var(--khatma-tilawa-to)',
                                'pages_color' => 'var(--khatma-tilawa-text)', 'label' => '📿 تلاوة',
                            ],
                        };
                    @endphp

                    <div style="background: var(--khatma-surface); border-radius: 16px; padding: 1.5rem; box-shadow: var(--khatma-shadow); border: 1px solid var(--khatma-border);">

                        {{-- هيدر البطاقة --}}
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                            <div style="font-weight: 700; color: var(--khatma-text); font-size: 1.05rem;">{{ $wird['name'] }}</div>
                            <span style="background: {{ $typeColors['badge_bg'] }}; color: {{ $typeColors['badge_text'] }}; padding: 0.25rem 0.8rem; border-radius: 50px; font-size: 0.8rem; font-weight: 600;">
                                {{ $typeColors['label'] }}
                            </span>
                        </div>

                        {{-- الصفحات --}}
                        <div style="font-family: 'Amiri', serif; font-size: 1.6rem; font-weight: 700; color: {{ $typeColors['pages_color'] }}; margin-bottom: 0.3rem;">
                            صفحة {{ $wird['from_page'] }} — {{ $wird['to_page'] }}
                        </div>

                        {{-- السورة --}}
                        <div style="font-size: 0.9rem; color: var(--khatma-muted); margin-bottom: 1rem;">
                            {{ $wird['surah_name'] }}
                        </div>

                        {{-- تفاصيل الورد --}}
                        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem; margin-bottom: 1rem;">
                            <div style="background: var(--khatma-surface-soft); border-radius: 10px; padding: 0.6rem; border: 1px solid var(--khatma-border);">
                                <div style="font-size: 0.75rem; color: var(--khatma-muted);">الورد اليومي</div>
                                <div style="font-weight: 700; color: var(--khatma-text);">{{ $wird['today_target_pages'] }} صفحة</div>
                            </div>
                            <div style="background: var(--khatma-surface-soft); border-radius: 10px; padding: 0.6rem; border: 1px solid var(--khatma-border);">
                                <div style="font-size: 0.75rem; color: var(--khatma-muted);">المتبقي اليوم</div>
                                <div style="font-weight: 700; color: var(--khatma-text);">{{ $wird['today_remaining_pages'] }} صفحة</div>
                            </div>
                            <div style="background: var(--khatma-surface-soft); border-radius: 10px; padding: 0.6rem; border: 1px solid var(--khatma-border);">
                                <div style="font-size: 0.75rem; color: var(--khatma-muted);">المنجز اليوم</div>
                                <div style="font-weight: 700; color: var(--khatma-text);">{{ $wird['today_done_pages'] }} صفحة</div>
                            </div>
                            <div style="background: var(--khatma-surface-soft); border-radius: 10px; padding: 0.6rem; border: 1px solid var(--khatma-border);">
                                <div style="font-size: 0.75rem; color: var(--khatma-muted);">المتبقي بالختمة</div>
                                <div style="font-weight: 700; color: var(--khatma-text);">{{ $wird['remaining_pages_total'] }} صفحة</div>
                            </div>
                        </div>

                        @if($wird['backlog_pages'] > 0)
                            <div style="margin-bottom: 1rem; padding: 0.5rem 0.7rem; border-radius: 10px; background: var(--khatma-warning-soft-bg); color: var(--khatma-warning-soft-text); font-size: 0.8rem;">
                                متبقي من الخطة: {{ $wird['backlog_pages'] }} صفحة
                            </div>
                        @endif

                        {{-- شريط التقدم --}}
                        <div style="margin-bottom: 0.5rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.3rem;">
                                <span>{{ $wird['completed_pages'] }} / {{ $wird['total_pages'] }} صفحة</span>
                                <span style="color: {{ $typeColors['pages_color'] }}; font-weight: 600;">{{ $wird['progress'] }}%</span>
                            </div>
                            <div style="height: 8px; background: var(--khatma-surface-soft); border-radius: 8px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $wird['progress'] }}%; background: linear-gradient(90deg, {{ $typeColors['progress_from'] }}, {{ $typeColors['progress_to'] }}); border-radius: 8px; transition: width 0.5s;"></div>
                            </div>
                        </div>

                        {{-- تاريخ الختم المتوقع --}}
                        <div style="font-size: 0.8rem; color: var(--khatma-muted-soft); margin-bottom: 1rem;">
                            📅 الختم المتوقع: {{ $wird['expected_end_date'] }}
                        </div>

                        {{-- أزرار الإنجاز --}}
                        @if(! $wird['is_started'])
                            <div style="width: 100%; padding: 0.8rem; border-radius: 12px; background: var(--khatma-surface-soft); color: var(--khatma-muted); text-align: center; font-weight: 600; font-size: 0.95rem; border: 1px solid var(--khatma-border);">
                                لم يبدأ موعد هذه الختمة بعد
                            </div>
                        @elseif($wird['is_rest_day'])
                            <div style="width: 100%; padding: 0.8rem; border-radius: 12px; background: var(--khatma-success-soft-bg); color: var(--khatma-success-soft-text); text-align: center; font-weight: 600; font-size: 0.95rem;">
                                لا يوجد ورد مطلوب اليوم حسب الخطة
                            </div>
                        @elseif($wird['is_done_today'])
                            <div style="width: 100%; padding: 0.8rem; border-radius: 12px; background: var(--khatma-review-bg); color: var(--khatma-review-text); text-align: center; font-weight: 700; font-size: 1rem; margin-bottom: 0.6rem;">
                                ✅ تم إنجاز ورد اليوم
                            </div>
                        @else
                            <button
                                wire:click="completeWird({{ $wird['id'] }})"
                                style="width: 100%; padding: 0.8rem; border-radius: 12px; border: none; background: linear-gradient(135deg, {{ $typeColors['btn_from'] }}, {{ $typeColors['btn_to'] }}); color: white; font-family: 'Tajawal', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; margin-bottom: 0.6rem;"
                            >
                                ✓ أتممت كامل الورد
                            </button>

                            <div style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 0.6rem;">
                                <input
                                    type="number"
                                    min="1"
                                    max="{{ max($wird['today_remaining_pages'], 1) }}"
                                    wire:model.defer="partialPages.{{ $wird['id'] }}"
                                    placeholder="عدد الصفحات"
                                    style="flex: 1; border: 1px solid var(--khatma-border); border-radius: 10px; padding: 0.55rem 0.7rem; font-size: 0.9rem; background: var(--khatma-surface); color: var(--khatma-text);"
                                >
                                <button
                                    wire:click="completePartialWird({{ $wird['id'] }})"
                                    style="border: none; border-radius: 10px; background: var(--khatma-surface-soft); color: var(--khatma-text); padding: 0.55rem 0.9rem; font-weight: 700; cursor: pointer;"
                                >
                                    تسجيل جزئي
                                </button>
                            </div>
                        @endif

                        <button
                            wire:click="pauseKhatma({{ $wird['id'] }})"
                            style="width: 100%; padding: 0.55rem; border-radius: 10px; border: 1px solid var(--khatma-border); background: var(--khatma-surface); color: var(--khatma-muted); font-weight: 600; cursor: pointer;"
                        >
                            إيقاف مؤقت
                        </button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- === الختمات المتوقفة === --}}
    @if(count($pausedKhatmas) > 0)
        <div style="margin-top: 2rem;">
            <h2 style="font-family: 'Amiri', serif; font-size: 1.5rem; font-weight: 700; color: var(--khatma-text); margin-bottom: 1rem;">
                ⏸️ ختمات متوقفة
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach($pausedKhatmas as $khatma)
                    @php
                        $typeColors = match($khatma['type']) {
                            \App\Enums\KhatmaType::Hifz => [
                                'badge_bg' => 'var(--khatma-hifz-bg)', 'badge_text' => 'var(--khatma-hifz-text)', 'label' => '📖 حفظ',
                                'progress_from' => 'var(--khatma-hifz-from)', 'progress_to' => 'var(--khatma-hifz-to)',
                            ],
                            \App\Enums\KhatmaType::Review => [
                                'badge_bg' => 'var(--khatma-review-bg)', 'badge_text' => 'var(--khatma-review-text)', 'label' => '🔄 مراجعة',
                                'progress_from' => 'var(--khatma-review-from)', 'progress_to' => 'var(--khatma-review-to)',
                            ],
                            \App\Enums\KhatmaType::Tilawa => [
                                'badge_bg' => 'var(--khatma-tilawa-bg)', 'badge_text' => 'var(--khatma-tilawa-text)', 'label' => '📿 تلاوة',
                                'progress_from' => 'var(--khatma-tilawa-from)', 'progress_to' => 'var(--khatma-tilawa-to)',
                            ],
                        };
                    @endphp

                    <div style="background: var(--khatma-surface); border-radius: 14px; padding: 1rem 1.2rem; box-shadow: var(--khatma-shadow); border: 1px solid var(--khatma-border);">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.7rem;">
                            <div style="font-weight: 700; color: var(--khatma-text);">{{ $khatma['name'] }}</div>
                            <span style="background: {{ $typeColors['badge_bg'] }}; color: {{ $typeColors['badge_text'] }}; padding: 0.2rem 0.65rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700;">
                                {{ $typeColors['label'] }}
                            </span>
                        </div>

                        <div style="font-size: 0.85rem; color: var(--khatma-muted); margin-bottom: 0.7rem;">
                            {{ $khatma['completed_pages'] }} / {{ $khatma['total_pages'] }} صفحة • {{ $khatma['progress'] }}%
                        </div>

                        <div style="height: 8px; background: var(--khatma-surface-soft); border-radius: 8px; overflow: hidden; margin-bottom: 0.8rem;">
                            <div style="height: 100%; width: {{ $khatma['progress'] }}%; background: linear-gradient(90deg, {{ $typeColors['progress_from'] }}, {{ $typeColors['progress_to'] }}); border-radius: 8px; transition: width 0.5s;"></div>
                        </div>

                        <button
                            wire:click="resumeKhatma({{ $khatma['id'] }})"
                            style="width: 100%; padding: 0.55rem; border-radius: 10px; border: none; background: var(--khatma-success-soft-bg); color: var(--khatma-success-soft-text); font-weight: 700; cursor: pointer;"
                        >
                            استئناف الختمة
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</x-filament-panels::page>
