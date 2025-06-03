<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstudioRealizadoResource\Pages;
use App\Models\EstudioRealizado;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EstudioRealizadoResource extends Resource
{
    protected static ?string $model = EstudioRealizado::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Estudiantes';
    protected static ?int $navigationSort = 201;
    protected static ?string $modelLabel = 'Estudio Realizado';
    protected static ?string $pluralModelLabel = 'Estudios Realizados';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('estudiante_id')
                    ->relationship('estudiante', 'cedula')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('titulo_obtenido')
                    ->required()
                    ->maxLength(255),
                TextInput::make('instituto')
                    ->required()
                    ->maxLength(255),
                TextInput::make('anio_graduacion')
                    ->label('Año de Graduación')
                    ->required()
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('estudiante.cedula')
                    ->label('Cédula del Estudiante')
                    ->searchable(),
                TextColumn::make('titulo_obtenido')
                    ->searchable(),
                TextColumn::make('instituto')
                    ->searchable(),
                TextColumn::make('anio_graduacion')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
            'index' => Pages\ListEstudioRealizados::route('/'),
            'create' => Pages\CreateEstudioRealizado::route('/create'),
            'edit' => Pages\EditEstudioRealizado::route('/{record}/edit'),
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