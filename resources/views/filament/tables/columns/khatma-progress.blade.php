@php
    $record = $getRecord();
    $progress = max(min((float) ($record->progress_percentage ?? 0), 100), 0);

    $colors = match ($record->type) {
        \App\Enums\KhatmaType::Hifz => ['from' => 'var(--khatma-hifz-from)', 'to' => 'var(--khatma-hifz-to)', 'text' => 'var(--khatma-hifz-text)'],
        \App\Enums\KhatmaType::Review => ['from' => 'var(--khatma-review-from)', 'to' => 'var(--khatma-review-to)', 'text' => 'var(--khatma-review-text)'],
        \App\Enums\KhatmaType::Tilawa => ['from' => 'var(--khatma-tilawa-from)', 'to' => 'var(--khatma-tilawa-to)', 'text' => 'var(--khatma-tilawa-text)'],
    };
@endphp

<div style="min-width: 170px;">
    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; margin-bottom: 0.28rem;">
        <span style="color: var(--khatma-muted);">{{ $record->completed_pages }} / {{ $record->total_pages }}</span>
        <span style="font-weight: 700; color: {{ $colors['text'] }};">{{ rtrim(rtrim(number_format($progress, 1), '0'), '.') }}%</span>
    </div>
    <div style="height: 8px; border-radius: 999px; background: var(--khatma-surface-soft); overflow: hidden;">
        <div style="height: 100%; width: {{ $progress }}%; background: linear-gradient(90deg, {{ $colors['from'] }}, {{ $colors['to'] }}); border-radius: 999px; transition: width 0.35s ease;"></div>
    </div>
</div>
