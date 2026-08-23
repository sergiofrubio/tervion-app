<?php
$pageTitle = "Panel de Control";
include TEMPLATE_DIR . 'header.php';

// Formato de fecha en español
$diasSemana = ['Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado'];
$meses = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];

$diaIngles = date('l');
$mesIngles = date('F');
$fechaHoyTexto = ($diasSemana[$diaIngles] ?? $diaIngles) . ', ' . date('d') . ' de ' . ($meses[$mesIngles] ?? $mesIngles);

$userGreetingName = !empty($_SESSION['nombre']) ? $_SESSION['nombre'] : (explode('@', $_SESSION['email'] ?? 'Doctor')[0]);
?>

<div class="space-y-8 animate-fade-in">
    <!-- Hero / Welcome Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
        <!-- Background subtle glowing accent inspired by landing hero -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-gradient-to-br from-primary-100 to-transparent rounded-full pointer-events-none opacity-40 blur-2xl"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <!-- <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 border border-primary-100 text-primary-600 text-xs font-semibold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                    <span>Resumen Operativo del Centro</span>
                </div> -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-light text-gray-900 tracking-tight leading-tight">
                    Bienvenido de nuevo, <span class="font-normal text-primary-600"><?= htmlspecialchars($userGreetingName) ?></span>
                </h1>
                <p class="text-sm sm:text-base text-gray-500 font-light leading-relaxed">
                    Hoy es <span class="font-medium text-gray-700"><?= $fechaHoyTexto ?></span>. Tienes <span class="font-semibold text-primary-600"><?= (int)$todayAppointmentsCount ?></span> <?= ((int)$todayAppointmentsCount === 1) ? 'cita programada' : 'citas programadas' ?> para la jornada de hoy.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="<?= PROJECT_ROOT ?>/citas/crear"
                    class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-all hover:scale-105 active:scale-95">
                    <i class="bi bi-plus-lg text-sm"></i>
                    <span>Nueva Cita</span>
                </a>
                <a href="<?= PROJECT_ROOT ?>/pacientes/crear"
                    class="inline-flex items-center gap-2 rounded-full bg-gray-900 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm hover:bg-gray-800 transition-all hover:scale-105 active:scale-95">
                    <i class="bi bi-person-plus text-sm"></i>
                    <span>Alta Paciente</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Metrics / Key Indicators Grid (Inspirado en e-Residency & Landing Stats) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Tarjeta: Pacientes Totales -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Directorio Clínico</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700 border border-primary-100">
                    Registrados
                </span>
            </div>
            <div class="my-4 flex items-baseline justify-between">
                <div>
                    <div class="text-4xl sm:text-5xl font-light text-gray-900 tracking-tight">
                        <?= number_format($totalPatients) ?>
                    </div>
                    <div class="text-xs font-medium text-gray-500 mt-1">Pacientes en historial</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center justify-between text-xs">
                <a href="<?= PROJECT_ROOT ?>/pacientes" class="text-primary-600 hover:text-primary-700 font-semibold inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    <span>Gestionar pacientes</span>
                    <i class="bi bi-arrow-right text-[11px]"></i>
                </a>
            </div>
        </div>

        <!-- Tarjeta: Facturación del Mes -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Facturación <?= $meses[$mesIngles] ?? date('F') ?></span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                    <i class="bi bi-shield-check mr-1 text-[11px]"></i> VERI*FACTU
                </span>
            </div>
            <div class="my-4 flex items-baseline justify-between">
                <div>
                    <div class="text-4xl sm:text-5xl font-light text-gray-900 tracking-tight">
                        <?= number_format($monthlyRevenue, 2, ',', '.') ?><span class="text-2xl sm:text-3xl font-light text-gray-500 ml-1">€</span>
                    </div>
                    <div class="text-xs font-medium text-gray-500 mt-1">Total facturado y cobrado</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-currency-euro"></i>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center justify-between text-xs">
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                    <a href="<?= PROJECT_ROOT ?>/facturas" class="text-emerald-700 hover:text-emerald-800 font-semibold inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                        <span>Ver libro de facturas</span>
                        <i class="bi bi-arrow-right text-[11px]"></i>
                    </a>
                <?php else: ?>
                    <span class="text-gray-400">Actualizado en tiempo real</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tarjeta: Citas Hoy -->
        <div class="bg-white rounded-3xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col justify-between sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Agenda de Hoy</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                    Sesiones
                </span>
            </div>
            <div class="my-4 flex items-baseline justify-between">
                <div>
                    <div class="text-4xl sm:text-5xl font-light text-gray-900 tracking-tight">
                        <?= (int)$todayAppointmentsCount ?>
                    </div>
                    <div class="text-xs font-medium text-gray-500 mt-1">Citas reservadas para hoy</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center justify-between text-xs">
                <a href="<?= PROJECT_ROOT ?>/citas" class="text-amber-700 hover:text-amber-800 font-semibold inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
                    <span>Abrir agenda</span>
                    <i class="bi bi-arrow-right text-[11px]"></i>
                </a>
            </div>
        </div>

    </div>

    <!-- Main Content Layout (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Columna Izquierda (2 spans): Próximas Citas -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">Próximas Citas</h2>
                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                        <?= count($upcomingAppointments ?? []) ?>
                    </span>
                </div>
                <a href="<?= PROJECT_ROOT ?>/citas" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors inline-flex items-center gap-1">
                    <span>Ver agenda completa</span>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                <?php if (empty($upcomingAppointments)): ?>
                    <div class="py-16 px-6 text-center space-y-4">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 text-2xl">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <div class="space-y-1 max-w-sm mx-auto">
                            <h3 class="text-sm font-bold text-gray-900">No hay citas pendientes para hoy</h3>
                            <p class="text-xs text-gray-500 font-light leading-relaxed">Las nuevas citas programadas para la jornada se listarán aquí en tiempo real.</p>
                        </div>
                        <a href="<?= PROJECT_ROOT ?>/citas/crear" class="inline-flex items-center gap-2 rounded-full bg-primary-50 text-primary-600 hover:bg-primary-100 px-4 py-2 text-xs font-semibold transition-colors">
                            <i class="bi bi-plus-lg"></i>
                            <span>Programar una cita</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-gray-100">
                        <?php foreach ($upcomingAppointments as $appointment):
                            $timeObj = strtotime($appointment['fecha_hora']);
                            $appointmentHour = date('H:i', $timeObj);
                            $appointmentDate = date('d/m', $timeObj);
                            $patientFullName = trim(($appointment['paciente_nombre'] ?? '') . ' ' . ($appointment['paciente_apellidos'] ?? ''));
                            $status = $appointment['estado'] ?? 'Programada';

                            // Badges por estado
                            $statusClasses = 'bg-blue-50 text-blue-700 border-blue-100';
                            if ($status === 'Completada' || $status === 'Realizada') {
                                $statusClasses = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                            } elseif ($status === 'Cancelada') {
                                $statusClasses = 'bg-rose-50 text-rose-700 border-rose-100';
                            } elseif ($status === 'Pendiente') {
                                $statusClasses = 'bg-amber-50 text-amber-700 border-amber-100';
                            }
                        ?>
                            <div class="p-4 sm:p-5 hover:bg-gray-50 transition-colors flex items-center justify-between gap-4 group">
                                <div class="flex items-center gap-4 min-w-0">
                                    <!-- Time Pill -->
                                    <div class="w-14 h-14 rounded-2xl bg-gray-50 border border-gray-200 group-hover:border-primary-200 group-hover:bg-primary-50 flex flex-col items-center justify-center shrink-0 transition-colors">
                                        <span class="text-sm font-extrabold text-gray-900 group-hover:text-primary-700 leading-none"><?= $appointmentHour ?></span>
                                        <span class="text-[10px] font-semibold text-gray-400 mt-0.5"><?= $appointmentDate ?></span>
                                    </div>

                                    <!-- Patient Info -->
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-primary-600 transition-colors">
                                            <?= htmlspecialchars($patientFullName) ?>
                                        </h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold border <?= $statusClasses ?>">
                                                <?= htmlspecialchars($status) ?>
                                            </span>
                                            <?php if (!empty($appointment['motivo_consulta'])): ?>
                                                <span class="text-xs text-gray-400 truncate max-w-xs hidden sm:inline-block">
                                                    · <?= htmlspecialchars($appointment['motivo_consulta']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="<?= PROJECT_ROOT ?>/citas?id=<?= $appointment['cita_id'] ?>"
                                        class="p-2 rounded-xl text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-colors"
                                        title="Ver detalles de la cita">
                                        <i class="bi bi-arrow-right-short text-xl"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Columna Derecha (1 span): Pacientes Recientes & Centro de Control -->
        <div class="space-y-6">

            <!-- Pacientes Recientes -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">Pacientes Recientes</h2>
                    <a href="<?= PROJECT_ROOT ?>/pacientes" class="text-xs font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                        Ver todos
                    </a>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-5 space-y-4">
                    <?php if (empty($recentPatients)): ?>
                        <p class="text-xs text-gray-500 italic text-center py-6">No hay pacientes registrados recientemente.</p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($recentPatients as $patient):
                                $initials = strtoupper(substr($patient['nombre'] ?? '', 0, 1) . substr($patient['apellidos'] ?? '', 0, 1));
                                $patientName = trim(($patient['nombre'] ?? '') . ' ' . ($patient['apellidos'] ?? ''));
                                $createdDate = !empty($patient['fecha_creacion']) ? date('d/m/Y', strtotime($patient['fecha_creacion'])) : 'Reciente';
                            ?>
                                <div class="flex items-center justify-between gap-3 p-2.5 rounded-2xl hover:bg-gray-50 transition-colors group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                            <?= $initials ?: 'P' ?>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-xs sm:text-sm font-bold text-gray-900 truncate group-hover:text-primary-600 transition-colors">
                                                <?= htmlspecialchars($patientName) ?>
                                            </h4>
                                            <p class="text-[11px] text-gray-400">Alta: <?= $createdDate ?></p>
                                        </div>
                                    </div>
                                    <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $patient['usuario_id'] ?>"
                                        class="p-2 rounded-xl text-gray-300 hover:text-primary-600 hover:bg-primary-50 transition-colors"
                                        title="Ficha clínica">
                                        <i class="bi bi-file-earmark-person text-base"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="pt-3 border-t border-gray-100 text-center">
                        <a href="<?= PROJECT_ROOT ?>/pacientes" class="text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-primary-600 transition-colors">
                            Directorio completo de pacientes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Widget Banner: Inspirado en la landing y Estonia e-Residency -->
            <!-- <div class="rounded-3xl bg-gray-900 text-white p-6 shadow-xl relative overflow-hidden border border-gray-800">
                 <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-primary-600 rounded-full opacity-25 blur-2xl pointer-events-none"></div>
                
                <div class="relative z-10 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-primary-500 bg-opacity-20 text-primary-400 border border-primary-500 text-[10px] font-bold uppercase tracking-wider">
                            <i class="bi bi-shield-check"></i> Cumplimiento Normativo
                        </span>
                    </div>
                    <h3 class="text-sm font-bold text-white tracking-tight">
                        VERI*FACTU & Facturación AEAT
                    </h3>
                    <p class="text-xs text-gray-400 font-light leading-relaxed">
                        Tus facturas y registros cumplen automáticamente con la normativa fiscal vigente. Generación de encadenamiento y QR instantáneo.
                    </p>
                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador'): ?>
                        <div class="pt-2">
                            <a href="<?= PROJECT_ROOT ?>/facturas" class="inline-flex items-center gap-1 text-xs font-semibold text-primary-400 hover:text-primary-300 transition-colors">
                                <span>Configuración fiscal</span>
                                <i class="bi bi-arrow-right text-[11px]"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div> 
            </div> -->

        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>