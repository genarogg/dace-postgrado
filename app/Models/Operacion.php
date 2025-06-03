<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Operacion extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $table = 'operaciones';

    protected $fillable = [
        'proceso',
        'fecha_desde',
        'fecha_hasta'
    ];

    protected $casts = [
        'fecha_desde' => 'datetime',
        'fecha_hasta' => 'datetime',
    ];

    public function sedes()
    {
        return $this->belongsToMany(Sede::class, 'operacion_sede');
    }

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'operacion_carrera');
    }
}