@extends('layouts.app')

@section('title', 'Tareas con Vue.js (3.2)')

@push('styles')
<style>
    .invalid-feedback { display: block; }
    .is-invalid { border-color: #dc3545 !important; }
</style>
@endpush

@section('content')
<div class="container mt-4" id="app">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-tasks text-primary"></i> Tareas con Vue.js (Problema 3.2)</h2>
        <button @click="abrirModalCrear" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-2"></i>Nueva Tarea
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Cliente</th>
                        <th>Persona</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="tarea in tareas" :key="tarea.id">
                        <td class="ps-4">@{{ tarea.id }}</td>
                        <td>@{{ tarea.cliente }}</td>
                        <td>@{{ tarea.persona }}</td>
                        <td>@{{ tarea.descripcion }}</td>
                        <td>
                            <span class="badge" :class="tarea.estado === 'P' ? 'bg-warning text-dark' : 'bg-success'">
                                @{{ tarea.estado }}
                            </span>
                        </td>
                        <td>
                            <button @click="abrirModalEditar(tarea)" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="eliminarTarea(tarea.id)" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="tareas.length === 0">
                        <td colspan="6" class="text-center py-4 text-muted">No hay tareas registradas.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Formulario -->
    <div v-if="mostrarModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">@{{ formulario.id ? 'Editar Tarea' : 'Nueva Tarea' }}</h5>
                    <button type="button" class="btn-close btn-close-white" @click="cerrarModal"></button>
                </div>
                <div class="modal-body">
                    
                    <!-- Alerta genérica de error -->
                    <div v-if="errores.length > 0" class="alert alert-danger py-2 mb-3">
                        <ul class="mb-0 small">
                            <li v-for="error in errores" :key="error">@{{ error }}</li>
                        </ul>
                    </div>

                    <form @submit.prevent="guardarTarea" novalidate>
                        <div class="row g-3">
                            <!-- Cliente -->
                            <div class="col-md-6">
                                <label class="form-label">Cliente *</label>
                                <select v-model="formulario.cliente_id" class="form-select" :class="{ 'is-invalid': fieldErrors.cliente_id }" required>
                                    <option value="">Seleccione...</option>
                                    <option v-for="c in clientes" :key="c.id" :value="c.id">@{{ c.nombre }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.cliente_id ? fieldErrors.cliente_id[0] : '' }}
                                </div>
                            </div>
                            
                            <!-- Operario -->
                            <div class="col-md-6">
                                <label class="form-label">Operario *</label>
                                <select v-model="formulario.empleado_id" class="form-select" :class="{ 'is-invalid': fieldErrors.empleado_id }" required>
                                    <option value="">Seleccione...</option>
                                    <option v-for="o in operarios" :key="o.id" :value="o.id">@{{ o.nombre }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.empleado_id ? fieldErrors.empleado_id[0] : '' }}
                                </div>
                            </div>

                            <!-- Persona Contacto -->
                            <div class="col-md-6">
                                <label class="form-label">Persona Contacto *</label>
                                <input type="text" v-model="formulario.persona_contacto" class="form-control" :class="{ 'is-invalid': fieldErrors.persona_contacto }" required>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.persona_contacto ? fieldErrors.persona_contacto[0] : '' }}
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div class="col-md-6">
                                <label class="form-label">Teléfono *</label>
                                <input type="text" v-model="formulario.telefono_contacto" class="form-control" :class="{ 'is-invalid': fieldErrors.telefono_contacto }" required>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.telefono_contacto ? fieldErrors.telefono_contacto[0] : '' }}
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="col-12">
                                <label class="form-label">Descripción *</label>
                                <textarea v-model="formulario.descripcion" class="form-control" rows="3" :class="{ 'is-invalid': fieldErrors.descripcion }" required></textarea>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.descripcion ? fieldErrors.descripcion[0] : '' }}
                                </div>
                            </div>

                            <!-- Correo -->
                            <div class="col-md-6">
                                <label class="form-label">Correo *</label>
                                <input type="email" v-model="formulario.correo_contacto" class="form-control" :class="{ 'is-invalid': fieldErrors.correo_contacto }" required>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.correo_contacto ? fieldErrors.correo_contacto[0] : '' }}
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div class="col-md-8">
                                <label class="form-label">Dirección *</label>
                                <input type="text" v-model="formulario.direccion" class="form-control" :class="{ 'is-invalid': fieldErrors.direccion }" required>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.direccion ? fieldErrors.direccion[0] : '' }}
                                </div>
                            </div>

                            <!-- Población -->
                            <div class="col-md-4">
                                <label class="form-label">Población *</label>
                                <input type="text" v-model="formulario.poblacion" class="form-control" :class="{ 'is-invalid': fieldErrors.poblacion }" required>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.poblacion ? fieldErrors.poblacion[0] : '' }}
                                </div>
                            </div>

                            <!-- CP -->
                            <div class="col-md-3">
                                <label class="form-label">CP *</label>
                                <input type="text" v-model="formulario.codigo_postal" class="form-control" maxlength="5" :class="{ 'is-invalid': fieldErrors.codigo_postal }" required>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.codigo_postal ? fieldErrors.codigo_postal[0] : '' }}
                                </div>
                            </div>
                            
                            <!-- Provincia -->
                            <div class="col-md-3">
                                <label class="form-label">Provincia *</label>
                                <select v-model="formulario.provincia_ine" class="form-select" :class="{ 'is-invalid': fieldErrors.provincia_ine }" required>
                                    <option value="21">Huelva (21)</option>
                                    <option value="41">Sevilla (41)</option>
                                    <option value="29">Málaga (29)</option>
                                </select>
                                <div class="invalid-feedback">
                                    @{{ fieldErrors.provincia_ine ? fieldErrors.provincia_ine[0] : '' }}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal">Cancelar</button>
                    <button type="button" class="btn btn-primary px-4" @click="guardarTarea" :disabled="cargando">
                        <span v-if="cargando" class="spinner-border spinner-border-sm me-2"></span>
                        @{{ formulario.id ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
        const { createApp, ref, reactive, onMounted } = Vue;

        createApp({
            setup() {
                const tareas = ref([]);
                const clientes = ref([]);
                const operarios = ref([]);
                const mostrarModal = ref(false);
                const cargando = ref(false);
                const errores = ref([]);
                const fieldErrors = ref({});

                const formulario = reactive({
                    id: null,
                    cliente_id: '',
                    empleado_id: '',
                    persona_contacto: '',
                    telefono_contacto: '',
                    descripcion: '',
                    correo_contacto: '',
                    direccion: '',
                    poblacion: '',
                    codigo_postal: '',
                    provincia_ine: '21'
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                const cargarDatos = async () => {
                    try {
                        const [tRes, cRes, oRes] = await Promise.all([
                            axios.get('/tareas/data'),
                            axios.get('/api/clientes'),
                            axios.get('/api/operarios')
                        ]);
                        tareas.value = tRes.data.data;
                        clientes.value = cRes.data;
                        operarios.value = oRes.data;
                    } catch (error) {
                        console.error('Error cargando datos:', error);
                    }
                };

                const limpiarErrores = () => {
                    errores.value = [];
                    fieldErrors.value = {};
                };

                const abrirModalCrear = () => {
                    limpiarErrores();
                    Object.assign(formulario, {
                        id: null, cliente_id: '', empleado_id: '', persona_contacto: '',
                        telefono_contacto: '', descripcion: '', correo_contacto: '',
                        direccion: '', poblacion: '', codigo_postal: '', provincia_ine: '21'
                    });
                    mostrarModal.value = true;
                };

                const abrirModalEditar = (tarea) => {
                    limpiarErrores();
                    Object.assign(formulario, {
                        id: tarea.id,
                        cliente_id: tarea.cliente_id || '',
                        empleado_id: tarea.empleado_id || '',
                        persona_contacto: tarea.persona || '',
                        telefono_contacto: tarea.telefono_contacto || '',
                        descripcion: tarea.descripcion,
                        correo_contacto: tarea.correo_contacto || '',
                        direccion: tarea.direccion || '',
                        poblacion: tarea.poblacion || '',
                        codigo_postal: tarea.codigo_postal || '',
                        provincia_ine: tarea.provincia_ine || '21'
                    });
                    mostrarModal.value = true;
                };

                const cerrarModal = () => {
                    mostrarModal.value = false;
                };

                const guardarTarea = async () => {
                    limpiarErrores();
                    cargando.value = true;

                    const url = formulario.id ? `/tareas/ajax/${formulario.id}` : '/tareas/ajax';
                    const method = formulario.id ? 'PUT' : 'POST';

                    try {
                        await axios({
                            method: 'POST',
                            url: url,
                            data: {
                                ...formulario,
                                _token: csrfToken,
                                _method: method
                            }
                        });
                        cerrarModal();
                        cargarDatos();
                    } catch (error) {
                        if (error.response && error.response.status === 422) {
                            fieldErrors.value = error.response.data.errors;
                        } else {
                            errores.value = ['Error inesperado al guardar.'];
                        }
                    } finally {
                        cargando.value = false;
                    }
                };

                const eliminarTarea = async (id) => {
                    if (!confirm('¿Estás seguro de eliminar esta tarea?')) return;
                    try {
                        await axios.delete(`/tareas/ajax/${id}`, { data: { _token: csrfToken } });
                        cargarDatos();
                    } catch (error) {
                        alert('Error al eliminar');
                    }
                };

                onMounted(() => {
                    cargarDatos();
                });

                return {
                    tareas, clientes, operarios, formulario, mostrarModal, cargando, errores, fieldErrors,
                    abrirModalCrear, abrirModalEditar, cerrarModal, guardarTarea, eliminarTarea
                };
            }
        }).mount('#app');
    </script>
@endpush