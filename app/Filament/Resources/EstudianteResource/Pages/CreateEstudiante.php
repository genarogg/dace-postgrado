<?php

namespace App\Filament\Resources\EstudianteResource\Pages;

use App\Filament\Resources\EstudianteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateEstudiante extends CreateRecord
{
    protected static string $resource = EstudianteResource::class;

    protected function beforeCreate(): void
    {
        if (auth()->user()->hasRole('Estudiante')) {
            $recipient = auth()->user();
            Notification::make()
                ->title('Error de validación')
                ->body('No tienes permiso para crear estudiantes.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

            $this->halt();
        }
    }
}
