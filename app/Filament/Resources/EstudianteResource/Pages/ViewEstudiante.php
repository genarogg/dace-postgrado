<?php

namespace App\Filament\Resources\EstudianteResource\Pages;

use App\Filament\Resources\EstudianteResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewEstudiante extends ViewRecord
{
    protected static string $resource = EstudianteResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información Personal')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Nombre'),
                        TextEntry::make('cedula')
                            ->label('Cédula'),
                        TextEntry::make('telefono')
                            ->label('Teléfono'),
                        TextEntry::make('direccion')
                            ->label('Dirección'),
                    ])->columns(2),

                Section::make('Información del Sistema')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Fecha de Creación')
                            ->dateTime('d/m/Y H:i'),
                        TextEntry::make('updated_at')
                            ->label('Última Actualización')
                            ->dateTime('d/m/Y H:i'),
                    ])->columns(2),

                Section::make('Estudios Realizados')
                    ->schema([
                        RepeatableEntry::make('estudiosRealizados')
                            ->schema([
                                TextEntry::make('titulo_obtenido'),
                                TextEntry::make('instituto'),
                                TextEntry::make('anio_graduacion')->label('Año de Graduación'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('Preinscripciones')
                    ->schema([
                        RepeatableEntry::make('preinscripciones')
                            ->schema([
                                TextEntry::make('carrera.nombre')->label('Carrera'),
                                TextEntry::make('sede.nombre')->label('Sede'),
                                TextEntry::make('estado')->label('Estado'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('Inscripciones')
                    ->schema([
                        RepeatableEntry::make('inscripciones')
                            ->schema([
                                TextEntry::make('carrera.nombre')->label('Carrera'),
                                TextEntry::make('sede.nombre')->label('Sede'),
                                TextEntry::make('periodo.nombre')->label('Periodo'),
                                TextEntry::make('estado')->label('Estado'),
                            ])
                            ->columns(4),
                    ]),

                Section::make('Historial Academico')
                    ->schema([
                        RepeatableEntry::make('historialAcademicos')
                            ->schema([
                                TextEntry::make('carrera.nombre')->label('Carrera'),
                                TextEntry::make('sede.nombre')->label('Sede'),
                                TextEntry::make('periodoIngreso.nombre')->label('Periodo de Ingreso'),
                                TextEntry::make('periodoIngreso.nombre')->label('Periodo de Egreso'),
                            ])
                            ->columns(4),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}