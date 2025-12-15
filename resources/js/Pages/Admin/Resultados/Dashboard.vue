<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    Chart as ChartJS,
    ArcElement,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';
import { Doughnut, Bar } from 'vue-chartjs';

ChartJS.register(
    ArcElement,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
);

const props = defineProps({
    estadisticas: Object,
    convocatorias: Array,
    convocatoria_seleccionada: Number,
});

const convocatoriaSeleccionada = ref(props.convocatoria_seleccionada);

// Datos para gráfico de dona (distribución de resultados)
const distribucionData = computed(() => ({
    labels: ['Aprobadas', 'Denegadas', 'En Proceso'],
    datasets: [
        {
            data: [
                props.estadisticas.aprobadas,
                props.estadisticas.denegadas,
                props.estadisticas.en_proceso,
            ],
            backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
            borderWidth: 0,
        },
    ],
}));

// Datos para gráfico de barras (distribución de puntajes)
const puntajesData = computed(() => ({
    labels: ['0-20', '21-40', '41-60', '61-80', '81-100'],
    datasets: [
        {
            label: 'Cantidad de Postulaciones',
            data: [
                props.estadisticas.distribucion_puntajes['0-20'] || 0,
                props.estadisticas.distribucion_puntajes['21-40'] || 0,
                props.estadisticas.distribucion_puntajes['41-60'] || 0,
                props.estadisticas.distribucion_puntajes['61-80'] || 0,
                props.estadisticas.distribucion_puntajes['81-100'] || 0,
            ],
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderRadius: 8,
        },
    ],
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
        },
    },
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB',
        minimumFractionDigits: 0,
    }).format(value);
};

const cambiarConvocatoria = () => {
    window.location.href = route('admin.resultados.dashboard', {
        convocatoria_id: convocatoriaSeleccionada.value,
    });
};
</script>

