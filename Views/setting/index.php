<?php
$pageTitle = "Configuración";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up" x-data="{ activeTab: 'clinica' }">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Configuración del Sistema</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona horarios, ausencias, especialidades y bonos de la clínica.</p>
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
                    @click="activeTab = 'especialidades'"
                    :class="activeTab === 'especialidades' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-tags"></i>
                    Especialidades
                </button>
                <button 
                    @click="activeTab = 'bonos'"
                    :class="activeTab === 'bonos' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-ticket-perforated"></i>
                    Bonos
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

                <form action="<?= PROJECT_ROOT ?>/configuracion/clinica/update" method="POST" class="max-w-4xl">
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

                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sitio Web</label>
                            <input type="url" name="sitio_web" value="<?= $clinica['sitio_web'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="https://www.clinica.com">
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

            <!-- Especialidades Tab -->
            <div x-show="activeTab === 'especialidades'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Especialidades Médicas</h4>
                    <a href="<?= PROJECT_ROOT ?>/configuracion/especialidades/create" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nueva Especialidad
                    </a>
                </div>
                
                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($especialidades)) : ?>
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay especialidades registradas.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($especialidades as $especialidad) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">#<?= $especialidad['especialidad_id'] ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= $especialidad['descripcion'] ?></td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="<?= PROJECT_ROOT ?>/configuracion/especialidades/edit?id=<?= $especialidad['especialidad_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar"><i class="bi bi-pencil"></i></a>
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
