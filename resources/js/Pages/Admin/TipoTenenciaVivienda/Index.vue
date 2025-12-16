<template>
    <AuthenticatedLayout>
        <Head title="Tipos de Tenencia de Vivienda" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Flash Message -->
                        <div
                            v-if="flash.message"
                            :class="[
                                'mb-4 rounded p-4',
                                flash.type === 'success'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700',
                            ]"
                        >
                            {{ flash.message }}
                        </div>

                        <!-- Header con botón Nuevo -->
                        <div class="mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">
                                Lista de Tipos de Tenencia
                            </h3>
                            <Link
                                :href="route('admin.tipos-tenencia.create')"
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white ring-blue-300 transition duration-150 ease-in-out hover:bg-blue-700 focus:border-blue-900 focus:outline-none focus:ring active:bg-blue-900 disabled:opacity-25"
                            >
                                Nuevo Tipo
                            </Link>
                        </div>

                        <!-- Tabla Simple -->
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
                                            Documento a Adjuntar
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
                                    <tr v-for="tipo in tipos" :key="tipo.id">
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ tipo.id }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ tipo.nombre }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ tipo.documento_adjuntar }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ tipo.puntaje }}
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
                                            class="whitespace-nowrap px-6 py-4 text-sm font-medium"
                                        >
                                            <div class="flex space-x-4">
                                                <!-- Botón Editar -->
                                                <Link
                                                    class="text-blue-600 hover:text-blue-900 hover:underline"
                                                >
                                                    Editar
                                                </Link>
                                                <!-- Botón Habilitar/Inhabilitar -->

                                                <Link
                                                    key="habilitar"
                                                    :href="
                                                        route(
                                                            'admin.tipos-tenencia.habilitar',
                                                            tipo.id,
                                                        )
                                                    "
                                                    class="text-yellow-600 hover:text-yellow-900 hover:underline"
                                                >
                                                    {{
                                                        tipo.activo
                                                            ? 'Inhabilitar'
                                                            : 'Habilitar'
                                                    }}
                                                </Link>
                                                <!-- Botón Eliminar con alert simple -->
                                                <button
                                                    @click="eliminarTipo(tipo)"
                                                    class="text-red-600 hover:text-red-900 hover:underline"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Mensaje cuando no hay datos -->
                            <div
                                v-if="tipos === 0"
                                class="border-t py-8 text-center text-gray-500"
                            >
                                No hay tipos de tenencia registrados.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    tipos: {
        type: Array,
        required: true,
    },
    flash: {
        type: Object,
        default: () => ({}),
    },
});

// Función para eliminar con alert de confirmación
const eliminarTipo = (tipo) => {
    if (
        confirm(
            `¿Estás seguro de que deseas eliminar el tipo de tenencia "${tipo.nombre}"?`,
        )
    ) {
        useForm({}).delete(route('admin.tipos-tenencia.destroy', tipo.id));
    }
};
</script>
