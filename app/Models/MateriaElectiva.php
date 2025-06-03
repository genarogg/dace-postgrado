<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class MateriaElectiva extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $table = 'materia_electivas';

    protected $fillable = [
        'pensum_id',
        'nombre',
        'codigo',
        'creditos',
        'horas_teoricas',
        'horas_practicas',
        'activo',
    ];

    protected $casts = [
        'creditos' => 'integer',
        'horas_teoricas' => 'integer',
        'horas_practicas' => 'integer',
        'activo' => 'boolean',
    ];

    public function pensum(): BelongsTo
    {
        return $this->belongsTo(Pensum::class);
    }
}