<template>
    <Head title="Dashboard de Resultados" />

    <AuthenticatedLayout>
        <div
            class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900"
        >
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <div class="mb-4 flex items-center justify-between">
                        <div>
                            <h1 class="mb-2 text-4xl font-bold text-white">
                                📊 Dashboard de Resultados
                            </h1>
                            <p class="text-blue-200">
                                Análisis completo de postulaciones y
                                clasificaciones
                            </p>
                        </div>

                        <!-- Selector de convocatoria -->
                        <div
                            class="rounded-xl bg-white/10 p-4 backdrop-blur-lg"
                        >
                            <label
                                class="mb-2 block text-sm font-semibold text-white"
                            >
                                Convocatoria:
                            </label>
                            <select
                                v-model="convocatoriaSeleccionada"
                                @change="cambiarConvocatoria"
                                class="rounded-lg border border-white/30 bg-white/20 px-4 py-2 text-white focus:ring-2 focus:ring-blue-500"
                            >
                                <option :value="null" class="text-slate-900">
                                    Todas
                                </option>
                                <option
                                    v-for="conv in convocatorias"
                                    :key="conv.id"
                                    :value="conv.id"
                                    class="text-slate-900"
                                >
                                    {{ conv.nombre }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- KPIs principales -->
                <div
                    class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4"
                >
                    <div
                        class="rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-2xl"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-5xl">📝</span>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-blue-100">
                                    Total
                                </p>
                                <p class="text-4xl font-bold">
                                    {{ estadisticas.total_postulaciones }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-blue-100">Postulaciones</p>
                    </div>

                    <div
                        class="rounded-2xl bg-gradient-to-br from-green-500 to-green-600 p-6 text-white shadow-2xl"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-5xl">✅</span>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-green-100">
                                    Aprobadas
                                </p>
                                <p class="text-4xl font-bold">
                                    {{ estadisticas.aprobadas }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-green-100">
                            Tasa: {{ estadisticas.tasa_aprobacion }}%
                        </p>
                    </div>

                    <div
                        class="rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 p-6 text-white shadow-2xl"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-5xl">📊</span>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-amber-100">
                                    Promedio
                                </p>
                                <p class="text-4xl font-bold">
                                    {{ estadisticas.promedio_puntaje }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-amber-100">Puntaje Promedio</p>
                    </div>

                    <div
                        class="rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white shadow-2xl"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-5xl">💰</span>
                            <div class="text-right">
                                <p
                                    class="text-sm font-semibold text-purple-100"
                                >
                                    Ejecutado
                                </p>
                                <p class="text-2xl font-bold">
                                    {{
                                        formatCurrency(
                                            estadisticas.presupuesto_utilizado,
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm text-purple-100">
                            De
                            {{ formatCurrency(estadisticas.presupuesto_total) }}
                        </p>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Distribución de resultados -->
                    <div
                        class="rounded-2xl bg-white/10 p-6 shadow-2xl backdrop-blur-lg"
                    >
                        <h2 class="mb-6 text-xl font-bold text-white">
                            Distribución de Resultados
                        </h2>
                        <div class="h-64">
                            <Doughnut
                                :data="distribucionData"
                                :options="chartOptions"
                            />
                        </div>
                    </div>

                    <!-- Distribución de puntajes -->
                    <div
                        class="rounded-2xl bg-white/10 p-6 shadow-2xl backdrop-blur-lg"
                    >
                        <h2 class="mb-6 text-xl font-bold text-white">
                            Distribución de Puntajes
                        </h2>
                        <div class="h-64">
                            <Bar :data="puntajesData" :options="chartOptions" />
                        </div>
                    </div>
                </div>

                <!-- Rankings por Beca -->
                <div
                    class="mb-8 rounded-2xl bg-white/10 p-6 shadow-2xl backdrop-blur-lg"
                >
                    <h2 class="mb-6 text-2xl font-bold text-white">
                        🎯 Rankings por Beca
                    </h2>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <Link
                            v-for="beca in estadisticas.becas"
                            :key="beca.id"
                            :href="route('admin.becas.ranking', beca.id)"
                            class="group rounded-xl border border-white/20 bg-white/5 p-5 transition-all hover:scale-105 hover:bg-white/10"
                        >
                            <div class="mb-3 flex items-center justify-between">
                                <h3
                                    class="text-lg font-bold text-white transition-colors group-hover:text-blue-300"
                                >
                                    {{ beca.nombre }}
                                </h3>
                                <span class="text-2xl">🏆</span>
                            </div>

                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-2xl font-bold text-blue-300">
                                        {{ beca.cupos }}
                                    </p>
                                    <p class="text-xs text-white/70">Cupos</p>
                                </div>
                                <div>
                                    <p
                                        class="text-2xl font-bold text-green-300"
                                    >
                                        {{ beca.aprobadas }}
                                    </p>
                                    <p class="text-xs text-white/70">
                                        Aprobadas
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-2xl font-bold text-amber-300"
                                    >
                                        {{ beca.tasa_ocupacion }}%
                                    </p>
                                    <p class="text-xs text-white/70">
                                        Ocupación
                                    </p>
                                </div>
                            </div>

                            <!-- Barra de progreso -->
                            <div
                                class="mt-4 h-2 w-full overflow-hidden rounded-full bg-white/20"
                            >
                                <div
                                    class="h-full bg-gradient-to-r from-green-400 to-blue-500 transition-all"
                                    :style="{
                                        width: beca.tasa_ocupacion + '%',
                                    }"
                                ></div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Link
                        :href="route('admin.resultados.comparacion')"
                        class="transform rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 p-6 text-white shadow-2xl transition-all hover:scale-105 hover:from-blue-600 hover:to-blue-700"
                    >
                        <span class="mb-3 block text-4xl">📈</span>
                        <h3 class="mb-2 text-xl font-bold">
                            Comparar Convocatorias
                        </h3>
                        <p class="text-sm text-blue-100">
                            Ver tendencias históricas
                        </p>
                    </Link>

                    <button
                        @click="
                            $inertia.post(
                                route('admin.resultados.exportar', {
                                    tipo: 'ranking_completo',
                                    formato: 'xlsx',
                                }),
                            )
                        "
                        class="transform rounded-2xl bg-gradient-to-br from-green-500 to-green-600 p-6 text-left text-white shadow-2xl transition-all hover:scale-105 hover:from-green-600 hover:to-green-700"
                    >
                        <span class="mb-3 block text-4xl">📥</span>
                        <h3 class="mb-2 text-xl font-bold">
                            Exportar Reportes
                        </h3>
                        <p class="text-sm text-green-100">
                            Descargar en Excel/PDF
                        </p>
                    </button>

                    <button
                        @click="
                            $inertia.post(
                                route('admin.resultados.notificar', {
                                    convocatoria_id: convocatoriaSeleccionada,
                                }),
                            )
                        "
                        class="transform rounded-2xl bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-left text-white shadow-2xl transition-all hover:scale-105 hover:from-purple-600 hover:to-purple-700"
                    >
                        <span class="mb-3 block text-4xl">📧</span>
                        <h3 class="mb-2 text-xl font-bold">
                            Notificar Resultados
                        </h3>
                        <p class="text-sm text-purple-100">
                            Envío masivo de emails
                        </p>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Animaciones para las tarjetas */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.grid > * {
    animation: fadeInUp 0.5s ease-out;
}
</style>
