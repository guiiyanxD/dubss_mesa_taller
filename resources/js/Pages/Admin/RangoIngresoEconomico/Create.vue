<template>
    <AuthenticatedLayout>
        <Head title="Nuevo Rango de Ingreso Económico" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Encabezado del formulario -->
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">Nuevo Rango de Ingreso Económico</h2>
                            <p class="text-gray-600 mt-2">Complete los datos del nuevo rango de ingreso</p>
                        </div>

                        <!-- Formulario -->
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Rango de Monto -->
                                <div>
                                    <label for="rango_nombre" class="block text-sm font-medium text-gray-700">
                                        Nombre del Rango de Monto
                                    </label>
                                    <input
                                        id="rango_nombre"
                                        type="text"
                                        v-model="form.rango_nombre"
                                        required
                                        maxlength="50"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="Ej: De $0 - $500"
                                    />
                                    <div v-if="form.errors.rango_nombre" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.rango_nombre }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Máximo 50 caracteres. Ej: "De $0 a $500", "$501 - $1000", etc.
                                    </p>
                                </div>

                                <!-- Puntaje -->
                                <div>
                                    <label for="puntaje" class="block text-sm font-medium text-gray-700">
                                        Puntaje
                                    </label>
                                    <input
                                        id="puntaje"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="999.99"
                                        v-model="form.puntaje"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="0.00"
                                    />
                                    <div v-if="form.errors.puntaje" class="mt-1 text-sm text-red-600">
                                        {{ form.errors.puntaje }}
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Puntaje numérico (opcional). Formato: 5 dígitos, 2 decimales.
                                    </p>
                                </div>

                                <!-- Estado -->
                                <div>
                                    <label class="flex items-center space-x-2">
                                        <input
                                            type="checkbox"
                                            v-model="form.activo"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        />
                                        <span class="text-sm font-medium text-gray-700">Activo</span>
                                    </label>
                                    <p class="mt-1 text-xs text-gray-500">
                                        Desmarque para crear el rango como inactivo
                                    </p>
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                    <Link
                                        :href="route('admin.rangos-ingreso.index')"
                                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        Cancelar
                                    </Link>
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        :class="[
                                            'inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150',
                                            form.processing ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"
                                    >
                                        <span v-if="form.processing">Guardando...</span>
                                        <span v-else>Guardar</span>
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
import { useForm, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    rango_nombre: '',
    puntaje: null,
    activo: true
});

const submit = () => {
    form.post(route('admin.rangos-ingreso.store'));
};
</script>
