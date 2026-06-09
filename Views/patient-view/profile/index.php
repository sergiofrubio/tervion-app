<?php
$pageTitle = "Mi Perfil - Velion";
include TEMPLATE_DIR . 'header.php';

// Los datos del usuario vienen del controlador en la variable $usuario
$nombreCompleto = ($usuario['nombre'] ?? '') . ' ' . ($usuario['apellidos'] ?? '');
$success = $_GET['success'] ?? null;
?>

<div class="space-y-8 animate-fade-in pb-12" x-data="{ editMode: false, showPassword: false }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Mi Perfil</h1>
            <p class="text-gray-500">Gestiona tu información personal y preferencias de cuenta.</p>
        </div>
        
        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-2xl flex items-center gap-2 animate-bounce">
                <i class="bi bi-check-circle-fill"></i>
                <span class="text-sm font-bold">¡Perfil actualizado con éxito!</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Profile Summary -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm text-center">
                <div class="relative inline-block mb-4">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-primary-100 to-primary-50 flex items-center justify-center text-primary-600 text-5xl font-bold border-4 border-white shadow-lg">
                        <?= mb_substr($usuario['nombre'] ?? 'P', 0, 1) ?>
                    </div>
                    <div class="absolute bottom-1 right-1 w-8 h-8 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>
                </div>
                <h2 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($nombreCompleto) ?></h2>
                <p class="text-sm text-gray-500 mb-6"><?= htmlspecialchars($usuario['email'] ?? '') ?></p>
                
                <div class="flex flex-col gap-2">
                    <button @click="editMode = !editMode" 
                            :class="editMode ? 'bg-gray-100 text-gray-700' : 'bg-primary-600 text-white shadow-primary-200'"
                            class="w-full py-3 rounded-2xl font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-lg flex items-center justify-center gap-2">
                        <i :class="editMode ? 'bi bi-x-circle' : 'bi bi-pencil-square'"></i>
                        <span x-text="editMode ? 'Cancelar Edición' : 'Editar mi Perfil'"></span>
                    </button>
                    <a href="<?= PROJECT_ROOT ?>/logout" class="text-xs text-red-500 font-bold hover:text-red-600 transition-colors py-2">Cerrar sesión</a>
                </div>
            </div>

            <!-- Account Stats -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Mi Actividad</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Citas Totales</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900"><?= $appointmentCount ?? 0 ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="bi bi-ticket-perforated"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-600">Bonos Activos</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900"><?= $bonoCount ?? 0 ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: Details / Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Information Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900" x-text="editMode ? 'Editar Información Personal' : 'Información Personal'"></h2>
                    <i class="bi bi-person-vcard text-gray-300 text-xl"></i>
                </div>
                
                <div class="p-8">
                    <!-- Display Mode -->
                    <div x-show="!editMode" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Nombre</label>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($usuario['nombre'] ?? '-') ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Apellidos</label>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($usuario['apellidos'] ?? '-') ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Email</label>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($usuario['email'] ?? '-') ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Teléfono</label>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($usuario['telefono'] ?? '-') ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Fecha de Nacimiento</label>
                            <p class="text-gray-900 font-medium"><?= !empty($usuario['fecha_nacimiento']) ? date('d/m/Y', strtotime($usuario['fecha_nacimiento'])) : '-' ?></p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Género</label>
                            <p class="text-gray-900 font-medium"><?= htmlspecialchars($usuario['genero'] ?? '-') ?></p>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Dirección Completa</label>
                            <p class="text-gray-900 font-medium">
                                <?= htmlspecialchars($usuario['direccion'] ?? '') ?><br>
                                <span class="text-gray-500 text-sm">
                                    <?= htmlspecialchars($usuario['cp'] ?? '') ?> <?= htmlspecialchars($usuario['municipio'] ?? '') ?> (<?= htmlspecialchars($usuario['provincia'] ?? '') ?>)
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Edit Mode -->
                    <form x-show="editMode" x-transition action="<?= PROJECT_ROOT ?>/vista-pacientes/perfil/edit" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="nombre" class="text-sm font-bold text-gray-700">Nombre</label>
                                <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="apellidos" class="text-sm font-bold text-gray-700">Apellidos</label>
                                <input type="text" name="apellidos" id="apellidos" value="<?= htmlspecialchars($usuario['apellidos'] ?? '') ?>" required
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-bold text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="telefono" class="text-sm font-bold text-gray-700">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="fecha_nacimiento" class="text-sm font-bold text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?= $usuario['fecha_nacimiento'] ?? '' ?>"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="genero" class="text-sm font-bold text-gray-700">Género</label>
                                <select name="genero" id="genero" 
                                        class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm appearance-none bg-white">
                                    <option value="Hombre" <?= ($usuario['genero'] ?? '') == 'Hombre' ? 'selected' : '' ?>>Hombre</option>
                                    <option value="Mujer" <?= ($usuario['genero'] ?? '') == 'Mujer' ? 'selected' : '' ?>>Mujer</option>
                                    <option value="Otro" <?= ($usuario['genero'] ?? '') == 'Otro' ? 'selected' : '' ?>>Otro</option>
                                </select>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label for="direccion" class="text-sm font-bold text-gray-700">Dirección</label>
                                <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($usuario['direccion'] ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="provincia" class="text-sm font-bold text-gray-700">Provincia</label>
                                <input type="text" name="provincia" id="provincia" value="<?= htmlspecialchars($usuario['provincia'] ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="municipio" class="text-sm font-bold text-gray-700">Municipio</label>
                                <input type="text" name="municipio" id="municipio" value="<?= htmlspecialchars($usuario['municipio'] ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                            <div class="space-y-2">
                                <label for="cp" class="text-sm font-bold text-gray-700">Código Postal</label>
                                <input type="text" name="cp" id="cp" value="<?= htmlspecialchars($usuario['cp'] ?? '') ?>"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="mt-8 pt-8 border-t border-gray-100 space-y-4">
                            <h3 class="text-sm font-bold text-gray-900">Cambiar Contraseña (opcional)</h3>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="pass" id="pass" placeholder="Nueva contraseña"
                                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                                <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400">Deja este campo en blanco si no deseas cambiar tu contraseña.</p>
                        </div>

                        <div class="pt-6 flex justify-end gap-3">
                            <button type="button" @click="editMode = false" class="px-6 py-3 rounded-2xl font-bold text-sm text-gray-500 hover:bg-gray-50 transition-all">Cancelar</button>
                            <button type="submit" class="px-8 py-3 rounded-2xl bg-primary-600 text-white font-bold text-sm shadow-lg shadow-primary-200 hover:scale-105 active:scale-95 transition-all">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payment Methods Card -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ addMethod: false }">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Métodos de Pago</h2>
                    <button @click="addMethod = !addMethod" class="text-primary-600 hover:text-primary-700 font-bold text-sm flex items-center gap-1 transition-colors">
                        <i :class="addMethod ? 'bi bi-x-lg' : 'bi bi-plus-lg'"></i>
                        <span x-text="addMethod ? 'Cancelar' : 'Añadir Método'"></span>
                    </button>
                </div>

                <div class="p-8">
                    <!-- Add Method Form -->
                    <div x-show="addMethod" x-transition class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <form action="<?= PROJECT_ROOT ?>/vista-pacientes/perfil/add-payment-method" method="POST" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Número de Tarjeta</label>
                                    <input type="text" name="card_number" placeholder="0000 0000 0000 0000" required
                                           class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 outline-none text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Fecha Expiración (MM/AA)</label>
                                    <input type="text" name="expiry" placeholder="MM/AA" required
                                           class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 outline-none text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Proveedor</label>
                                    <select name="proveedor" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 outline-none text-sm bg-white">
                                        <option value="Visa">Visa</option>
                                        <option value="MasterCard">MasterCard</option>
                                        <option value="American Express">American Express</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 pt-5">
                                    <input type="checkbox" name="es_predeterminado" id="es_predeterminado" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <label for="es_predeterminado" class="text-sm text-gray-600">Marcar como predeterminado</label>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 bg-primary-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-primary-200 hover:scale-[1.01] transition-all">
                                Guardar Tarjeta
                            </button>
                        </form>
                    </div>

                    <!-- List of Methods -->
                    <div class="space-y-4">
                        <?php if (empty($metodosPago)): ?>
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="bi bi-credit-card text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm italic">No tienes métodos de pago guardados.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($metodosPago as $metodo): ?>
                                <div class="flex items-center justify-between p-5 rounded-2xl border border-gray-100 hover:border-primary-100 transition-all bg-white group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-primary-600 group-hover:bg-primary-50 transition-colors">
                                            <?php if ($metodo['proveedor'] == 'Visa'): ?>
                                                <i class="bi bi-credit-card-2-front text-xl"></i>
                                            <?php else: ?>
                                                <i class="bi bi-credit-card text-xl"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 flex items-center gap-2">
                                                <?= htmlspecialchars($metodo['proveedor']) ?> •••• <?= htmlspecialchars($metodo['last4']) ?>
                                                <?php if ($metodo['es_predeterminado']): ?>
                                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-black uppercase rounded-full">Predeterminado</span>
                                                <?php endif; ?>
                                            </p>
                                            <p class="text-xs text-gray-400">Expira en <?= htmlspecialchars($metodo['fecha_expiracion']) ?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <?php if (!$metodo['es_predeterminado']): ?>
                                            <a href="<?= PROJECT_ROOT ?>/vista-pacientes/perfil/set-primary-payment?id=<?= $metodo['metodo_id'] ?>" 
                                               title="Hacer predeterminado" class="p-2 text-gray-400 hover:text-primary-600 transition-colors">
                                                <i class="bi bi-star"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?= PROJECT_ROOT ?>/vista-pacientes/perfil/delete-payment-method?id=<?= $metodo['metodo_id'] ?>" 
                                           onclick="return confirm('¿Estás seguro de que deseas eliminar este método de pago?')"
                                           class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Security & Preferences Tip -->
            <div class="bg-indigo-900 rounded-3xl p-8 text-white overflow-hidden relative">
                <div class="relative z-10 space-y-3">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <i class="bi bi-shield-lock-fill text-indigo-400"></i>
                        Tu privacidad es lo primero
                    </h3>
                    <p class="text-indigo-200 text-sm leading-relaxed max-w-lg">
                        En Velion utilizamos encriptación de grado médico para proteger todos tus datos personales y de salud. Puedes solicitar un reporte completo de tus datos en cualquier momento contactando con nosotros.
                    </p>
                </div>
                <div class="absolute right-0 bottom-0 opacity-10 pointer-events-none">
                    <i class="bi bi-shield-check text-[120px] -mr-4 -mb-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
