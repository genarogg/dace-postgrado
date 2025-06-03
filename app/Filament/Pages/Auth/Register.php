<?php

namespace App\Filament\Pages\Auth;

use App\Models\Estudiante;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getCedulaFormComponent(),
                //$this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getCedulaFormComponent(): Component
    {
        return TextInput::make('estudiantes.cedula')
            ->required()
            ->numeric()
            ->maxLength(10)
            ->autofocus()
            ->unique(table: Estudiante::class);
    }

    /* protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['estudiante'] = [
            'user_id' => null,
        ];
        return $data;
    } */

    /* protected function afterRegister(User $user): void
    {
        $user->estudiante()->create([
            'user_id' => $user->id,
        ]);
    } */

    /* protected function handleRegistration(array $data): Model
    {
        return $this->getUserModel()::create($data);
    } */

    protected function handleRegistration(array $data): User
    {
        return DB::transaction(function () use ($data) {      
            // Validar datos primero
            /* $validator = Validator::make($data, 
                [
                    'estudiantes.cedula' => 'numeric|unique:estudiantes,cedula',
                ],
                [
                    'estudiantes.cedula.numeric' => 'La cédula debe ser numérica',
                    'estudiantes.cedula.unique' => 'Esta cédula ya está registrada',
                ]
            ); */
            $validator = Validator::make($data, 
                [
                    'estudiantes.cedula' => 'numeric',
                ],
                [
                    'estudiantes.cedula.numeric' => 'La cédula debe ser numérica',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            /* // Crear usuario
            $data['name'] = $data['estudiantes']['cedula'];
            $usuario = $this->getUserModel()::create($data); */
            
            $usuario = $this->getUserModel()::where('name', $data['estudiantes']['cedula'])->first();
            if (!$usuario) {
                // Crear usuario
                $data['name'] = $data['estudiantes']['cedula'];
                $usuario = $this->getUserModel()::create($data);

                // Asignar rol de estudiante
                $usuario->assignRole('Estudiante');
            }

            /* // Crear estudiante relacionado
            $usuario->estudiante()->create([
                'user_id' => $usuario->id,
                'cedula' => $data['estudiantes']['cedula'],
            ]); */

            $estudiante = Estudiante::where('cedula', $data['estudiantes']['cedula'])->whereNull('user_id')->first();
            if ($estudiante) {
                // Si el estudiante ya existe, asociarlo al usuario
                $usuario->estudiante()->associate($estudiante);
            } else {
                // Crear estudiante relacionado
                $usuario->estudiante()->create([
                    'user_id' => $usuario->id,
                    'cedula' => $data['estudiantes']['cedula'],
                ]);
            }
            
            return $usuario;
        }) ?? new User();
    }
}