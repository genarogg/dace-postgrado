<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Expediente extends Model implements AuditableContract
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'estudiante_id',
        'observaciones',
        'estado',
        'codigo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($expediente) {
            $estudiante = $expediente->estudiante;
            $preinscripcion = $estudiante->preinscripciones()->latest()->first();
            
            if ($preinscripcion) {
                $sede = $preinscripcion->sede;
                $carrera = $preinscripcion->carrera;
                $año = date('Y');
                
                $ultimoExpediente = static::where('codigo', 'like', "{$sede->codigo}-{$carrera->codigo}-{$año}-%")
                    ->orderByRaw('CAST(SUBSTRING(codigo, -4) AS UNSIGNED) DESC')
                    ->first();
                
                $numero = 1;
                if ($ultimoExpediente) {
                    $partes = explode('-', $ultimoExpediente->codigo);
                    $numero = intval(end($partes)) + 1;
                }
                
                $expediente->codigo = sprintf("%s-%s-%s-%04d", $sede->codigo, $carrera->codigo, $año, $numero);
            }
        });
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class);
    }

    public function documentos(): BelongsToMany
    {
        return $this->belongsToMany(DocumentoRequerido::class, 'expediente_documento')
            ->withPivot('entregado')
            ->withTimestamps();
    }
}