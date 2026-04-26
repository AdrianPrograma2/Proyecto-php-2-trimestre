<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->nullOnDelete();
            $table->string('persona_contacto');
            $table->string('telefono_contacto');
            $table->text('descripcion');
            $table->string('correo_contacto');
            $table->string('direccion');
            $table->string('poblacion');
            $table->string('codigo_postal', 5);
            $table->string('provincia_ine', 2);
            $table->enum('estado', ['P', 'R', 'C', 'A'])->default('P'); // Pendiente, Realizada, Completada, Anulada
            $table->timestamp('fecha_creacion');
            $table->timestamp('fecha_realizacion')->nullable();
            $table->text('anotaciones_anteriores')->nullable();
            $table->text('anotaciones_posteriores')->nullable();
            $table->string('fichero_resumen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};