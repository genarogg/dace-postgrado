<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class EstudioRealizado extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $table = 'estudios_realizados';

    protected $fillable = [
        'estudiante_id',
        'titulo_obtenido',
        'instituto',
        'anio_graduacion',
    ];

    protected $casts = [
        'anio_graduacion' => 'integer',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class);
    }
}