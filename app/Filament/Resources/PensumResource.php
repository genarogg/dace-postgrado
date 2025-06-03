<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PensumResource\Pages;
use App\Models\Pensum;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class PensumResource extends Resource
{
    protected static ?string $model = Pensum::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $modelLabel = 'Pensum';

    protected static ?string $pluralModelLabel = 'Pensums';

    protected static ?string $navigationGroup = 'Configuración';
    
    protected static ?int $navigationSort = 505;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('carrera_id')
                    ->relationship('carrera', 'nombre')
                    ->required()
                    ->label('Carrera')
                    ->live(),

                TextInput::make('codigo')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Código'),

                TextInput::make('numero_resolucion')
                    ->required()
                    ->label('Número de Resolución'),

                Select::make('activo')
                    ->label('Estado')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo',
                    ])
                    ->default(true)
                    ->required(),

                Repeater::make('detalles')
                    ->relationship()
                    ->schema([
                        Select::make('materia_id')
                            ->relationship('materia', 'nombre', function (Builder $query, callable $get) {
                                $carreraId = $get('../../carrera_id');
                                if (!$carreraId) return $query;
                                return $query->whereHas('carreras', function ($query) use ($carreraId) {
                                    $query->where('carreras.id', $carreraId);
                                })->where('activo', true)->orderBy('codigo');
                            })
                            ->preload()
                            ->searchable()
                            ->required()
                            ->label('Materia')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('codigo')
                                    ->required()
                                    ->maxLength(10),
                                Forms\Components\Textarea::make('descripcion')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('carreras')
                                    ->relationship('carreras', 'nombre')
                                    ->required()
                                    ->default(fn (callable $get) => $get('../../carrera_id')),
                                Forms\Components\TextInput::make('horas_teoricas')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                                Forms\Components\TextInput::make('horas_practicas')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100),
                                Forms\Components\TextInput::make('creditos')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10),
                                Forms\Components\Toggle::make('activo')
                                    ->required()
                                    ->default(true),
                            ])
                            /* ->createOptionUsing(function (array $data, callable $get) {
                                $carreraId = $get('../carrera_id');
                                return \App\Models\Materia::create([
                                    ...array_merge($data, ['carrera_id' => $carreraId])
                                ]);
                            }) */,

                        Forms\Components\Select::make('periodo')
                            ->options([
                                '0' => 'Introductorio',
                                '1' => 'Primer Periodo',
                                '2' => 'Segundo Periodo',
                                '3' => 'Tercer Periodo',
                                '4' => 'Cuarto Periodo',
                                '5' => 'Quinto Periodo',
                                '6' => 'Sexto Periodo',
                                '7' => 'Septimo Periodo',
                                '8' => 'Octavo Periodo',
                                '9' => 'Noveno Periodo',
                                '10' => 'Decimo Periodo',
                            ])
                            ->required()
                            ->label('Periodo Académico'),
                    ])
                    ->label('Materias del Pensum')
                    ->defaultItems(0)
                    ->reorderable(true)
                    ->grid(1)
                    ->columnSpan('full'),

                    Repeater::make('lineas')
                    ->relationship()
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(255)
                            ->label('Nombre de la Línea'),

                        TextInput::make('coordinador')
                            ->required()
                            ->maxLength(255)
                            ->label('Coordinador'),

                        Select::make('activo')
                            ->label('Estado')
                            ->options([
                                true => 'Activo',
                                false => 'Inactivo',
                            ])
                            ->default(true)
                            ->required(),
                    ])
                    ->label('Líneas de Investigación')
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->grid(1)
                    ->columnSpan('full'),

                    Repeater::make('electivas')
                    ->relationship()
                    ->schema([
                        TextInput::make('nombre')
                            ->required()
                            ->maxLength(255)
                            ->label('Nombre de la Materia'),

                        TextInput::make('codigo')
                            ->required()
                            ->maxLength(10)
                            ->label('Código'),

                        TextInput::make('creditos')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10)
                            ->label('Créditos'),

                        TextInput::make('horas_teoricas')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->label('Horas Teóricas'),

                        TextInput::make('horas_practicas')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->label('Horas Prácticas'),

                        Select::make('activo')
                            ->label('Estado')
                            ->options([
                                true => 'Activo',
                                false => 'Inactivo',
                            ])
                            ->default(true)
                            ->required(),
                    ])
                    ->label('Materias Electivas')
                    ->defaultItems(0)
                    ->reorderable(false)
                    ->grid(1)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('carrera.nombre')
                    ->sortable()
                    ->searchable()
                    ->label('Carrera'),

                TextColumn::make('codigo')
                    ->sortable()
                    ->searchable()
                    ->label('Código'),

                TextColumn::make('numero_resolucion')
                    ->sortable()
                    ->searchable()
                    ->label('Número de Resolución'),

                TextColumn::make('detalles_count')
                    ->counts('detalles')
                    ->label('Materias'),
                
                IconColumn::make('activo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListPensums::route('/'),
            'create' => Pages\CreatePensum::route('/create'),
            'edit' => Pages\EditPensum::route('/{record}/edit'),
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