<?php

namespace App\Filament\Resources\PreinscripcionResource\Pages;

use App\Filament\Resources\PreinscripcionResource;
use App\Models\Estudiante;
use App\Models\Operacion;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePreinscripcion extends CreateRecord
{
    protected static string $resource = PreinscripcionResource::class;

    protected function beforeCreate(): void
    {
        $recipient = auth()->user();
        $estudiante = Estudiante::find($this->data['estudiante_id'])->with('estudiosRealizados')->first();
        //dd($estudiante);
        if (!$estudiante->estudiosRealizados()->exists()) {
            Notification::make()
                ->title('Error de validación')
                ->body('Debe registrar sus estudios realizados antes de crear una preinscripción.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

            $this->halt();
        }

        // Validar que exista una operación activa que coincida con la carrera y sede seleccionada
        $operacionActiva = Operacion::query()
            ->whereDate('fecha_desde', '<=', now())
            ->whereDate('fecha_hasta', '>=', now())
            ->whereHas('carreras', function ($query) {
                $query->where('carrera_id', $this->data['carrera_id']);
            })
            ->whereHas('sedes', function ($query) {
                $query->where('sede_id', $this->data['sede_id']);
            })
            ->where('proceso', 'preinscripciones')
            ->first();

        if (!$operacionActiva) {
            Notification::make()
                ->title('Error de validación')
                ->body('No hay un proceso de preinscripción activo para la carrera y sede seleccionada en este momento.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

            $this->halt();
        }
    }
}
