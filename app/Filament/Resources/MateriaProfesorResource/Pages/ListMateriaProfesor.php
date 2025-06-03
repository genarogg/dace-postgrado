<?php

namespace App\Filament\Resources\MateriaProfesorResource\Pages;

use App\Filament\Resources\MateriaProfesorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMateriaProfesor extends ListRecords
{
    protected static string $resource = MateriaProfesorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}