<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue'; // Importación correcta de 'computed'

const props = defineProps({
    auth: Object, // Incluye el objeto auth que tiene la información del usuario
});

const hasRole = (roles) => {
    const requiredRoles = Array.isArray(roles) ? roles : [roles];

    if (props.auth.user && props.auth.user.roles) {
        return props.auth.user.roles.some(requiredRoles.includes(userRole));
    }
    return false;
};

const atajos = {
    // ----------------------------------------------------
    // ATACJOS PARA ESTUDIANTES / USUARIO BASE (Cualquiera)
    // ----------------------------------------------------
    ESTUDIANTE: [
        {
            title: 'Llenar Formulario Socioeconómico',
            description:
                'Inicia tu postulación rellenando el formulario de datos.',
            icon: '📝',
            route: 'formularios.create',
        },
        {
            title: 'Mi Perfil de Usuario',
            description: 'Gestiona tu información de contacto y contraseña.',
            icon: '👤',
            route: 'profile.edit',
        },
    ],

    // ----------------------------------------------------
    // ATAJOS PARA OPERADOR (Operador)
    // ----------------------------------------------------
    OPERADOR: [
        {
            title: 'Trámites Pendientes de Revisión',
            description:
                'Accede a la lista de trámites que requieren validación.',
            icon: '⏳',
            route: 'operador.tramites.pendientes',
        },
        {
            title: 'Trámites Validados',
            description: 'Revisa la lista de trámites que ya has validado.',
            icon: '✅',
            route: 'operador.tramites.validados',
        },
        {
            title: 'Buscar Trámites (Validar / Digitalizar)',
            description: 'Busca un trámite específico por CI o código.',
            icon: '🔎',
            route: 'operador.tramites.buscar',
        },
        {
            title: 'Historial de Gestión',
            description:
                'Revisa los trámites que has gestionado personalmente.',
            icon: '📜',
            route: 'operador.historial',
        },
    ],

    // ----------------------------------------------------
    // ATAJOS PARA ADMINISTRADOR (Dpto. Sistema, Dirección)
    // ----------------------------------------------------
    ADMIN: [
        {
            title: 'Gestion de Tipos de Tenencia de Vivienda',
            description: 'Gestiona tipos de tenencia de vivienda.',
            icon: '📊',
            route: 'admin.tipos-tenencia.index',
        },
        {
            title: 'Panel de Resultados y Estadísticas',
            description:
                'Visualiza reportes, gráficos y métricas de postulaciones.',
            icon: '📊',
            route: 'admin.resultados.dashboard',
        },
        {
            title: 'Gestión de Convocatorias',
            description: 'Ver, crear y editar convocatorias vigentes.',
            icon: '📅',
            route: 'admin.convocatorias.index',
        },
        {
            title: 'Crear Nueva Convocatoria',
            description:
                'Inicia el proceso de creación de una nueva convocatoria.',
            icon: '➕',
            route: 'admin.convocatorias.create',
        },
        {
            title: 'Gestión de Becas',
            description: 'Administra los tipos de beca disponibles.',
            icon: '🏅',
            route: 'admin.becas.index',
        },
        {
            title: 'Gestión de Requisitos',
            description: 'Administra la lista de requisitos documentales.',
            icon: '📄',
            route: 'admin.requisitos.index',
        },
        {
            title: 'Reportes y Exportación (Excel/PDF)',
            description: 'Generar y limpiar archivos de reportes consolidados.',
            icon: '📤',
            route: 'admin.reportes.index',
        },
    ],
};

// Determinar el conjunto de atajos a mostrar de forma dinámica
const atajosAMostrar = computed(() => {
    let links = [];
    const roles = props.auth.user.roles || [];

    // 1. Roles Administrativos (Dpto. Sistema, Dirección)
    if (
        roles.includes('dptso_sistema') ||
        roles.includes('direccion') ||
        roles.includes('super_admin')
    ) {
        const adminLinks = new Set([...atajos.ADMIN, ...atajos.OPERADOR]);
        links.push(...Array.from(adminLinks));
    } else if (roles.includes('operador')) {
        links.push(...atajos.OPERADOR);
    }

    if (links.length === 0) {
        links.push(...atajos.ESTUDIANTE);
    }

    return links;
});

// Helper para colores
const getColorClass = (index) => {
    const colors = [
        'bg-blue-100 border-blue-500 text-blue-800 hover:bg-blue-200',
        'bg-green-100 border-green-500 text-green-800 hover:bg-green-200',
        'bg-yellow-100 border-yellow-500 text-yellow-800 hover:bg-yellow-200',
        'bg-red-100 border-red-500 text-red-800 hover:bg-red-200',
    ];
    return colors[index % colors.length];
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard de {{ props.auth.user.roles[0] || 'Usuario' }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white p-8 shadow-xl sm:rounded-lg"
                >
                    <h3
                        class="mb-8 border-b pb-3 text-2xl font-bold text-gray-800"
                    >
                        Bienvenido, {{ props.auth.user.name }}
                    </h3>

                    <h4 class="mb-6 text-xl font-semibold text-gray-700">
                        Accesos Rápidos
                    </h4>

                    <div
                        class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
                    >
                        <a
                            v-for="(atajo, index) in atajosAMostrar"
                            :key="index"
                            :href="route(atajo.route)"
                            class="block transform rounded-lg border-l-4 p-6 shadow-md transition duration-150 hover:scale-[1.02]"
                            :class="getColorClass(index)"
                        >
                            <div class="flex items-start space-x-4">
                                <span class="pt-1 text-4xl leading-none">{{
                                    atajo.icon
                                }}</span>
                                <div>
                                    <h4 class="text-lg font-bold">
                                        {{ atajo.title }}
                                    </h4>
                                    <p class="mt-1 text-sm opacity-80">
                                        {{ atajo.description }}
                                    </p>
                                </div>
                            </div>
                        </a>

                        <div
                            v-if="atajosAMostrar.length === 0"
                            class="py-10 text-center text-gray-500 lg:col-span-4"
                        >
                            <p>
                                No tienes accesos rápidos configurados. Tu
                                perfil solo te permite navegar por el menú
                                principal.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
