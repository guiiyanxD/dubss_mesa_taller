<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import { router, Head } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    becas: Object,
    filtros: Object,
});

const busqueda = ref(props.filtros?.busqueda || '');

watch(
    busqueda,
    debounce(function (value) {
        router.get(
            route('admin.becas.index'),
            {
                busqueda: value,
            },
            {
                preserveState: true, // Mantiene el valor de 'busqueda' en el input
                preserveScroll: true,
            },
        );
    }, 300),
);

const eliminar = (id) => {
    if (
        confirm(
            '¿Eliminar esta beca? Esta acción es irreversible si no hay postulaciones.',
        )
    ) {
        router.delete(route('admin.becas.destroy', id), {
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Becas" />

        <div class="mx-auto max-w-7xl p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Becas</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Administración de becas por convocatoria
                    </p>
                </div>
                <a
                    :href="route('admin.becas.create')"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                >
                    + Nueva
                </a>
            </div>

            <div class="mb-4 rounded-lg bg-white p-4 shadow-sm">
                <input
                    v-model="busqueda"
                    type="text"
                    placeholder="Buscar por nombre de beca o convocatoria..."
                    class="w-full rounded-lg border px-3 py-2 text-sm"
                />
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                Beca
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                Convocatoria
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500"
                            >
                                Cupos
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500"
                            >
                                Requisitos
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500"
                            >
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr
                            v-for="beca in becas.data"
                            :key="beca.id"
                            class="transition hover:bg-gray-50"
                        >
                            <td class="px-4 py-3">
                                <a
                                    :href="route('admin.becas.show', beca.id)"
                                    class="font-medium text-blue-600 hover:underline"
                                >
                                    {{ beca.nombre }}
                                </a>
                                <div
                                    v-if="beca.descripcion"
                                    class="mt-1 max-w-xs truncate text-xs text-gray-500"
                                >
                                    {{ beca.descripcion }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900">
                                    {{ beca.convocatoria.nombre }}
                                </div>
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800':
                                            beca.convocatoria.estado ===
                                            'ACTIVA',
                                        'bg-yellow-100 text-yellow-800':
                                            beca.convocatoria.estado ===
                                            'BORRADOR',
                                        'bg-gray-100 text-gray-800':
                                            beca.convocatoria.estado ===
                                            'FINALIZADA',
                                    }"
                                    class="rounded-full px-2 py-0.5 text-xs"
                                >
                                    {{ beca.convocatoria.estado }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-3 text-center text-sm text-gray-900"
                            >
                                {{ beca.cupos_disponibles }}
                            </td>
                            <td
                                class="px-4 py-3 text-center text-sm text-gray-900"
                            >
                                {{ beca.total_requisitos }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a
                                    :href="route('admin.becas.edit', beca.id)"
                                    class="mr-3 text-blue-600 hover:text-blue-800"
                                >
                                    Editar
                                </a>
                                <button
                                    @click="eliminar(beca.id)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-if="becas.data.length === 0" class="py-12 text-center">
                    <p class="text-gray-500">
                        No se encontraron becas que coincidan con la búsqueda.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <Pagination :links="becas.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
