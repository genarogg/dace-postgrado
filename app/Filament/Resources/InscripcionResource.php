<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscripcionResource\Pages;
use App\Filament\Resources\InscripcionResource\RelationManagers;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Preinscripcion;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Blade;

class InscripcionResource extends Resource
{
    protected static ?string $model = Inscripcion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Estudiantes';
    protected static ?int $navigationSort = 203;
    protected static ?string $modelLabel = 'Inscripción';
    protected static ?string $pluralModelLabel = 'Inscripciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('preinscripcion_id')
                    ->relationship('preinscripcion', 'id', function ($query) {
                        $estudiante_id = Estudiante::where('user_id', auth()->id())->first()?->id;
                        return $query->where('estado', 'aprobada')->where('estudiante_id', $estudiante_id);
                    })
                    ->label('A la espera de inscripción')
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Carrera: {$record->carrera->nombre} | Sede: {$record->sede->nombre}")
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $preinscripcion = \App\Models\Preinscripcion::find($state);
                            if ($preinscripcion) {
                                $set('carrera_id', $preinscripcion->carrera_id);
                                $set('sede_id', $preinscripcion->sede_id);
                            }
                        }
                    })
                    ->required(fn () => auth()->user()->hasRole('Estudiante')),
                Forms\Components\Select::make('estudiante_id')
                    ->relationship('estudiante', 'cedula')
                    ->label('Estudiante')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante'))
                    ->default(fn () => auth()->user()->hasRole('Estudiante')
                        ? Estudiante::where('user_id', auth()->id())->first()?->id
                        : null),
                    Forms\Components\Select::make('carrera_id')
                        ->relationship('carrera', 'nombre')
                        ->label('Carrera')
                        ->searchable()
                        ->preload()
                        ->disabled(fn () => auth()->user()->hasRole('Estudiante'))
                        ->required(),
                    Forms\Components\Select::make('sede_id')
                        ->label('Sede')
                        ->options(function (Forms\Get $get) {
                            $carreraId = $get('carrera_id');
                            if (!$carreraId) {
                                return [];
                            }
                            $carrera = \App\Models\Carrera::find($carreraId);
                            return $carrera ? $carrera->sedes()->pluck('sedes.nombre', 'sedes.id') : [];
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->disabled(fn () => auth()->user()->hasRole('Estudiante'))
                        ->live(),
                /* Forms\Components\Select::make('periodo_id')
                    ->label('Período')
                    ->options(function (Forms\Get $get) {
                        $carreraId = $get('carrera_id');
                        if (!$carreraId) {
                            return [];
                        }
                        $carrera = \App\Models\Carrera::find($carreraId);
                        return $carrera ? \App\Models\Periodo::where('modalidad', $carrera->modalidad)
                            ->where('activo', true)
                            ->pluck('nombre', 'id')
                            : [];
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante'))
                    ->live(), */
                Forms\Components\Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'rechazada' => 'Rechazada',
                    ])
                    ->required()
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante'))
                    ->default('pendiente'),
                /* Forms\Components\Select::make('tipo')
                    ->options([
                        'nuevo' => 'Nuevo',
                        'regular' => 'Regular',
                    ])
                    ->required(),                
                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->required(),
                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->after('fecha_inicio'), */

                Fieldset::make('Pago de inscripción')
                    ->schema([
                        Forms\Components\TextInput::make('numero_referencia_pago')
                            ->label('Número de Referencia del Pago')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('monto_pago')
                            ->label('Monto del Pago')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->numeric()
                            ->prefix('Bs.')
                            ->maxValue(9999999999.99),
                        Forms\Components\DatePicker::make('fecha_pago')
                            ->label('Fecha del Pago')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->format('Y-m-d')
                            ->maxDate(now()),
                        Forms\Components\FileUpload::make('comprobante_pago')
                            ->label('Comprobante de Pago')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->image()
                            ->directory('preinscripciones')
                            ->maxSize(5120),
                        Forms\Components\Toggle::make('pago_verificado')
                            ->label('Pago Verificado')
                            ->default(false)
                            ->hidden(fn () => auth()->user()->hasRole('Estudiante')),
                ]),

                Fieldset::make('Pago administrativo')
                    ->schema([
                        Forms\Components\TextInput::make('numero_referencia_pago_administrativo')
                            ->label('Número de Referencia del Pago')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('monto_pago_administrativo')
                            ->label('Monto del Pago (Administrativo)')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->numeric()
                            ->prefix('Bs.')
                            ->maxValue(9999999999.99),
                        Forms\Components\DatePicker::make('fecha_pago_administrativo')
                            ->label('Fecha del Pago (Administrativo)')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->format('Y-m-d')
                            ->maxDate(now()),
                        Forms\Components\FileUpload::make('comprobante_pago_administrativo')
                            ->label('Comprobante de Pago (Administrativo)')
                            ->required(fn () => auth()->user()->hasRole('Estudiante'))
                            ->image()
                            ->directory('preinscripciones')
                            ->maxSize(5120),
                        Forms\Components\Toggle::make('pago_verificado_administrativo')
                            ->label('Pago Verificado (Administrativo)')
                            ->default(false)
                            ->hidden(fn () => auth()->user()->hasRole('Estudiante')),
                ]),

                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->maxLength(65535)
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante'))
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('seccion')
                    ->required()
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante'))
                    ->maxLength(10),

                Forms\Components\Repeater::make('materias')
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante'))
                    ->label('Materias Inscritas')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('materia_id')
                            /* ->relationship('materia', 'nombre', function (Builder $query, callable $get) {
                                $carreraId = $get('../../carrera_id');
                                if (!$carreraId) return $query;
                                return $query->whereHas('carreras', function ($query) use ($carreraId) {
                                    $query->where('carreras.id', $carreraId);
                                })->where('activo', true)->orderBy('codigo');
                            }) */
                            /* ->relationship('materia', 'nombre', function (Builder $query, callable $get) {
                                $carreraId = $get('../../carrera_id');
                                $sedeId = $get('../../sede_id');
                                $estudianteId = $get('../../estudiante_id');
                                if (!$carreraId || !$estudianteId) return $query;

                                $historialAcademico = \App\Models\HistorialAcademico::where('estudiante_id', $estudianteId)
                                    ->where('carrera_id', $carreraId)
                                    ->first();

                                if (!$historialAcademico || !$historialAcademico->pensum_id) return $query;

                                return $query->whereHas('carreras', function ($query) use ($carreraId) {
                                    $query->where('carreras.id', $carreraId);
                                })
                                ->whereHas('carreras.pensums.detalles', function ($query) use ($historialAcademico) {
                                    $query->where('pensum_id', $historialAcademico->pensum_id);
                                })
                                ->where(function ($query) use ($estudianteId, $carreraId, $sedeId) {
                                    $query->whereDoesntHave('inscripcionesMateria', function ($query) use ($estudianteId, $carreraId, $sedeId) {
                                        $query->whereHas('inscripcion', function ($query) use ($estudianteId, $carreraId, $sedeId) {
                                            $query->where('estudiante_id', $estudianteId)
                                                ->where('carrera_id', $carreraId)
                                                ->where('sede_id', $sedeId);
                                                //->whereIn('estado', ['aprobada']);
                                        });
                                    })
                                    ->orWhereHas('inscripcionesMateria', function ($query) use ($estudianteId, $carreraId, $sedeId) {
                                        $query->whereHas('inscripcion', function ($query) use ($estudianteId, $carreraId, $sedeId) {
                                            $query->where('estudiante_id', $estudianteId)
                                                ->where('carrera_id', $carreraId)
                                                ->where('sede_id', $sedeId)
                                                //->whereIn('estado', ['aprobada'])
                                                ->whereHas('materias', function ($query) {
                                                    $query->where('nota', '<', 6);
                                                });
                                        });
                                    });
                                })
                                ->where('activo', true)
                                ->orderBy('codigo');
                            }) */
                            ->relationship('materia', 'nombre', function (Builder $query, callable $get) {
                                $carreraId = $get('../../carrera_id');
                                $sedeId = $get('../../sede_id');
                                $estudianteId = $get('../../estudiante_id');

                                $historialAcademico = \App\Models\HistorialAcademico::where('estudiante_id', $estudianteId)
                                    ->where('carrera_id', $carreraId)
                                    ->first();

                                if (!$historialAcademico || !$historialAcademico->pensum_id) return $query;

                                $pensum = \App\Models\Pensum::where('id', $historialAcademico->pensum_id)
                                    ->first();
                                if (!$pensum) {
                                    return $query;
                                }
                                
                                $pensumDetalles = \App\Models\PensumDetalle::where('pensum_id', $pensum->id)
                                    ->get();

                                if ($pensumDetalles->isEmpty()) {
                                    return $query;
                                }

                                $materiasIds = $pensumDetalles->pluck('materia_id');

                                $inscripciones = \App\Models\Inscripcion::where('estudiante_id', $estudianteId)
                                    ->where('carrera_id', $carreraId)
                                    ->where('sede_id', $sedeId)
                                    ->where('id', '!=', $get('../../../record.id'))
                                    ->with('materias')
                                    ->get();
                                foreach ($inscripciones as $inscripcion) {
                                    foreach ($inscripcion->materias as $materia) {
                                        if ($materia->nota >= 6) {
                                            //$materiasIds->pop($materia->materia_id);
                                            foreach ($materiasIds as $key => $materiaId) {
                                                if ($materiaId == $materia->materia_id) {
                                                    unset($materiasIds[$key]);
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }

                                return $query->whereIn('id', $materiasIds)
                                    ->where('activo', true)
                                    ->orderBy('codigo');
                            })
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $materiaId = $get('materia_id');
                                $carreraId = $get('../../carrera_id');
                                $sedeId = $get('../../sede_id');
                                $estudianteId = $get('../../estudiante_id');
                                
                                if ($materiaId && $carreraId && $sedeId && $estudianteId) {
                                    $historialAcademico = \App\Models\HistorialAcademico::where('estudiante_id', $estudianteId)
                                        ->where('carrera_id', $carreraId)
                                        ->where('sede_id', $sedeId)
                                        ->first();
                                    
                                    if ($historialAcademico && $historialAcademico->pensum_id) {
                                        $pensumDetalle = \App\Models\PensumDetalle::where('pensum_id', $historialAcademico->pensum_id)
                                            ->where('materia_id', $materiaId)
                                            ->first();
                                        
                                        if ($pensumDetalle) {
                                            $set('periodo', $pensumDetalle->periodo);
                                        }
                                    }
                                }
                            })
                            ->label('Materia')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
                        Forms\Components\TextInput::make('periodo')
                            ->label('Período')
                            ->live()
                            ->numeric()
                            ->readOnly()
                            ->required(),
                         Forms\Components\TextInput::make('nota')
                            ->label('Nota')
                            ->numeric(),
                        /* Forms\Components\Select::make('profesor_id')
                            ->relationship('profesor', fn ($query) => $query->where('activo', true))
                            ->label('Profesor')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombre} {$record->apellido}")
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('periodo')
                            ->label('Período')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'inscrita' => 'Inscrita',
                                'aprobada' => 'Aprobada',
                                'reprobada' => 'Reprobada',
                                'retirada' => 'Retirada',
                            ])
                            ->required()
                            ->default('inscrita'), */
                    ])
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->hasRole('super_admin')) {
                    $query->whereHas('periodo', function (Builder $query) {
                        $query->where('activo', true);
                    });
                }
                if (auth()->user()->hasRole('Estudiante')) {
                    $query->whereHas('estudiante', function (Builder $query) {
                        $query->whereHas('user', function (Builder $query) {
                            $query->where('id', auth()->id());
                        });
                    });
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('estudiante.cedula')
                    ->label('Cédula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('carrera.nombre')
                    ->label('Carrera')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->label('Sede')
                    ->searchable(),
                Tables\Columns\TextColumn::make('periodo.nombre')
                    ->label('Período')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'nuevo' => 'success',
                        'regular' => 'info',
                    }),
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobada',
                        'secondary' => 'completada',
                        'danger' => 'rechazada',
                    ]),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'nuevo' => 'Nuevo',
                        'regular' => 'Regular',
                    ]),
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activa' => 'Activa',
                        'suspendida' => 'Suspendida',
                        'egresado' => 'Egresado',
                    ]),
                Tables\Filters\SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->relationship('sede', 'nombre'),
            ])
            ->actions([
                Action::make('constancia_pdf')
                    ->label('Constancia inscripción')
                    ->icon('heroicon-o-document')
                    ->action(function (Inscripcion $record) {
                        $dataInscripcion = Inscripcion::where('id', $record->id)->orderBy('id','desc')->with('materias.materia')->first();

                        return response()->streamDownload(function () use ($record, $dataInscripcion) {
                            //$customPaper = array(0,0,360,360);
                            $customPaper = 'carta';
                            echo Pdf::loadHtml(
                                Blade::render('pdfs.constancia-inscripcion', ['inscripcion' => $record, 'dataInscripcion' => $dataInscripcion])
                            )//->stream()
                            ->setPaper($customPaper, 'portrait')
                            ->download('constancia-inscripcion' . $record->cedula . '.pdf');                            
                        }, 'constancia-inscripcion' . $record->cedula . '.pdf');
                    })
                    ->visible(fn (Inscripcion $record) => $record->estado === 'aprobada'),
                Tables\Actions\EditAction::make()
                    ->hidden(fn (Inscripcion $record): bool =>
                        auth()->user()->hasRole('Estudiante') && $record->estado !== 'pendiente'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (Inscripcion $record): bool =>
                        auth()->user()->hasRole('Estudiante') && $record->estado !== 'pendiente'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInscripcions::route('/'),
            'create' => Pages\CreateInscripcion::route('/create'),
            'edit' => Pages\EditInscripcion::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
