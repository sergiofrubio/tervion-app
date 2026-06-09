<?php
$e = $especialidad ?? [];
$isEdit = !empty($e);
$pageTitle = $isEdit ? "Editar Especialidad" : "Nueva Especialidad";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/configuracion" class="p-2 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-primary-600 transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Especialidad" : "Nueva Especialidad" ?></h1>
            <p class="text-sm text-gray-500"><?= $isEdit ? "Modifica la descripción de la especialidad." : "Añade una nueva especialidad médica al catálogo." ?></p>
        </div>
    </div>

    <form action="<?= PROJECT_ROOT ?>/configuracion/especialidades/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        <?php if ($isEdit) : ?>
            <input type="hidden" name="especialidad_id" value="<?= $e['especialidad_id'] ?>">
        <?php endif; ?>

        <div>
            <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-2">Nombre de la Especialidad</label>
            <input type="text" name="descripcion" id="descripcion" required 
                value="<?= htmlspecialchars($e['descripcion'] ?? '') ?>"
                class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm" placeholder="Ej. Fisioterapia Osteopática">
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-check2"></i>
                <?= $isEdit ? 'Guardar Cambios' : 'Guardar Especialidad' ?>
            </button>
        </div>
    </form>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
