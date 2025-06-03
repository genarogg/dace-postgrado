<?php

namespace App\Filament\Resources\EstudioRealizadoResource\Pages;

use App\Filament\Resources\EstudioRealizadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEstudioRealizado extends EditRecord
{
    protected static string $resource = EstudioRealizadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}