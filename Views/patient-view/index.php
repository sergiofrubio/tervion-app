<?php
$pageTitle = "Inicio - Mi Panel de Salud";
include TEMPLATE_DIR . 'header.php';

// Simulación de datos para la vista (esto vendría del controlador en una implementación real)
$nombrePaciente = $_SESSION['nombre'] ?? 'Paciente';
$proximaCita = $proximaCita ?? [
    'fecha' => '15 de Mayo, 2026',
    'hora' => '10:30',
    'fisioterapeuta' => 'Dr. Alejandro Ruiz',
    'especialidad' => 'Fisioterapia Deportiva',
    'estado' => 'Confirmada'
];

$bonosActivos = $bonosActivos ?? [
    ['nombre' => 'Bono 10 Sesiones', 'restantes' => 4, 'total' => 10],
];

$recientes = $recientes ?? [
    ['fecha' => '02/05/2026', 'servicio' => 'Sesión Individual', 'estado' => 'Completada'],
    ['fecha' => '20/04/2026', 'servicio' => 'Masaje Descontracturante', 'estado' => 'Completada'],
];
?>

<div class="space-y-8 animate-fade-in pb-12">
    <!-- Hero Section / Welcome -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-primary-700 to-indigo-800 p-8 text-white shadow-2xl shadow-primary-200/50">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">¡Hola de nuevo, <?= htmlspecialchars($nombrePaciente) ?>! 👋</h1>
                <p class="text-primary-100 text-lg max-w-xl">Nos alegra verte. Tu bienestar es nuestra prioridad. ¿En qué podemos ayudarte hoy?</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?= PROJECT_ROOT ?>/vista-pacientes/citas/nueva" class="inline-flex items-center gap-2 bg-white text-primary-700 px-6 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-primary-50 hover:scale-105 active:scale-95 shadow-lg">
                    <i class="bi bi-calendar-plus-fill"></i>
                    Reservar Cita
                </a>
                <a href="<?= PROJECT_ROOT ?>/vista-pacientes/tienda" class="inline-flex items-center gap-2 bg-primary-500/20 backdrop-blur-md border border-white/20 text-white px-6 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-white/20">
                    <i class="bi bi-cart-fill"></i>
                    Ir a la Tienda
                </a>
            </div>
        </div>
        
        <!-- Abstract background decorations -->
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-indigo-400/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Next Appointment Card -->
        <div class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-primary-50 text-primary-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar-check text-2xl"></i>
                </div>
                <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                    <?= $proximaCita['estado'] ?>
                </span>
            </div>
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Próxima Cita</h3>
            <div class="mt-2 space-y-1">
                <p class="text-xl font-bold text-gray-900"><?= $proximaCita['fecha'] ?></p>
                <p class="text-sm font-medium text-gray-600"><?= $proximaCita['hora'] ?> • <?= $proximaCita['fisioterapeuta'] ?></p>
                <p class="text-xs text-gray-400"><?= $proximaCita['especialidad'] ?></p>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50">
                <a href="<?= PROJECT_ROOT ?>/vista-pacientes/citas" class="text-primary-600 text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all">
                    Gestionar mis citas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Bonos Card -->
        <div class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:border-amber-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-ticket-perforated text-2xl"></i>
                </div>
                <span class="text-amber-600 text-xs font-bold"><?= count($bonosActivos) ?> Activo(s)</span>
            </div>
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Sesiones Disponibles</h3>
            <div class="mt-2">
                <?php if (!empty($bonosActivos)): ?>
                    <?php foreach($bonosActivos as $bono): ?>
                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <p class="text-2xl font-black text-gray-900"><?= $bono['restantes'] ?></p>
                                <p class="text-xs text-gray-400 mb-1">de <?= $bono['total'] ?> sesiones</p>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-amber-400 h-full rounded-full transition-all duration-1000" style="width: <?= ($bono['restantes'] / $bono['total']) * 100 ?>%"></div>
                            </div>
                            <p class="text-xs font-medium text-gray-600"><?= $bono['nombre'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-400 text-sm italic mt-2">No tienes bonos activos.</p>
                <?php endif; ?>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50">
                <a href="<?= PROJECT_ROOT ?>/tienda" class="text-amber-600 text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all">
                    Comprar sesiones <i class="bi bi-plus"></i>
                </a>
            </div>
        </div>

        <!-- Medical Record Card -->
        <div class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:border-rose-100 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-2xl group-hover:scale-110 transition-transform">
                    <i class="bi bi-clipboard2-pulse text-2xl"></i>
                </div>
            </div>
            <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider">Historial Médico</h3>
            <div class="mt-2 space-y-1">
                <p class="text-xl font-bold text-gray-900">Seguimiento</p>
                <p class="text-sm text-gray-600">Revisa tus informes médicos y recomendaciones de tus terapeutas.</p>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-50">
                <a href="<?= PROJECT_ROOT ?>/paciente/historial" class="text-rose-600 text-sm font-bold flex items-center gap-1 hover:gap-2 transition-all">
                    Ver mi historial <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Area: Two Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Recent Activity & Reminders -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Actividad Reciente</h2>
                    <button class="text-gray-400 hover:text-primary-600 transition-colors">
                        <i class="bi bi-three-dots"></i>
                    </button>
                </div>
                <div class="divide-y divide-gray-50">
                    <?php if (!empty($recientes)): ?>
                        <?php foreach($recientes as $item): ?>
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                        <i class="bi bi-activity"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900"><?= $item['servicio'] ?></p>
                                        <p class="text-xs text-gray-500"><?= $item['fecha'] ?></p>
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-gray-400"><?= $item['estado'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="px-6 py-8 text-center">
                            <p class="text-gray-400 italic">No hay actividad reciente.</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-50">
                    <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Ver todo el historial</a>
                </div>
            </div>

            <!-- Promotion / Tip Card -->
            <div class="relative bg-indigo-900 rounded-3xl p-8 text-white overflow-hidden shadow-xl">
                <div class="relative z-10 max-w-lg space-y-4">
                    <span class="inline-block px-3 py-1 bg-indigo-500/30 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-widest text-indigo-200 border border-indigo-400/20">Consejo de salud</span>
                    <h3 class="text-2xl font-bold">¿Sabías que la hidratación es clave para tus músculos?</h3>
                    <p class="text-indigo-200 text-sm leading-relaxed">Beber suficiente agua ayuda a mantener la elasticidad de los tejidos y previene calambres durante tus sesiones de fisioterapia. ¡No olvides tu botella!</p>
                    <button class="text-white font-bold text-sm border-b-2 border-indigo-500 pb-1 hover:text-indigo-200 transition-colors">Leer más consejos</button>
                </div>
                <div class="absolute right-0 bottom-0 opacity-20 pointer-events-none">
                    <i class="bi bi-droplet-fill text-[180px] -mr-10 -mb-10 text-indigo-400"></i>
                </div>
            </div>
        </div>

        <!-- Right: Actions & Profile Summary -->
        <div class="space-y-6">
            <!-- Quick Actions Grid -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Acciones Rápidas</h2>
                <div class="grid grid-cols-2 gap-4">
                    <a href="<?= PROJECT_ROOT ?>/paciente/citas/nueva" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 hover:bg-primary-50 group transition-all">
                        <i class="bi bi-plus-circle text-2xl text-gray-400 group-hover:text-primary-600 transition-colors"></i>
                        <span class="text-[11px] font-bold text-gray-500 group-hover:text-primary-700 mt-2 uppercase tracking-tighter">Nueva Cita</span>
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/tienda" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 hover:bg-amber-50 group transition-all">
                        <i class="bi bi-bag text-2xl text-gray-400 group-hover:text-amber-600 transition-colors"></i>
                        <span class="text-[11px] font-bold text-gray-500 group-hover:text-amber-700 mt-2 uppercase tracking-tighter">Tienda</span>
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/paciente/perfil" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 hover:bg-rose-50 group transition-all">
                        <i class="bi bi-person text-2xl text-gray-400 group-hover:text-rose-600 transition-colors"></i>
                        <span class="text-[11px] font-bold text-gray-500 group-hover:text-rose-700 mt-2 uppercase tracking-tighter">Mi Perfil</span>
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/paciente/facturas" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 hover:bg-emerald-50 group transition-all">
                        <i class="bi bi-receipt text-2xl text-gray-400 group-hover:text-emerald-600 transition-colors"></i>
                        <span class="text-[11px] font-bold text-gray-500 group-hover:text-emerald-700 mt-2 uppercase tracking-tighter">Facturas</span>
                    </a>
                </div>
            </div>

            <!-- Clinic Contact -->
            <div class="bg-primary-50 rounded-3xl p-6 border border-primary-100">
                <h3 class="text-primary-900 font-bold text-sm mb-4">¿Necesitas ayuda inmediata?</h3>
                <div class="space-y-4">
                    <a href="tel:910000000" class="flex items-center gap-3 text-primary-700 hover:text-primary-800 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm">
                            <i class="bi bi-telephone-fill text-xs"></i>
                        </div>
                        <span class="text-sm font-semibold">910 00 00 00</span>
                    </a>
                    <a href="https://wa.me/34600000000" class="flex items-center gap-3 text-primary-700 hover:text-primary-800 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm">
                            <i class="bi bi-whatsapp text-xs"></i>
                        </div>
                        <span class="text-sm font-semibold">WhatsApp</span>
                    </a>
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
