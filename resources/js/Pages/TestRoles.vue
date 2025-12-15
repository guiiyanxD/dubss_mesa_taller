<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    user: Object,
});
</script>

<template>
    <Head title="Test de Roles" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Prueba de Roles y Permisos
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Información del Usuario -->
                <div
                    class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="mb-2 text-lg font-bold">
                            👤 Información del Usuario
                        </h3>
                        <p><strong>Nombre:</strong> {{ user.name }}</p>
                        <p><strong>Email:</strong> {{ user.email }}</p>
                    </div>
                </div>

                <!-- Roles -->
                <div
                    class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="border-b border-gray-200 p-6">
                        <h3 class="mb-4 text-lg font-bold">
                            🎭 Roles Asignados
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                v-for="role in user.roles"
                                :key="role"
                                class="rounded-full bg-blue-100 px-4 py-2 font-semibold text-blue-800"
                            >
                                {{ role }}
                            </span>
                        </div>
                        <p
                            v-if="user.roles.length === 0"
                            class="italic text-gray-500"
                        >
                            No tiene roles asignados
                        </p>
                    </div>
                </div>

                <!-- Permisos -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-bold">
                            🔐 Permisos ({{ user.permissions.length }})
                        </h3>
                        <div
                            class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                v-for="permission in user.permissions"
                                :key="permission"
                                class="rounded-lg border border-green-200 bg-green-50 p-3"
                            >
                                <span class="text-green-700"
                                    >✓ {{ permission }}</span
                                >
                            </div>
                        </div>
                        <p
                            v-if="user.permissions.length === 0"
                            class="italic text-gray-500"
                        >
                            No tiene permisos asignados
                        </p>
                    </div>
                </div>

                <!-- Instrucciones -->
                <div
                    class="mt-6 rounded-lg border border-yellow-200 bg-yellow-50 p-6"
                >
                    <h4 class="mb-2 font-bold text-yellow-800">
                        💡 Prueba el sistema:
                    </h4>
                    <ol
                        class="list-inside list-decimal space-y-1 text-yellow-800"
                    >
                        <li>Cierra sesión (Log Out)</li>
                        <li>
                            Inicia sesión con otro usuario (ver credenciales en
                            terminal)
                        </li>
                        <li>
                            Vuelve a esta página y observa los cambios en
                            roles/permisos
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
