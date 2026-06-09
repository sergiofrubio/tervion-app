<?php
$pageTitle = "Inicio";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-8 animate-fade-in-up">
    <!-- Welcome Header -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-6 overflow-hidden relative">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-primary-50 rounded-full opacity-50"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">¡Hola de nuevo, <?= explode('@', $_SESSION['email'])[0] ?>! 👋</h1>
            <p class="mt-2 text-gray-500 max-w-md">Hoy es <?= date('l, d \d\e F') ?>. Tienes un día productivo por delante con <?= $todayAppointmentsCount ?> citas programadas.</p>
        </div>
        <div class="relative z-10 flex gap-3">
            <a href="<?= PROJECT_ROOT ?>/citas/create" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-primary-700 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-plus-lg"></i>
                Nueva Cita
            </a>
            <a href="<?= PROJECT_ROOT ?>/usuarios/create" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-gray-800 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-person-plus"></i>
                Nuevo Paciente
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Tarjeta de pacientes -->
        <div class="bg-white overflow-hidden rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md duration-300 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-14 h-14 bg-primary-50 text-primary-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="bi bi-people-fill text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pacientes Totales</dt>
                            <dd class="flex items-baseline mt-1">
                                <div class="text-3xl font-black text-gray-900 tracking-tight"><?= number_format($totalPatients) ?></div>
                                <div class="ml-3 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center">
                                    Total
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de facturas -->
        <div class="bg-white overflow-hidden rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md duration-300 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="bi bi-currency-euro text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ingresos Mes</dt>
                            <dd class="flex items-baseline mt-1">
                                <div class="text-3xl font-black text-gray-900 tracking-tight"><?= number_format($monthlyRevenue, 2) ?>€</div>
                                <div class="ml-3 text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full flex items-center">
                                    Pagado
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de citas -->
        <div class="bg-white overflow-hidden rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md duration-300 group">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="bi bi-calendar-check-fill text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-xs font-bold text-gray-400 uppercase tracking-wider">Citas Hoy</dt>
                            <dd class="flex items-baseline mt-1">
                                <div class="text-3xl font-black text-gray-900 tracking-tight"><?= $todayAppointmentsCount ?></div>
                                <div class="ml-3 text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full flex items-center">
                                    Sesiones
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upcoming Appointments -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900 tracking-tight">Próximas Citas</h3>
                <a href="<?= PROJECT_ROOT ?>/appointments" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">Ver todas</a>
            </div>
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <ul class="divide-y divide-gray-50">
                    <?php if (empty($upcomingAppointments)): ?>
                        <li class="p-10 text-center text-gray-500 italic">No hay citas programadas para hoy.</li>
                    <?php else: ?>
                        <?php foreach ($upcomingAppointments as $appointment): ?>
                            <li class="p-5 hover:bg-gray-50/50 transition-colors flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 flex flex-col items-center justify-center border border-gray-100 group-hover:bg-white group-hover:border-primary-100 transition-colors">
                                        <span class="text-xs font-bold text-gray-400 group-hover:text-primary-400"><?= date('H', strtotime($appointment['fecha_hora'])) ?></span>
                                        <span class="text-xs font-black text-gray-900 leading-none"><?= date('i', strtotime($appointment['fecha_hora'])) ?></span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900"><?= $appointment['paciente_nombre'] . ' ' . $appointment['paciente_apellidos'] ?></h4>
                                        <p class="text-xs text-gray-500 mt-0.5"><?= $appointment['especialidad'] ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="hidden sm:inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        <?= $appointment['estado'] ?>
                                    </span>
                                    <a href="<?= PROJECT_ROOT ?>/appointments/edit?id=<?= $appointment['cita_id'] ?>" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Recent Patients & Insights -->
        <div class="space-y-6">
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-gray-900 tracking-tight">Pacientes Recientes</h3>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <?php if (empty($recentPatients)): ?>
                        <p class="text-xs text-gray-500 italic text-center py-4">No hay pacientes registrados recientemente.</p>
                    <?php else: ?>
                        <?php foreach ($recentPatients as $patient): ?>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs uppercase">
                                    <?= substr($patient['nombre'], 0, 1) . substr($patient['apellidos'], 0, 1) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-bold text-gray-900 truncate"><?= $patient['nombre'] . ' ' . $patient['apellidos'] ?></h4>
                                    <p class="text-xs text-gray-500"><?= date('d/m/Y', strtotime($patient['fecha_creacion'])) ?></p>
                                </div>
                                <a href="<?= PROJECT_ROOT ?>/users/userdetail?id=<?= $patient['usuario_id'] ?>" class="p-1.5 text-gray-300 hover:text-gray-500 transition-colors">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <a href="<?= PROJECT_ROOT ?>/users" class="block text-center w-full mt-2 py-2 text-xs font-bold text-gray-500 hover:text-primary-600 transition-colors uppercase tracking-widest border-t border-gray-50 pt-4">
                        Ver listado completo
                    </a>
                </div>
            </div>

            <!-- Dashboard Insight -->
            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl shadow-lg p-6 text-white overflow-hidden relative">
                <i class="bi bi-lightning-charge-fill absolute -bottom-4 -right-4 text-6xl text-white/10 rotate-12"></i>
                <h4 class="text-sm font-bold opacity-75 uppercase tracking-widest mb-1">Tip del día</h4>
                <p class="text-sm font-medium leading-relaxed">¿Sabías que puedes exportar tus facturas directamente desde el listado de pacientes? Ahorra tiempo en tu gestión mensual.</p>
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