<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    requisito: {
        type: Object,
        default: null,
    },
    tipos: Object,
});

const esEdicion = !!props.requisito;

const form = useForm({
    nombre: props.requisito?.nombre || '',
    descripcion: props.requisito?.descripcion || '',
    tipo: props.requisito?.tipo || 'DOCUMENTO',
    obligatorio: props.requisito?.obligatorio ?? true,
});

const submit = () => {
    if (esEdicion) {
        form.post(route('admin.requisitos.update', props.requisito.id), {
            _method: 'PUT',
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.requisitos.store'));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="esEdicion ? 'Editar Requisito' : 'Nuevo Requisito'" />

        <div class="mx-auto max-w-3xl p-6">
            <!-- Header -->
            <div class="mb-6">
                <a
                    :href="route('admin.requisitos.index')"
                    class="mb-2 inline-block text-sm text-blue-600 hover:underline"
                >
                    ← Requisitos
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ esEdicion ? 'Editar Requisito' : 'Nuevo Requisito' }}
                </h1>
            </div>

            <!-- Formulario compacto -->
            <form
                @submit.prevent="submit"
                class="space-y-4 rounded-lg bg-white p-6 shadow-sm"
            >
                <!-- Nombre -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model="form.nombre"
                        type="text"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                        placeholder="Cédula de Identidad"
                    />
                    <div
                        v-if="form.errors.nombre"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ form.errors.nombre }}
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Descripción
                    </label>
                    <textarea
                        v-model="form.descripcion"
                        rows="2"
                        class="w-full rounded-lg border px-3 py-2"
                        placeholder="Instrucciones o detalles del requisito..."
                    />
                </div>

                <!-- Tipo y Obligatorio en grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Tipo <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.tipo"
                            required
                            class="w-full rounded-lg border px-3 py-2"
                        >
                            <option
                                v-for="(label, valor) in tipos"
                                :key="valor"
                                :value="valor"
                            >
                                {{ label }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.tipo"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.tipo }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Obligatorio
                        </label>
                        <div class="flex h-10 items-center">
                            <label class="flex cursor-pointer items-center">
                                <input
                                    v-model="form.obligatorio"
                                    type="checkbox"
                                    class="h-5 w-5 rounded"
                                />
                                <span class="ml-2 text-sm text-gray-700">
                                    Es obligatorio
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4">
                    <a
                        :href="route('admin.requisitos.index')"
                        class="rounded-lg border px-4 py-2 text-gray-700 hover:bg-gray-50"
                    >
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Guardando...' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
