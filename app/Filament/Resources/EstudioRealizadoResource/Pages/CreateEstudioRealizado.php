<?php

namespace App\Filament\Resources\EstudioRealizadoResource\Pages;

use App\Filament\Resources\EstudioRealizadoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstudioRealizado extends CreateRecord
{
    protected static string $resource = EstudioRealizadoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}