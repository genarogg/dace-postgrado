<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscripcionMateriaResource\Pages;
use App\Filament\Resources\InscripcionMateriaResource\RelationManagers;
use App\Models\InscripcionMateria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class InscripcionMateriaResource extends Resource
{
    protected static ?string $model = InscripcionMateria::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Estudiantes';
    protected static ?string $modelLabel = 'Inscripción de Materia';
    protected static ?string $pluralModelLabel = 'Inscripciones de Materias';
    
    protected static ?int $navigationSort = 204;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('inscripcion_id')
                    ->relationship('inscripcion', 'id')
                    ->required(),
                Forms\Components\Select::make('materia_id')
                    ->relationship('materia', 'nombre')
                    ->required(),
                Forms\Components\Select::make('profesor_id')
                    ->relationship('profesor', 'nombre')
                    ->required(),
                Forms\Components\Select::make('estado')
                    ->options([
                        'inscrita' => 'Inscrita',
                        'aprobada' => 'Aprobada',
                        'reprobada' => 'Reprobada',
                        'retirada' => 'Retirada',
                    ])
                    ->required()
                    ->default('inscrita'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inscripcion.estudiante.cedula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('materia.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profesor.nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'inscrita' => 'info',
                        'aprobada' => 'success',
                        'reprobada' => 'danger',
                        'retirada' => 'warning',
                    }),
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
                Tables\Filters\SelectFilter::make('estado')
                    ->options([
                        'inscrita' => 'Inscrita',
                        'aprobada' => 'Aprobada',
                        'reprobada' => 'Reprobada',
                        'retirada' => 'Retirada',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListInscripcionMaterias::route('/'),
            'create' => Pages\CreateInscripcionMateria::route('/create'),
            'edit' => Pages\EditInscripcionMateria::route('/{record}/edit'),
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
