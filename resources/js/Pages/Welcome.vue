<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="DUBSS - Sistema de Becas" />

    <div
        class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50"
    >
        <!-- Header -->
        <header
            class="fixed left-0 right-0 top-0 z-50 bg-white/80 shadow-sm backdrop-blur-md"
        >
            <div class="mx-auto max-w-7xl px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600"
                        >
                            <svg
                                class="h-6 w-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">
                                DUBSS
                            </h1>
                            <p class="text-xs text-gray-600">
                                Sistema de Becas
                            </p>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav v-if="canLogin" class="flex items-center space-x-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="px-4 py-2 font-medium text-blue-600 transition hover:text-blue-700"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="px-4 py-2 font-medium text-gray-700 transition hover:text-blue-600"
                            >
                                Iniciar Sesión
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-lg bg-blue-600 px-6 py-2 text-white shadow-sm transition hover:bg-blue-700"
                            >
                                Registrarse
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="pt-24">
            <div class="mx-auto max-w-7xl px-6 py-20">
                <div class="grid items-center gap-12 md:grid-cols-2">
                    <!-- Left Content -->
                    <div>
                        <div
                            class="mb-6 inline-block rounded-full bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700"
                        >
                            Dirección Universitaria de Bienestar Social y Salud
                        </div>

                        <h2
                            class="mb-6 text-5xl font-bold leading-tight text-gray-900"
                        >
                            Gestión de Becas<br />
                            <span class="text-blue-600"
                                >Transparente y Eficiente</span
                            >
                        </h2>

                        <p class="mb-8 text-xl leading-relaxed text-gray-600">
                            Sistema integral para la administración de becas
                            socioeconómicas. Digitaliza tu postulación y realiza
                            seguimiento en tiempo real.
                        </p>

                        <div class="flex flex-wrap gap-4">
                            <Link
                                v-if="!$page.props.auth.user"
                                :href="route('register')"
                                class="rounded-lg bg-blue-600 px-8 py-4 font-semibold text-white shadow-lg transition hover:bg-blue-700 hover:shadow-xl"
                            >
                                Postular Ahora
                            </Link>

                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="rounded-lg bg-blue-600 px-8 py-4 font-semibold text-white shadow-lg transition hover:bg-blue-700 hover:shadow-xl"
                            >
                                Ir al Dashboard
                            </Link>

                            <a
                                href="#caracteristicas"
                                class="rounded-lg border-2 border-gray-300 px-8 py-4 font-semibold text-gray-700 transition hover:border-blue-600 hover:text-blue-600"
                            >
                                Conocer Más
                            </a>
                        </div>
                    </div>

                    <!-- Right Illustration -->
                    <div class="hidden md:block">
                        <div class="relative">
                            <div
                                class="absolute inset-0 rotate-6 transform rounded-2xl bg-blue-600 opacity-10"
                            ></div>
                            <div
                                class="relative rounded-2xl bg-white p-8 shadow-2xl"
                            >
                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100"
                                        >
                                            <svg
                                                class="h-6 w-6 text-green-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <p
                                                class="font-semibold text-gray-900"
                                            >
                                                Proceso Digital
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Sin filas ni trámites
                                                presenciales
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100"
                                        >
                                            <svg
                                                class="h-6 w-6 text-blue-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <p
                                                class="font-semibold text-gray-900"
                                            >
                                                Resultados Rápidos
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Clasificación automática
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100"
                                        >
                                            <svg
                                                class="h-6 w-6 text-purple-600"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                                />
                                            </svg>
                                        </div>
                                        <div>
                                            <p
                                                class="font-semibold text-gray-900"
                                            >
                                                Totalmente Transparente
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                Seguimiento en tiempo real
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="caracteristicas" class="bg-white py-20">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="mb-16 text-center">
                        <h3 class="mb-4 text-3xl font-bold text-gray-900">
                            ¿Por qué elegir DUBSS?
                        </h3>
                        <p class="text-lg text-gray-600">
                            Un sistema diseñado para estudiantes y
                            administradores
                        </p>
                    </div>

                    <div class="grid gap-8 md:grid-cols-3">
                        <!-- Feature 1 -->
                        <div
                            class="rounded-xl border border-gray-100 p-8 transition hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-blue-100"
                            >
                                <svg
                                    class="h-7 w-7 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <h4 class="mb-3 text-xl font-bold text-gray-900">
                                App Móvil
                            </h4>
                            <p class="text-gray-600">
                                Completa tu formulario socioeconómico desde tu
                                teléfono en cualquier momento.
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div
                            class="rounded-xl border border-gray-100 p-8 transition hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-green-100"
                            >
                                <svg
                                    class="h-7 w-7 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                                    />
                                </svg>
                            </div>
                            <h4 class="mb-3 text-xl font-bold text-gray-900">
                                Seguimiento en Tiempo Real
                            </h4>
                            <p class="text-gray-600">
                                Conoce el estado de tu postulación en cada etapa
                                del proceso.
                            </p>
                        </div>

                        <!-- Feature 3 -->
                        <div
                            class="rounded-xl border border-gray-100 p-8 transition hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-purple-100"
                            >
                                <svg
                                    class="h-7 w-7 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                    />
                                </svg>
                            </div>
                            <h4 class="mb-3 text-xl font-bold text-gray-900">
                                Seguro y Confiable
                            </h4>
                            <p class="text-gray-600">
                                Tus datos están protegidos con los más altos
                                estándares de seguridad.
                            </p>
                        </div>

                        <!-- Feature 4 -->
                        <div
                            class="rounded-xl border border-gray-100 p-8 transition hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-yellow-100"
                            >
                                <svg
                                    class="h-7 w-7 text-yellow-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"
                                    />
                                </svg>
                            </div>
                            <h4 class="mb-3 text-xl font-bold text-gray-900">
                                Notificaciones
                            </h4>
                            <p class="text-gray-600">
                                Recibe alertas sobre cambios en tu postulación y
                                nuevas convocatorias.
                            </p>
                        </div>

                        <!-- Feature 5 -->
                        <div
                            class="rounded-xl border border-gray-100 p-8 transition hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-red-100"
                            >
                                <svg
                                    class="h-7 w-7 text-red-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>
                            <h4 class="mb-3 text-xl font-bold text-gray-900">
                                Gestión de Documentos
                            </h4>
                            <p class="text-gray-600">
                                Sube y administra toda tu documentación de forma
                                digital.
                            </p>
                        </div>

                        <!-- Feature 6 -->
                        <div
                            class="rounded-xl border border-gray-100 p-8 transition hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-14 w-14 items-center justify-center rounded-lg bg-indigo-100"
                            >
                                <svg
                                    class="h-7 w-7 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                    />
                                </svg>
                            </div>
                            <h4 class="mb-3 text-xl font-bold text-gray-900">
                                Dashboards
                            </h4>
                            <p class="text-gray-600">
                                Visualiza estadísticas y métricas del proceso de
                                becas.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 py-20">
                <div class="mx-auto max-w-4xl px-6 text-center">
                    <h3 class="mb-6 text-3xl font-bold text-white">
                        ¿Listo para postular a una beca?
                    </h3>
                    <p class="mb-8 text-xl text-blue-100">
                        Regístrate ahora y comienza tu proceso de postulación
                    </p>
                    <div class="flex justify-center gap-4">
                        <Link
                            v-if="!$page.props.auth.user && canRegister"
                            :href="route('register')"
                            class="rounded-lg bg-white px-8 py-4 font-semibold text-blue-600 shadow-lg transition hover:bg-gray-50"
                        >
                            Crear Cuenta
                        </Link>
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('login')"
                            class="rounded-lg border-2 border-white px-8 py-4 font-semibold text-white transition hover:bg-white/10"
                        >
                            Ya tengo cuenta
                        </Link>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 py-12 text-gray-400">
            <div class="mx-auto max-w-7xl px-6">
                <div class="grid gap-8 md:grid-cols-3">
                    <div>
                        <h5 class="mb-4 font-bold text-white">DUBSS</h5>
                        <p class="text-sm">
                            Dirección Universitaria de Bienestar Social y Salud
                        </p>
                    </div>
                    <div>
                        <h5 class="mb-4 font-bold text-white">Enlaces</h5>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="#" class="transition hover:text-white"
                                    >Convocatorias</a
                                >
                            </li>
                            <li>
                                <a href="#" class="transition hover:text-white"
                                    >Requisitos</a
                                >
                            </li>
                            <li>
                                <a href="#" class="transition hover:text-white"
                                    >Preguntas Frecuentes</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="mb-4 font-bold text-white">Contacto</h5>
                        <ul class="space-y-2 text-sm">
                            <li>Email: becas@universidad.edu</li>
                            <li>Teléfono: (591) 2-1234567</li>
                            <li>Horario: Lun-Vie 8:00-16:00</li>
                        </ul>
                    </div>
                </div>
                <div
                    class="mt-8 border-t border-gray-800 pt-8 text-center text-sm"
                >
                    <p>&copy; 2025 DUBSS. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>
    </div>
</template>
