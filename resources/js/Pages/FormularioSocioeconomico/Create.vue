<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const currentStep = ref(1);
const totalSteps = 4;
//const listaBecas = ref([]);
const convocatorias = ref([]);
const form = useForm({
    ci_estudiante: '',
    id_convocatoria: '',
    id_beca: '',
    fecha_llenado: new Date().toISOString().split('T')[0],
    telefono_referencia: '',
    lugar_procedencia: '',
    comentario_personal: '',

    discapacidad: false,
    comentario_discapacidad: '',
    otro_beneficio: false,
    comentario_otro_beneficio: '',
    observaciones: '',

    grupo_familiar: {
        tiene_hijos: false,
        cantidad_hijos: 0,
        cantidad_familiares: 0,
        miembros: [],
    },

    residencia: {
        provincia: '',
        zona: '',
        barrio: '',
        calle: '',
        cant_dormitorios: 0,
        cant_banhos: 0,
        cant_salas: 0,
        cantt_comedor: 0,
        cant_patios: 0,
    },
    tenencia: {
        tipo_tenencia: 'PROPIA',
        detalle_tenencia: '',
    },

    economica: {
        tipo_dependencia: 'PADRES',
        nota_ocupacion: '',
        rango_ingreso: '',
        ocupacion_nombre: '',
    },
});

const nextStep = () => {
    if (currentStep.value < totalSteps) currentStep.value++;
};
const prevStep = () => {
    if (currentStep.value > 1) currentStep.value--;
};

const nuevoMiembro = ref({
    nombre_completo: '',
    parentesco: '',
    edad: '',
    ocupacion: '',
});

const agregarMiembro = () => {
    if (nuevoMiembro.value.nombre_completo && nuevoMiembro.value.parentesco) {
        form.grupo_familiar.miembros.push({ ...nuevoMiembro.value });
        nuevoMiembro.value = {
            nombre_completo: '',
            parentesco: '',
            edad: '',
            ocupacion: '',
        };
        form.grupo_familiar.cantidad_familiares =
            form.grupo_familiar.miembros.length;
    }
};

const eliminarMiembro = (index) => {
    form.grupo_familiar.miembros.splice(index, 1);
    form.grupo_familiar.cantidad_familiares =
        form.grupo_familiar.miembros.length;
};

const submit = () => {
    form.post(route('admin.formularios.store'), {
        preserveScroll: true,
        onSuccess: () => {
            alert('¡Formulario, Postulación y Trámite creados con éxito!');
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors);
            alert(
                'Hubo un error al guardar. Revisa los campos marcados en rojo.',
            );
        },
    });
};
onMounted(() => {
    fetchConvocatoriasActivas();
});

const progressWidth = computed(
    () => `${(currentStep.value / totalSteps) * 100}%`,
);

const becasFiltradas = computed(() => {
    if (!form.id_convocatoria) {
        return [];
    }
    const selectedConvocatoria = convocatorias.value.find(
        (conv) => conv.id === form.id_convocatoria,
    );
    //console.log('Convocatoria seleccionada:', selectedConvocatoria.becas);
    return selectedConvocatoria ? selectedConvocatoria.becas : [];
});

const fetchConvocatoriasActivas = async () => {
    try {
        const response = await axios.get(route('convocatorias.activas'));

        if (response.data.success) {
            convocatorias.value = response.data.convocatorias;
        } else {
            console.error(
                'Error al cargar convocatorias activas:',
                response.data.message,
            );
        }
    } catch (error) {
        console.error('Error de red al cargar convocatorias activas:', error);
    }
};
</script>

