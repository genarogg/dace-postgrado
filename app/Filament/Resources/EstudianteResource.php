<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EstudianteResource\Pages;
use App\Filament\Resources\EstudianteResource\RelationManagers;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Blade;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class EstudianteResource extends Resource
{
    protected static ?string $model = Estudiante::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Estudiantes';
    protected static ?int $navigationSort = 200;
    protected static ?string $modelLabel = 'Estudiante';
    protected static ?string $pluralModelLabel = 'Estudiantes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->unique(ignoreRecord: true)
                    ->relationship('user', 'name', fn (Builder $query) => $query->whereHas('roles', fn (Builder $query) => $query->where('name', 'Estudiante')))
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->options([
                                '4' => 'Estudiante',
                            ])
                            ->required()
                            ->default(['4'])
                    ])
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante')),
                Forms\Components\TextInput::make('cedula')
                    ->label('Cédula')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('apellido')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de Nacimiento')
                    ->required()
                    ->maxDate(now()),
                Forms\Components\Select::make('genero')
                    ->label('Sexo')
                    ->options([
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('direccion')
                    ->label('Dirección')
                    ->maxLength(255),
                /* Forms\Components\TextInput::make('titulo_pregrado')
                    ->label('Título de Pregrado')
                    ->maxLength(255),
                Forms\Components\TextInput::make('universidad_pregrado')
                    ->label('Universidad de Pregrado')
                    ->maxLength(255),
                Forms\Components\TextInput::make('anio_egreso_pregrado')
                    ->label('Año de Egreso')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y')), */
                Forms\Components\Toggle::make('activo')
                    ->label('Activo')
                    ->required()
                    ->default(true)
                    ->hidden(fn () => auth()->user()->hasRole('Estudiante')),
                Forms\Components\Repeater::make('estudiosRealizados')
                    ->label('Estudios Realizados')
                    ->relationship()
                    ->schema([
                        Forms\Components\TextInput::make('titulo_obtenido')
                            ->label('Título Obtenido')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('instituto')
                            ->label('Instituto')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('anio_graduacion')
                            ->label('Año de Graduación')
                            ->numeric()
                            ->required()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                    ])
                    ->defaultItems(0)
                    ->reorderable(true)
                    ->columnSpanFull()
                    ->addActionLabel('Agregar Estudio')
                    ->deleteAction(fn (Forms\Components\Actions\Action $action) => $action->label('Eliminar')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (auth()->user()->hasRole('Estudiante')) {
                    $query->whereHas('user', function (Builder $query) {
                        $query->where('id', auth()->id());
                    });
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('cedula')
                    ->label('Cédula')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('apellido')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->searchable(),
                /* Tables\Columns\TextColumn::make('titulo_pregrado')
                    ->label('Título de Pregrado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('universidad_pregrado')
                    ->label('Universidad de Pregrado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('anio_egreso_pregrado')
                    ->label('Año de Egreso')
                    ->sortable(), */
                Tables\Columns\IconColumn::make('activo')
                    ->label('Activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Fecha de Actualización')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('activo')
            ])
            ->actions([
                Tables\Actions\Action::make('pdf') 
                    ->label('Constancia de Estudios')
                    ->color('success')
                    ->icon('heroicon-o-credit-card')
                    ->requiresConfirmation()
                    ->action(function (Estudiante $record) {
                        $inscripcion = Inscripcion::where('estudiante_id', $record->id)->orderBy('id','desc')->with('materias.materia')->first();

                        return response()->streamDownload(function () use ($record, $inscripcion) {
                            //$customPaper = array(0,0,360,360);
                            $customPaper = 'carta';
                            echo Pdf::loadHtml(
                                Blade::render('pdfs.constancia-estudios', ['estudiante' => $record, 'inscripcion' => $inscripcion])
                            )//->stream()
                            ->setPaper($customPaper, 'portrait')
                            ->download('constancia-estudios' . $record->cedula . '.pdf');                            
                        }, 'constancia-estudios' . $record->cedula . '.pdf');
                    })
                    ->visible(fn (Estudiante $record) => $record->inscripciones->count() > 0 ? $record->inscripciones->last()->estado === 'aprobada' : false),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListEstudiantes::route('/'),
            'create' => Pages\CreateEstudiante::route('/create'),
            'edit' => Pages\EditEstudiante::route('/{record}/edit'),
            'view' => Pages\ViewEstudiante::route('/{record}'),
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
