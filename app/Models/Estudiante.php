<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Estudiante extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    public $table = 'estudiantes';
    public $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'cedula',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'genero',
        'telefono',
        'direccion',
        'titulo_pregrado',
        'universidad_pregrado',
        'anio_egreso_pregrado',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'anio_egreso_pregrado' => 'integer'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'user_id',
        'cedula',
        'nombres',
        'apellido',
        'fecha_nacimiento',
        'genero',
        'telefono',
        'direccion',
        'titulo_pregrado',
        'universidad_pregrado',
        'anio_egreso_pregrado',
        'activo'
    ];

    public function estudiosRealizados()
    {
        return $this->hasMany(EstudioRealizado::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preinscripciones()
    {
        return $this->hasMany(Preinscripcion::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function historialAcademicos()
    {
        return $this->hasMany(HistorialAcademico::class);
    }
}
