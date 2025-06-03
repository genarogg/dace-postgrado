<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class CarreraMateria extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $table = 'carrera_materia';

    protected $fillable = [
        'carrera_id',
        'materia_id',
        'periodo'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'carrera_id',
        'materia_id',
        'periodo'
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}
