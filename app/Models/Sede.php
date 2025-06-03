<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Sede extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'estado_id',
        'nombre',
        'codigo',
        'direccion',
        'telefono',
        'descripcion',
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
        'estado_id',
        'nombre',
        'codigo',
        'direccion',
        'telefono',
        'descripcion',
        'activo'
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'carrera_sede')
            ->withPivot('activo')
            ->withTimestamps();
    }
}
