<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum KhatmaScope: string implements HasLabel
{
    case Full = 'full';
    case Custom = 'custom';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Full => 'كاملة (٦٠٤ صفحات)',
            self::Custom => 'مخصصة',
        };
    }
}
