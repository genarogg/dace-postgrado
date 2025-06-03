<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;


class Pensum extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'carrera_id',
        'codigo',
        'numero_resolucion',
        'activo',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($pensum) {
            if ($pensum->activo) {
                static::where('carrera_id', $pensum->carrera_id)
                    ->where('id', '!=', $pensum->id)
                    ->update(['activo' => false]);
            }
        });
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(PensumDetalle::class);
    }

    public function lineas(): HasMany
    {
        return $this->hasMany(LineaInvestigacion::class);
    }

    public function electivas(): HasMany
    {
        return $this->hasMany(MateriaElectiva::class);
    }
}