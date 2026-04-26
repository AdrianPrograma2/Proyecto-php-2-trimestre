@extends('layouts.app')

@section('title', 'Gestión Dinámica de Tareas (3.1)')

{{-- CDNs con @push (porque el layout usa @stack) --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-tasks"></i> Tareas Dinámicas (Problema 3.1)</h2>
        <button class="btn btn-primary" onclick="abrirModalCrear()">
            <i class="fas fa-plus"></i> Nueva Tarea
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="tablaTareas" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Persona</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Crear/Editar -->
<div class="modal fade" id="modalTarea" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Nueva Tarea</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTarea">
                    @csrf
                    <input type="hidden" id="tareaId">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cliente *</label>
                            <select class="form-select" id="cliente_id" required>
                                <option value="">Seleccione...</option>
                                @foreach(\App\Models\Cliente::all() as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-cliente_id"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Operario *</label>
                            <select class="form-select" id="empleado_id" required>
                                <option value="">Seleccione...</option>
                                @foreach(\App\Models\Empleado::where('tipo', 'operario')->get() as $e)
                                    <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-empleado_id"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Persona Contacto *</label>
                            <input type="text" class="form-control" id="persona_contacto" required>
                            <div class="invalid-feedback" id="error-persona_contacto"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono Contacto *</label>
                            <input type="text" class="form-control" id="telefono_contacto" required>
                            <div class="invalid-feedback" id="error-telefono_contacto"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea class="form-control" id="descripcion" rows="2" required></textarea>
                        <div class="invalid-feedback" id="error-descripcion"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Correo *</label>
                            <input type="email" class="form-control" id="correo_contacto" required>
                            <div class="invalid-feedback" id="error-correo_contacto"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estado">
                                <option value="P">Pendiente</option>
                                <option value="R">Realizada</option>
                                <option value="C">Cancelada</option>
                                <option value="A">Anulada</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label">Dirección *</label>
                            <input type="text" class="form-control" id="direccion" required>
                            <div class="invalid-feedback" id="error-direccion"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Población *</label>
                            <input type="text" class="form-control" id="poblacion" required>
                            <div class="invalid-feedback" id="error-poblacion"></div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">CP *</label>
                            <input type="text" class="form-control" id="codigo_postal" maxlength="5" required>
                            <div class="invalid-feedback" id="error-codigo_postal"></div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Prov (INE) *</label>
                            <select class="form-select" id="provincia_ine" required>
                                <option value="21">Huelva (21)</option>
                                <option value="41">Sevilla (41)</option>
                                <option value="29">Málaga (29)</option>
                            </select>
                            <div class="invalid-feedback" id="error-provincia_ine"></div>
                        </div>
                    </div>
                    
                    <div id="alertaServidor" class="alert alert-danger d-none" role="alert"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarTarea()">Guardar</button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts con @push (NO @section) --}}
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let tabla;
let modalTarea;

$(document).ready(function() {
    modalTarea = new bootstrap.Modal(document.getElementById('modalTarea'));
    
    // Inicializar DataTable con AJAX - CORREGIDO: data: antes de cada campo
    tabla = $('#tablaTareas').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("tareas.data") }}',
        columns: [
            { data: 'id' },
            { data: 'cliente' },
            { data: 'persona' },
            { data: 'descripcion' },
            { data: 'estado' },
            { data: 'fecha' },
            { 
                data: 'acciones',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-warning me-1" onclick="abrirModalEditar(${row.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarTarea(${row.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
    });
});

// FUNCIONES GLOBALES (fuera de document.ready)

function abrirModalCrear() {
    limpiarFormulario();
    document.getElementById('modalTitulo').textContent = 'Nueva Tarea';
    document.getElementById('tareaId').value = '';
    modalTarea.show();
}

function abrirModalEditar(id) {
    limpiarFormulario();
    document.getElementById('modalTitulo').textContent = 'Editar Tarea';
    document.getElementById('tareaId').value = id;
    
    // Cargar datos del servidor
    fetch('{{ route("tareas.data") }}?draw=1&start=0&length=-1')
        .then(res => res.json())
        .then(data => {
            const tarea = data.data?.find(t => t.id == id);
            if(tarea) {
                document.getElementById('persona_contacto').value = tarea.persona || '';
                document.getElementById('telefono_contacto').value = tarea.persona || '';
                document.getElementById('descripcion').value = tarea.descripcion || '';
                document.getElementById('estado').value = tarea.estado || 'P';
                modalTarea.show();
            }
        });
}

function guardarTarea() {
    if (!validarCamposJS()) return;

    const id = document.getElementById('tareaId').value;
    const url = id ? '/tareas/ajax/' + id : '{{ route("tareas.storeAjax") }}';
    const method = id ? 'PUT' : 'POST';

    const datos = {
        _token: csrfToken,
        _method: method,
        cliente_id: document.getElementById('cliente_id').value,
        empleado_id: document.getElementById('empleado_id').value,
        persona_contacto: document.getElementById('persona_contacto').value,
        telefono_contacto: document.getElementById('telefono_contacto').value,
        descripcion: document.getElementById('descripcion').value,
        correo_contacto: document.getElementById('correo_contacto').value,
        direccion: document.getElementById('direccion').value,
        poblacion: document.getElementById('poblacion').value,
        codigo_postal: document.getElementById('codigo_postal').value,
        provincia_ine: document.getElementById('provincia_ine').value,
        estado: document.getElementById('estado').value
    };

    fetch(url, {
        method: 'POST',
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-HTTP-Method-Override': method
        },
        body: JSON.stringify(datos)
    })
    .then(async response => {
        if (!response.ok) {
            const errorData = await response.json();
            throw errorData;
        }
        return response.json();
    })
    .then(data => {
        modalTarea.hide();
        tabla.ajax.reload(null, false);
        limpiarFormulario();
        Swal.fire('¡Éxito!', 'Tarea guardada correctamente', 'success');
    })
    .catch(error => {
        if (error.errors) {
            mostrarErroresServidor(error.errors);
        } else {
            Swal.fire('Error', 'Hubo un problema al guardar', 'error');
        }
    });
}

function eliminarTarea(id) {
    Swal.fire({
        title: '¿Eliminar tarea?',
        text: "No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/tareas/ajax/' + id, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(() => {
                tabla.ajax.reload(null, false);
                Swal.fire('Eliminada', 'Tarea eliminada correctamente', 'success');
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo eliminar', 'error');
            });
        }
    });
}

// Validación JS
function validarCamposJS() {
    let valido = true;
    limpiarErrores();

    if (!document.getElementById('cliente_id').value) {
        marcarError('cliente_id', 'Seleccione un cliente'); 
        valido = false;
    }
    if (!document.getElementById('empleado_id').value) {
        marcarError('empleado_id', 'Seleccione un operario'); 
        valido = false;
    }
    if (!document.getElementById('persona_contacto').value.trim()) {
        marcarError('persona_contacto', 'Obligatorio'); 
        valido = false;
    }
    if (!document.getElementById('telefono_contacto').value.trim()) {
        marcarError('telefono_contacto', 'Obligatorio'); 
        valido = false;
    }
    if (!document.getElementById('descripcion').value.trim()) {
        marcarError('descripcion', 'Obligatorio'); 
        valido = false;
    }
    if (!document.getElementById('correo_contacto').value.trim()) {
        marcarError('correo_contacto', 'Obligatorio'); 
        valido = false;
    }
    if (!document.getElementById('codigo_postal').value.match(/^\d{5}$/)) {
        marcarError('codigo_postal', 'CP: 5 dígitos'); 
        valido = false;
    }

    return valido;
}

function marcarError(campo, msg) {
    const el = document.getElementById(campo);
    el.classList.add('is-invalid');
    const err = document.getElementById(`error-${campo}`);
    if(err) err.textContent = msg;
}

function limpiarErrores() {
    document.querySelectorAll('.is-invalid').forEach(e => e.classList.remove('is-invalid'));
    document.querySelectorAll('[id^="error-"]').forEach(e => e.textContent = '');
    document.getElementById('alertaServidor')?.classList.add('d-none');
}

function mostrarErroresServidor(errors) {
    let html = '<ul class="mb-0">';
    Object.values(errors).flat().forEach(msg => html += `<li>${msg}</li>`);
    html += '</ul>';
    const box = document.getElementById('alertaServidor');
    box.innerHTML = html;
    box.classList.remove('d-none');
}

function limpiarFormulario() {
    document.getElementById('formTarea')?.reset();
    limpiarErrores();
}
</script>
@endpush