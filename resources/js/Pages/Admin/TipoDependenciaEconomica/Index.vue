<template>
    <AuthenticatedLayout>
        <Head title="Tipos de Dependencia Económica" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Header -->
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">
                                    Tipos de Dependencia Económica
                                </h2>
                                <p class="mt-1 text-gray-600">
                                    Gestione los tipos de dependencia económica
                                </p>
                            </div>
                            <Link
                                :href="route('admin.tipos-dependencia.create')"
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Nuevo Tipo
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
                                            Nombre
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
                                            Creado
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
                                    <tr v-for="tipo in tipos" :key="tipo.id">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ tipo.id }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900"
                                        >
                                            {{ tipo.nombre }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            <span
                                                v-if="tipo.puntaje !== null"
                                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800"
                                            >
                                                {{ tipo.puntaje }}
                                            </span>
                                            <span
                                                v-else
                                                class="italic text-gray-500"
                                                >N/A</span
                                            >
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="[
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                    tipo.activo
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                ]"
                                            >
                                                {{
                                                    tipo.activo
                                                        ? 'Activo'
                                                        : 'Inactivo'
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-500"
                                        >
                                            {{ formatDate(tipo.created_at) }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm font-medium"
                                        >
                                            <div class="flex space-x-4">
                                                <Link
                                                    class="flex items-center text-blue-600 hover:text-blue-900 hover:underline"
                                                >
                                                    Editar
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.tipos-dependencia.habilitar',
                                                            tipo.id,
                                                        )
                                                    "
                                                    class="flex items-center text-yellow-600 hover:text-yellow-900 hover:underline"
                                                >
                                                    {{
                                                        tipo.activo
                                                            ? 'Deshabilitar'
                                                            : 'Habilitar'
                                                    }}
                                                </Link>
                                                <button
                                                    @click="eliminarTipo(tipo)"
                                                    class="flex items-center text-red-600 hover:text-red-900 hover:underline"
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
                                v-if="tipos.length === 0"
                                class="border-t py-12 text-center text-gray-500"
                            >
                                <div class="flex flex-col items-center">
                                    <p class="text-lg font-medium">
                                        No hay tipos de dependencia registrados
                                    </p>
                                    <p class="mt-1 text-sm">
                                        Cree el primer tipo usando el botón
                                        "Nuevo Tipo"
                                    </p>
                                </div>
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
    tipos: {
        type: Array,
        required: true,
        default: () => [],
    },
});

// Función para formatear fecha
const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

// Función para eliminar con confirmación
const eliminarTipo = (tipo) => {
    if (confirm(`¿Está seguro de eliminar el tipo "${tipo.nombre}"?`)) {
        useForm({}).delete(route('admin.tipos-dependencia.destroy', tipo.id));
    }
};
</script>
