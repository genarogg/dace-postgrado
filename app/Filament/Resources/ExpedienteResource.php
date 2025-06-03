<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpedienteResource\Pages;
use App\Filament\Resources\ExpedienteResource\RelationManagers;
use App\Models\DocumentoRequerido;
use App\Models\Expediente;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class ExpedienteResource extends Resource
{
    protected static ?string $model = Expediente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Estudiantes';
    
    protected static ?int $navigationSort = 206;

    public static function form(Form $form): Form
    {
        $documentos = DocumentoRequerido::all()->pluck('nombre', 'id')->toArray();

        return $form
            ->schema([
                Section::make('Información del Expediente')
                    ->schema([
                        Select::make('estudiante_id')
                            ->relationship('estudiante', 'cedula')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'completo' => 'Completo',
                                'incompleto' => 'Incompleto'
                            ])
                            ->required(),
                        Textarea::make('observaciones')
                            ->maxLength(65535)
                            ->columnSpanFull()
                    ])->columns(2),
                Section::make('Documentos Entregados')
                    ->schema([
                        CheckboxList::make('documentos')
                            ->relationship('documentos', 'nombre')
                            ->label('')
                            ->bulkToggleable()
                            ->gridDirection('row')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('estudiante.cedula')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completo' => 'success',
                        'incompleto' => 'warning',
                        'pendiente' => 'gray',
                    }),
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Action::make('etiqueta_pdf')
                    ->label('Generar Etiqueta')
                    ->icon('heroicon-o-document')
                    ->action(function (Expediente $record) {
                        return response()->streamDownload(function () use ($record) {
                            $customPaper = 'carta';
                            echo Pdf::loadHtml(
                                Blade::render('pdfs.etiqueta-expediente', ['expediente' => $record])
                            )
                            ->setPaper($customPaper, 'portrait')
                            ->stream();
                        }, 'etiqueta-expediente-' . $record->estudiante->cedula . '.pdf');
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListExpedientes::route('/'),
            'create' => Pages\CreateExpediente::route('/create'),
            'edit' => Pages\EditExpediente::route('/{record}/edit'),
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
