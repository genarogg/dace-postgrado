<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class InscripcionMateria extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'inscripcion_id',
        'materia_id',
        'profesor_id',
        'periodo',
        'estado',
        'nota',
        'observacion_nota'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'inscripcion_id',
        'materia_id',
        'profesor_id',
        'periodo',
        'estado',
        'nota',
        'observacion_nota'
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function profesor()
    {
        return $this->belongsTo(Profesor::class);
    }

    public function notas()
    {
        return $this->hasMany(Nota::class);
    }
}
