<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class HistorialAcademico extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'estudiante_id',
        'carrera_id',
        'sede_id',
        'periodo_ingreso_id',
        'periodo_egreso_id',
        'pensum_id',
        'estado',
        'observaciones'
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
        'periodo_ingreso_id',
        'periodo_egreso_id',
        'pensum_id',
        'estado',
        'observaciones'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function pensum()
    {
        return $this->belongsTo(Pensum::class);
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }

    public function periodoIngreso()
    {
        return $this->belongsTo(Periodo::class, 'periodo_ingreso_id');
    }

    public function periodoEgreso()
    {
        return $this->belongsTo(Periodo::class, 'periodo_egreso_id');
    }
}
