<template>
    <AuthenticatedLayout>
        <Head title="Lugares de Procedencia" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Flash Message -->
                        <div
                            v-if="$page.props.flash.message"
                            :class="[
                                'mb-6 rounded-lg p-4',
                                $page.props.flash.type === 'success'
                                    ? 'border border-green-200 bg-green-50 text-green-700'
                                    : 'border border-red-200 bg-red-50 text-red-700',
                            ]"
                        ></div>

                        <!-- Header -->
                        <div class="mb-8 flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">
                                    Lugares de Procedencia
                                </h2>
                                <p class="mt-1 text-gray-600">
                                    Gestione los lugares de procedencia y sus
                                    puntajes
                                </p>
                            </div>
                            <Link
                                :href="route('admin.lugar-procedencia.create')"
                                class="inline-flex items-center rounded-lg border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            >
                                Nuevo Lugar
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
                                            Nombre del Lugar
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
                                        v-for="lugar in lugares"
                                        :key="lugar.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-900"
                                        >
                                            {{ lugar.id }}
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ lugar.nombre_lugar }}
                                            </div>
                                            <div
                                                v-if="lugar.created_at"
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                Creado:
                                                {{
                                                    formatDate(lugar.created_at)
                                                }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                class="inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-800"
                                            >
                                                {{ lugar.puntaje }}
                                                <span
                                                    class="ml-1 text-xs text-blue-600"
                                                    >pts</span
                                                >
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="[
                                                    'inline-flex items-center rounded-full px-3 py-1 text-xs font-medium',
                                                    lugar.activo
                                                        ? 'border border-green-200 bg-green-100 text-green-800'
                                                        : 'border border-red-200 bg-red-100 text-red-800',
                                                ]"
                                            >
                                                <span
                                                    :class="[
                                                        'mr-2 h-2 w-2 rounded-full',
                                                        lugar.activo
                                                            ? 'bg-green-500'
                                                            : 'bg-red-500',
                                                    ]"
                                                ></span>
                                                {{
                                                    lugar.activo
                                                        ? 'Activo'
                                                        : 'Inactivo'
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm font-medium"
                                        >
                                            <div
                                                class="flex items-center space-x-3"
                                            >
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.lugar-procedencia.edit',
                                                            lugar.id,
                                                        )
                                                    "
                                                    class="inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 transition duration-150 ease-in-out hover:bg-blue-100"
                                                >
                                                    Editar
                                                </Link>
                                                <Link
                                                    :href="
                                                        route(
                                                            'admin.lugar-procedencia.habilitar',
                                                            lugar.id,
                                                        )
                                                    "
                                                    class="inline-flex items-center rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-1.5 text-xs font-medium text-yellow-700 transition duration-150 ease-in-out hover:bg-yellow-100"
                                                >
                                                    {{
                                                        lugar.activo
                                                            ? 'Desahabilitar'
                                                            : 'Habilitar'
                                                    }}
                                                </Link>
                                                <button
                                                    @click="
                                                        eliminarLugar(lugar)
                                                    "
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
                                v-if="lugares.length === 0"
                                class="py-12 text-center"
                            >
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <h3
                                        class="mb-2 text-lg font-medium text-gray-900"
                                    >
                                        No hay lugares registrados
                                    </h3>
                                    <p class="mb-6 text-gray-500">
                                        Comience creando el primer lugar de
                                        procedencia
                                    </p>
                                    <Link
                                        :href="
                                            route(
                                                'admin.lugar-procedencia.create',
                                            )
                                        "
                                        class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                                    >
                                        Crear Primer Lugar
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas -->
                        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div
                                class="rounded-lg border border-gray-200 bg-gray-50 p-4"
                            >
                                <div class="text-sm text-gray-500">
                                    Total de Lugares
                                </div>
                                <div class="text-2xl font-bold text-gray-800">
                                    {{ lugares.length }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg border border-gray-200 bg-gray-50 p-4"
                            >
                                <div class="text-sm text-gray-500">
                                    Lugares Activos
                                </div>
                                <div class="text-2xl font-bold text-green-600">
                                    {{ lugares.filter((l) => l.activo).length }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg border border-gray-200 bg-gray-50 p-4"
                            >
                                <div class="text-sm text-gray-500">
                                    Puntaje Promedio
                                </div>
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ calcularPuntajePromedio() }}
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
    lugares: {
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

// Función para calcular puntaje promedio
const calcularPuntajePromedio = () => {
    if (props.lugares.length === 0) return '0.00';

    const suma = props.lugares.reduce((total, lugar) => {
        return total + parseFloat(lugar.puntaje);
    }, 0);

    return (suma / props.lugares.length).toFixed(2);
};

// Función para eliminar con confirmación
const eliminarLugar = (lugar) => {
    if (
        confirm(
            `¿Está seguro de eliminar el lugar "${lugar.nombre_lugar}"?\n\nEsta acción no se puede deshacer.`,
        )
    ) {
        useForm({}).delete(route('admin.lugar-procedencia.destroy', lugar.id));
    }
};
</script>
