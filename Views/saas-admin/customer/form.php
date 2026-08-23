<?php include TEMPLATE_DIR . 'header.php'; ?>

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Alta de Nueva Clínica</h1>
            <p class="text-gray-500 text-sm mt-0.5">Crea una nueva organización cliente y su usuario administrador principal.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/superadmin/clientes" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 px-3.5 py-2 rounded-xl transition-colors">
            <i class="bi bi-arrow-left"></i>
            <span>Volver a Clientes</span>
        </a>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($errorMessage)): ?>
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm font-medium flex items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill text-rose-500 text-lg"></i>
            <span><?= htmlspecialchars($errorMessage) ?></span>
        </div>
    <?php endif; ?>

    <!-- Onboarding Form -->
    <form action="<?= PROJECT_ROOT ?>/superadmin/clientes/create" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-8">
        <!-- Seccion 1: Datos de la Empresa / Cliente -->
        <div>
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-xl bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-building"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">1. Datos Fiscales de la Empresa</h2>
                    <p class="text-xs text-gray-400">Información para la cuenta de cliente y facturación SaaS.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre Fiscal Empresa *</label>
                    <input type="text" name="nombre_empresa" required placeholder="Ej: Clínica Fisioterapia Salud S.L." class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">NIF / CIF Fiscal *</label>
                    <input type="text" name="nif_cif" required placeholder="Ej: B12345678" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none uppercase">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre Comercial de la Clínica</label>
                    <input type="text" name="nombre_comercial" placeholder="Ej: Fisioterapia Velion Centro" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Plan Inicial de Suscripción</label>
                    <select name="plan_suscripcion" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none font-medium">
                        <option value="Basico">Plan Básico (29.00 €/mes)</option>
                        <option value="Profesional" selected>Plan Profesional (79.00 €/mes)</option>
                        <option value="Premium">Plan Premium (199.00 €/mes)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Seccion 2: Ubicación & Contacto -->
        <div>
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">2. Ubicación y Teléfono de Contacto</h2>
                    <p class="text-xs text-gray-400">Datos de localización de la clínica principal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Dirección Calle</label>
                    <input type="text" name="direccion_calle" placeholder="Ej: Calle Gran Vía, 42" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ciudad</label>
                    <input type="text" name="ciudad" placeholder="Madrid" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Provincia</label>
                    <input type="text" name="provincia" placeholder="Madrid" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Código Postal</label>
                    <input type="text" name="cp" placeholder="28001" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Teléfono Clínica</label>
                    <input type="text" name="telefono_contacto" placeholder="Ej: 912345678" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>
            </div>
        </div>

        <!-- Seccion 3: Administrador Principal -->
        <div>
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">3. Administrador Inicial de la Clínica</h2>
                    <p class="text-xs text-gray-400">Credenciales de acceso para el dueño/administrador de la clínica.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-5">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">DNI / NIF Administrador (ID Usuario) *</label>
                    <input type="text" name="admin_dni" required placeholder="Ej: 12345678A" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none uppercase">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Acceso Administrador *</label>
                    <input type="email" name="admin_email" required placeholder="admin@clinica.es" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre *</label>
                    <input type="text" name="admin_nombre" required placeholder="Ej: Carlos" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Apellidos *</label>
                    <input type="text" name="admin_apellidos" required placeholder="Ej: García Pérez" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Contraseña de Acceso *</label>
                    <input type="password" name="admin_pass" required placeholder="••••••••" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Género</label>
                    <select name="genero" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:outline-none">
                        <option value="Hombre">Hombre</option>
                        <option value="Mujer">Mujer</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="<?= PROJECT_ROOT ?>/superadmin/clientes" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-primary-600/30 transition-all hover:scale-105 active:scale-95 cursor-pointer">
                <i class="bi bi-building-add mr-1"></i> Crear Clínica e Inquilino
            </button>
        </div>
    </form>
</div>

<?php include_once __DIR__ . '/../../Templates/footer.php'; ?>