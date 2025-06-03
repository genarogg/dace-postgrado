<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OperacionResource\Pages;
use App\Models\Operacion;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class OperacionResource extends Resource
{
    protected static ?string $model = Operacion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Configuración';
    
    protected static ?int $navigationSort = 507;

    protected static ?string $slug = 'operaciones';

    protected static ?string $modelLabel = 'Operación';

    protected static ?string $pluralModelLabel = 'Operaciones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('proceso')
                    ->options([
                        'preinscripciones' => 'Preinscripciones',
                        'inscripciones' => 'Inscripciones',
                    ])
                    ->required(),

                Select::make('sedes')
                    ->relationship('sedes', 'nombre')
                    ->multiple()
                    ->preload()
                    ->required(),

                Select::make('carreras')
                    ->relationship('carreras', 'nombre')
                    ->multiple()
                    ->preload()
                    ->required(),

                DateTimePicker::make('fecha_desde')
                    ->required(),

                DateTimePicker::make('fecha_hasta')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('proceso')
                    ->searchable(),

                TextColumn::make('sedes.nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('carreras.nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('fecha_desde')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('fecha_hasta')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
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
            'index' => Pages\ListOperacions::route('/'),
            'create' => Pages\CreateOperacion::route('/create'),
            'edit' => Pages\EditOperacion::route('/{record}/edit'),
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
