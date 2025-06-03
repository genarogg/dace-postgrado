<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Carrera extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'modalidad',
        'creditos_totales',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'nombre',
        'codigo',
        'descripcion',
        'duracion_anios',
        'creditos_totales',
        'activo'
    ];

    /* public function materias()
    {
        return $this->belongsToMany(Materia::class, 'carrera_materia')
            ->withPivot('periodo')
            ->withTimestamps();
    } */
    public function materias()
    {
        return $this->hasMany(Materia::class);
    }

    public function sedes()
    {
        return $this->belongsToMany(Sede::class, 'carrera_sede')
            ->withPivot('activo')
            ->withTimestamps();
    }

    public function preinscripciones()
    {
        return $this->hasMany(Preinscripcion::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function pensums()
    {
        return $this->hasMany(Pensum::class);
    }
}
