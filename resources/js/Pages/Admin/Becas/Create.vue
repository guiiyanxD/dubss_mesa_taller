<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    beca: {
        type: Object,
        default: null,
    },
});

const esEdicion = !!props.beca;

const convocatorias = ref([]);
const requisitos = ref([]);
const cargando = ref(true);

const form = useForm({
    id_convocatoria: props.beca?.convocatoria?.id || '',
    nombre: props.beca?.nombre || '',
    descripcion: props.beca?.descripcion || '',
    cupos_disponibles: props.beca?.cupos_disponibles || 10,
    requisitos: props.beca?.requisitos?.map((r) => r.id) || [],
});

onMounted(async () => {
    try {
        const [convRes, reqRes] = await Promise.all([
            fetch(route('admin.convocatorias.index')),
            fetch(route('admin.requisitos.index')),
        ]);

        convocatorias.value = await convRes.json();
        requisitos.value = await reqRes.json();
    } catch (error) {
        console.error('Error cargando datos:', error);
    } finally {
        cargando.value = false;
    }
});

const submit = () => {
    if (esEdicion) {
        form.post(route('admin.becas.update', props.beca.id), {
            _method: 'PUT',
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.becas.store'));
    }
};

const toggleRequisito = (id) => {
    const index = form.requisitos.indexOf(id);
    if (index > -1) {
        form.requisitos.splice(index, 1);
    } else {
        form.requisitos.push(id);
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="esEdicion ? 'Editar Beca' : 'Nueva Beca'" />
        <div class="mx-auto max-w-3xl p-6">
            <!-- Header -->
            <div class="mb-6">
                <a
                    :href="route('admin.becas.index')"
                    class="mb-2 inline-block text-sm text-blue-600 hover:underline"
                >
                    ← Becas
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ esEdicion ? 'Editar Beca' : 'Nueva Beca' }}
                </h1>
            </div>

            <!-- Loader -->
            <div
                v-if="cargando"
                class="rounded-lg bg-white p-12 text-center shadow-sm"
            >
                <div
                    class="mx-auto h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"
                ></div>
                <p class="mt-4 text-gray-600">Cargando...</p>
            </div>

            <!-- Formulario -->
            <form
                v-else
                @submit.prevent="submit"
                class="space-y-4 rounded-lg bg-white p-6 shadow-sm"
            >
                <!-- Convocatoria -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Convocatoria <span class="text-red-500">*</span>
                    </label>
                    <select
                        v-model="form.id_convocatoria"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                    >
                        <option value="">Seleccionar...</option>
                        <option
                            v-for="conv in convocatorias"
                            :key="conv.id"
                            :value="conv.id"
                        >
                            {{ conv.nombre }}
                        </option>
                    </select>
                    <div
                        v-if="form.errors.id_convocatoria"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ form.errors.id_convocatoria }}
                    </div>
                </div>

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
                        placeholder="Beca de Alimentación"
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
                        placeholder="Descripción opcional..."
                    />
                </div>

                <!-- Cupos -->
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700">
                        Cupos Disponibles <span class="text-red-500">*</span>
                    </label>
                    <input
                        v-model.number="form.cupos_disponibles"
                        type="number"
                        min="1"
                        required
                        class="w-full rounded-lg border px-3 py-2"
                    />
                    <div
                        v-if="form.errors.cupos_disponibles"
                        class="mt-1 text-xs text-red-500"
                    >
                        {{ form.errors.cupos_disponibles }}
                    </div>
                </div>

                <!-- Requisitos (checkboxes compactos) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Requisitos
                    </label>
                    <div
                        class="grid max-h-40 grid-cols-2 gap-2 overflow-y-auto rounded-lg border p-3"
                    >
                        <label
                            v-for="req in requisitos"
                            :key="req.id"
                            class="flex items-center gap-2 text-sm"
                        >
                            <input
                                type="checkbox"
                                :checked="form.requisitos.includes(req.id)"
                                @change="toggleRequisito(req.id)"
                                class="rounded"
                            />
                            <span>{{ req.nombre }}</span>
                        </label>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end gap-3 pt-4">
                    <a
                        :href="route('admin.becas.index')"
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
