<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Inicial - Tervion</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232563eb'><path d='M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z'/></svg>">
</head>
<body class="h-full flex flex-col justify-between text-slate-800 antialiased selection:bg-primary-500 selection:text-white"
      x-data="{
          step: <?= (!empty($error) || !empty($data)) ? '2' : '1' ?>,
          allOk: <?= $requirements['allOk'] ? 'true' : 'false' ?>,
          nombre_comercial: '<?= htmlspecialchars($data['nombre_comercial'] ?? '', ENT_QUOTES) ?>',
          razon_social: '<?= htmlspecialchars($data['razon_social'] ?? '', ENT_QUOTES) ?>',
          nif_cif: '<?= htmlspecialchars($data['nif_cif'] ?? '', ENT_QUOTES) ?>',
          telefono_contacto: '<?= htmlspecialchars($data['telefono_contacto'] ?? '', ENT_QUOTES) ?>',
          email_contacto: '<?= htmlspecialchars($data['email_contacto'] ?? '', ENT_QUOTES) ?>',
          direccion: '<?= htmlspecialchars($data['direccion'] ?? '', ENT_QUOTES) ?>',
          ciudad: '<?= htmlspecialchars($data['ciudad'] ?? '', ENT_QUOTES) ?>',
          provincia_estado: '<?= htmlspecialchars($data['provincia_estado'] ?? '', ENT_QUOTES) ?>',
          codigo_postal: '<?= htmlspecialchars($data['codigo_postal'] ?? '', ENT_QUOTES) ?>',
          admin_nombre: '<?= htmlspecialchars($data['admin_nombre'] ?? '', ENT_QUOTES) ?>',
          admin_apellidos: '<?= htmlspecialchars($data['admin_apellidos'] ?? '', ENT_QUOTES) ?>',
          admin_dni: '<?= htmlspecialchars($data['admin_dni'] ?? '', ENT_QUOTES) ?>',
          admin_email: '<?= htmlspecialchars($data['admin_email'] ?? '', ENT_QUOTES) ?>',
          admin_pass: '',
          admin_confirm_pass: '',
          loading: false,
          validateStep2() {
              if (!this.nombre_comercial.trim()) {
                  alert('Por favor introduce el nombre comercial de la clínica.');
                  return false;
              }
              return true;
          },
          validateStep3() {
              if (!this.admin_nombre.trim()) {
                  alert('Por favor introduce el nombre del administrador.');
                  return false;
              }
              if (!this.admin_email.trim() || !this.admin_email.includes('@')) {
                  alert('Por favor introduce un correo electrónico válido para el administrador.');
                  return false;
              }
              if (this.admin_pass.length < 8) {
                  alert('La contraseña debe tener al menos 8 caracteres.');
                  return false;
              }
              if (this.admin_pass !== this.admin_confirm_pass) {
                  alert('Las contraseñas no coinciden.');
                  return false;
              }
              return true;
          }
      }">

    <!-- Top Navigation / Brand Header -->
    <header class="w-full bg-white border-b border-slate-200/80 py-4 px-6 shadow-sm">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-primary-500/20">
                    <i class="bi bi-heart-pulse-fill text-xl"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        Tervion
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Open Source</span>
                    </h1>
                    <p class="text-xs text-slate-500">Asistente de Configuración Inicial</p>
                </div>
            </div>
            <div class="text-xs font-medium text-slate-500 flex items-center gap-2">
                <i class="bi bi-shield-check text-base text-primary-600"></i>
                Instalación Guiada
            </div>
        </div>
    </header>

    <!-- Main Wizard Card -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8">
        <?php if (!empty($error)): ?>
            <div class="mb-6 rounded-2xl bg-red-50 p-4 border border-red-200 flex items-start gap-3 text-red-800">
                <i class="bi bi-exclamation-octagon-fill text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
                <div class="text-sm font-medium"><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <!-- Progress Steps -->
        <nav aria-label="Progreso" class="mb-8 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <ol class="flex items-center justify-between gap-2 sm:gap-4 text-xs sm:text-sm font-medium">
                <li class="flex items-center gap-2 flex-1">
                    <span :class="step >= 1 ? 'bg-primary-600 text-white shadow-sm shadow-primary-500/30' : 'bg-slate-100 text-slate-500'" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors">1</span>
                    <span :class="step >= 1 ? 'text-primary-700 font-semibold' : 'text-slate-500'" class="hidden sm:inline">Diagnóstico</span>
                </li>
                <li class="h-0.5 flex-1 bg-slate-200" :class="step > 1 ? '!bg-primary-600' : ''"></li>
                <li class="flex items-center gap-2 flex-1 justify-center">
                    <span :class="step >= 2 ? 'bg-primary-600 text-white shadow-sm shadow-primary-500/30' : 'bg-slate-100 text-slate-500'" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors">2</span>
                    <span :class="step >= 2 ? 'text-primary-700 font-semibold' : 'text-slate-500'" class="hidden sm:inline">Clínica</span>
                </li>
                <li class="h-0.5 flex-1 bg-slate-200" :class="step > 2 ? '!bg-primary-600' : ''"></li>
                <li class="flex items-center gap-2 flex-1 justify-end">
                    <span :class="step >= 3 ? 'bg-primary-600 text-white shadow-sm shadow-primary-500/30' : 'bg-slate-100 text-slate-500'" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs transition-colors">3</span>
                    <span :class="step >= 3 ? 'text-primary-700 font-semibold' : 'text-slate-500'" class="hidden sm:inline">Administrador</span>
                </li>
            </ol>
        </nav>

        <form id="installForm" method="POST" action="<?= PROJECT_ROOT ?>/install/process" @submit="loading = true" class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <!-- PASO 1: REQUISITOS DEL ENTORNO -->
            <div x-show="step === 1" class="p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Comprobación del Entorno</h2>
                    <p class="text-sm text-slate-500 mt-1">Verificamos que tu servidor cumple con todos los requisitos para ejecutar Tervion sin problemas.</p>
                </div>

                <div class="space-y-4">
                    <!-- PHP Version -->
                    <div class="p-4 rounded-2xl border flex items-center justify-between <?= $requirements['php']['ok'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-red-50/50 border-red-200' ?>">
                        <div class="flex items-center gap-3">
                            <i class="bi <?= $requirements['php']['ok'] ? 'bi-check-circle-fill text-emerald-500' : 'bi-x-circle-fill text-red-500' ?> text-xl"></i>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Versión de PHP</p>
                                <p class="text-xs text-slate-500">Detectada: <?= htmlspecialchars($requirements['php']['version']) ?> (Requerida: &ge; <?= $requirements['php']['required'] ?>)</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full <?= $requirements['php']['ok'] ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $requirements['php']['ok'] ? 'Compatible' : 'Incompatible' ?>
                        </span>
                    </div>

                    <!-- Base de Datos -->
                    <div class="p-4 rounded-2xl border flex items-center justify-between <?= $requirements['database']['ok'] ? 'bg-emerald-50/50 border-emerald-200' : 'bg-red-50/50 border-red-200' ?>">
                        <div class="flex items-center gap-3">
                            <i class="bi <?= $requirements['database']['ok'] ? 'bi-database-check text-emerald-500' : 'bi-database-x text-red-500' ?> text-xl"></i>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Base de Datos MySQL / MariaDB</p>
                                <p class="text-xs text-slate-500"><?= htmlspecialchars($requirements['database']['message']) ?> <?= isset($requirements['database']['version']) ? '(v' . htmlspecialchars($requirements['database']['version']) . ')' : '' ?></p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full <?= $requirements['database']['ok'] ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $requirements['database']['ok'] ? 'Conectado' : 'Error BD' ?>
                        </span>
                    </div>

                    <!-- Extensiones -->
                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Extensiones de PHP Requeridas</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php foreach ($requirements['extensions']['list'] as $extKey => $extInfo): ?>
                                <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-slate-200/60 text-xs">
                                    <span class="font-medium text-slate-700"><?= htmlspecialchars($extInfo['label']) ?></span>
                                    <i class="bi <?= $extInfo['loaded'] ? 'bi-check-circle-fill text-emerald-500' : 'bi-x-circle-fill text-red-500' ?>"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Permisos de Directorios -->
                    <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50/50 space-y-3">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Permisos de Escritura</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <?php foreach ($requirements['permissions']['list'] as $dirKey => $dirInfo): ?>
                                <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-slate-200/60 text-xs">
                                    <span class="font-mono text-slate-700 truncate"><?= htmlspecialchars($dirInfo['path']) ?>/</span>
                                    <i class="bi <?= $dirInfo['writable'] ? 'bi-check-circle-fill text-emerald-500' : 'bi-x-circle-fill text-red-500' ?>"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="button" 
                            :disabled="!allOk" 
                            @click="step = 2"
                            :class="allOk ? 'bg-primary-600 hover:bg-primary-700 text-white shadow-md shadow-primary-500/20 cursor-pointer' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-medium text-sm transition-all">
                        Continuar
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- PASO 2: DATOS DE LA CLÍNICA -->
            <div x-show="step === 2" style="display: none;" class="p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Información de la Clínica</h2>
                    <p class="text-sm text-slate-500 mt-1">Configura la identidad y los datos de contacto de tu centro de fisioterapia.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nombre Comercial <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre_comercial" x-model="nombre_comercial" required placeholder="Ej. Clínica de Fisioterapia Salud Vital" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Razón Social</label>
                        <input type="text" name="razon_social" x-model="razon_social" placeholder="Ej. Fisioterapia Salud Vital S.L." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">NIF / CIF</label>
                        <input type="text" name="nif_cif" x-model="nif_cif" placeholder="Ej. B12345678" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Teléfono de Contacto</label>
                        <input type="tel" name="telefono_contacto" x-model="telefono_contacto" placeholder="Ej. 912 345 678" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Oficial de la Clínica</label>
                        <input type="email" name="email_contacto" x-model="email_contacto" placeholder="info@miclinica.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Dirección Completa</label>
                        <input type="text" name="direccion" x-model="direccion" placeholder="Calle Mayor, 12, Bajo B" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Ciudad / Municipio</label>
                        <input type="text" name="ciudad" x-model="ciudad" placeholder="Madrid" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Provincia</label>
                        <input type="text" name="provincia_estado" x-model="provincia_estado" placeholder="Madrid" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Código Postal</label>
                        <input type="text" name="codigo_postal" x-model="codigo_postal" placeholder="28001" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">País</label>
                        <input type="text" name="pais" value="España" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between">
                    <button type="button" @click="step = 1" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-100 transition-all">
                        <i class="bi bi-arrow-left"></i>
                        Atrás
                    </button>
                    <button type="button" @click="if (validateStep2()) step = 3;" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm shadow-md shadow-primary-500/20 transition-all cursor-pointer">
                        Siguiente: Cuenta Admin
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- PASO 3: CUENTA DE ADMINISTRADOR -->
            <div x-show="step === 3" style="display: none;" class="p-6 sm:p-8 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Primer Administrador del Sistema</h2>
                    <p class="text-sm text-slate-500 mt-1">Crea la cuenta con privilegios totales de administración con la que gestionarás la clínica.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nombre <span class="text-red-500">*</span></label>
                        <input type="text" name="admin_nombre" x-model="admin_nombre" required placeholder="Ej. Ana" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Apellidos</label>
                        <input type="text" name="admin_apellidos" x-model="admin_apellidos" placeholder="Ej. Martínez Gómez" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">DNI / NIF</label>
                        <input type="text" name="admin_dni" x-model="admin_dni" placeholder="Ej. 12345678Z" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Correo Electrónico (Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="admin_email" x-model="admin_email" required placeholder="admin@miclinica.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Contraseña <span class="text-red-500">*</span></label>
                        <input type="password" name="admin_pass" x-model="admin_pass" required minlength="8" placeholder="Mínimo 8 caracteres" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Confirmar Contraseña <span class="text-red-500">*</span></label>
                        <input type="password" name="admin_confirm_pass" x-model="admin_confirm_pass" required minlength="8" placeholder="Repite la contraseña" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none">
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
                    <i class="bi bi-shield-lock-fill text-amber-600 text-base flex-shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="font-semibold">Seguridad importante:</strong>
                        Una vez completada la configuración, el asistente de instalación quedará bloqueado permanentemente para proteger el acceso a tus datos.
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between">
                    <button type="button" @click="step = 2" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:bg-slate-100 transition-all">
                        <i class="bi bi-arrow-left"></i>
                        Atrás
                    </button>
                    <button type="submit" 
                            :disabled="loading" 
                            @click="if (!validateStep3()) $event.preventDefault();"
                            class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-md shadow-emerald-500/20 transition-all cursor-pointer">
                        <span x-show="!loading" class="flex items-center gap-2">
                            <i class="bi bi-check2-circle text-lg"></i>
                            Finalizar e Instalar Tervion
                        </span>
                        <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Configurando el sistema...
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </main>

    <!-- Simple Footer -->
    <footer class="w-full text-center py-4 text-xs text-slate-400">
        Tervion Open Source &bull; Licencia LGPLv3
    </footer>
</body>
</html>