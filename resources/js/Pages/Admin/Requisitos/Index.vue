<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    requisitos: Object,
    tipos: Object,
    filtros: Object,
});

const busqueda = ref(props.filtros?.busqueda || '');
const tipoFiltro = ref(props.filtros?.tipo || '');
const obligatorioFiltro = ref(props.filtros?.obligatorio ?? ''); // Nuevo filtro

const applyFilters = debounce(() => {
    let obligatorioValue =
        obligatorioFiltro.value === '' ? null : obligatorioFiltro.value;

    router.get(
        route('admin.requisitos.index'),
        {
            busqueda: busqueda.value,
            tipo: tipoFiltro.value,
            obligatorio: obligatorioValue,
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['requisitos', 'filtros'],
        },
    );
}, 300);

watch([busqueda, tipoFiltro, obligatorioFiltro], applyFilters);

/*const eliminar = (id) => {
    if (confirm('¿Eliminar este requisito?')) {
        // ... (código delete) ...
    }
};
*/
const tipoLabel = (tipo) => {
    return props.tipos[tipo] || tipo;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Requisitos" />

        <div class="mx-auto max-w-7xl p-6">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Requisitos</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Documentos necesarios para postular
                    </p>
                </div>
                <a
                    :href="route('admin.requisitos.create')"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                >
                    + Nuevo
                </a>
            </div>

            <div class="mb-4 rounded-lg bg-white p-4 shadow-sm">
                <div class="flex gap-3">
                    <input
                        v-model="busqueda"
                        type="text"
                        placeholder="Buscar por nombre..."
                        class="flex-1 rounded-lg border px-3 py-2 text-sm"
                    />

                    <select
                        v-model="tipoFiltro"
                        class="rounded-lg border px-3 py-2 text-sm"
                    >
                        <option value="">Todos los tipos</option>
                        <option
                            v-for="(label, valor) in tipos"
                            :key="valor"
                            :value="valor"
                        >
                            {{ label }}
                        </option>
                    </select>

                    <select
                        v-model="obligatorioFiltro"
                        class="rounded-lg border px-3 py-2 text-sm"
                    >
                        <option value="">Todos</option>
                        <option :value="true">Obligatorios</option>
                        <option :value="false">Opcionales</option>
                    </select>
                </div>
            </div>

            <div class="overflow-hidden rounded-lg bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                Nombre
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500"
                            >
                                Tipo
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500"
                            >
                                Obligatorio
                            </th>
                            <th
                                class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500"
                            >
                                Becas
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
                            v-for="req in requisitos.data"
                            :key="req.id"
                            class="transition hover:bg-gray-50"
                        >
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">
                                    {{ req.nombre }}
                                </div>
                                <div
                                    v-if="req.descripcion"
                                    class="mt-1 max-w-md truncate text-xs text-gray-500"
                                >
                                    {{ req.descripcion }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">{{
                                    tipoLabel(req.tipo)
                                }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span
                                    :class="
                                        req.obligatorio
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-gray-100 text-gray-800'
                                    "
                                    class="rounded-full px-2 py-1 text-xs"
                                >
                                    {{ req.obligatorio ? 'Sí' : 'No' }}
                                </span>
                            </td>
                            <td
                                class="px-4 py-3 text-center text-sm text-gray-900"
                            >
                                {{ req.total_becas }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm">
                                <a
                                    :href="
                                        route('admin.requisitos.edit', req.id)
                                    "
                                    class="mr-3 text-blue-600 hover:text-blue-800"
                                >
                                    Editar
                                </a>
                                <button
                                    @click="eliminar(req.id)"
                                    class="text-red-600 hover:text-red-800"
                                >
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div
                    v-if="requisitos.data.length === 0"
                    class="py-12 text-center"
                >
                    <p class="text-gray-500">No se encontraron requisitos</p>
                </div>
            </div>

            <div class="mt-6">
                <Pagination :links="requisitos.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
