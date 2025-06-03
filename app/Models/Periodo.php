<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Periodo extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'activo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
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
        'fecha_inicio',
        'fecha_fin',
        'modalidad',
        'activo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($periodo) {
            if ($periodo->activo) {
                static::where('modalidad', $periodo->modalidad)
                    ->where('id', '!=', $periodo->id)
                    ->update(['activo' => false]);
            }
        });
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }
}
