<?php

namespace App\Filament\Resources\PreinscripcionResource\Pages;

use App\Filament\Resources\PreinscripcionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPreinscripcion extends EditRecord
{
    protected static string $resource = PreinscripcionResource::class;

    public function beforeEdit(): void
    {
        if (auth()->user()->hasRole('Estudiante') && $this->record->estado !== 'pendiente') {
            Notification::make()
                ->warning()
                ->title('Acción no permitida')
                ->body('Solo puede editar preinscripciones en estado pendiente.')
                ->send();

            $this->redirect(PreinscripcionResource::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
