<?php

namespace App\Filament\Resources\PreinscripcionResource\Pages;

use App\Filament\Resources\PreinscripcionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewPreinscripcion extends ViewRecord
{
    protected static string $resource = PreinscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información del Estudiante')
                    ->schema([
                        TextEntry::make('estudiante.cedula')
                            ->label('Cédula'),
                        TextEntry::make('estudiante.nombre')
                            ->label('Nombre'),
                        TextEntry::make('estudiante.apellido')
                            ->label('Apellido'),
                        TextEntry::make('estudiante.email')
                            ->label('Correo Electrónico'),
                        TextEntry::make('estudiante.telefono')
                            ->label('Teléfono'),
                        RepeatableEntry::make('estudiante.estudiosRealizados')
                            ->schema([
                                TextEntry::make('titulo_obtenido')
                                    ->label('Título Obtenido'),
                                TextEntry::make('instituto')
                                    ->label('Instituto'),
                                TextEntry::make('anio_graduacion')
                                    ->label('Año de Graduación'),
                            ])
                            ->label('Estudios Realizados')
                            ->columns(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Información de la Preinscripción')
                    ->schema([
                        TextEntry::make('carrera.nombre')
                            ->label('Carrera'),
                        TextEntry::make('sede.nombre')
                            ->label('Sede'),
                        TextEntry::make('estado')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pendiente' => 'warning',
                                'aprobada' => 'success',
                                'completada' => 'secondary',
                                'rechazada' => 'danger',
                            }),
                        TextEntry::make('observaciones')
                            ->label('Observaciones')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Información del Pago')
                    ->schema([
                        TextEntry::make('numero_referencia_pago')
                            ->label('Número de Referencia'),
                        TextEntry::make('monto_pago')
                            ->label('Monto')
                            ->prefix('Bs.'),
                        TextEntry::make('fecha_pago')
                            ->label('Fecha de Pago')
                            ->date(),
                        ImageEntry::make('comprobante_pago')
                            ->label('Comprobante')
                            ->columnSpanFull(),
                        TextEntry::make('pago_verificado')
                            ->label('Pago Verificado')
                            ->badge()
                            ->color(fn (bool $state): string => $state ? 'success' : 'warning'),
                    ])
                    ->columns(2),
            ]);
    }
}