<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MateriaProfesorResource\Pages;
use App\Models\MateriaProfesor;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class MateriaProfesorResource extends Resource
{
    protected static ?string $model = MateriaProfesor::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Profesores';
    protected static ?int $navigationSort = 301;
    protected static ?string $navigationLabel = 'Asignación de Materias';
    protected static ?string $modelLabel = 'Asignación de Materia';
    protected static ?string $pluralModelLabel = 'Asignaciones de Materias';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('profesor_id')
                    ->relationship('profesor', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Profesor'),

                Select::make('materia_id')
                    ->relationship('materia', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Materia'),

                Select::make('periodo_id')
                    ->relationship('periodo', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Periodo Académico'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('profesor.nombre')
                    ->label('Profesor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('periodo.nombre')
                    ->label('Periodo Académico')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('profesor')
                    ->relationship('profesor', 'nombre')
                    ->label('Profesor'),

                SelectFilter::make('materia')
                    ->relationship('materia', 'nombre')
                    ->label('Materia'),

                SelectFilter::make('periodo')
                    ->relationship('periodo', 'nombre')
                    ->label('Periodo'),
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
            'index' => Pages\ListMateriaProfesor::route('/'),
            'create' => Pages\CreateMateriaProfesor::route('/create'),
            'edit' => Pages\EditMateriaProfesor::route('/{record}/edit'),
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