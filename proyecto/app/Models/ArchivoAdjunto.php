<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoAdjunto extends Model
{
    use HasFactory;
    protected $table = 'archivos_adjuntos';

    protected $fillable = [
        'tarea_id',
        'nombre_archivo',
        'ruta_archivo',
        'tipo_mime',
        'tamano',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'tamano' => 'integer',
    ];

    /**
     * Obtiene la tarea a la que pertenece este archivo.
     */
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Obtiene la ruta completa del archivo.
     */
    public function getRutaCompletaAttribute(): string
    {
        return storage_path('app/' . $this->ruta_archivo);
    }

    /**
     * Obtiene el tamaño formateado en KB/MB.
     */
    public function getTamanoFormateadoAttribute(): string
    {
        $bytes = $this->tamano;
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' B';
    }

    /**
     * Verifica si el archivo existe en el sistema.
     */
    public function existe(): bool
    {
        return file_exists($this->ruta_completa);
    }

    /**
     * Obtiene la extensión del archivo.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->nombre_archivo, PATHINFO_EXTENSION);
    }
}