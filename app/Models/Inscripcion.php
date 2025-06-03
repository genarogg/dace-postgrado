<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;
use Filament\Notifications\Notification;

class Inscripcion extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'estudiante_id',
        'carrera_id',
        'sede_id',
        'periodo_id',
        'tipo',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
        'seccion',
        'preinscripcion_id',

        'numero_referencia_pago',
        'monto_pago',
        'fecha_pago',
        'comprobante_pago',
        'pago_verificado',

        'numero_referencia_pago_administrativo',
        'monto_pago_administrativo',
        'fecha_pago_administrativo',
        'comprobante_pago_administrativo',
        'pago_verificado_administrativo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'estudiante_id',
        'carrera_id',
        'sede_id',
        'periodo_id',
        'tipo',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
        'seccion',

        'numero_referencia_pago',
        'monto_pago',
        'fecha_pago',
        'comprobante_pago',
        'pago_verificado',

        'numero_referencia_pago_administrativo',
        'monto_pago_administrativo',
        'fecha_pago_administrativo',
        'comprobante_pago_administrativo',
        'pago_verificado_administrativo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inscripcion) {
            DB::beginTransaction();
            try {
                // Asignar sección automáticamente si no tiene una
                if (!$inscripcion->seccion) {
                    $ultimaInscripcion = static::where('periodo_id', $inscripcion->periodo_id)
                        ->where('carrera_id', $inscripcion->carrera_id)
                        ->orderBy('seccion', 'desc')
                        ->first();

                    if (!$ultimaInscripcion || !$ultimaInscripcion->seccion) {
                        $inscripcion->seccion = 'A';
                    } else {
                        $estudiantesEnSeccion = static::where('periodo_id', $inscripcion->periodo_id)
                            ->where('carrera_id', $inscripcion->carrera_id)
                            ->where('seccion', $ultimaInscripcion->seccion)
                            ->count();

                        if ($estudiantesEnSeccion >= 10) {
                            // Si la sección está llena, crear una nueva
                            $inscripcion->seccion = chr(ord($ultimaInscripcion->seccion) + 1);
                        } else {
                            // Si hay espacio, usar la misma sección
                            $inscripcion->seccion = $ultimaInscripcion->seccion;
                        }
                    }
                }

                if (auth()->user()->hasRole('Estudiante')) {
                    $inscripcion->estudiante_id = Estudiante::where('user_id', auth()->id())->first()?->id;
                }

                if ($inscripcion->preinscripcion_id) {
                    $preinscripcion = Preinscripcion::find($inscripcion->preinscripcion_id);
                    if ($preinscripcion) {
                        $inscripcion->carrera_id = $preinscripcion->carrera_id;
                        $inscripcion->sede_id = $preinscripcion->sede_id;
                    }
                    unset($inscripcion->preinscripcion_id);
                }

                $inscripciones = Inscripcion::where('estudiante_id', $inscripcion->estudiante_id)
                    ->where('carrera_id', $inscripcion->carrera_id)
                    ->where('sede_id', $inscripcion->sede_id)
                    ->get();
                if (!$inscripcion->tipo) {
                    if ($inscripciones->count() > 0) {
                        $inscripcion->tipo = 'regular';
                    } else {
                        $inscripcion->tipo = 'nuevo';
                    }
                }
                
                if (!$inscripcion->estado) {
                    $inscripcion->estado = 'pendiente';
                }

                $carrera = \App\Models\Carrera::find($inscripcion->carrera_id);
                $fechaActual = date('Y-m-d');
                $periodo = \App\Models\Periodo::where('modalidad', $carrera->modalidad)
                    ->where('fecha_inicio', '<=', $fechaActual)
                    ->where('fecha_fin', '>=', $fechaActual)
                    ->where('activo', 1)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($periodo) {
                    $inscripcion->periodo_id = $periodo->id;
                }

                // Validar si ya existe una inscripción para el mismo estudiante, carrera, sede y periodo
                $inscripcionExistente = Inscripcion::where('estudiante_id', $inscripcion->estudiante_id)
                    ->where('carrera_id', $inscripcion->carrera_id)
                    ->where('sede_id', $inscripcion->sede_id)
                    ->where('periodo_id', $inscripcion->periodo_id)
                    ->where('id', '!=', $inscripcion->id)
                    ->first();
                if ($inscripcionExistente) {
                    DB::rollback();

                    $recipient = auth()->user();
                    Notification::make()
                        ->title('Error de validación')
                        ->body('Ya existe una inscripción para este estudiante en la misma carrera, sede y periodo.')
                        ->danger()
                        ->send()
                        ->sendToDatabase($recipient);

                    throw new \Exception('Ya existe una inscripción para este estudiante en la misma carrera, sede y periodo.');
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        });

        static::updating(function ($inscripcion) {
            unset($inscripcion->preinscripcion_id);
        });
    }

    protected static function booted(): void
    {
        static::created(function (Inscripcion $inscripcion) {
            DB::beginTransaction();
            try {
                $historial = HistorialAcademico::where('estudiante_id', $inscripcion->estudiante_id)
                    ->where('carrera_id', $inscripcion->carrera_id)
                    ->where('sede_id', $inscripcion->sede_id)
                    ->first();
                
                if ($historial) {
                    $pensum = Pensum::where('id', $historial->pensum_id)
                    ->first();
                } else {
                    $pensum = Pensum::where('carrera_id', $inscripcion->carrera_id)
                        ->where('activo', 1)
                        ->orderBy('id', 'desc')
                        ->first();
                }

                if (isset($pensum) && $pensum) {
                    $pensumDetalles = PensumDetalle::where('pensum_id', $pensum->id)
                        ->get();

                    $inscripciones = Inscripcion::where('estudiante_id', $inscripcion->estudiante_id)
                        ->where('carrera_id', $inscripcion->carrera_id)
                        ->where('sede_id', $inscripcion->sede_id)
                        ->get();

                    if ($inscripciones->count() == 1) {
                        $periodoActual = 0;
                        $pensumDetallesPeriodos = $pensumDetalles->where('periodo', $periodoActual);
                        $introductorio = false;
                        if ($pensumDetallesPeriodos->count() > 0) {
                            $introductorio = true;
                        } else {
                            $periodoActual = 1;
                            $pensumDetallesPeriodos = $pensumDetalles->where('periodo', $periodoActual);
                            $introductorio = false;
                        }
                        if ($pensumDetallesPeriodos->count() > 0) {
                            foreach ($pensumDetallesPeriodos as $pensumDetallesPeriodo) {
                                $inscripcionMateria = new InscripcionMateria([
                                    'materia_id' => $pensumDetallesPeriodo->materia_id,
                                    'estado' => 'inscrita',
                                    'periodo' => $periodoActual,
                                ]);
                                if ($inscripcion->id) {
                                    $inscripcionMateria->inscripcion_id = $inscripcion->id;
                                    $inscripcionMateria->save();
                                }
                            }
                        }
                        /* if ($periodoActual == 1) {
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
                        } */
                        if (!$historial) {
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
                        }
                    } else {
                        $ultimaInscripcion = Inscripcion::where('estudiante_id', $inscripcion->estudiante_id)
                            ->where('carrera_id', $inscripcion->carrera_id)
                            ->where('sede_id', $inscripcion->sede_id)
                            ->where('id', '!=', $inscripcion->id)
                            ->orderBy('id', 'desc')
                            ->first();
                        $inscripcionMaterias = InscripcionMateria::where('inscripcion_id', $ultimaInscripcion->id)
                            ->orderBy('periodo', 'desc')
                            ->first();
                        $periodoActual = $inscripcionMaterias->periodo + 1;
                        $pensumDetallesPeriodos = $pensumDetalles->where('periodo', $periodoActual);
                        $introductorio = false;
                        if ($pensumDetallesPeriodos->count() > 0) {
                            foreach ($pensumDetallesPeriodos as $pensumDetallesPeriodo) {
                                $inscripcionMateria = new InscripcionMateria([
                                    'materia_id' => $pensumDetallesPeriodo->materia_id,
                                    'estado' => 'inscrita',
                                    'periodo' => $periodoActual,
                                ]);
                                if ($inscripcion->id) {
                                    $inscripcionMateria->inscripcion_id = $inscripcion->id;
                                    $inscripcionMateria->save();
                                }
                            }
                        }
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $inscripcion->delete();
                throw $e;
            }
        });
    }

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function preinscripcion()
    {
        return $this->belongsTo(Preinscripcion::class);
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }

    public function materias()
    {
        return $this->hasMany(InscripcionMateria::class);
    }
}
