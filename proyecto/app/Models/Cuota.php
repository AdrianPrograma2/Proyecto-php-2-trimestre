<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'concepto',
        'fecha_emision',
        'importe',
        'pagada',
        'fecha_pago',
        'notas',
        'importe_euros',
        'tasa_cambio',
        'fecha_conversion',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_pago' => 'date',
        'fecha_conversion' => 'date',
        'importe' => 'decimal:2',
        'importe_euros' => 'decimal:2',
        'tasa_cambio' => 'decimal:6',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
}