<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    convocatoria: {
        type: Object,
        default: null,
    },
    flash: {
        type: Object,
        default: () => ({ success: null, error: null }),
    },
});

const esEdicion = !!props.convocatoria;

const form = useForm({
    nombre: props.convocatoria?.nombre || '',
    descripcion: props.convocatoria?.descripcion || '',
    fecha_inicio: props.convocatoria?.fecha_inicio || '',
    fecha_fin: props.convocatoria?.fecha_fin || '',
    estado: props.convocatoria?.estado || 'BORRADOR',
});

const submit = () => {
    if (esEdicion) {
        form.post(route('admin.convocatorias.update', props.convocatoria.id), {
            _method: 'PUT',
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.convocatorias.store'));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head
            :title="esEdicion ? 'Editar Convocatoria' : 'Nueva Convocatoria'"
        />

        <div class="mx-auto max-w-3xl p-6">
            <!-- Header -->
            <div class="mb-6">
                <a
                    :href="route('admin.convocatorias.index')"
                    class="mb-2 inline-block text-sm text-blue-600 hover:underline"
                >
                    ← Convocatorias
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{
                        esEdicion ? 'Editar Convocatoria' : 'Nueva Convocatoria'
                    }}
                </h1>
            </div>

            <div
                v-if="flash.error"
                class="relative mb-4 rounded border border-red-400 bg-red-100 px-4 py-3 text-red-700"
            >
                <span class="block sm:inline">{{ flash.error }}</span>
            </div>
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
                        placeholder="Convocatoria 2025-1"
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
                        rows="3"
                        class="w-full rounded-lg border px-3 py-2"
                        placeholder="Descripción opcional..."
                    />
                </div>

                <!-- Fechas en grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Fecha Inicio <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.fecha_inicio"
                            type="date"
                            required
                            class="w-full rounded-lg border px-3 py-2"
                        />
                        <div
                            v-if="form.errors.fecha_inicio"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.fecha_inicio }}
                        </div>
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                        >
                            Fecha Fin <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.fecha_fin"
                            type="date"
                            required
                            class="w-full rounded-lg border px-3 py-2"
                        />
                        <div
                            v-if="form.errors.fecha_fin"
                            class="mt-1 text-xs text-red-500"
                        >
                            {{ form.errors.fecha_fin }}
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Estado
                    </label>
                    <select
                        v-model="form.estado"
                        class="w-full rounded-lg border px-3 py-2"
                    >
                        <option value="BORRADOR">Borrador</option>
                        <option value="ACTIVA">Activa</option>
                        <option value="FINALIZADA">Finalizada</option>
                    </select>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4">
                    <a
                        :href="route('admin.convocatorias.index')"
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
