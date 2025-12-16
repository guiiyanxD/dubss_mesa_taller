<template>
    <AuthenticatedLayout>
        <Head title="Nuevo Tipo de Ocupación del Dependiente" />

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Navegación -->
                        <div
                            class="mb-6 flex items-center text-sm text-gray-500"
                        >
                            <Link
                                :href="
                                    route(
                                        'admin.tipo-ocupacion-dependiente.index',
                                    )
                                "
                                class="flex items-center hover:text-blue-600"
                            >
                                Volver a la lista
                            </Link>
                        </div>

                        <!-- Encabezado -->
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800">
                                Nuevo Tipo de Ocupación
                            </h2>
                            <p class="mt-2 text-gray-600">
                                Complete los datos para crear un nuevo tipo de
                                ocupación del dependiente
                            </p>
                        </div>

                        <!-- Formulario -->
                        <form @submit.prevent="submit" class="space-y-8">
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
                                    placeholder="Ej: Empleado Público, Empresario, Independiente, etc."
                                    @input="form.errors.nombre = null"
                                />
                                <div
                                    v-if="form.errors.nombre"
                                    class="mt-2 flex items-center text-sm text-red-600"
                                >
                                    {{ form.errors.nombre }}
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Nombre descriptivo del tipo de ocupación.
                                    Máximo 100 caracteres.
                                </p>
                            </div>

                            <!-- Archivo a Adjuntar -->
                            <div>
                                <label
                                    for="archivo_adjuntar"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Archivo a Adjuntar *
                                </label>
                                <textarea
                                    id="archivo_adjuntar"
                                    v-model="form.archivo_adjuntar"
                                    required
                                    maxlength="255"
                                    rows="3"
                                    class="block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm transition duration-150 ease-in-out focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    placeholder="Ej: Certificado de trabajo, Constancia de ingresos, etc."
                                    @input="form.errors.archivo_adjuntar = null"
                                ></textarea>
                                <div
                                    v-if="form.errors.archivo_adjuntar"
                                    class="mt-2 flex items-center text-sm text-red-600"
                                >
                                    {{ form.errors.archivo_adjuntar }}
                                </div>
                                <div class="mt-2 flex justify-between text-sm">
                                    <span class="text-gray-500"
                                        >Descripción del documento
                                        requerido.</span
                                    >
                                    <span
                                        :class="
                                            form.archivo_adjuntar.length > 250
                                                ? 'text-red-500'
                                                : 'text-gray-500'
                                        "
                                    >
                                        {{ form.archivo_adjuntar.length }}/255
                                    </span>
                                </div>
                            </div>

                            <!-- Puntaje -->
                            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
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
                                                Los tipos inactivos no estarán
                                                disponibles para su uso en el
                                                sistema.
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div
                                class="flex justify-end space-x-4 border-t border-gray-200 pt-8"
                            >
                                <Link
                                    :href="
                                        route(
                                            'admin.tipo-ocupacion-dependiente.index',
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
                                        Crear Tipo
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
    nombre: '',
    archivo_adjuntar: '',
    puntaje: null,
    activo: true,
});

const submit = () => {
    form.post(route('admin.tipo-ocupacion-dependiente.store'));
};
</script>
