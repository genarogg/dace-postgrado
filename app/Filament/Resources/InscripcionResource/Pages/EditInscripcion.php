<?php

namespace App\Filament\Resources\InscripcionResource\Pages;

use App\Filament\Resources\InscripcionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditInscripcion extends EditRecord
{
    protected static string $resource = InscripcionResource::class;

    public function beforeEdit(): void
    {
        if (auth()->user()->hasRole('Estudiante') && $this->record->estado !== 'pendiente') {
            Notification::make()
                ->warning()
                ->title('Acción no permitida')
                ->body('Solo puede editar inscripción en estado pendiente.')
                ->send();

            $this->redirect(InscripcionResource::getUrl('index'));
        }
        
        $user = auth()->user();
        $periodoActivo = $this->record->periodo->activo;

        if (!$user->hasRole('super_admin')) {
            if (!$periodoActivo || ($user->hasRole('Estudiante') && $this->record->estado !== 'pendiente')) {
                Notification::make()
                    ->warning()
                    ->title('Acción no permitida')
                    ->body($periodoActivo ? 'Solo puede editar inscripción en estado pendiente.' : 'No se puede editar la inscripción porque el período no está activo.')
                    ->send();

                $this->redirect(InscripcionResource::getUrl('index'));
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
