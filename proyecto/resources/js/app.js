import './bootstrap';
import { createApp } from 'vue';
import TareaCrud from './components/TareaCrud.vue';

const app = createApp({});

// Registrar componente globalmente
app.component('tarea-crud', TareaCrud);

app.mount('#app');