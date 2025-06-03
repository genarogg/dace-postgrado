<?php

namespace App\Imports;

use App\Models\Carrera;
use App\Models\Estado;
use App\Models\Estudiante;
use App\Models\HistorialAcademico;
use App\Models\Inscripcion;
use App\Models\InscripcionMateria;
use App\Models\Pensum;
use App\Models\PensumDetalle;
use App\Models\Periodo;
use App\Models\Preinscripcion;
use App\Models\Sede;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class InscripcionImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $recipient = auth()->user();

        foreach ($rows as $row) {
            $estado = Estado::where('codigo',$row['codigo_estado'])->first();
            if (!$estado) {
               Notification::make()
                ->title('Error de validación')
                ->body('El estado no existe.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

                break;
            }

            $sede = Sede::where('codigo',$row['codigo_sede'])->first();
            if (!$sede) {
                Notification::make()
                ->title('Error de validación')
                ->body('La sede no existe.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

                break;
            }

            $carrera = Carrera::where('codigo',$row['codigo_programa'])->first();
            if (!$carrera) {
                Notification::make()
                ->title('Error de validación')
                ->body('El programa no existe.')
                ->danger()
                ->send()
                ->sendToDatabase($recipient);

                break;
            }

            DB::beginTransaction();
            try {
                $estudiante = Estudiante::where('cedula',$row['cedula'])->first();
                if (!$estudiante) {
                    $user = new User();
                    $user->name = $row['cedula'];
                    $user->email = $row['correo'];
                    $user->password = bcrypt($row['cedula']);
                    $user->save();

                    if(!$user) {
                        Notification::make()
                            ->title('Error de validación')
                            ->body('No se pudo crear el usuario ' . $row['cedula'])
                            ->danger()
                            ->send()
                            ->sendToDatabase($recipient);

                        continue;
                    }

                    $user->assignRole('Estudiante');

                    $estudiante = new Estudiante();
                    $estudiante->user_id = $user->id;
                    $estudiante->cedula = $row['cedula'];
                    $estudiante->nombre = $row['nombres'];
                    $estudiante->apellido = $row['apellidos'];
                    $estudiante->telefono = $row['telefono'];
                    $estudiante->save();

                    if(!$estudiante) {
                        Notification::make()
                            ->title('Error de validación')
                            ->body('No se pudo crear el estudiante ' . $row['cedula'])
                            ->danger()
                            ->send()
                            ->sendToDatabase($recipient);

                        continue;
                    }
                }

                $pensum = Pensum::where('carrera_id', $carrera->id)
                        ->where('activo', 1)
                        ->orderBy('id', 'desc')
                        ->first();
                if (!$pensum) {
                    Notification::make()
                        ->title('Error de validación')
                        ->body('No existe un pensum activo para el programa '. $carrera->nombre)
                        ->danger()
                        ->send()
                        ->sendToDatabase($recipient);

                    continue;
                }

                $trimestre_o_semestre = $row['trimestre_o_semestre'];

                $pensumDetalles = PensumDetalle::where('pensum_id', $pensum->id)
                    ->get();

                $inscripciones = Inscripcion::where('estudiante_id', $estudiante->id)
                    ->where('carrera_id', $carrera->id)
                    ->where('sede_id', $sede->id)
                    ->get();
                if ($inscripciones->count() == 0) {
                    $indicePeriodo = 0;
                    for($i = 0; $i <= $trimestre_o_semestre; $i++) {
                        $pensumDetallesPeriodos = $pensumDetalles->where('periodo', $i);
                        if ($pensumDetallesPeriodos->count() > 0) {
                            
                            $periodos = Periodo::where('modalidad', $carrera->modalidad)
                                ->orderBy('nombre', 'asc')
                                ->get();
                            $periodoActual = null;
                            foreach ($periodos as $key => $periodo) {
                                if ($key == $indicePeriodo) {
                                    $periodoActual = $periodo->id;
                                    break;
                                }
                            }

                            $preinscripciones = Preinscripcion::where('estudiante_id', $estudiante->id)
                                ->where('carrera_id', $carrera->id)
                                ->where('sede_id', $sede->id)
                                ->first();
                            if (!$preinscripciones) {
                                $preinscripcion = new Preinscripcion([
                                    'estudiante_id' => $estudiante->id,
                                    'carrera_id' => $carrera->id,
                                    'sede_id' => $sede->id,
                                    'estado' => 'aprobada',
                                ]);
                                $preinscripcion->save();

                                if(!$preinscripcion) {
                                    DB::rollback();
                                    Notification::make()
                                        ->title('Error de validación')
                                        ->body('No se pudo crear la preinscripción para el estudiante '. $estudiante->cedula)
                                        ->danger()
                                        ->send()
                                        ->sendToDatabase($recipient);
                                    continue;
                                }
                            }                            

                            /* $inscripcion = new Inscripcion([
                                'estudiante_id' => $estudiante->id,
                                'carrera_id' => $carrera->id,
                                'sede_id' => $sede->id,
                                'periodo_id' => $periodoActual,
                                'tipo' => $i == 0 ? 'nuevo' : 'regular',
                                'estado' => 'aprobada',
                            ]);
                            $inscripcion->save(); */
                            DB::insert('insert into inscripcions (estudiante_id, carrera_id, sede_id, periodo_id, tipo, estado) values (?, ?, ?, ?, ?, ?)', [$estudiante->id, $carrera->id, $sede->id, $periodoActual, ($indicePeriodo == 0 ? 'nuevo' : 'regular'), 'aprobada']);
                            $inscripcion = Inscripcion::where('estudiante_id', $estudiante->id)
                                ->where('carrera_id', $carrera->id)
                                ->where('sede_id', $sede->id)
                                ->orderBy('id', 'desc')
                                ->first();

                            if(!$inscripcion) {
                                DB::rollback();
                                Notification::make()
                                    ->title('Error de validación')
                                    ->body('No se pudo crear la inscripción para el estudiante '. $estudiante->cedula)
                                    ->danger()
                                    ->send()
                                    ->sendToDatabase($recipient);
                                continue;
                            }

                            foreach ($pensumDetallesPeriodos as $pensumDetallesPeriodo) {
                                $inscripcionMateria = new InscripcionMateria([
                                    'materia_id' => $pensumDetallesPeriodo->materia_id,
                                    'estado' => 'inscrita',
                                    'periodo' => $pensumDetallesPeriodo->periodo,
                                ]);
                                if ($inscripcion->id) {
                                    $inscripcionMateria->inscripcion_id = $inscripcion->id;
                                    $inscripcionMateria->save();

                                    if(!$inscripcionMateria) {
                                        DB::rollback();
                                        Notification::make()
                                            ->title('Error de validación')
                                            ->body('No se pudo crear la inscripción de materias para el estudiante '. $estudiante->cedula)
                                            ->danger()
                                            ->send()
                                            ->sendToDatabase($recipient);
                                        continue;
                                    }
                                }                                
                            }
                            if (isset($pensumDetallesPeriodo) && $pensumDetallesPeriodo->periodo == 1) {
                                $historial = new HistorialAcademico([
                                    'estudiante_id' => $inscripcion->estudiante_id,
                                    'carrera_id' => $inscripcion->carrera_id,
                                    'sede_id' => $inscripcion->sede_id,
                                    'periodo_ingreso_id' => $inscripcion->periodo_id,
                                    'pensum_id' => $pensum->id,
                                    'estado' => 'activo',
                                    'observaciones' => 'Inscripción',   
                                ]);
                                $historial->save();

                                if(!$historial) {
                                    DB::rollback();
                                    Notification::make()
                                        ->title('Error de validación')
                                        ->body('No se pudo crear el historial académico para el estudiante '. $estudiante->cedula)
                                        ->danger()
                                        ->send()
                                        ->sendToDatabase($recipient);
                                    continue;
                                }
                            }

                            $indicePeriodo++;
                        }
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Notification::make()
                    ->title('Error de validación')
                    ->body('Fallo la inscripción para el estudiante '. $estudiante->cedula . '. ERROR: '. $e->getMessage())
                    ->danger()
                    ->send()
                    ->danger()
                    ->send()
                    ->sendToDatabase($recipient);

                continue;
            }
            
        }
    }
}
