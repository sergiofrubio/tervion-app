<?php
$pageTitle = "Configuración";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up" x-data="{ activeTab: localStorage.getItem('settings_active_tab') || 'clinica' }" x-init="$watch('activeTab', value => localStorage.setItem('settings_active_tab', value))">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Configuración del Sistema</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona horarios, ausencias y bonos de la clínica.</p>
        </div>
    </div>

    <!-- Mensajes de Estado -->
    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="rounded-2xl bg-green-50 p-4 border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
            <p class="text-sm font-medium text-green-800"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="rounded-2xl bg-red-50 p-4 border border-red-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
            <p class="text-sm font-medium text-red-800"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
        <!-- Tabs Header -->
        <div class="border-b border-gray-100 bg-gray-50/30 px-6 pt-4">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                <button 
                    @click="activeTab = 'clinica'"
                    :class="activeTab === 'clinica' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-building"></i>
                    Clínica
                </button>
                <button 
                    @click="activeTab = 'horarios'"
                    :class="activeTab === 'horarios' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-clock"></i>
                    Horarios
                </button>
                <button 
                    @click="activeTab = 'ausencias'"
                    :class="activeTab === 'ausencias' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-calendar-x"></i>
                    Ausencias
                </button>
                <button 
                    @click="activeTab = 'bonos'"
                    :class="activeTab === 'bonos' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-ticket-perforated"></i>
                    Bonos
                </button>
                <button 
                    @click="activeTab = 'suscripcion'"
                    :class="activeTab === 'suscripcion' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-credit-card"></i>
                    Suscripción y Pago
                </button>
            </nav>
        </div>

        <!-- Tab Panels -->
        <div class="p-6 flex-1">
            
            <!-- Clínica Tab -->
            <div x-show="activeTab === 'clinica'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Datos de la Clínica</h4>
                </div>

                <form action="<?= PROJECT_ROOT ?>/configuracion/clinica/update" method="POST" enctype="multipart/form-data" class="max-w-4xl">
                    <?php if ($clinica) : ?>
                        <input type="hidden" name="id_clinica" value="<?= $clinica['id_clinica'] ?>">
                    <?php endif; ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre Comercial</label>
                            <input type="text" name="nombre_comercial" value="<?= $clinica['nombre_comercial'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Clínica Velion">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Razón Social</label>
                            <input type="text" name="razon_social" value="<?= $clinica['razon_social'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Velion S.L.">
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dirección</label>
                            <input type="text" name="direccion_calle" value="<?= $clinica['direccion_calle'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Calle, número, piso...">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ciudad</label>
                            <input type="text" name="ciudad" value="<?= $clinica['ciudad'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Madrid">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Provincia / Estado</label>
                            <input type="text" name="provincia_estado" value="<?= $clinica['provincia_estado'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Madrid">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Código Postal</label>
                            <input type="text" name="codigo_postal" value="<?= $clinica['codigo_postal'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: 28001">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">País</label>
                            <input type="text" name="pais" value="<?= $clinica['pais'] ?? 'España' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</label>
                            <input type="text" name="telefono_contacto" value="<?= $clinica['telefono_contacto'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: 910 00 00 00">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                            <input type="email" name="email_contacto" value="<?= $clinica['email_contacto'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="contacto@clinica.com">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NIF / CIF Emisor</label>
                            <input type="text" name="nif_cif" value="<?= $clinica['nif_cif'] ?? 'B12345678' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: B12345678">
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sitio Web</label>
                            <input type="url" name="sitio_web" value="<?= $clinica['sitio_web'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="https://www.clinica.com">
                        </div>
                    </div>

                    <!-- Configuración Verifactu -->
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Configuración Verifactu (AEAT)</h5>
                                <p class="text-xs text-gray-500">Ajustes para la remisión de registros de facturación con la Agencia Tributaria.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Entorno de Trabajo</label>
                                <select name="verifactu_env" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                                    <option value="pruebas" <?= ($clinica['verifactu_env'] ?? 'pruebas') === 'pruebas' ? 'selected' : '' ?>>Pruebas / Sandbox (prewww1.aeat.es)</option>
                                    <option value="produccion" <?= ($clinica['verifactu_env'] ?? '') === 'produccion' ? 'selected' : '' ?>>Producción (www1.agenciatributaria.gob.es)</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Certificado Digital (.p12 / .pfx / .pem)</label>
                                <input type="file" name="verifactu_cert_file" accept=".p12,.pfx,.pem"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all border border-gray-200 rounded-xl bg-white">
                                <?php if (!empty($clinica['verifactu_cert_path'])) : ?>
                                    <p class="text-xs text-emerald-600 font-medium mt-1.5 flex items-center gap-1">
                                        <i class="bi bi-file-earmark-check-fill text-sm"></i> Certificado activo: <span class="font-mono bg-emerald-50 px-2 py-0.5 rounded text-emerald-800"><?= htmlspecialchars(basename($clinica['verifactu_cert_path'])) ?></span>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Contraseña Certificado</label>
                                <input type="password" name="verifactu_cert_password" value="<?= htmlspecialchars($clinica['verifactu_cert_password'] ?? '') ?>"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                    placeholder="••••••••">
                            </div>

                            <div class="space-y-1 flex items-center pt-5">
                                <label class="inline-flex items-center cursor-pointer gap-3">
                                    <input type="checkbox" name="verifactu_activo" value="1" <?= (!isset($clinica['verifactu_activo']) || $clinica['verifactu_activo']) ? 'checked' : '' ?> class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                    <span class="text-sm font-medium text-gray-900">Activar comunicación automática con AEAT</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <i class="bi bi-save"></i>
                            <?= $clinica ? 'Guardar Cambios' : 'Crear Registro' ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Horarios Tab -->
            <div x-show="activeTab === 'horarios'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Horarios de Fisioterapeutas</h4>
                    <a href="<?= PROJECT_ROOT ?>/configuracion/horarios/create" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Horario
                    </a>
                </div>
                
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fisioterapeuta</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Día de la semana</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora Inicio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora Fin</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($horarios)) : ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay horarios registrados.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($horarios as $horario) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= $horario['nombre'] . ' ' . $horario['apellidos'] ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= $horario['dia_semana'] ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('H:i', strtotime($horario['hora_inicio'])) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('H:i', strtotime($horario['hora_fin'])) ?></td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="<?= PROJECT_ROOT ?>/configuracion/horarios/edit?id=<?= $horario['horario_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar"><i class="bi bi-pencil"></i></a>
                                            <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ausencias Tab -->
            <div x-show="activeTab === 'ausencias'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Ausencias de Fisioterapeutas</h4>
                    <a href="<?= PROJECT_ROOT ?>/configuracion/ausencias/create" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Registrar Ausencia
                    </a>
                </div>
                
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fisioterapeuta</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Motivo</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($ausencias)) : ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay ausencias registradas.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($ausencias as $ausencia) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= $ausencia['nombre'] . ' ' . $ausencia['apellidos'] ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y', strtotime($ausencia['fecha_inicio'])) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= date('d/m/Y', strtotime($ausencia['fecha_fin'])) ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= $ausencia['motivo'] ?: 'Sin especificar' ?></td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="<?= PROJECT_ROOT ?>/configuracion/ausencias/edit?id=<?= $ausencia['ausencia_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar"><i class="bi bi-pencil"></i></a>
                                            <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Bonos Tab -->
            <div x-show="activeTab === 'bonos'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Bonos de Sesiones</h4>
                    <a href="<?= PROJECT_ROOT ?>/configuracion/bonos/create" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Bono
                    </a>
                </div>
                
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sesiones</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($bonos)) : ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay bonos registrados.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($bonos as $bono) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= $bono['nombre'] ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= $bono['numero_sesiones'] ?></td>
                                        <td class="px-4 py-3 text-sm font-medium text-primary-600"><?= number_format($bono['precio'], 2) ?> €</td>
                                        <td class="px-4 py-3 text-sm">
                                            <?php if ($bono['estado'] == 'Activo') : ?>
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="<?= PROJECT_ROOT ?>/configuracion/bonos/edit?id=<?= $bono['bono_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar"><i class="bi bi-pencil"></i></a>
                                            <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Suscripción y Pago Tab -->
            <div x-show="activeTab === 'suscripcion'" x-cloak x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left: Current Subscription Details -->
                    <div class="lg:col-span-1 bg-gray-50 rounded-2xl p-6 border border-gray-100 space-y-6">
                        <div>
                            <h4 class="text-base font-bold text-gray-900 mb-1">Tu Suscripción</h4>
                            <p class="text-xs text-gray-500">Detalles del plan mensual contratado en Velion.</p>
                        </div>

                        <?php if (!empty($cuenta['plan_proximo'])) : ?>
                            <!-- Alerta de Downgrade / Cambio Programado -->
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 space-y-2">
                                <div class="flex items-start gap-2.5">
                                    <i class="bi bi-clock-history text-amber-600 text-base mt-0.5"></i>
                                    <div>
                                        <h5 class="text-xs font-bold uppercase tracking-wider text-amber-800">Cambio de plan programado</h5>
                                        <p class="text-xs text-amber-700 mt-1">
                                            Tu suscripción cambiará al plan <span class="font-bold text-amber-900"><?= htmlspecialchars($cuenta['plan_proximo']) ?></span> al finalizar tu ciclo actual
                                            <?php if (!empty($cuenta['fecha_renovacion'])) : ?>
                                                el <strong><?= date('d/m/Y', strtotime($cuenta['fecha_renovacion'])) ?></strong>.
                                            <?php else : ?>
                                                en tu próxima fecha de cobro.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <form action="<?= PROJECT_ROOT ?>/configuracion/suscripcion/cancel-downgrade" method="POST" class="pt-1">
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-white border border-amber-300 text-amber-800 px-3 py-1.5 text-xs font-semibold hover:bg-amber-100/60 transition-all shadow-2xs">
                                        <i class="bi bi-x-circle"></i>
                                        Cancelar cambio diferido
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                        <div class="p-4 bg-white rounded-xl border border-gray-200/50 space-y-4"
                             x-data="{ 
                                currentPlan: '<?= $cuenta['plan_suscripcion'] ?? 'Basico' ?>', 
                                selectedPlan: '<?= $cuenta['plan_proximo'] ?? ($cuenta['plan_suscripcion'] ?? 'Basico') ?>',
                                planOrder: { 'Basico': 1, 'Profesional': 2, 'Premium': 3 }
                             }">
                            <form action="<?= PROJECT_ROOT ?>/configuracion/suscripcion/update-plan" method="POST" class="space-y-3">
                                <div>
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-widest block mb-1.5">Plan Contratado</label>
                                    <select name="plan_suscripcion" x-model="selectedPlan" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                                        <option value="Basico" <?= ($cuenta['plan_suscripcion'] ?? '') === 'Basico' ? 'selected' : '' ?>>Básico (29,99€/mes)</option>
                                        <option value="Profesional" <?= ($cuenta['plan_suscripcion'] ?? '') === 'Profesional' ? 'selected' : '' ?>>Profesional (59,99€/mes)</option>
                                        <option value="Premium" <?= ($cuenta['plan_suscripcion'] ?? '') === 'Premium' ? 'selected' : '' ?>>Premium (99,99€/mes)</option>
                                    </select>
                                </div>

                                <!-- Dynamic notification about change type -->
                                <template x-if="planOrder[selectedPlan] > planOrder[currentPlan]">
                                    <div class="text-[11px] bg-emerald-50 text-emerald-800 border border-emerald-100 rounded-lg p-2.5 flex items-center gap-1.5">
                                        <i class="bi bi-lightning-charge-fill text-emerald-600"></i>
                                        <span><strong>Upgrade Inmediato:</strong> Se aplicará al instante.</span>
                                    </div>
                                </template>
                                
                                <template x-if="planOrder[selectedPlan] < planOrder[currentPlan]">
                                    <div class="text-[11px] bg-blue-50 text-blue-800 border border-blue-100 rounded-lg p-2.5 flex items-center gap-1.5">
                                        <i class="bi bi-calendar-check text-blue-600"></i>
                                        <span><strong>Downgrade Seguro:</strong> Entrará en vigor al renovar tu ciclo. Mantienes tus ventajas hasta entonces.</span>
                                    </div>
                                </template>

                                <button type="submit" 
                                        :disabled="selectedPlan === currentPlan && '<?= !empty($cuenta['plan_proximo']) ? 'true' : 'false' ?>' !== 'true'"
                                        class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-indigo-600 text-white px-4 py-2.5 text-xs font-semibold hover:bg-indigo-700 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-text="planOrder[selectedPlan] > planOrder[currentPlan] ? 'Subir de Plan (Inmediato)' : (planOrder[selectedPlan] < planOrder[currentPlan] ? 'Programar Cambio a Fin de Ciclo' : 'Guardar Plan')"></span>
                                </button>
                            </form>
                            
                            <div class="pt-2 border-t border-gray-100 flex justify-between text-sm text-gray-600">
                                <span>Mensualidad actual:</span>
                                <span class="font-bold text-gray-900">
                                    <?php
                                    $plan = $cuenta['plan_suscripcion'] ?? 'Basico';
                                    if ($plan === 'Basico') echo '29,99€';
                                    elseif ($plan === 'Profesional') echo '59,99€';
                                    elseif ($plan === 'Premium') echo '99,99€';
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Estado:</span>
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    <?= htmlspecialchars($cuenta['estado_cuenta'] ?? 'Activo') ?>
                                </span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>F. de Renovación:</span>
                                <span class="font-medium text-gray-800">
                                    <?= !empty($cuenta['fecha_renovacion']) ? date('d/m/Y', strtotime($cuenta['fecha_renovacion'])) : date('d/m/Y', strtotime('+1 month', strtotime($cuenta['fecha_alta'] ?? 'now'))) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2 bg-indigo-50 p-4 rounded-xl text-xs text-indigo-800 leading-relaxed border border-indigo-100">
                            <i class="bi bi-info-circle-fill text-sm"></i>
                            <p>Los cargos se realizan de forma automática cada mes a la tarjeta guardada. Las reducciones de plan se aplican al terminar el ciclo ya pagado.</p>
                        </div>
                    </div>

                    <!-- Right: Edit Card details -->
                    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 space-y-6">
                        <div>
                            <h4 class="text-base font-bold text-gray-900 mb-1">Método de Pago Guardado</h4>
                            <p class="text-xs text-gray-500">Actualiza los datos de la tarjeta de crédito para la facturación mensual.</p>
                        </div>

                        <form action="<?= PROJECT_ROOT ?>/configuracion/tarjeta/update" method="POST" class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Titular de la Tarjeta *</label>
                                    <input type="text" name="card_holder" required value="<?= htmlspecialchars($tarjeta['nombre_titular'] ?? '') ?>"
                                        class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                        placeholder="JUAN PEREZ GONZALEZ">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Número de Tarjeta *</label>
                                    <input type="text" name="card_number" required value="<?= htmlspecialchars($tarjeta['numero_completo'] ?? '') ?>" maxlength="19"
                                        class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                        placeholder="4000 1234 5678 9010">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Vence (MM/YYYY) *</label>
                                        <input type="text" name="card_expiry" required value="<?= htmlspecialchars($tarjeta['fecha_expiracion'] ?? '') ?>" maxlength="7"
                                            class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                            placeholder="12/2028">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">CVV *</label>
                                        <input type="text" name="card_cvv" required value="<?= htmlspecialchars($tarjeta['cvv'] ?? '') ?>" maxlength="4"
                                            class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                            placeholder="123">
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100 flex justify-end">
                                <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 text-white px-5 py-3 text-sm font-semibold hover:bg-gray-800 transition-all shadow-sm">
                                    <i class="bi bi-shield-check"></i>
                                    Guardar Cambios de Tarjeta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
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
