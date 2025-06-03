<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Profesor extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'direccion',
        'titulo_academico',
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
        'user_id',
        'nombre',
        'apellido',
        'cedula',
        'email',
        'telefono',
        'direccion',
        'titulo_academico',
        'activo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'materia_profesor')
            ->withTimestamps();
    }

    public function inscripcionesMateria()
    {
        return $this->hasMany(InscripcionMateria::class);
    }
}
