<template>
    <AuthenticatedLayout>
        <Head title="Nuevo Tipo de Dependencia Económica" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Breadcrumb o navegación -->
                        <div
                            class="mb-6 flex items-center text-sm text-gray-500"
                        >
                            <Link
                                :href="route('admin.tipos-dependencia.index')"
                                class="hover:text-blue-600"
                            >
                                Tipos de Dependencia
                            </Link>
                            <span class="font-medium text-gray-700"
                                >Nuevo Tipo</span
                            >
                        </div>

                        <!-- Encabezado -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800">
                                Nuevo Tipo de Dependencia Económica
                            </h2>
                            <p class="mt-2 text-gray-600">
                                Complete los datos del nuevo tipo de dependencia
                            </p>
                        </div>

                        <!-- Formulario -->
                        <form @submit.prevent="submit" class="max-w-2xl">
                            <div class="space-y-8">
                                <!-- Nombre -->
                                <div>
                                    <label
                                        for="nombre"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Nombre del Tipo *
                                    </label>
                                    <input
                                        id="nombre"
                                        type="text"
                                        v-model="form.nombre"
                                        required
                                        maxlength="100"
                                        class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                        placeholder="Ej: Dependiente total, Independiente, etc."
                                        @input="form.errors.nombre = null"
                                    />
                                    <div
                                        v-if="form.errors.nombre"
                                        class="mt-2 flex items-center text-sm text-red-600"
                                    >
                                        {{ form.errors.nombre }}
                                    </div>
                                    <p class="mt-2 text-sm text-gray-500">
                                        Nombre descriptivo del tipo de
                                        dependencia económica. Máximo 100
                                        caracteres.
                                    </p>
                                </div>

                                <!-- Puntaje -->
                                <div>
                                    <label
                                        for="puntaje"
                                        class="mb-2 block text-sm font-medium text-gray-700"
                                    >
                                        Puntaje (Opcional)
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="puntaje"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            max="999.99"
                                            v-model="form.puntaje"
                                            class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                            placeholder="0.00"
                                            @input="form.errors.puntaje = null"
                                        />
                                        <div
                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
                                        >
                                            <span class="text-gray-500"
                                                >pts</span
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
                                        Puntaje numérico asociado a este tipo de
                                        dependencia. Formato: máximo 999.99 con
                                        2 decimales.
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
                                                >Tipo Activo</span
                                            >
                                            <p
                                                class="mt-1 text-sm text-gray-500"
                                            >
                                                Si está activo, este tipo de
                                                dependencia estará disponible
                                                para su uso en el sistema.
                                            </p>
                                        </div>
                                    </label>
                                </div>

                                <!-- Botones -->
                                <div
                                    class="flex justify-end space-x-4 border-t border-gray-200 pt-6"
                                >
                                    <Link
                                        :href="
                                            route(
                                                'admin.tipos-dependencia.index',
                                            )
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
                                            Guardar Tipo
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    nombre: '',
    puntaje: null,
    activo: true,
});

const submit = () => {
    form.post(route('admin.tipos-dependencia.store'));
};
</script>
