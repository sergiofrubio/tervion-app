<?php
$pageTitle = "Detalle de Usuario";
include TEMPLATE_DIR . 'header.php';

function calcularEdad($fechaNacimiento) {
    if (empty($fechaNacimiento)) return 'N/D';
    $nacimiento = new DateTime($fechaNacimiento);
    $hoy = new DateTime();
    $edad = $hoy->diff($nacimiento);
    return $edad->y;
}
?>

<div class="space-y-6 animate-fade-in-up" x-data="{ activeTab: 'history' }">
    <!-- Profile Header Banner -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-primary-600 to-primary-400"></div>
        <div class="px-6 pb-6">
            <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-12 sm:space-x-5">
                <div class="relative">
                    <div class="h-24 w-24 rounded-2xl bg-white p-1 shadow-md">
                        <div class="h-full w-full rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 text-3xl font-bold">
                            <?= substr($usuario['nombre'], 0, 1) ?>
                        </div>
                    </div>
                </div>
                <div class="mt-6 sm:mt-0 flex-1 text-center sm:text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                        <h1 class="text-2xl font-bold text-gray-900"><?= $usuario['nombre'] . ' ' . $usuario['apellidos'] ?></h1>
                        <!-- <span class="inline-flex items-center rounded-full bg-primary-50 px-2.5 py-0.5 text-sm font-medium text-primary-700 ring-1 ring-inset ring-primary-700/10">
                            <?= $usuario['rol'] ?>
                        </span> -->
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="bi bi-person-vcard mr-1"></i> ID: <?= $usuario['usuario_id'] ?>
                    </p>
                </div>
                <div class="mt-6 sm:mt-0">
                    <a href="<?= PROJECT_ROOT ?>/usuarios" class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                        <i class="bi bi-arrow-left"></i>
                        Volver
                    </a>
                </div>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-6 border-t border-gray-50">
                <div class="p-3 rounded-2xl bg-gray-50/50">
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Género</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-900"><?= $usuario['genero'] ?? 'No especificado' ?></span>
                </div>
                <div class="p-3 rounded-2xl bg-gray-50/50">
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Edad</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-900"><?= calcularEdad($usuario['fecha_nacimiento']) ?> años</span>
                </div>
                <div class="p-3 rounded-2xl bg-gray-50/50">
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">F. Nacimiento</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-900"><?= date('d/m/Y', strtotime($usuario['fecha_nacimiento'])) ?></span>
                </div>
                <div class="p-3 rounded-2xl bg-gray-50/50">
                    <span class="block text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-900"><?= $usuario['telefono'] ?? 'S/T' ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Sidebar: Info Card -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="bi bi-info-circle text-primary-600"></i>
                    Información General
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Correo Electrónico</label>
                        <a href="mailto:<?= $usuario['email'] ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700 break-all transition-colors">
                            <?= $usuario['email'] ?>
                        </a>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1">Dirección</label>
                        <p class="text-sm text-gray-900 leading-relaxed">
                            <?= $usuario['direccion'] ?><br>
                            <?= $usuario['cp'] . ' ' . $usuario['municipio'] ?><br>
                            <?= $usuario['provincia'] ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content: Tabbed Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                <!-- Tabs Header -->
                <div class="border-b border-gray-100 bg-gray-50/30 px-6 pt-4">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button 
                            @click="activeTab = 'history'"
                            :class="activeTab === 'history' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-file-earmark-medical"></i>
                            Historial Médico
                        </button>
                        <button 
                            @click="activeTab = 'appointments'"
                            :class="activeTab === 'appointments' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-calendar-check"></i>
                            Citas
                        </button>
                        <?php if ($rol == "Paciente") : ?>
                        <button 
                            @click="activeTab = 'settings'"
                            :class="activeTab === 'settings' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                            <i class="bi bi-gear"></i>
                            Gestionar Cuenta
                        </button>
                        <?php endif; ?>
                    </nav>
                </div>

                <!-- Tab Panels -->
                <div class="p-6 flex-1">
                    <!-- History Tab -->
                    <div x-show="activeTab === 'history'" x-cloak x-transition>
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-base font-bold text-gray-900">Informes Clínicos</h4>
                            <?php if ($rol != "Paciente") : ?>
                            <a href="<?= PROJECT_ROOT ?>/medical-history/create?paciente_id=<?= $usuario['usuario_id'] ?>" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                                <i class="bi bi-plus-circle"></i>
                                Nuevo Informe
                            </a>
                            <?php endif; ?>
                        </div>

                        <?php if (empty($informes)) : ?>
                            <div class="flex flex-col items-center justify-center py-12 text-center bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                    <i class="bi bi-folder2-open text-xl text-gray-400"></i>
                                </div>
                                <p class="text-sm text-gray-500">No hay registros médicos disponibles.</p>
                            </div>
                        <?php else : ?>
                            <div class="space-y-3">
                                <?php foreach ($informes as $informe) : ?>
                                    <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-100 hover:bg-gray-50/50 transition-colors">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900"><?php echo $informe['especialidad']; ?></div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <i class="bi bi-clock mr-1"></i>
                                                <?php echo date('d/m/Y H:i', strtotime($informe['fecha_consulta'])); ?>
                                            </div>
                                        </div>
                                        <a href="<?= PROJECT_ROOT ?>/medical-history/detail?id=<?= $informe['historial_id'] ?>" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                            <i class="bi bi-eye text-lg"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Appointments Tab -->
                    <div x-show="activeTab === 'appointments'" x-cloak x-transition>
                        <h4 class="text-base font-bold text-gray-900 mb-6">Próximas y Pasadas</h4>
                        <div class="overflow-x-auto rounded-2xl border border-gray-100">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Motivo</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    <?php if (empty($citas)) : ?>
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500 italic">
                                                No se han encontrado citas programadas.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($citas as $cita) : ?>
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                                    <?= date('d/m/Y H:i', strtotime($cita['fecha_hora'])) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-600">
                                                    <?= $cita['descripcion'] ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Programada
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-right text-sm">
                                                    <button class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Settings Tab Placeholder -->
                    <div x-show="activeTab === 'settings'" x-cloak x-transition>
                        <h4 class="text-base font-bold text-gray-900 mb-6">Gestión de Cuenta</h4>
                        <div class="bg-amber-50 rounded-2xl border border-amber-100 p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                                    <i class="bi bi-shield-lock text-xl"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-amber-900">Seguridad de la cuenta</h5>
                                    <p class="text-sm text-amber-800 mt-1 leading-relaxed">
                                        Como paciente, puedes modificar tus datos de contacto y cambiar tu contraseña desde esta sección. 
                                        Para cambios en tu historial clínico, contacta con tu fisioterapeuta.
                                    </p>
                                    <button class="mt-4 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-700 transition-all">
                                        Editar Perfil
                                    </button>
                                </div>
                            </div>
                        </div>
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