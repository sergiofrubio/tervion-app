<?php
$pageTitle = "Añadir Historial Médico";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto animate-fade-in-up">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Nuevo Informe Clínico</h1>
            <p class="mt-1 text-sm text-gray-500">Registrar una nueva entrada en el historial de <?= $paciente['nombre'] . ' ' . $paciente['apellidos'] ?>.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/usuarios/detail?id=<?= $paciente['usuario_id'] ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver al perfil
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/historial/create" method="POST" class="p-8 space-y-6">
            <input type="hidden" name="paciente_id" value="<?= $paciente['usuario_id'] ?>">

            <div class="grid grid-cols-1 gap-6">
                <!-- Motivo -->
                <div>
                    <label for="motivo_consulta" class="block text-sm font-bold text-gray-700 mb-2">Motivo de la Consulta</label>
                    <input type="text" name="motivo_consulta" id="motivo_consulta" required
                        class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3 border"
                        placeholder="Ej: Dolor lumbar persistente...">
                </div>

                <!-- Diagnóstico -->
                <div>
                    <label for="diagnostico" class="block text-sm font-bold text-gray-700 mb-2">Diagnóstico</label>
                    <textarea name="diagnostico" id="diagnostico" rows="4" required
                        class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3 border"
                        placeholder="Descripción detallada del diagnóstico..."></textarea>
                </div>

                <!-- Tratamiento -->
                <div>
                    <label for="tratamiento" class="block text-sm font-bold text-gray-700 mb-2">Tratamiento Realizado / Recomendado</label>
                    <textarea name="tratamiento" id="tratamiento" rows="4" required
                        class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3 border"
                        placeholder="Terapia manual, ejercicios, pautas..."></textarea>
                </div>

                <!-- Observaciones -->
                <div>
                    <label for="observaciones" class="block text-sm font-bold text-gray-700 mb-2">Observaciones Adicionales</label>
                    <textarea name="observaciones" id="observaciones" rows="3"
                        class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm p-3 border"
                        placeholder="Notas internas o evolución..."></textarea>
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3">
                <button type="reset" class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Limpiar
                </button>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-8 py-2.5 text-sm font-bold text-white shadow-md hover:bg-primary-700 transition-all hover:scale-105 active:scale-95">
                    <i class="bi bi-check-lg"></i>
                    Guardar Informe
                </button>
            </div>
        </form>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
