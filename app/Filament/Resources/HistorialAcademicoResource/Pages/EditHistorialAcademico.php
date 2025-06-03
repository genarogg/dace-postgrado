<?php

namespace App\Filament\Resources\HistorialAcademicoResource\Pages;

use App\Filament\Resources\HistorialAcademicoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHistorialAcademico extends EditRecord
{
    protected static string $resource = HistorialAcademicoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
