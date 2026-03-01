<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum KhatmaStatus: string implements HasLabel, HasColor, HasIcon
{
    case Active = 'active';
    case Paused = 'paused';
    case Completed = 'completed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'نشطة',
            self::Paused => 'متوقفة',
            self::Completed => 'مكتملة',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Paused => 'gray',
            self::Completed => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Active => 'heroicon-o-play',
            self::Paused => 'heroicon-o-pause',
            self::Completed => 'heroicon-o-check-circle',
        };
    }
}
