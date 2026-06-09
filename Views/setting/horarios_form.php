<?php
$h = $horario ?? [];
$isEdit = !empty($h);
$pageTitle = $isEdit ? "Editar Horario" : "Nuevo Horario";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/configuracion" class="p-2 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-primary-600 transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Horario" : "Nuevo Horario" ?></h1>
            <p class="text-sm text-gray-500"><?= $isEdit ? "Modifica el horario de atención." : "Asigna un nuevo horario de atención a un fisioterapeuta." ?></p>
        </div>
    </div>

    <form action="<?= PROJECT_ROOT ?>/configuracion/horarios/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        <?php if ($isEdit) : ?>
            <input type="hidden" name="horario_id" value="<?= $h['horario_id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="fisioterapeuta_id" class="block text-sm font-semibold text-gray-700 mb-2">Fisioterapeuta</label>
                <select name="fisioterapeuta_id" id="fisioterapeuta_id" required class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                    <?php foreach ($fisios as $fisio): ?>
                        <option value="<?= $fisio['usuario_id'] ?>" <?= ($h['fisioterapeuta_id'] ?? '') == $fisio['usuario_id'] ? 'selected' : '' ?>>
                            <?= $fisio['nombre'] . ' ' . $fisio['apellidos'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="dia_semana" class="block text-sm font-semibold text-gray-700 mb-2">Día de la Semana</label>
                <select name="dia_semana" id="dia_semana" required class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                    <?php 
                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    foreach ($dias as $dia): 
                    ?>
                        <option value="<?= $dia ?>" <?= ($h['dia_semana'] ?? '') === $dia ? 'selected' : '' ?>><?= $dia ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="hora_inicio" class="block text-sm font-semibold text-gray-700 mb-2">Hora Inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" required 
                        value="<?= isset($h['hora_inicio']) ? date('H:i', strtotime($h['hora_inicio'])) : '' ?>"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                </div>
                <div>
                    <label for="hora_fin" class="block text-sm font-semibold text-gray-700 mb-2">Hora Fin</label>
                    <input type="time" name="hora_fin" id="hora_fin" required 
                        value="<?= isset($h['hora_fin']) ? date('H:i', strtotime($h['hora_fin'])) : '' ?>"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-check2"></i>
                <?= $isEdit ? 'Guardar Cambios' : 'Guardar Horario' ?>
            </button>
        </div>
    </form>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
