<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum KhatmaDirection: string implements HasLabel
{
    case Forward = 'forward';
    case Backward = 'backward';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Forward => 'من الصفحة 1 إلى 604',
            self::Backward => 'من الصفحة 604 إلى 1 (تنازلي)',
        };
    }
}
