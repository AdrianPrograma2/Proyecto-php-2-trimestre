<template>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-bolt text-warning"></i> Tareas Vue 3 + Vite (Problema 3.3)</h2>
            <button @click="abrirModal" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>Nueva Tarea
            </button>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div v-if="cargando" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                
                <table v-else class="table table-hover mb-0">
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
                            <td class="ps-4">{{ tarea.id }}</td>
                            <td>{{ tarea.cliente_nombre }}</td>
                            <td>{{ tarea.persona_contacto }}</td>
                            <td>{{ tarea.descripcion }}</td>
                            <td>
                                <span class="badge" :class="getEstadoBadge(tarea.estado)">
                                    {{ tarea.estado }}
                                </span>
                            </td>
                            <td>
                                <button @click="editarTarea(tarea)" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="eliminarTarea(tarea.id)" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="mostrarModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">{{ esEdicion ? 'Editar Tarea' : 'Nueva Tarea' }}</h5>
                        <button type="button" class="btn-close btn-close-white" @click="cerrarModal"></button>
                    </div>
                    <div class="modal-body">
                        
                        <!-- Errores genéricos -->
                        <div v-if="errores.length > 0" class="alert alert-danger py-2 mb-3">
                            <ul class="mb-0 small">
                                <li v-for="error in errores" :key="error">{{ error }}</li>
                            </ul>
                        </div>

                        <form @submit.prevent="guardarTarea">
                            <div class="row g-3">
                                <!-- Cliente -->
                                <div class="col-md-6">
                                    <label class="form-label">Cliente *</label>
                                    <select v-model="form.cliente_id" class="form-select" required
                                        :class="{ 'is-invalid': fieldErrors.cliente_id }">
                                        <option value="">Seleccione...</option>
                                        <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.cliente_id ? fieldErrors.cliente_id[0] : '' }}
                                    </div>
                                </div>

                                <!-- Operario -->
                                <div class="col-md-6">
                                    <label class="form-label">Operario *</label>
                                    <select v-model="form.empleado_id" class="form-select" required
                                        :class="{ 'is-invalid': fieldErrors.empleado_id }">
                                        <option value="">Seleccione...</option>
                                        <option v-for="o in operarios" :key="o.id" :value="o.id">{{ o.nombre }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.empleado_id ? fieldErrors.empleado_id[0] : '' }}
                                    </div>
                                </div>

                                <!-- Persona Contacto -->
                                <div class="col-md-6">
                                    <label class="form-label">Persona Contacto *</label>
                                    <input type="text" v-model="form.persona_contacto" class="form-control" required
                                        :class="{ 'is-invalid': fieldErrors.persona_contacto }">
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.persona_contacto ? fieldErrors.persona_contacto[0] : '' }}
                                    </div>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6">
                                    <label class="form-label">Teléfono *</label>
                                    <input type="text" v-model="form.telefono_contacto" class="form-control" required
                                        :class="{ 'is-invalid': fieldErrors.telefono_contacto }">
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.telefono_contacto ? fieldErrors.telefono_contacto[0] : '' }}
                                    </div>
                                </div>

                                <!-- Descripción -->
                                <div class="col-12">
                                    <label class="form-label">Descripción *</label>
                                    <textarea v-model="form.descripcion" class="form-control" rows="3" required
                                        :class="{ 'is-invalid': fieldErrors.descripcion }"></textarea>
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.descripcion ? fieldErrors.descripcion[0] : '' }}
                                    </div>
                                </div>

                                <!-- Correo -->
                                <div class="col-md-6">
                                    <label class="form-label">Correo *</label>
                                    <input type="email" v-model="form.correo_contacto" class="form-control" required
                                        :class="{ 'is-invalid': fieldErrors.correo_contacto }">
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.correo_contacto ? fieldErrors.correo_contacto[0] : '' }}
                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div class="col-md-8">
                                    <label class="form-label">Dirección *</label>
                                    <input type="text" v-model="form.direccion" class="form-control" required
                                        :class="{ 'is-invalid': fieldErrors.direccion }">
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.direccion ? fieldErrors.direccion[0] : '' }}
                                    </div>
                                </div>

                                <!-- Población -->
                                <div class="col-md-4">
                                    <label class="form-label">Población *</label>
                                    <input type="text" v-model="form.poblacion" class="form-control" required
                                        :class="{ 'is-invalid': fieldErrors.poblacion }">
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.poblacion ? fieldErrors.poblacion[0] : '' }}
                                    </div>
                                </div>

                                <!-- CP y Provincia -->
                                <div class="col-md-3">
                                    <label class="form-label">CP *</label>
                                    <input type="text" v-model="form.codigo_postal" class="form-control" maxlength="5" required
                                        :class="{ 'is-invalid': fieldErrors.codigo_postal }">
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.codigo_postal ? fieldErrors.codigo_postal[0] : '' }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Provincia *</label>
                                    <select v-model="form.provincia_ine" class="form-select" required
                                        :class="{ 'is-invalid': fieldErrors.provincia_ine }">
                                        <option value="21">Huelva (21)</option>
                                        <option value="41">Sevilla (41)</option>
                                        <option value="29">Málaga (29)</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ fieldErrors.provincia_ine ? fieldErrors.provincia_ine[0] : '' }}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal">Cancelar</button>
                        <button type="button" class="btn btn-primary px-4" @click="guardarTarea" :disabled="guardando">
                            <span v-if="guardando" class="spinner-border spinner-border-sm me-2"></span>
                            {{ guardando ? 'Guardando...' : (esEdicion ? 'Actualizar' : 'Guardar') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

// Estado
const tareas = ref([]);
const clientes = ref([]);
const operarios = ref([]);
const cargando = ref(false);
const guardando = ref(false);
const mostrarModal = ref(false);
const esEdicion = ref(false);
const errores = ref([]);

// Errores campo por campo
const fieldErrors = ref({});

// Formulario
const form = reactive({
    id: null,
    cliente_id: '',
    empleado_id: '',
    persona_contacto: '',
    telefono_contacto: '',
    descripcion: '',
    correo_contacto: '',
    estado: 'P',
    direccion: '',
    poblacion: '',
    codigo_postal: '',
    provincia_ine: '21'
});

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// Métodos
const limpiarErrores = () => {
    errores.value = [];
    fieldErrors.value = {};
};

const cargarDatos = async () => {
    cargando.value = true;
    try {
        const [tRes, cRes, oRes] = await Promise.all([
            axios.get('/api/tareas'),
            axios.get('/api/clientes'),
            axios.get('/api/operarios')
        ]);
        tareas.value = tRes.data;
        clientes.value = cRes.data;
        operarios.value = oRes.data;
    } catch (error) {
        console.error('Error cargando datos:', error);
    } finally {
        cargando.value = false;
    }
};

const abrirModal = () => {
    limpiarErrores();
    Object.assign(form, {
        id: null, cliente_id: '', empleado_id: '', persona_contacto: '',
        telefono_contacto: '', descripcion: '', correo_contacto: '',
        estado: 'P', direccion: '', poblacion: '', codigo_postal: '', provincia_ine: '21'
    });
    esEdicion.value = false;
    mostrarModal.value = true;
};

const editarTarea = (tarea) => {
    limpiarErrores();
    Object.assign(form, {
        id: tarea.id,
        cliente_id: tarea.cliente_id,
        empleado_id: tarea.empleado_id,
        persona_contacto: tarea.persona_contacto,
        telefono_contacto: tarea.telefono_contacto,
        descripcion: tarea.descripcion,
        correo_contacto: tarea.correo_contacto,
        estado: tarea.estado,
        direccion: tarea.direccion,
        poblacion: tarea.poblacion,
        codigo_postal: tarea.codigo_postal,
        provincia_ine: tarea.provincia_ine
    });
    esEdicion.value = true;
    mostrarModal.value = true;
};

const cerrarModal = () => {
    mostrarModal.value = false;
};

const guardarTarea = async () => {
    limpiarErrores();
    guardando.value = true;

    const url = esEdicion.value ? `/tareas/ajax/${form.id}` : '/tareas/ajax';
    const method = esEdicion.value ? 'PUT' : 'POST';

    try {
        await axios({
            method: 'POST',
            url: url,
            data: {
                ...form,
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
        guardando.value = false;
    }
};

const eliminarTarea = async (id) => {
    if (!confirm('¿Seguro que deseas eliminar?')) return;
    try {
        await axios.delete(`/tareas/ajax/${id}`, { data: { _token: csrfToken } });
        cargarDatos();
    } catch (error) {
        alert('Error al eliminar');
    }
};

const getEstadoBadge = (estado) => {
    const map = { 'P': 'bg-warning text-dark', 'R': 'bg-success', 'C': 'bg-secondary', 'A': 'bg-danger' };
    return map[estado] || 'bg-secondary';
};

onMounted(() => {
    cargarDatos();
});
</script>

<style scoped>
.modal { display: block; }
.modal-backdrop { display: none; }
</style>