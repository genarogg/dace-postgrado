<?php

namespace App\Filament\Resources\InscripcionResource\Pages;

use App\Filament\Resources\InscripcionResource;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Operacion;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateInscripcion extends CreateRecord
{
    protected static string $resource = InscripcionResource::class;

    protected function beforeCreate(): void
    {
        $carrera = \App\Models\Carrera::find($this->data['carrera_id']);
        $fechaActual = date('Y-m-d');
        $recipient = auth()->user();
        $periodo = \App\Models\Periodo::where('modalidad', $carrera->modalidad)
            ->where('fecha_inicio', '<=', $fechaActual)
            ->where('fecha_fin', '>=', $fechaActual)
            ->where('activo', 1)
            ->orderBy('id', 'desc')
            ->first();
        if (!$periodo) {
            Notification::make()
                ->title('Error de validación')
                ->body('No hay un periodo activo en este momento.')
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
            ->where('proceso', 'inscripciones')
            ->first();

        if (!$operacionActiva) {
            Notification::make()
                ->title('Error de validación')
                ->body('No hay un proceso de inscripción activo para la carrera y sede seleccionada en este momento.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

            $this->halt();
        }


        // Validar si ya existe una inscripción para el mismo estudiante, carrera, sede y periodo
        if (auth()->user()->hasRole('Estudiante')) {
            $estudiante_id = Estudiante::where('user_id', auth()->id())->first()?->id;
            $inscripcionExistente = Inscripcion::where('estudiante_id', $estudiante_id)
                ->where('carrera_id', $this->data['carrera_id'])
                ->where('sede_id', $this->data['sede_id'])
                ->where('periodo_id', $periodo->id)
                ->first();
            if ($inscripcionExistente) {
                Notification::make()
                    ->title('Error de validación')
                    ->body('Ya existe una inscripción para este estudiante en la misma carrera, sede y periodo.')
                    ->danger()
                    ->send()
                    ->sendToDatabase($recipient);

                $this->halt();
            }
        }
    }
}
