<script setup>
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    convocatoria: Object,
    estadisticas: Object,
});

const activar = () => {
    if (confirm('¿Activar esta convocatoria?')) {
        router.post(
            route('admin.convocatorias.activar', props.convocatoria.id),
            {},
            {
                preserveScroll: true,
            },
        );
    }
};

const finalizar = () => {
    if (
        confirm(
            '¿Finalizar esta convocatoria? Esta acción no se puede revertir.',
        )
    ) {
        router.post(
            route('admin.convocatorias.finalizar', props.convocatoria.id),
            {},
            {
                preserveScroll: true,
            },
        );
    }
};
</script>

<template>
    <Head :title="convocatoria.nombre" />

    <div class="mx-auto max-w-7xl p-6">
        <!-- Header -->
        <div class="mb-6">
            <a
                :href="route('admin.convocatorias.index')"
                class="mb-2 inline-block text-sm text-blue-600 hover:underline"
            >
                ← Volver a convocatorias
            </a>
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ convocatoria.nombre }}
                    </h1>
                    <p class="mt-1 text-gray-600">
                        {{ convocatoria.fecha_inicio }} -
                        {{ convocatoria.fecha_fin }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a
                        :href="
                            route('admin.convocatorias.edit', convocatoria.id)
                        "
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-200"
                    >
                        Editar
                    </a>
                    <button
                        v-if="convocatoria.estado === 'BORRADOR'"
                        @click="activar"
                        class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white transition hover:bg-green-700"
                    >
                        Activar
                    </button>
                    <button
                        v-if="convocatoria.estado === 'ACTIVA'"
                        @click="finalizar"
                        class="rounded-lg bg-red-600 px-4 py-2 text-sm text-white transition hover:bg-red-700"
                    >
                        Finalizar
                    </button>
                </div>
            </div>
        </div>

        <!-- Estadísticas compactas -->
        <div class="mb-6 grid grid-cols-4 gap-4">
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="text-2xl font-bold text-gray-900">
                    {{ estadisticas.total_postulaciones }}
                </div>
                <div class="text-xs text-gray-600">Postulaciones</div>
            </div>
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="text-2xl font-bold text-green-600">
                    {{ estadisticas.aprobadas }}
                </div>
                <div class="text-xs text-gray-600">Aprobadas</div>
            </div>
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="text-2xl font-bold text-gray-900">
                    {{ estadisticas.total_becas }}
                </div>
                <div class="text-xs text-gray-600">Becas</div>
            </div>
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <div class="text-2xl font-bold text-blue-600">
                    {{ estadisticas.cupos_totales }}
                </div>
                <div class="text-xs text-gray-600">Cupos Totales</div>
            </div>
        </div>

        <!-- Lista de becas (solo nombres y datos básicos) -->
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Becas</h2>
                <a
                    :href="
                        route('admin.becas.create', {
                            convocatoria_id: convocatoria.id,
                        })
                    "
                    class="text-sm text-blue-600 hover:underline"
                >
                    + Agregar beca
                </a>
            </div>

            <div v-if="convocatoria.becas.length > 0" class="space-y-2">
                <div
                    v-for="beca in convocatoria.becas"
                    :key="beca.id"
                    class="flex items-center justify-between rounded-lg border p-3 hover:bg-gray-50"
                >
                    <div>
                        <a
                            :href="route('admin.becas.show', beca.id)"
                            class="font-medium text-blue-600 hover:underline"
                        >
                            {{ beca.nombre }}
                        </a>
                        <div class="mt-1 text-xs text-gray-600">
                            {{ beca.cupos_disponibles }} cupos ·
                            {{ beca.requisitos_count }} requisitos
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="py-8 text-center text-sm text-gray-500">
                No hay becas agregadas
            </div>
        </div>
    </div>
</template>
