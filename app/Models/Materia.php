<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Materia extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'carrera_id',
        'periodo',
        'nombre',
        'codigo',
        'descripcion',
        'creditos',
        'horas_teoricas',
        'horas_practicas',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'creditos' => 'integer',
        'horas_teoricas' => 'integer',
        'horas_practicas' => 'integer',
        'periodo' => 'integer'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'carrera_id',
        'periodo',
        'nombre',
        'codigo',
        'descripcion',
        'creditos',
        'horas_teoricas',
        'horas_practicas',
        'activo'
    ];

    /* public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'carrera_materia')
            ->withPivot('periodo')
            ->withTimestamps();
    } */
    public function carreras()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id');
    }

    public function prerrequisitos()
    {
        return $this->belongsToMany(Materia::class, 'materia_prerrequisito', 'materia_id', 'prerrequisito_id')
            ->withTimestamps();
    }

    public function esPrerrequisitoDe()
    {
        return $this->belongsToMany(Materia::class, 'materia_prerrequisito', 'prerrequisito_id', 'materia_id')
            ->withTimestamps();
    }

    public function profesores()
    {
        return $this->belongsToMany(Profesor::class, 'materia_profesor')
            ->withTimestamps();
    }

    public function inscripcionesMateria()
    {
        return $this->hasMany(InscripcionMateria::class);
    }
}
