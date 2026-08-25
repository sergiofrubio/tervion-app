<?php
$pageTitle = "Añadir Historial Médico";
include TEMPLATE_DIR . 'header.php';
?>

<div class="w-full space-y-6 animate-fade-in-up">
    <!-- Header / Barra superior -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Nuevo Informe Clínico</h1>
            <p class="mt-0.5 text-xs sm:text-sm text-gray-500">
                Registrar una nueva entrada en el historial de <span class="font-semibold text-gray-800"><?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?></span> (NHC: #<?= htmlspecialchars($paciente['usuario_id']) ?>).
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $paciente['usuario_id'] ?>" onclick="if (history.length > 1) { history.back(); return false; }" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-xs sm:text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Volver</span>
            </a>
        </div>
    </div>

    <!-- Formulario a ancho completo con cuadrícula en dos columnas -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/historial/crear" method="POST" class="p-6 sm:p-8 space-y-6">
            <input type="hidden" name="paciente_id" value="<?= $paciente['usuario_id'] ?>">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Motivo de la consulta (Ocupa ambas columnas) -->
                <div class="lg:col-span-2">
                    <label for="motivo_consulta" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        Motivo de la Consulta <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="motivo_consulta" id="motivo_consulta" required
                        class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3.5 border bg-slate-50/50 focus:bg-white transition-colors"
                        placeholder="Ej: Dolor lumbar agudo tras esfuerzo físico, recidiva de molestia cervical...">
                </div>

                <!-- Diagnóstico / Evaluación -->
                <div class="flex flex-col">
                    <label for="diagnostico" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        Diagnóstico / Evaluación Clínica <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="diagnostico" id="diagnostico" rows="6" required
                        class="block w-full flex-1 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3.5 border bg-slate-50/50 focus:bg-white transition-colors resize-y min-h-[160px]"
                        placeholder="Descripción detallada de la evaluación, test realizados, palpación y diagnóstico clínico..."></textarea>
                </div>

                <!-- Tratamiento Realizado / Recomendado -->
                <div class="flex flex-col">
                    <label for="tratamiento" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        Tratamiento Realizado / Recomendado <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="tratamiento" id="tratamiento" rows="6" required
                        class="block w-full flex-1 rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3.5 border bg-slate-50/50 focus:bg-white transition-colors resize-y min-h-[160px]"
                        placeholder="Terapia manual aplicada, vendajes, electroterapia, ejercicios prescritos y recomendaciones..."></textarea>
                </div>

                <!-- Observaciones Adicionales (Ocupa ambas columnas) -->
                <div class="lg:col-span-2">
                    <label for="observaciones" class="block text-xs sm:text-sm font-bold text-gray-700 mb-2">
                        Observaciones Adicionales / Notas de Evolución
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="3"
                        class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3.5 border bg-slate-50/50 focus:bg-white transition-colors resize-y"
                        placeholder="Comentarios internos, próxima revisión sugerida, precauciones o evolución del paciente..."></textarea>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="reset" class="px-5 py-2.5 text-xs sm:text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Limpiar campos
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 sm:px-8 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-primary-700 transition-all hover:scale-105 active:scale-95">
                    <i class="bi bi-check-lg text-base"></i>
                    <span>Guardar Informe</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>