<template>
    <Head title="Nuevo Formulario Socioeconómico" />

    <div class="mx-auto max-w-5xl p-6">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Formulario Socioeconómico
            </h1>
        </div>

        <div class="mb-6 h-2.5 w-full rounded-full bg-gray-200">
            <div
                class="h-2.5 rounded-full bg-blue-600 transition-all duration-300"
                :style="{ width: progressWidth }"
            ></div>
        </div>

        <form
            @submit.prevent="submit"
            class="rounded-lg border border-gray-100 bg-white p-6 shadow-lg"
        >
            <div v-show="currentStep === 1">
                <h2
                    class="mb-4 border-b pb-2 text-xl font-semibold text-blue-800"
                >
                    1. Datos Generales del Postulante
                </h2>

                <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- CI DDEL POSTULANTE-->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >CI Estudiante</label
                        >
                        <input
                            v-model="form.ci_estudiante"
                            type="text"
                            required
                            placeholder="Ej: 8345678"
                            class="w-full rounded-md border-gray-300"
                        />
                        <small
                            >*Asegurese de colocar correctamente el numero de su
                            documento</small
                        >
                        <div
                            v-if="form.errors.ci_estudiante"
                            class="text-xs text-red-500"
                        >
                            {{ form.errors.ci_estudiante }}
                        </div>
                    </div>
                    <!-- SELECT DE LA CONVOCATORIA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Convocatoria
                        </label>
                        <select
                            v-model="form.id_convocatoria"
                            required
                            class="w-full rounded-md border-gray-300"
                        >
                            <option value="" disabled>
                                Seleccione Convocatoria
                            </option>
                            <option
                                v-for="conv in convocatorias"
                                :key="conv.id"
                                :value="conv.id"
                            >
                                {{ conv.nombre }}
                            </option>
                        </select>
                    </div>
                    <!-- SELECT DE LA BECA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Beca Disponible</label
                        >
                        <select
                            v-model="form.id_beca"
                            required
                            :disabled="
                                !form.id_convocatoria ||
                                becasFiltradas.length === 0
                            "
                            class="w-full rounded-md border-gray-300 disabled:bg-gray-100"
                        >
                            <option value="" disabled>
                                {{
                                    form.id_convocatoria
                                        ? 'Seleccione Beca'
                                        : 'Elija Convocatoria primero'
                                }}
                            </option>
                            <option
                                v-for="beca in becasFiltradas"
                                :key="beca.id"
                                :value="beca.id"
                            >
                                {{ beca.nombre }}
                            </option>
                        </select>
                        <div
                            v-if="form.errors.id_beca"
                            class="text-xs text-red-500"
                        >
                            {{ form.errors.id_beca }}
                        </div>
                    </div>
                    <!-- FECHA DE LLENADO
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Fecha Llenado</label
                        >
                        <input
                            v-model="form.fecha_llenado"
                            type="date"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        />

                    </div>
                    -->
                    <!-- TELÉFONO DE REFERENCIA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Teléfono Referencia</label
                        >
                        <input
                            v-model="form.telefono_referencia"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        />
                    </div>
                    <!-- TELÉFONO DE REFERENCIA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Teléfono Referencia</label
                        >
                        <input
                            v-model="form.telefono_referencia"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        />
                    </div>
                    <!-- LUGAR DE PROCEDENCIA AQUI DEBO COLOCAR LOS LUGARES DONDE SE ENCUENTRA EL POSTULANTE ???-->

                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Lugar de Procedencia</label
                        >
                        <input
                            v-model="form.lugar_procedencia"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        />
                        <small>
                            *Adjuntar acreditacion actual de la misma
                        </small>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input
                                v-model="form.discapacidad"
                                id="discapacidad"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label
                                for="discapacidad"
                                class="font-medium text-gray-700"
                                >¿Tiene alguna discapacidad?</label
                            >
                        </div>
                    </div>
                    <div v-if="form.discapacidad" class="ml-7">
                        <input
                            v-model="form.comentario_discapacidad"
                            type="text"
                            placeholder="Detalle la discapacidad..."
                            class="block w-full rounded-md border-gray-300 text-sm shadow-sm"
                        />
                    </div>

                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input
                                v-model="form.otro_beneficio"
                                id="beneficio"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label
                                for="beneficio"
                                class="font-medium text-gray-700"
                                >¿Recibe otro beneficio/beca externa?</label
                            >
                        </div>
                    </div>
                    <div v-if="form.otro_beneficio" class="ml-7">
                        <input
                            v-model="form.comentario_otro_beneficio"
                            type="text"
                            placeholder="Detalle el beneficio..."
                            class="block w-full rounded-md border-gray-300 text-sm shadow-sm"
                        />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700"
                        >Comentario Personal / Situación</label
                    >
                    <textarea
                        v-model="form.comentario_personal"
                        rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                    ></textarea>
                </div>
            </div>

            <div v-show="currentStep === 2">
                <h2
                    class="mb-4 border-b pb-2 text-xl font-semibold text-blue-800"
                >
                    2. Grupo Familiar
                </h2>

                <div
                    class="mb-6 grid grid-cols-1 gap-4 rounded-lg bg-gray-50 p-4 md:grid-cols-2"
                >
                    <div class="flex items-center">
                        <input
                            v-model="form.grupo_familiar.tiene_hijos"
                            id="hijos"
                            type="checkbox"
                            class="mr-2 h-4 w-4 rounded border-gray-300 text-blue-600"
                        />
                        <label
                            for="hijos"
                            class="text-sm font-medium text-gray-700"
                        >
                            ¿Tiene hijos propios?
                        </label>
                    </div>
                    <div v-if="form.grupo_familiar.tiene_hijos">
                        <label class="block text-sm font-medium text-gray-700">
                            Cantidad de Hijos
                        </label>
                        <input
                            v-model="form.grupo_familiar.cantidad_hijos"
                            type="number"
                            min="1"
                            class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm"
                        />
                    </div>
                </div>

                <div class="mb-2">
                    <h3 class="font-medium text-gray-800">
                        Agregar Miembros (Padres, Hermanos, etc.)
                    </h3>
                    <p class="mb-2 text-xs text-gray-500">
                        Total registrados:
                        {{ form.grupo_familiar.cantidad_familiares }}
                    </p>

                    <div
                        class="grid grid-cols-1 items-end gap-2 rounded-md bg-blue-50 p-3 md:grid-cols-5"
                    >
                        <div class="md:col-span-2">
                            <label class="text-xs text-gray-600"
                                >Nombre Completo</label
                            >
                            <input
                                v-model="nuevoMiembro.nombre_completo"
                                type="text"
                                class="w-full rounded-md border-gray-300 text-sm"
                            />
                        </div>
                        <div>
                            <label class="text-xs text-gray-600"
                                >Parentesco</label
                            >
                            <select
                                v-model="nuevoMiembro.parentesco"
                                class="w-full rounded-md border-gray-300 text-sm"
                            >
                                <option value="">Sel...</option>
                                <option value="PADRE">Padre</option>
                                <option value="MADRE">Madre</option>
                                <option value="HERMANO/A">Hermano/a</option>
                                <option value="ABUELO/A">Abuelo/a</option>
                                <option value="TIO/A">Tío/a</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-gray-600">Edad</label>
                            <input
                                v-model="nuevoMiembro.edad"
                                type="number"
                                class="w-full rounded-md border-gray-300 text-sm"
                            />
                        </div>
                        <div>
                            <button
                                @click.prevent="agregarMiembro"
                                type="button"
                                class="w-full rounded-md bg-blue-600 px-3 py-2 text-sm text-white hover:bg-blue-700"
                            >
                                + Agregar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Nombre
                                </th>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Parentesco
                                </th>
                                <th
                                    class="px-3 py-2 text-left text-xs font-medium uppercase text-gray-500"
                                >
                                    Edad
                                </th>
                                <th
                                    class="px-3 py-2 text-right text-xs font-medium uppercase text-gray-500"
                                >
                                    Acción
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr
                                v-for="(miembro, index) in form.grupo_familiar
                                    .miembros"
                                :key="index"
                            >
                                <td class="px-3 py-2 text-sm text-gray-900">
                                    {{ miembro.nombre_completo }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500">
                                    {{ miembro.parentesco }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500">
                                    {{ miembro.edad }}
                                </td>
                                <td class="px-3 py-2 text-right text-sm">
                                    <button
                                        @click.prevent="eliminarMiembro(index)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                            <tr
                                v-if="form.grupo_familiar.miembros.length === 0"
                            >
                                <td
                                    colspan="4"
                                    class="px-3 py-4 text-center text-sm text-gray-400"
                                >
                                    No hay miembros agregados aún.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-show="currentStep === 3">
                <h2
                    class="mb-4 border-b pb-2 text-xl font-semibold text-blue-800"
                >
                    3. Vivienda y Residencia
                </h2>

                <h3 class="mb-2 font-medium text-gray-700">Ubicación</h3>
                <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <input
                        v-model="form.residencia.provincia"
                        type="text"
                        placeholder="Provincia"
                        class="rounded-md border-gray-300 shadow-sm"
                    />
                    <input
                        v-model="form.residencia.zona"
                        type="text"
                        placeholder="Zona"
                        class="rounded-md border-gray-300 shadow-sm"
                    />
                    <input
                        v-model="form.residencia.barrio"
                        type="text"
                        placeholder="Barrio"
                        class="rounded-md border-gray-300 shadow-sm"
                    />
                    <input
                        v-model="form.residencia.calle"
                        type="text"
                        placeholder="Calle / Av"
                        class="rounded-md border-gray-300 shadow-sm"
                    />
                </div>

                <h3 class="mb-2 mt-6 font-medium text-gray-700">
                    Características del Inmueble
                </h3>
                <div class="mb-4 grid grid-cols-2 gap-4 md:grid-cols-5">
                    <div>
                        <label class="block text-xs text-gray-500"
                            >Dormitorios</label
                        >
                        <input
                            v-model="form.residencia.cant_dormitorios"
                            type="number"
                            class="w-full rounded-md border-gray-300"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Baños</label>
                        <input
                            v-model="form.residencia.cant_banhos"
                            type="number"
                            class="w-full rounded-md border-gray-300"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500">Salas</label>
                        <input
                            v-model="form.residencia.cant_salas"
                            type="number"
                            class="w-full rounded-md border-gray-300"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500"
                            >Comedores</label
                        >
                        <input
                            v-model="form.residencia.cantt_comedor"
                            type="number"
                            class="w-full rounded-md border-gray-300"
                        />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500"
                            >Patios</label
                        >
                        <input
                            v-model="form.residencia.cant_patios"
                            type="number"
                            class="w-full rounded-md border-gray-300"
                        />
                    </div>
                </div>

                <h3 class="mb-2 mt-6 font-medium text-gray-700">Tenencia</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Tipo Tenencia</label
                        >
                        <select
                            v-model="form.tenencia.tipo_tenencia"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        >
                            <option value="PROPIA">Propia</option>
                            <option value="ALQUILADA">Alquilada</option>
                            <option value="ANTICRETICO">Anticrético</option>
                            <option value="CEDIDA">Cedida / Prestada</option>
                            <option value="PAGANDO">
                                Pagando al banco/crédito
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700"
                            >Detalle Adicional</label
                        >
                        <input
                            v-model="form.tenencia.detalle_tenencia"
                            type="text"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        />
                    </div>
                </div>
            </div>

            <div v-show="currentStep === 4">
                <h2
                    class="mb-4 border-b pb-2 text-xl font-semibold text-blue-800"
                >
                    4. Situación Económica
                </h2>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Dependencia Económica (¿De quién depende?)</label
                        >
                        <select
                            v-model="form.economica.tipo_dependencia"
                            class="block w-full rounded-md border-gray-300 shadow-sm"
                        >
                            <option value="PADRES">Ambos Padres</option>
                            <option value="SOLO_PADRE">Solo Padre</option>
                            <option value="SOLO_MADRE">Solo Madre</option>
                            <option value="TUTOR">Tutor / Familiar</option>
                            <option value="INDEPENDIENTE">
                                Autosustento (Trabaja)
                            </option>
                            <option value="ESPOSO">Esposo/a</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Ocupación del Sostén Económico</label
                        >
                        <input
                            v-model="form.economica.ocupacion_nombre"
                            type="text"
                            placeholder="Ej: Comerciante, Albañil, Profesor..."
                            class="block w-full rounded-md border-gray-300 shadow-sm"
                        />
                    </div>

                    <div>
                        <label
                            class="mb-1 block text-sm font-medium text-gray-700"
                            >Notas sobre la ocupación (Opcional)</label
                        >
                        <textarea
                            v-model="form.economica.nota_ocupacion"
                            rows="2"
                            class="block w-full rounded-md border-gray-300 shadow-sm"
                        ></textarea>
                    </div>

                    <div
                        class="rounded-md border border-yellow-200 bg-yellow-50 p-4"
                    >
                        <label
                            class="mb-2 block text-sm font-bold text-gray-800"
                            >Rango de Ingreso Mensual Familiar (Bs.)</label
                        >
                        <select
                            v-model="form.economica.rango_ingreso"
                            class="block w-full rounded-md border-gray-300 shadow-sm"
                        >
                            <option value="">Seleccione...</option>
                            <option value="MENOS_2000">
                                Menos de 2.000 Bs
                            </option>
                            <option value="MENOS_2500">
                                Menor a Bs. 2.500
                            </option>
                            <option value="3000_4500">
                                De Bs. 2.501 a Bs. 4.000
                            </option>
                            <option value="4500_6000">
                                De Bs. 4.001 a Bs. 6.000
                            </option>
                            <option value="MAS_6000">Más de 6.000 Bs</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">
                            Sumar ingresos de todos los miembros que aportan.
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="mt-8 flex justify-between border-t border-gray-100 pt-4"
            >
                <button
                    v-if="currentStep > 1"
                    type="button"
                    @click="prevStep"
                    class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 hover:bg-gray-50"
                >
                    Atrás
                </button>
                <div v-else></div>
                <button
                    v-if="currentStep < totalSteps"
                    type="button"
                    @click="nextStep"
                    class="rounded-md bg-blue-600 px-6 py-2 font-medium text-white hover:bg-blue-700"
                >
                    Siguiente
                </button>

                <div
                    class="mt-8 flex justify-between border-t border-gray-100 pt-4"
                >
                    <button
                        v-if="currentStep === totalSteps"
                        type="submit"
                        :disabled="form.processing"
                        class="rounded-md bg-green-600 px-6 py-2 font-bold text-white hover:bg-green-700 disabled:opacity-50"
                    >
                        {{
                            form.processing
                                ? 'Guardando...'
                                : 'Finalizar y Guardar'
                        }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
