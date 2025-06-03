<?php

namespace App\Filament\Resources\CarreraMateriaResource\Pages;

use App\Filament\Resources\CarreraMateriaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarreraMateria extends EditRecord
{
    protected static string $resource = CarreraMateriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
