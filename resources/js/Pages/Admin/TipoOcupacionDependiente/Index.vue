<template>
    <AuthenticatedLayout>
        <Head title="Tipos de Ocupación del Dependiente" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Header -->
                        <div class="mb-8 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">
                                    Tipos de Ocupación del Dependiente
                                </h2>
                                <p class="mt-1 text-gray-600">
                                    Gestione los tipos de ocupación para
                                    dependientes económicos
                                </p>
                            </div>
                            <Link
                                :href="
                                    route(
                                        'admin.tipo-ocupacion-dependiente.create',
                                    )
                                "
                                class="inline-flex items-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Nuevo Tipo
                            </Link>
                        </div>

                        <!-- Tabla -->
                        <div
                            class="overflow-x-auto rounded-lg border border-gray-200"
                        >
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
                                            Archivo a Adjuntar
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
                                    <tr
                                        v-for="tipo in tipos"
                                        :key="tipo.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ tipo.id }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ tipo.nombre }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="max-w-xs truncate text-sm text-gray-900"
                                                :title="tipo.archivo_adjuntar"
                                            >
                                                {{ tipo.archivo_adjuntar }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                v-if="tipo.puntaje !== null"
                                                class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800"
                                            >
                                                {{ tipo.puntaje }}
                                            </span>
                                            <span
                                                v-else
                                                class="text-sm italic text-gray-500"
                                                >N/A</span
                                            >
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="[
                                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium',
                                                    tipo.activo
                                                        ? 'border border-green-200 bg-green-100 text-green-800'
                                                        : 'border border-red-200 bg-red-100 text-red-800',
                                                ]"
                                            >
                                                <span
                                                    :class="[
                                                        'mr-2 h-2 w-2 rounded-full',
                                                        tipo.activo
                                                            ? 'bg-green-500'
                                                            : 'bg-red-500',
                                                    ]"
                                                ></span>
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
                                            <div
                                                class="flex items-center space-x-4"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.tipo-ocupacion-dependiente.edit',
                                                            tipo.id,
                                                        )
                                                    "
                                                    class="inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition duration-150 ease-in-out hover:bg-blue-100"
                                                >
                                                    Editar
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.tipo-ocupacion-dependiente.habilitar',
                                                            tipo.id,
                                                        )
                                                    "
                                                    class="inline-flex items-center rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 transition duration-150 ease-in-out hover:bg-yellow-100"
                                                >
                                                    {{
                                                        tipo.activo
                                                            ? 'Deshabilitar'
                                                            : 'Habilitar'
                                                    }}
                                                </Link>
                                                <button
                                                    @click="eliminarTipo(tipo)"
                                                    class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 transition duration-150 ease-in-out hover:bg-red-100"
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
                                class="py-12 text-center"
                            >
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <h3
                                        class="mb-2 text-lg font-medium text-gray-900"
                                    >
                                        No hay tipos de ocupación registrados
                                    </h3>
                                    <p class="mb-6 text-gray-500">
                                        Comience creando el primer tipo de
                                        ocupación
                                    </p>
                                    <Link
                                        :href="
                                            route(
                                                'admin.tipo-ocupacion-dependiente.create',
                                            )
                                        "
                                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                                    >
                                        Crear Primer Tipo
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Contador -->
                        <div class="mt-6 text-sm text-gray-500">
                            Mostrando {{ tipos.length }}
                            {{ tipos.length === 1 ? 'registro' : 'registros' }}
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

// Función para eliminar con confirmación
const eliminarTipo = (tipo) => {
    if (
        confirm(
            `¿Está seguro de eliminar el tipo "${tipo.nombre}"?\n\nEsta acción no se puede deshacer.`,
        )
    ) {
        useForm({}).delete(
            route('admin.tipo-ocupacion-dependiente.destroy', tipo.id),
        );
    }
};
</script>
