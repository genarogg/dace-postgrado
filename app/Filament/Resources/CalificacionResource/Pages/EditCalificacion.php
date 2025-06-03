<?php

namespace App\Filament\Resources\CalificacionResource\Pages;

use App\Filament\Resources\CalificacionResource;
use COM;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCalificacion extends EditRecord
{
    protected static string $resource = CalificacionResource::class;

    public function beforeEdit(): void
    {
        if (auth()->user()->hasRole('Estudiante') && $this->record->estado !== 'aprobada') {
            Notification::make()
                ->warning()
                ->title('Acción no permitida')
                ->body('Solo puede editar la calificación en estado en aprobada.')
                ->send();

            $this->redirect(CalificacionResource::getUrl('index'));
        }
        
        $user = auth()->user();
        $periodoActivo = $this->record->periodo->activo;

        if (!$user->hasRole('super_admin')) {
            if (!$periodoActivo || ($user->hasRole('Estudiante') && $this->record->estado !== 'aprobada')) {
                Notification::make()
                    ->warning()
                    ->title('Acción no permitida')
                    ->body($periodoActivo ? 'Solo puede editar la calificación en estado en aprobada.' : 'No se puede editar la calificación porque el período no está activo.')
                    ->send();

                $this->redirect(CalificacionResource::getUrl('index'));
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}