<?php

namespace App\Filament\Resources\EstudioRealizadoResource\Pages;

use App\Filament\Resources\EstudioRealizadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstudioRealizados extends ListRecords
{
    protected static string $resource = EstudioRealizadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}