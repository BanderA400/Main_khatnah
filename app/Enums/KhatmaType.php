<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum KhatmaType: string implements HasLabel, HasColor, HasIcon
{
    case Hifz = 'hifz';
    case Review = 'review';
    case Tilawa = 'tilawa';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Hifz => 'حفظ',
            self::Review => 'مراجعة',
            self::Tilawa => 'تلاوة',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Hifz => 'primary',
            self::Review => 'success',
            self::Tilawa => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Hifz => 'heroicon-o-book-open',
            self::Review => 'heroicon-o-arrow-path',
            self::Tilawa => 'heroicon-o-speaker-wave',
        };
    }
}
