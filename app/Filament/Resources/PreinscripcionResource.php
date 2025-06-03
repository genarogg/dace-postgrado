<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreinscripcionResource\Pages;
use App\Filament\Resources\PreinscripcionResource\RelationManagers;
use App\Models\Estudiante;
use App\Models\Preinscripcion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class PreinscripcionResource extends Resource
{
    protected static ?string $model = Preinscripcion::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Estudiantes';
    protected static ?int $navigationSort = 202;
    protected static ?string $modelLabel = 'Preinscripción';
    protected static ?string $pluralModelLabel = 'Preinscripciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->live(),
                Forms\Components\Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'rechazada' => 'Rechazada',
                    ])
                    ->label('Estado')
                    ->required()
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante')),
                Forms\Components\Textarea::make('observaciones')
                    ->label('Observaciones')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante')),
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function (Builder $query) {
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
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'aprobada',
                        'secondary' => 'completada',
                        'danger' => 'rechazada',
                    ]),
                Tables\Columns\TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('numero_referencia_pago')
                    ->label('Número de Referencia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_pago')
                    ->label('Monto')
                    ->money('VES')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('comprobante_pago')
                    ->label('Comprobante'),
                Tables\Columns\IconColumn::make('pago_verificado')
                    ->label('Pago Verificado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'aprobada' => 'Aprobada',
                        'completada' => 'Completada',
                        'rechazada' => 'Rechazada',
                    ]),
                Tables\Filters\SelectFilter::make('sede_id')
                    ->label('Sede')
                    ->relationship('sede', 'nombre'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn (Preinscripcion $record): bool =>
                        auth()->user()->hasRole('Estudiante') && $record->estado !== 'pendiente'),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (Preinscripcion $record): bool =>
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
            'index' => Pages\ListPreinscripcions::route('/'),
            'create' => Pages\CreatePreinscripcion::route('/create'),
            'view' => Pages\ViewPreinscripcion::route('/{record}'),
            'edit' => Pages\EditPreinscripcion::route('/{record}/edit'),
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
