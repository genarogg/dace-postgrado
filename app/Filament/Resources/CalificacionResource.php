<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalificacionResource\Pages;
use App\Models\InscripcionMateria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class CalificacionResource extends Resource
{
    protected static ?string $model = InscripcionMateria::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Calificaciones';
    protected static ?string $modelLabel = 'Calificación';
    protected static ?string $pluralModelLabel = 'Calificaciones';
    
    protected static ?int $navigationSort = 401;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nota')
                    ->label('Nota')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(20),
                Forms\Components\Textarea::make('observacion_nota')
                    ->label('Observaciones')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->hasRole('super_admin')) {
                    $query->whereHas('inscripcion.periodo', function (Builder $query) {
                        $query->where('activo', 1);
                    });
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('inscripcion.estudiante.cedula')
                    ->label('Cédula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inscripcion.estudiante.nombre')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('inscripcion.estudiante.apellido')
                    ->label('Apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profesor.nombre')
                    ->label('Profesor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nota')
                    ->label('Nota')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('observacion_nota')
                    ->label('Observaciones')
                    ->limit(50)
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('periodo')
                    ->relationship('inscripcion.periodo', 'nombre')
                    ->label('Periodo')
                    ->multiple()
                    ->hidden(!auth()->user()->hasRole('super_admin'))
                    ->preload(),
                Tables\Filters\SelectFilter::make('estudiante')
                    ->relationship('inscripcion.estudiante', 'cedula')
                    ->label('Estudiante')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Editar Calificación')
                    ->modalWidth('md')
                    ->slideOver(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListCalificaciones::route('/'),
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