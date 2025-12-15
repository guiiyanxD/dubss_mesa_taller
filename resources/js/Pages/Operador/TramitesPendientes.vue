<script setup>
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    tramites: Array,
});

const estadoBadgeClass = (estadoNombre) => {
    switch (estadoNombre) {
        case 'PENDIENTE':
            return 'bg-yellow-100 text-yellow-800 border-yellow-300';
        case 'EN_VALIDACION':
            return 'bg-blue-100 text-blue-800 border-blue-300';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-300';
    }
};

const irAValidar = (tramiteId) => {
    router.get(route('operador.tramites.validar', tramiteId));
};
</script>

<template>
    <Head title="Trámites Pendientes" />

    <div class="mx-auto max-w-7xl p-6">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    🚨 Trámites Pendientes de Validación
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Lista de trámites listos o en proceso de revisión por el
                    operador.
                </p>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow-xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                        >
                            Trámite ID
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                        >
                            Postulante
                        </th>
                        <th
                            class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                        >
                            Beca
                        </th>
                        <th
                            class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500"
                        >
                            Estado
                        </th>
                        <th
                            class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500"
                        >
                            Fecha Ingreso
                        </th>
                        <th
                            class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500"
                        >
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr
                        v-for="tramite in tramites"
                        :key="tramite.id"
                        class="transition hover:bg-gray-50"
                    >
                        <td
                            class="px-4 py-3 text-sm font-semibold text-gray-700"
                        >
                            #{{ tramite.id }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">
                                {{
                                    tramite.postulacion.estudiante.usuario
                                        .nombres
                                }}
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                Cédula:
                                {{ tramite.postulacion.estudiante.usuario.ci }}
                            </div>
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-600">
                            <div class="font-semibold">
                                {{ tramite.postulacion.beca.nombre }}
                            </div>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span
                                :class="
                                    estadoBadgeClass(
                                        tramite.estado_actual.nombre,
                                    )
                                "
                                class="rounded-full border px-3 py-1 text-xs font-bold shadow-sm"
                            >
                                {{
                                    tramite.estado_actual.nombre.replace(
                                        '_',
                                        ' ',
                                    )
                                }}
                            </span>
                        </td>

                        <td
                            class="whitespace-nowrap px-4 py-3 text-center text-sm text-gray-600"
                        >
                            {{
                                new Date(
                                    tramite.created_at,
                                ).toLocaleDateString()
                            }}
                        </td>

                        <td class="px-4 py-3 text-right text-sm">
                            <button
                                @click="irAValidar(tramite.id)"
                                class="rounded-lg bg-green-600 px-3 py-1 font-semibold text-white transition hover:bg-green-700"
                            >
                                Validar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="tramites.length === 0" class="py-12 text-center">
                <p class="text-lg text-gray-500">
                    🎉 ¡No hay trámites pendientes!
                </p>
                <p class="mt-1 text-gray-400">
                    Revisa más tarde o contacta al administrador.
                </p>
            </div>
        </div>
    </div>
</template>
