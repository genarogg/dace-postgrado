<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Preinscripcion extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'estudiante_id',
        'carrera_id',
        'sede_id',
        'estado',
        'observaciones',
        'numero_referencia_pago',
        'monto_pago',
        'fecha_pago',
        'comprobante_pago',
        'pago_verificado',
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
        'estado',
        'observaciones',
        'numero_referencia_pago',
        'monto_pago',
        'fecha_pago',
        'comprobante_pago',
        'pago_verificado',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($preinscripcion) {
            if (auth()->user()->hasRole('Estudiante')) {
                $preinscripcion->estudiante_id = Estudiante::where('user_id', auth()->id())->first()?->id;
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
}
