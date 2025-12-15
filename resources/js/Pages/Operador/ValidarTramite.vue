<script setup>
import { Head, useForm } from '@inertiajs/vue3';
// Asumiendo que tienes un componente Layout
// import OperadorLayout from '@/Layouts/OperadorLayout.vue';
//import { router } from '@inertiajs/vue3';

const props = defineProps({
    tramite: Object,
});

// --- Lógica del Formulario de Validación/Acción ---
const form = useForm({
    comentario: '',
    estado_accion: 'APROBAR', // Opciones: APROBAR, DENEGAR, DEVOLVER
    // Si manejas la clasificación aquí, necesitarías más campos
});

const submitAccion = () => {
    // Definir la ruta de acción (asumiendo que POST o PUT va a una ruta de acción)
    // NECESITARÁS UNA RUTA NUEVA: route('operador.tramites.accion', props.tramite.id)
    console.log('Acción de validación enviada.');

    // EJEMPLO de envío:
    // form.post(route('operador.tramites.accion', props.tramite.id), {
    //     preserveScroll: true,
    // });
};

// Lógica para el badge de estado
const estadoBadgeClass = (estadoNombre) => {
    // PENDIENTE, EN_VALIDACION, APROBADO, DENEGADO
    switch (estadoNombre) {
        case 'APROBADO':
            return 'bg-green-100 text-green-800 border-green-300';
        case 'DENEGADO':
            return 'bg-red-100 text-red-800 border-red-300';
        case 'EN_VALIDACION':
            return 'bg-blue-100 text-blue-800 border-blue-300';
        case 'PENDIENTE':
            return 'bg-yellow-100 text-yellow-800 border-yellow-300';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-300';
    }
};

const estadoDocumentoClass = (estado) => {
    if (estado === 'APROBADO') return 'text-green-600';
    if (estado === 'RECHAZADO') return 'text-red-600';
    return 'text-gray-500';
};
</script>

<template>
    <Head :title="'Validar Trámite ' + tramite.codigo" />

    <div class="mx-auto max-w-7xl p-6">
        <a
            :href="route('operador.tramites.pendientes')"
            class="mb-4 inline-block text-sm text-blue-600 hover:underline"
        >
            ← Volver a Trámites Pendientes
        </a>

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">
                Revisión: Trámite {{ tramite.codigo }}
            </h1>
            <span
                :class="estadoBadgeClass(tramite.estado_actual.nombre)"
                class="rounded-full border px-4 py-1 text-sm font-bold shadow-sm"
            >
                {{ tramite.estado_actual.nombre.replace('_', ' ') }}
            </span>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-lg bg-white p-6 shadow-lg">
                    <h2
                        class="mb-4 border-b pb-2 text-xl font-semibold text-gray-800"
                    >
                        📂 Documentos Adjuntos
                    </h2>
                    <ul class="divide-y divide-gray-200">
                        <li
                            v-for="doc in tramite.documentos"
                            :key="doc.id"
                            class="flex items-center justify-between py-3"
                        >
                            <div>
                                <div class="font-medium text-gray-700">
                                    {{ doc.nombre }}
                                </div>
                                <div
                                    class="text-xs font-semibold"
                                    :class="
                                        estadoDocumentoClass(
                                            doc.estado_validacion,
                                        )
                                    "
                                >
                                    Estado: {{ doc.estado_validacion }}
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a
                                    :href="doc.url_archivo"
                                    target="_blank"
                                    class="text-sm text-blue-600 hover:text-blue-800"
                                >
                                    Ver Archivo
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-lg">
                    <h2
                        class="mb-4 border-b pb-2 text-xl font-semibold text-gray-800"
                    >
                        📜 Historial de Revisiones
                    </h2>
                    <ul
                        v-if="tramite.historial && tramite.historial.length"
                        class="space-y-4"
                    >
                        <li
                            v-for="h in tramite.historial"
                            :key="h.id"
                            class="border-l-4 border-gray-300 pl-4"
                        >
                            <p class="text-sm font-medium text-gray-900">
                                {{ h.revisador.nombres }} -
                                <span class="text-xs text-gray-500">{{
                                    new Date(h.created_at).toLocaleString()
                                }}</span>
                            </p>
                            <p class="mt-1 text-xs text-gray-700">
                                Comentario:
                                {{ h.comentario || 'Sin comentario.' }}
                            </p>
                            <p class="mt-1 text-xs">
                                <span class="text-gray-500">Cambio:</span>
                                <span class="font-medium text-red-600">{{
                                    h.estado_anterior
                                }}</span>
                                →
                                <span class="font-medium text-green-600">{{
                                    h.estado_nuevo
                                }}</span>
                            </p>
                        </li>
                    </ul>
                    <p v-else class="text-sm text-gray-500">
                        Este trámite no tiene historial de revisiones.
                    </p>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-1">
                <div class="rounded-lg bg-white p-6 shadow-lg">
                    <h2
                        class="mb-4 border-b pb-2 text-xl font-semibold text-gray-800"
                    >
                        🧑 Información Clave
                    </h2>
                    <dl class="space-y-3 text-sm">
                        <dt class="font-medium text-gray-700">Postulante:</dt>
                        <dd class="text-gray-900">
                            {{ tramite.postulacion.estudiante.usuario.nombres }}
                        </dd>

                        <dt class="pt-2 font-medium text-gray-700">
                            Cédula (CI):
                        </dt>
                        <dd class="text-gray-900">
                            {{ tramite.postulacion.estudiante.usuario.ci }}
                        </dd>

                        <dt class="pt-2 font-medium text-gray-700">Beca:</dt>
                        <dd class="font-semibold text-blue-600">
                            {{ tramite.postulacion.beca.nombre }}
                        </dd>

                        <dt class="pt-2 font-medium text-gray-700">
                            Convocatoria:
                        </dt>
                        <dd class="text-gray-900">
                            {{ tramite.postulacion.beca.convocatoria.nombre }}
                        </dd>

                        <dt class="pt-2 font-medium text-gray-700">
                            Carrera / Semestre:
                        </dt>
                        <dd class="text-gray-900">
                            {{ tramite.postulacion.estudiante.carrera }} (Sem.
                            {{ tramite.postulacion.estudiante.semestre }})
                        </dd>
                    </dl>
                </div>

                <form
                    @submit.prevent="submitAccion"
                    class="space-y-4 rounded-lg border-t-4 border-blue-500 bg-white p-6 shadow-lg"
                >
                    <h2 class="text-xl font-semibold text-gray-800">
                        Decisión Operativa
                    </h2>

                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Acción</label
                        >
                        <select
                            v-model="form.estado_accion"
                            class="w-full rounded-lg border px-3 py-2"
                        >
                            <option value="APROBAR">✅ Aprobar Trámite</option>
                            <option value="DENEGAR">❌ Denegar Trámite</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Comentario (Obligatorio para
                            Denegar/Devolver)</label
                        >
                        <textarea
                            v-model="form.comentario"
                            rows="3"
                            class="w-full rounded-lg border px-3 py-2"
                            placeholder="Motivo de la decisión, observaciones, etc."
                        ></textarea>
                        <div
                            v-if="form.errors.comentario"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.comentario }}
                        </div>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? 'Procesando...'
                                    : 'Registrar Decisión'
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
