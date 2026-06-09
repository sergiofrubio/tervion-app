<?php
$b = $bono ?? [];
$isEdit = !empty($b);
$pageTitle = $isEdit ? "Editar Bono" : "Nuevo Bono";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto space-y-6 animate-fade-in-up">
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/configuracion" class="p-2 rounded-xl bg-white border border-gray-100 text-gray-400 hover:text-primary-600 transition-all shadow-sm">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Bono" : "Nuevo Bono" ?></h1>
            <p class="text-sm text-gray-500"><?= $isEdit ? "Modifica los detalles del bono de sesiones." : "Crea un nuevo bono de sesiones para los pacientes." ?></p>
        </div>
    </div>

    <form action="<?= PROJECT_ROOT ?>/configuracion/bonos/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 space-y-6">
        <?php if ($isEdit) : ?>
            <input type="hidden" name="bono_id" value="<?= $b['bono_id'] ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Bono</label>
                <input type="text" name="nombre" id="nombre" required 
                    value="<?= htmlspecialchars($b['nombre'] ?? '') ?>"
                    class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm" placeholder="Ej. Bono 10 Sesiones">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="numero_sesiones" class="block text-sm font-semibold text-gray-700 mb-2">Número de Sesiones</label>
                    <input type="number" name="numero_sesiones" id="numero_sesiones" required min="1" 
                        value="<?= $b['numero_sesiones'] ?? '' ?>"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                </div>
                <div>
                    <label for="precio" class="block text-sm font-semibold text-gray-700 mb-2">Precio (€)</label>
                    <input type="number" step="0.01" name="precio" id="precio" required min="0" 
                        value="<?= $b['precio'] ?? '' ?>"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                </div>
            </div>

            <div>
                <label for="estado" class="block text-sm font-semibold text-gray-700 mb-2">Estado</label>
                <select name="estado" id="estado" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:border-primary-500 focus:ring-primary-500 transition-all sm:text-sm">
                    <option value="Activo" <?= ($b['estado'] ?? '') === 'Activo' ? 'selected' : '' ?>>Activo</option>
                    <option value="Inactivo" <?= ($b['estado'] ?? '') === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                <i class="bi bi-check2"></i>
                <?= $isEdit ? 'Guardar Cambios' : 'Guardar Bono' ?>
            </button>
        </div>
    </form>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
