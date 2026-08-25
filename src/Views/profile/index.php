<?php
$pageTitle = "Mi Perfil - Velion";
include TEMPLATE_DIR . 'header.php';

$nombreCompleto = trim(($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? ''));
$success = $_GET['success'] ?? null;
$rol = $usuario['rol'] ?? ($_SESSION['rol'] ?? 'Usuario');
?>

<div class="space-y-6 animate-fade-in-up pb-12" x-data="{ editMode: false, showPassword: false }">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-gray-900">Mi Perfil</h1>
            <p class="text-gray-500 text-sm mt-0.5">Consulta y gestiona la información de tu cuenta y datos personales.</p>
        </div>

        <?php if ($success): ?>
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-2 rounded-2xl flex items-center gap-2 text-sm font-medium">
                <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
                <span>¡Perfil actualizado con éxito!</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar Card: User Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center">
                <div class="relative inline-block mb-4">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-primary-600 to-primary-800 flex items-center justify-center text-white text-3xl font-extrabold border-4 border-white shadow-md mx-auto">
                        <?= mb_substr($usuario['nombre'] ?? 'U', 0, 1) ?>
                    </div>
                    <span class="absolute bottom-0 right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Conectado"></span>
                </div>

                <h2 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($nombreCompleto) ?></h2>
                <p class="text-xs text-gray-500 mb-3"><?= htmlspecialchars($usuario['email'] ?? '') ?></p>

                <div class="mb-5 inline-block">
                    <?php if ($rol === 'SuperAdmin' || $rol === 'Administrador'): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span> <?= htmlspecialchars($rol) ?>
                        </span>
                    <?php elseif ($rol === 'Fisioterapeuta'): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span> Fisioterapeuta
                        </span>
                    <?php elseif ($rol === 'Secretario'): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Secretario
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Paciente
                        </span>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-2">
                    <button @click="editMode = !editMode"
                        :class="editMode ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-primary-600 hover:bg-primary-700 text-white shadow-md'"
                        class="w-full py-2.5 rounded-2xl font-semibold text-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <i :class="editMode ? 'bi bi-x-circle' : 'bi bi-pencil-square'"></i>
                        <span x-text="editMode ? 'Cancelar Edición' : 'Editar mi Perfil'"></span>
                    </button>
                    <a href="<?= PROJECT_ROOT ?>/logout" class="text-xs text-rose-600 font-semibold hover:underline py-1">Cerrar sesión</a>
                </div>
            </div>

            <!-- Quick Info -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Datos de Cuenta</h3>
                
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 font-medium">ID Usuario / DNI</span>
                    <span class="font-bold text-gray-900 font-mono">#<?= htmlspecialchars($usuario['usuario_id'] ?? '-') ?></span>
                </div>
                
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 font-medium">Teléfono</span>
                    <span class="font-bold text-gray-900"><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></span>
                </div>

                <?php if (!empty($usuario['fecha_nacimiento'])): ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500 font-medium">Fecha Nacimiento</span>
                        <span class="font-bold text-gray-900"><?= date('d/m/Y', strtotime($usuario['fecha_nacimiento'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Content Card: Details & Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-gray-900" x-text="editMode ? 'Editar Datos de Perfil' : 'Información Personal'"></h2>
                    <i class="bi bi-person-vcard text-gray-400 text-lg"></i>
                </div>

                <div class="p-6">
                    <!-- Display Mode -->
                    <div x-show="!editMode" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nombre</span>
                                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($usuario['nombre'] ?? '-') ?></p>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Apellidos</span>
                                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($usuario['apellidos'] ?? '-') ?></p>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Email</span>
                                <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($usuario['email'] ?? '-') ?></p>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Teléfono</span>
                                <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></p>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Fecha de Nacimiento</span>
                                <p class="text-sm font-semibold text-gray-800"><?= !empty($usuario['fecha_nacimiento']) ? date('d/m/Y', strtotime($usuario['fecha_nacimiento'])) : '-' ?></p>
                            </div>
                            <div>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Género</span>
                                <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($usuario['genero'] ?? '-') ?></p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dirección Registrada</span>
                            <p class="text-sm font-medium text-gray-800">
                                <?= htmlspecialchars($usuario['direccion'] ?? 'Sin dirección especificada') ?>
                                <?php if (!empty($usuario['cp']) || !empty($usuario['municipio']) || !empty($usuario['provincia'])): ?>
                                    <span class="block text-xs text-gray-500 font-normal mt-0.5">
                                        <?= htmlspecialchars($usuario['cp'] ?? '') ?> <?= htmlspecialchars($usuario['municipio'] ?? '') ?> (<?= htmlspecialchars($usuario['provincia'] ?? '') ?>)
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if ($rol !== 'Paciente' && (!empty($usuario['nss']) || !empty($usuario['iban']))): ?>
                            <div class="pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <?php if (!empty($usuario['nss'])): ?>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Nº Seguridad Social (NSS)</span>
                                        <p class="text-xs font-mono font-bold text-gray-800"><?= htmlspecialchars($usuario['nss']) ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($usuario['iban'])): ?>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Cuenta Bancaria (IBAN)</span>
                                        <p class="text-xs font-mono font-bold text-gray-800"><?= htmlspecialchars($usuario['iban']) ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Edit Mode Form -->
                    <form x-show="editMode" action="<?= PROJECT_ROOT ?>/perfil/editar" method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nombre" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Nombre</label>
                                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="apellidos" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Apellidos</label>
                                <input type="text" name="apellidos" id="apellidos" value="<?= htmlspecialchars($usuario['apellidos'] ?? '') ?>" required
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="email" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Email</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="telefono" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="fecha_nacimiento" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?= $usuario['fecha_nacimiento'] ?? '' ?>"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="genero" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Género</label>
                                <select name="genero" id="genero" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer">
                                    <option value="Hombre" <?= ($usuario['genero'] ?? '') == 'Hombre' ? 'selected' : '' ?>>Hombre</option>
                                    <option value="Mujer" <?= ($usuario['genero'] ?? '') == 'Mujer' ? 'selected' : '' ?>>Mujer</option>
                                    <option value="Otro" <?= ($usuario['genero'] ?? '') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="direccion" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Dirección</label>
                                <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="provincia" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Provincia</label>
                                <input type="text" name="provincia" id="provincia" value="<?= htmlspecialchars($usuario['provincia'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="municipio" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Municipio</label>
                                <input type="text" name="municipio" id="municipio" value="<?= htmlspecialchars($usuario['municipio'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                            <div>
                                <label for="cp" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Código Postal</label>
                                <input type="text" name="cp" id="cp" value="<?= htmlspecialchars($usuario['cp'] ?? '') ?>"
                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                            </div>
                        </div>

                        <?php if ($rol !== 'Paciente'): ?>
                            <div class="pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="nss" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Nº Seguridad Social (NSS)</label>
                                    <input type="text" name="nss" id="nss" value="<?= htmlspecialchars($usuario['nss'] ?? '') ?>"
                                        class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                                </div>
                                <div>
                                    <label for="iban" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">IBAN Cuenta Bancaria</label>
                                    <input type="text" name="iban" id="iban" value="<?= htmlspecialchars($usuario['iban'] ?? '') ?>"
                                        class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Change Password Section -->
                        <div class="pt-4 border-t border-gray-100 space-y-2">
                            <label for="pass" class="block text-[10px] font-bold uppercase text-gray-400 tracking-wider mb-1">Cambiar Contraseña (opcional)</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="pass" id="pass" placeholder="Nueva contraseña..."
                                    class="w-full pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-end gap-2">
                            <button type="button" @click="editMode = false" class="px-4 py-2 rounded-xl font-semibold text-xs text-gray-600 hover:bg-gray-100 transition-colors cursor-pointer">Cancelar</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-xs shadow-md transition-all cursor-pointer">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
