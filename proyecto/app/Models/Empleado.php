<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dni',
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'fecha_alta',
        'tipo',
        'password',
    ];

    /**
     * Los atributos que deben ser ocultados al serializar.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_alta' => 'date',
        'password' => 'hashed',
    ];

    /**
     * Obtiene las tareas asignadas a este empleado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'empleado_id');
    }

    /**
     * Verifica si el empleado es administrador.
     *
     * @return bool
     */
    public function esAdministrador(): bool
    {
        return $this->tipo === 'admin';
    }

    /**
     * Verifica si el empleado es operario.
     *
     * @return bool
     */
    public function esOperario(): bool
    {
        return $this->tipo === 'operario';
    }

    /**
     * Scope para filtrar solo administradores.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdministradores($query)
    {
        return $query->where('tipo', 'admin');
    }

    /**
     * Scope para filtrar solo operarios.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOperarios($query)
    {
        return $query->where('tipo', 'operario');
    }

    /**
     * Obtiene el nombre completo formateado.
     *
     * @return string
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->nombre;
    }
}