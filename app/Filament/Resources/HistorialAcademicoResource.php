<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistorialAcademicoResource\Pages;
use App\Filament\Resources\HistorialAcademicoResource\RelationManagers;
use App\Models\Carrera;
use App\Models\HistorialAcademico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class HistorialAcademicoResource extends Resource
{
    protected static ?string $model = HistorialAcademico::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Estudiantes';
    
    protected static ?int $navigationSort = 205;

    protected static ?string $modelLabel = 'Historial Académico';
    
    protected static ?string $pluralModelLabel = 'Historiales Académicos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('estudiante_id')
                    ->relationship('estudiante', 'cedula')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('carrera_id')
                    ->relationship('carrera', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('pensum_id')
                    ->relationship('pensum', 'codigo', fn (Builder $query, Get $get) =>
                        $query->where('carrera_id', $get('carrera_id'))
                              ->where('activo', true)
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Pensum'),
                Forms\Components\Select::make('sede_id')
                    ->relationship('sede', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('periodo_ingreso_id')
                    ->relationship('periodoIngreso', 'nombre', fn (Builder $query, Get $get) => 
                        $query->when($get('carrera_id'), function (Builder $query, $carreraId) {
                            $modalidad = Carrera::find($carreraId)?->modalidad;
                            $query->where('modalidad', $modalidad);
                        })
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live(),
                Forms\Components\Select::make('periodo_egreso_id')
                    ->relationship('periodoEgreso', 'nombre', fn (Builder $query, Get $get) =>
                        $query->when($get('carrera_id'), function (Builder $query, $carreraId) {
                            $modalidad = Carrera::find($carreraId)?->modalidad;
                            $query->where('modalidad', $modalidad);
                        })
                    )
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Forms\Components\Select::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'egresado' => 'Egresado',
                        'retirado' => 'Retirado',
                        'suspendido' => 'Suspendido',
                    ])
                    ->required()
                    ->default('activo'),
                Forms\Components\Textarea::make('observaciones')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estudiante.user.name')
                    ->label('Estudiante')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estudiante.cedula')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('carrera.nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sede.nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('periodoIngreso.nombre')
                    ->label('Periodo de Ingreso')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('periodoEgreso.nombre')
                    ->label('Periodo de Egreso')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'egresado' => 'Egresado',
                        'retirado' => 'Retirado',
                        'suspendido' => 'Suspendido',
                    ])
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
                Tables\Filters\SelectFilter::make('carrera')
                    ->relationship('carrera', 'nombre')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('sede')
                    ->relationship('sede', 'nombre')
                    ->searchable()
                    ->preload()
                    ->multiple(),
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'activo' => 'Activo',
                        'egresado' => 'Egresado',
                        'retirado' => 'Retirado',
                        'suspendido' => 'Suspendido',
                    ])
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListHistorialAcademicos::route('/'),
            'create' => Pages\CreateHistorialAcademico::route('/create'),
            'edit' => Pages\EditHistorialAcademico::route('/{record}/edit'),
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
