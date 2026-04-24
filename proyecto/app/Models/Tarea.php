<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'empleado_id',
        'persona_contacto',
        'telefono_contacto',
        'descripcion',
        'correo_contacto',
        'direccion',
        'poblacion',
        'codigo_postal',
        'provincia_ine',
        'estado',
        'fecha_creacion',
        'fecha_realizacion',
        'anotaciones_anteriores',
        'anotaciones_posteriores',
        'fichero_resumen',
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_realizacion' => 'date',
    ];

    /**
     * Obtiene el cliente asociado a esta tarea.
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Obtiene el empleado asignado a esta tarea.
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Obtiene los archivos adjuntos de esta tarea.
     */
    public function archivosAdjuntos(): HasMany
    {
        return $this->hasMany(ArchivoAdjunto::class);
    }

    /**
     * Scope para filtrar tareas por estado.
     */
    public function scopePorEstado($query, string $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para filtrar tareas pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'P');
    }

    /**
     * Scope para filtrar tareas realizadas.
     */
    public function scopeRealizadas($query)
    {
        return $query->where('estado', 'R');
    }

    /**
     * Scope para filtrar tareas completadas.
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'C');
    }

    /**
     * Scope para filtrar tareas anuladas.
     */
    public function scopeAnuladas($query)
    {
        return $query->where('estado', 'A');
    }

    /**
     * Verifica si la tarea está pendiente.
     */
    public function estaPendiente(): bool
    {
        return $this->estado === 'P';
    }

    /**
     * Verifica si la tarea está realizada.
     */
    public function estaRealizada(): bool
    {
        return $this->estado === 'R';
    }

    /**
     * Verifica si la tarea está completada.
     */
    public function estaCompletada(): bool
    {
        return $this->estado === 'C';
    }

    /**
     * Marca la tarea como realizada.
     */
    public function marcarComoRealizada(string $anotaciones = null): bool
    {
        $this->estado = 'R';
        $this->fecha_realizacion = now();
        
        if ($anotaciones) {
            $this->anotaciones_posteriores = $anotaciones;
        }
        
        return $this->save();
    }

    /**
     * Marca la tarea como completada.
     */
    public function marcarComoCompletada(string $anotaciones = null): bool
    {
        $this->estado = 'C';
        $this->fecha_realizacion = now();
        
        if ($anotaciones) {
            $this->anotaciones_posteriores = $anotaciones;
        }
        
        return $this->save();
    }

    /**
     * Obtiene el estado formateado.
     */
    public function getEstadoFormateadoAttribute(): string
    {
        $estados = [
            'P' => 'Pendiente',
            'R' => 'Realizada',
            'C' => 'Completada',
            'A' => 'Anulada',
        ];
        
        return $estados[$this->estado] ?? 'Desconocido';
    }

    /**
     * Verifica si la validación de CP y provincia INE es correcta.
     * Los 2 primeros dígitos del CP deben coincidir con provincia_ine.
     */
    public function validarCodigoPostalProvincia(): bool
    {
        if (!$this->codigo_postal || !$this->provincia_ine) {
            return false;
        }
        
        $primerosDosDigitosCP = substr($this->codigo_postal, 0, 2);
        return $primerosDosDigitosCP === $this->provincia_ine;
    }
}