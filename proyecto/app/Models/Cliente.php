<?php

/**
 * Modelo Cliente
 * 
 * Representa a los clientes de la empresa Nosecaen S.L.
 * Gestiona el acceso a la tabla 'clientes' y sus relaciones con cuotas y tareas.
 * 
 * @author Tu Nombre
 * @version 1.0
 * @since 2026-01-24
 * @category Models
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cif',
        'nombre',
        'telefono',
        'correo',
        'cuenta_corriente',
        'pais',
        'moneda',
        'importe_cuota_mensual',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'importe_cuota_mensual' => 'decimal:2',
    ];

    /**
     * Obtiene las cuotas asociadas a este cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuotas(): HasMany
    {
        return $this->hasMany(Cuota::class);
    }

    /**
     * Obtiene las tareas/incidencias asociadas a este cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Scope para filtrar clientes por país.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $pais
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorPais($query, string $pais)
    {
        return $query->where('pais', $pais);
    }

    /**
     * Scope para filtrar clientes por moneda.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $moneda
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePorMoneda($query, string $moneda)
    {
        return $query->where('moneda', $moneda);
    }

    /**
     * Scope para obtener clientes activos (con cuota mensual mayor a 0).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivos($query)
    {
        return $query->where('importe_cuota_mensual', '>', 0);
    }

    /**
     * Calcula el total de cuotas pendientes de pago.
     *
     * @return float
     */
    public function getTotalCuotasPendientesAttribute(): float
    {
        return $this->cuotas()
            ->where('pagada', 'N')
            ->sum('importe');
    }

    /**
     * Calcula el total de cuotas pagadas.
     *
     * @return float
     */
    public function getTotalCuotasPagadasAttribute(): float
    {
        return $this->cuotas()
            ->where('pagada', 'S')
            ->sum('importe');
    }

    /**
     * Obtiene el nombre formateado con el CIF.
     *
     * @return string
     */
    public function getNombreConCifAttribute(): string
    {
        return "{$this->nombre} ({$this->cif})";
    }

    /**
     * Verifica si el cliente tiene tareas pendientes.
     *
     * @return bool
     */
    public function tieneTareasPendientes(): bool
    {
        return $this->tareas()
            ->where('estado', 'P')
            ->exists();
    }

    /**
     * Obtiene el número total de tareas del cliente.
     *
     * @return int
     */
    public function getTotalTareasAttribute(): int
    {
        return $this->tareas()->count();
    }
}