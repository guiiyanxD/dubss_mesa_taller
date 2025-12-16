<template>
    <AuthenticatedLayout>
        <Head title="Rangos de Ingreso Económico" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Header -->
                        <div class="mb-6 flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-800">
                                Rangos de Ingreso Económico
                            </h2>
                            <Link
                                :href="route('admin.rangos-ingreso.create')"
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Nuevo Rango
                            </Link>
                        </div>

                        <!-- Tabla -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            ID
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Rango de Monto
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Puntaje
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Estado
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr v-for="rango in rangos" :key="rango.id">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ rango.id }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ rango.rango_nombre }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ rango.puntaje ?? 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="[
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                    rango.activo
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                ]"
                                            >
                                                {{
                                                    rango.activo
                                                        ? 'Activo'
                                                        : 'Inactivo'
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm font-medium"
                                        >
                                            <div class="flex space-x-4">
                                                <Link
                                                    class="text-blue-600 hover:text-blue-900 hover:underline"
                                                >
                                                    Editar
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.rangos-ingreso.habilitar',
                                                            rango.id,
                                                        )
                                                    "
                                                    class="text-yellow-600 hover:text-yellow-900 hover:underline"
                                                >
                                                    {{
                                                        rango.activo
                                                            ? 'Deshabilitar'
                                                            : 'Habilitar'
                                                    }}
                                                </Link>
                                                <button
                                                    @click="
                                                        eliminarRango(rango)
                                                    "
                                                    class="text-red-600 hover:text-red-900 hover:underline"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Estado vacío -->
                            <div
                                v-if="rangos.length === 0"
                                class="border-t py-8 text-center text-gray-500"
                            >
                                No hay rangos de ingreso registrados.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    rangos: {
        type: Array,
        required: true,
    },
});

// Función para eliminar con confirmación
const eliminarRango = (rango) => {
    if (confirm(`¿Estás seguro de eliminar el rango "${rango.rango_monto}"?`)) {
        useForm({}).delete(route('admin.rangos-ingreso.destroy', rango.id));
    }
};
</script>
