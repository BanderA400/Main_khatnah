<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PlanningMethod: string implements HasLabel
{
    case ByDuration = 'by_duration';
    case ByWird = 'by_wird';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ByDuration => 'بالمدة (أحدد تاريخ الختم)',
            self::ByWird => 'بالورد (أحدد عدد الصفحات يومياً)',
        };
    }
}
