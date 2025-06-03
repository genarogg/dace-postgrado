<?php

namespace App\Filament\Resources\MateriaProfesorResource\Pages;

use App\Filament\Resources\MateriaProfesorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMateriaProfesor extends EditRecord
{
    protected static string $resource = MateriaProfesorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}