<?php

namespace App\Filament\Resources\CarreraMateriaResource\Pages;

use App\Filament\Resources\CarreraMateriaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarreraMaterias extends ListRecords
{
    protected static string $resource = CarreraMateriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
