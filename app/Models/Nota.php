<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Nota extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'inscripcion_materia_id',
        'nota',
        'observaciones'
    ];

    protected $casts = [
        'nota' => 'decimal:2'
    ];

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored'
    ];

    protected $auditInclude = [
        'inscripcion_materia_id',
        'nota',
        'observaciones'
    ];

    public function inscripcionMateria()
    {
        return $this->belongsTo(InscripcionMateria::class);
    }
}
