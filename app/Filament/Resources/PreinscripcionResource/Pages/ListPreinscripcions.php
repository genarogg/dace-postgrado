<?php

namespace App\Filament\Resources\PreinscripcionResource\Pages;

use App\Filament\Resources\PreinscripcionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreinscripcions extends ListRecords
{
    protected static string $resource = PreinscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
