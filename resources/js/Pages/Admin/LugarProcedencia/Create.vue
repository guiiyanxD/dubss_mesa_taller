<template>
    <AuthenticatedLayout>
        <Head title="Nuevo Lugar de Procedencia" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Navegación -->
                        <div
                            class="mb-6 flex items-center text-sm text-gray-500"
                        >
                            <Link
                                :href="route('admin.lugar-procedencia.index')"
                                class="flex items-center hover:text-blue-600"
                            >
                                Volver a la lista
                            </Link>
                        </div>

                        <!-- Encabezado -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800">
                                Nuevo Lugar de Procedencia
                            </h2>
                            <p class="mt-2 text-gray-600">
                                Complete los datos para crear un nuevo lugar de
                                procedencia
                            </p>
                        </div>

                        <!-- Formulario -->
                        <form @submit.prevent="submit" class="space-y-8">
                            <!-- Nombre del Lugar -->
                            <div>
                                <label
                                    for="nombre_lugar"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Nombre del Lugar *
                                </label>
                                <input
                                    id="nombre_lugar"
                                    type="text"
                                    v-model="form.nombre_lugar"
                                    required
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ej: Zona Urbana, Zona Rural, Centro Urbano, etc."
                                    @input="form.errors.nombre_lugar = null"
                                />
                                <div
                                    v-if="form.errors.nombre_lugar"
                                    class="mt-2 flex items-center text-sm text-red-600"
                                >
                                    {{ form.errors.nombre_lugar }}
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    El nombre debe ser único. No se permiten
                                    duplicados.
                                </p>
                            </div>

                            <!-- Puntaje -->
                            <div>
                                <label
                                    for="puntaje"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Puntaje *
                                </label>
                                <div class="relative">
                                    <input
                                        id="puntaje"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="999.99"
                                        v-model="form.puntaje"
                                        required
                                        class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        placeholder="0.00"
                                        @input="form.errors.puntaje = null"
                                    />
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                    >
                                        <span class="text-gray-500"
                                            >puntos</span
                                        >
                                    </div>
                                </div>
                                <div
                                    v-if="form.errors.puntaje"
                                    class="mt-2 flex items-center text-sm text-red-600"
                                >
                                    {{ form.errors.puntaje }}
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Puntaje numérico para este lugar. Rango:
                                    0.00 a 999.99
                                </p>
                            </div>

                            <!-- Estado -->
                            <div class="rounded-lg bg-gray-50 p-6">
                                <label
                                    class="flex cursor-pointer items-start space-x-3"
                                >
                                    <div class="flex h-5 items-center">
                                        <input
                                            type="checkbox"
                                            v-model="form.activo"
                                            class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <span
                                            class="text-sm font-medium text-gray-700"
                                            >Lugar Activo</span
                                        >
                                        <p class="mt-1 text-sm text-gray-500">
                                            Los lugares inactivos no estarán
                                            disponibles para su uso en el
                                            sistema.
                                        </p>
                                    </div>
                                </label>
                            </div>

                            <!-- Ejemplos de puntajes -->
                            <div
                                class="rounded-lg border border-blue-200 bg-blue-50 p-6"
                            >
                                <h3
                                    class="mb-3 text-sm font-medium text-blue-800"
                                >
                                    📊 Ejemplos de Puntajes Sugeridos:
                                </h3>
                                <ul class="space-y-2 text-sm text-blue-700">
                                    <li class="flex items-center">
                                        <span class="font-medium"
                                            >Zona Urbana desarrollada:</span
                                        >
                                        <span class="ml-2"
                                            >8.00 - 10.00 puntos</span
                                        >
                                    </li>
                                    <li class="flex items-center">
                                        <span class="font-medium"
                                            >Zona Rural accesible:</span
                                        >
                                        <span class="ml-2"
                                            >5.00 - 7.50 puntos</span
                                        >
                                    </li>
                                    <li class="flex items-center">
                                        <span class="font-medium"
                                            >Zona Remota:</span
                                        >
                                        <span class="ml-2"
                                            >2.00 - 4.50 puntos</span
                                        >
                                    </li>
                                </ul>
                            </div>

                            <!-- Botones -->
                            <div
                                class="flex justify-end space-x-4 border-t border-gray-200 pt-8"
                            >
                                <Link
                                    :href="
                                        route('admin.lugar-procedencia.index')
                                    "
                                    class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-3 font-medium text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    Cancelar
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    :class="[
                                        'inline-flex items-center rounded-lg border border-transparent px-5 py-3 font-medium text-white transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
                                        form.processing
                                            ? 'cursor-not-allowed bg-blue-400'
                                            : 'bg-blue-600 hover:bg-blue-700',
                                    ]"
                                >
                                    <span
                                        v-if="form.processing"
                                        class="flex items-center"
                                    >
                                        Guardando...
                                    </span>
                                    <span v-else class="flex items-center">
                                        Crear Lugar
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    nombre_lugar: '',
    puntaje: 0,
    activo: true,
});

const submit = () => {
    form.post(route('admin.lugar-procedencia.store'));
};
</script>
