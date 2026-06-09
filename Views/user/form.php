<?php
$u = $usuario ?? [];
$isEdit = !empty($u);
$pageTitle = $isEdit ? "Editar Usuario" : "Agregar Usuario";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto animate-fade-in-up">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Usuario" : "Nuevo Usuario" ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= $isEdit ? "Modifica la información del usuario seleccionado." : "Completa la información para registrar un nuevo usuario en el sistema." ?></p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/usuarios" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/usuarios/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="p-8">
            <?php if ($isEdit): ?>
                <input type="hidden" name="original_id" value="<?= $u['usuario_id'] ?>">
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Información Personal Section -->
                <div class="space-y-6 md:col-span-2 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person text-primary-600"></i>
                        Información Personal
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="usuario_id" class="block text-sm font-medium text-gray-700">DNI / NIE / ID</label>
                    <input type="text" name="usuario_id" id="usuario_id" required maxlength="9"
                        value="<?= htmlspecialchars($u['usuario_id'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. 12345678X">
                    <?php if ($isEdit): ?>
                        <p class="text-xs text-amber-600 font-medium"><i class="bi bi-exclamation-triangle"></i> Si cambias el ID, asegúrate de que sea correcto.</p>
                    <?php endif; ?>
                </div>

                <div class="space-y-2">
                    <label for="rol" class="block text-sm font-medium text-gray-700">Rol de Usuario</label>
                    <select name="rol" id="rol" required
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                        <option value="Paciente" <?= ($u['rol'] ?? '') === 'Paciente' ? 'selected' : '' ?>>Paciente</option>
                        <option value="Fisioterapeuta" <?= ($u['rol'] ?? '') === 'Fisioterapeuta' ? 'selected' : '' ?>>Fisioterapeuta</option>
                        <option value="Administrador" <?= ($u['rol'] ?? '') === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required
                        value="<?= htmlspecialchars($u['nombre'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. Juan">
                </div>

                <div class="space-y-2">
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" required
                        value="<?= htmlspecialchars($u['apellidos'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. Pérez García">
                </div>

                <div class="space-y-2">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                        value="<?= $u['fecha_nacimiento'] ?? '' ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all">
                </div>

                <div class="space-y-2">
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select name="genero" id="genero" required
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                        <option value="Hombre" <?= ($u['genero'] ?? '') === 'Hombre' ? 'selected' : '' ?>>Hombre</option>
                        <option value="Mujer" <?= ($u['genero'] ?? '') === 'Mujer' ? 'selected' : '' ?>>Mujer</option>
                        <option value="Otro" <?= ($u['genero'] ?? '') === 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <!-- Contacto Section -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-envelope-at text-primary-600"></i>
                        Contacto y Ubicación
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required
                        value="<?= htmlspecialchars($u['email'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="juan@ejemplo.com">
                </div>

                <div class="space-y-2">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono"
                        value="<?= htmlspecialchars($u['telefono'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. 600 000 000">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" id="direccion"
                        value="<?= htmlspecialchars($u['direccion'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Calle, número, piso, puerta">
                </div>

                <div class="space-y-2">
                    <label for="municipio" class="block text-sm font-medium text-gray-700">Municipio</label>
                    <input type="text" name="municipio" id="municipio"
                        value="<?= htmlspecialchars($u['municipio'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. Madrid">
                </div>

                <div class="space-y-2 flex gap-4">
                    <div class="flex-1 space-y-2">
                        <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                        <input type="text" name="provincia" id="provincia"
                            value="<?= htmlspecialchars($u['provincia'] ?? '') ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                            placeholder="Ej. Madrid">
                    </div>
                    <div class="w-24 space-y-2">
                        <label for="cp" class="block text-sm font-medium text-gray-700">C.P.</label>
                        <input type="text" name="cp" id="cp" maxlength="5"
                            value="<?= htmlspecialchars($u['cp'] ?? '') ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                            placeholder="28001">
                    </div>
                </div>

                <!-- Seguridad Section -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-shield-lock text-primary-600"></i>
                        <?= $isEdit ? "Seguridad" : "Seguridad y Adicionales" ?>
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="pass" class="block text-sm font-medium text-gray-700"><?= $isEdit ? "Cambiar Contraseña" : "Contraseña Temporal" ?></label>
                    <input type="password" name="pass" id="pass"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="<?= $isEdit ? 'Nueva contraseña' : 'Mínimo 6 caracteres' ?>">
                    <p class="text-xs text-gray-500"><?= $isEdit ? "Dejar en blanco para mantener la contraseña actual." : "Si se deja vacío, será '123456' por defecto." ?></p>
                </div>

                <?php if (isset($especialidades)): ?>
                <div class="space-y-2" x-data="{ isFisio: '<?= $u['rol'] ?? '' ?>' === 'Fisioterapeuta' }" x-init="$watch('$root.querySelector(\'#rol\').value', value => isFisio = value === 'Fisioterapeuta')">
                    <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad Principal</label>
                    <select name="especialidad" id="especialidad"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                        <option value="">Seleccionar especialidad...</option>
                        <?php foreach ($especialidades as $esp): ?>
                            <option value="<?= $esp['especialidad_id'] ?>" <?= (isset($u['especialidad_id']) && $esp['especialidad_id'] == $u['especialidad_id']) ? 'selected' : '' ?>>
                                <?= $esp['descripcion'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500">Solo requerido para fisioterapeutas.</p>
                </div>
                <?php endif; ?>
            </div> 

            <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/usuarios" class="px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                    <?= $isEdit ? "Guardar Cambios" : "Guardar Usuario" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
