<template>
    <AuthenticatedLayout>
        <Head title="Nuevo Tipo de Tenencia de Vivienda" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <form @submit.prevent="submit">
                            <div class="space-y-6">
                                <!-- Nombre -->
                                <div>
                                    <InputLabel for="nombre" value="Nombre *" />
                                    <TextInput
                                        id="nombre"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.nombre"
                                        required
                                        maxlength="50"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.nombre"
                                    />
                                </div>

                                <!-- Documento a Adjuntar -->
                                <div>
                                    <InputLabel
                                        for="documento_adjuntar"
                                        value="Documento a Adjuntar *"
                                    />
                                    <TextInput
                                        id="documento_adjuntar"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.documento_adjuntar"
                                        required
                                        maxlength="50"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="
                                            form.errors.documento_adjuntar
                                        "
                                    />
                                </div>

                                <!-- Puntaje -->
                                <div>
                                    <InputLabel
                                        for="puntaje"
                                        value="Puntaje *"
                                    />
                                    <TextInput
                                        id="puntaje"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="999.99"
                                        class="mt-1 block w-full"
                                        v-model="form.puntaje"
                                        required
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.puntaje"
                                    />
                                </div>

                                <!-- Activo -->
                                <div>
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            v-model="form.activo"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        />
                                        <span class="ml-2 text-sm text-gray-600"
                                            >Activo</span
                                        >
                                    </label>
                                </div>

                                <!-- Buttons -->
                                <div class="flex justify-end space-x-2">
                                    <Link
                                        :href="
                                            route('admin.tipos-tenencia.index')
                                        "
                                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                                    >
                                        Cancelar
                                    </Link>
                                    <PrimaryButton
                                        type="submit"
                                        :disabled="form.processing"
                                    >
                                        Guardar
                                    </PrimaryButton>
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
import { useForm, Head, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    nombre: '',
    documento_adjuntar: '',
    puntaje: 0,
    activo: true,
});

const submit = () => {
    form.post(route('admin.tipos-tenencia.store'));
};
</script>
