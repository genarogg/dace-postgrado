<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class LineaInvestigacion extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $table = 'linea_investigaciones';

    protected $fillable = [
        'pensum_id',
        'nombre',
        'coordinador',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function pensum(): BelongsTo
    {
        return $this->belongsTo(Pensum::class);
    }
}