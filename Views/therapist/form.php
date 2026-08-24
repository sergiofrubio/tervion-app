<?php
$t = $therapist ?? [];
$isEdit = !empty($t);
$pageTitle = $isEdit ? "Editar Facultativo" : "Nuevo Facultativo";
include TEMPLATE_DIR . 'header.php';
?>

<div class="w-full animate-fade-in-up">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Facultativo / Empleado" : "Nuevo Facultativo / Empleado" ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= $isEdit ? "Modifica los datos personales y laborales del especialista." : "Registra un nuevo especialista o miembro del personal en el sistema." ?></p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/terapeutas" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver al listado
        </a>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <div class="mb-6 p-4 rounded-2xl border bg-rose-50 text-rose-800 border-rose-200 text-sm font-medium">
            <i class="bi bi-exclamation-triangle-fill text-rose-500 mr-2"></i>
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/terapeutas/<?= $isEdit ? 'editar' : 'crear' ?>" method="POST" class="p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Datos Profesionales / Rol -->
                <div class="space-y-6 md:col-span-2 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-badge text-primary-600"></i>
                        1. Información Profesional y Cuenta
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="usuario_id" class="block text-sm font-medium text-gray-700">DNI / NIF <span class="text-rose-500">*</span></label>
                    <input type="text" id="usuario_id" name="usuario_id" value="<?= htmlspecialchars($t['usuario_id'] ?? '') ?>" <?= $isEdit ? 'readonly class="block w-full rounded-xl border-gray-200 bg-gray-100 p-3 text-sm text-gray-500 cursor-not-allowed"' : 'required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white"' ?> placeholder="12345678X">
                </div>

                <div class="space-y-2">
                    <label for="rol" class="block text-sm font-medium text-gray-700">Rol / Cargo <span class="text-rose-500">*</span></label>
                    <select name="rol" id="rol" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                        <option value="Fisioterapeuta" <?= ($isEdit && ($t['rol'] ?? '') === 'Fisioterapeuta') ? 'selected' : '' ?>>Fisioterapeuta / Facultativo</option>
                        <option value="Secretario" <?= ($isEdit && ($t['rol'] ?? '') === 'Secretario') ? 'selected' : '' ?>>Secretario / Recepción</option>
                        <option value="Administrador" <?= ($isEdit && ($t['rol'] ?? '') === 'Administrador') ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($t['nombre'] ?? '') ?>" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos <span class="text-rose-500">*</span></label>
                    <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($t['apellidos'] ?? '') ?>" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico <span class="text-rose-500">*</span></label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($t['email'] ?? '') ?>" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="pass" class="block text-sm font-medium text-gray-700">Contraseña <?= $isEdit ? '(dejar en blanco para conservar)' : '' ?></label>
                    <input type="password" id="pass" name="pass" <?= $isEdit ? '' : '' ?> class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white" placeholder="<?= $isEdit ? '••••••••' : 'Contraseña por defecto (DNI)' ?>">
                </div>

                <!-- Datos Laborales y de Seguridad Social -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-card-checklist text-primary-600"></i>
                        2. Datos Laborales y Nómina
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="nss" class="block text-sm font-medium text-gray-700">Número de la Seguridad Social (NSS)</label>
                    <input type="text" id="nss" name="nss" value="<?= htmlspecialchars($t['nss'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white" placeholder="28/1234567890">
                </div>

                <div class="space-y-2">
                    <label for="iban" class="block text-sm font-medium text-gray-700">Cuenta Bancaria (IBAN)</label>
                    <input type="text" id="iban" name="iban" value="<?= htmlspecialchars($t['iban'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white" placeholder="ES91 0000 0000 0000 0000 0000">
                </div>

                <div class="space-y-2">
                    <label for="grupo_cotizacion" class="block text-sm font-medium text-gray-700">Grupo de Cotización</label>
                    <select name="grupo_cotizacion" id="grupo_cotizacion" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                        <option value="1" <?= ($isEdit && ($t['grupo_cotizacion'] ?? 1) == 1) ? 'selected' : '' ?>>Grupo 1 - Ingenieros y Licenciados</option>
                        <option value="2" <?= ($isEdit && ($t['grupo_cotizacion'] ?? 1) == 2) ? 'selected' : '' ?>>Grupo 2 - Ingenieros Técnicos / Diplomados</option>
                        <option value="3" <?= ($isEdit && ($t['grupo_cotizacion'] ?? 1) == 3) ? 'selected' : '' ?>>Grupo 3 - Jefes Administrativos y de Taller</option>
                        <option value="4" <?= ($isEdit && ($t['grupo_cotizacion'] ?? 1) == 4) ? 'selected' : '' ?>>Grupo 4 - Ayudantes no Titulados</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select name="genero" id="genero" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                        <option value="Hombre" <?= ($isEdit && ($t['genero'] ?? '') === 'Hombre') ? 'selected' : '' ?>>Hombre</option>
                        <option value="Mujer" <?= ($isEdit && ($t['genero'] ?? '') === 'Mujer') ? 'selected' : '' ?>>Mujer</option>
                        <option value="Otro" <?= ($isEdit && ($t['genero'] ?? '') === 'Otro') ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <!-- Datos de Contacto y Dirección -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-primary-600"></i>
                        3. Contacto y Dirección
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($t['telefono'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($t['fecha_nacimiento'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($t['direccion'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="municipio" class="block text-sm font-medium text-gray-700">Municipio</label>
                    <input type="text" id="municipio" name="municipio" value="<?= htmlspecialchars($t['municipio'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                    <input type="text" id="provincia" name="provincia" value="<?= htmlspecialchars($t['provincia'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="cp" class="block text-sm font-medium text-gray-700">Código Postal</label>
                    <input type="text" id="cp" name="cp" value="<?= htmlspecialchars($t['cp'] ?? '') ?>" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border p-3 bg-white">
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/terapeutas" class="px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                    <?= $isEdit ? "Guardar Cambios" : "Registrar Facultativo" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
