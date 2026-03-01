<?php

namespace App\Filament\Resources\KhatmaResource\Pages;

use App\Filament\Resources\KhatmaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKhatmas extends ListRecords
{
    protected static string $resource = KhatmaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('ختمة جديدة')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
