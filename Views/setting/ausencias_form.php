<?php
$a = $ausencia ?? [];
$isEdit = !empty($a);
$pageTitle = $isEdit ? "Editar Ausencia" : "Registrar Ausencia";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/configuracion" class="p-2 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-primary-600 transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Ausencia" : "Registrar Ausencia" ?></h1>
            <p class="text-sm text-gray-500"><?= $isEdit ? "Modifica los detalles de la ausencia." : "Registra un periodo de ausencia para un fisioterapeuta." ?></p>
        </div>
    </div>

    <form action="<?= PROJECT_ROOT ?>/configuracion/ausencias/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        <?php if ($isEdit) : ?>
            <input type="hidden" name="ausencia_id" value="<?= $a['ausencia_id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="fisioterapeuta_id" class="block text-sm font-semibold text-gray-700 mb-2">Fisioterapeuta</label>
                <select name="fisioterapeuta_id" id="fisioterapeuta_id" required class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                    <?php foreach ($fisios as $fisio): ?>
                        <option value="<?= $fisio['usuario_id'] ?>" <?= ($a['fisioterapeuta_id'] ?? '') == $fisio['usuario_id'] ? 'selected' : '' ?>>
                            <?= $fisio['nombre'] . ' ' . $fisio['apellidos'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required 
                        value="<?= $a['fecha_inicio'] ?? '' ?>"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                </div>
                <div>
                    <label for="fecha_fin" class="block text-sm font-semibold text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date" name="fecha_fin" id="fecha_fin" required 
                        value="<?= $a['fecha_fin'] ?? '' ?>"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                </div>
            </div>

            <div>
                <label for="motivo" class="block text-sm font-semibold text-gray-700 mb-2">Motivo</label>
                <textarea name="motivo" id="motivo" rows="3" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm" placeholder="Ej. Vacaciones, Baja médica..."><?= htmlspecialchars($a['motivo'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-check2"></i>
                <?= $isEdit ? 'Guardar Cambios' : 'Guardar Ausencia' ?>
            </button>
        </div>
    </form>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
