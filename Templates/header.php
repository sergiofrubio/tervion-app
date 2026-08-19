<!doctype html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Velion' : 'Panel de Gestión — Velion' ?></title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/logo-ventana.jpg" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/custom/css/app.css">

    <script src="<?= PROJECT_ROOT ?>/public/custom/js/timeout.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?= PROJECT_ROOT ?>/public/custom/js/validaciones.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$userEmail = $_SESSION['email'] ?? '';
$userName = $_SESSION['nombre'] ?? (explode('@', $userEmail)[0] ?? 'Usuario');
$userRole = $_SESSION['rol'] ?? 'Usuario';
?>

<body class="h-full flex overflow-hidden font-sans antialiased text-slate-900 bg-slate-50 <?= $hasSystemAlert ? 'pt-7' : '' ?>" x-data="{ sidebarOpen: false }">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-gray-950/70 backdrop-blur-sm transition-opacity lg:hidden"
        @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <!-- Sidebar (Color secundario bg-gray-900) -->
    <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-gray-900 border-r border-gray-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-2xl lg:shadow-none text-white">

        <!-- Sidebar Brand Header -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-gray-800 bg-gray-900">
            <a href="<?= PROJECT_ROOT ?>/inicio" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gray-800 border border-gray-700 p-1.5 flex items-center justify-center shadow-inner group-hover:border-primary-500 transition-colors">
                    <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-ventana-oscuro.jpg" alt="Velion Emblem" class="w-full h-full object-contain rounded-md">
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight text-white group-hover:text-primary-400 transition-colors leading-none">Velion</span>
                    <span class="text-[10px] uppercase font-semibold tracking-widest text-gray-400 mt-1">Gestión Clínica</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white focus:outline-none p-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Links -->
        <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">

            <!-- Grupo: Principal -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Principal</div>
                <div class="space-y-1">
                    <?php $isHomeActive = (strpos($currentUri, '/inicio') !== false || $currentUri === PROJECT_ROOT . '/' || $currentUri === PROJECT_ROOT); ?>
                    <a href="<?= PROJECT_ROOT ?>/inicio"
                        class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isHomeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-grid-1x2 text-base <?= $isHomeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                        <span>Panel de Control</span>
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Paciente') : ?>
                <!-- Menú para Pacientes -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Mi Área Personal</div>
                    <div class="space-y-1">
                        <?php $isAppointmentsActive = strpos($currentUri, '/paciente/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/citas"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar-week text-base <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Mis Citas</span>
                        </a>

                        <?php $isInvoicesActive = strpos($currentUri, '/paciente/facturas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/facturas"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-receipt text-base <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Mis Facturas</span>
                        </a>

                        <?php $isShopActive = strpos($currentUri, '/paciente/tienda') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/tienda"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isShopActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-bag text-base <?= $isShopActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Tienda & Bonos</span>
                        </a>

                        <?php $isProfileActive = strpos($currentUri, '/paciente/perfil') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/perfil"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isProfileActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-person-badge text-base <?= $isProfileActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Mi Perfil</span>
                        </a>
                    </div>
                </div>

            <?php else : ?>
                <!-- Menú para Personal Sanitario / Administración -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Atención y Citas</div>
                    <div class="space-y-1">
                        <?php $isPatientsActive = strpos($currentUri, '/pacientes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/pacientes"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isPatientsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-people text-base <?= $isPatientsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Pacientes</span>
                        </a>

                        <?php $isAppointmentsActive = strpos($currentUri, '/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/citas"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar3 text-base <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Agenda y Citas</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Equipo y Horarios</div>
                    <div class="space-y-1">
                        <?php $isPayrollActive = strpos($currentUri, '/nominas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/nominas"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isPayrollActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-cash-stack text-base <?= $isPayrollActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Nóminas</span>
                        </a>

                        <?php $isTimeActive = strpos($currentUri, '/control-horario') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/control-horario"
                            class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isTimeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-clock-history text-base <?= $isTimeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span>Control Horario</span>
                        </a>
                    </div>
                </div>

                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') : ?>
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">Administración</div>
                        <div class="space-y-1">
                            <?php $isInvoicesActive = strpos($currentUri, '/facturas') !== false; ?>
                            <a href="<?= PROJECT_ROOT ?>/facturas"
                                class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                                <i class="bi bi-receipt-cutoff text-base <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                                <span>Facturas & AEAT</span>
                            </a>

                            <?php $isConfigActive = strpos($currentUri, '/configuracion') !== false; ?>
                            <a href="<?= PROJECT_ROOT ?>/configuracion"
                                class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all <?= $isConfigActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                                <i class="bi bi-sliders text-base <?= $isConfigActive ? 'text-white' : 'text-gray-400' ?>"></i>
                                <span>Configuración</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </nav>

        <!-- Sidebar User Card & Footer -->
        <div class="p-4 border-t border-gray-800 bg-gray-950">
            <div class="flex items-center justify-between gap-3 p-2 rounded-2xl bg-gray-900 border border-gray-800">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-primary-600/20 text-primary-400 border border-primary-500/30 flex items-center justify-center font-bold text-xs shrink-0">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-white truncate"><?= htmlspecialchars($userName) ?></p>
                        <span class="inline-block text-[10px] text-gray-400 font-medium"><?= htmlspecialchars($userRole) ?></span>
                    </div>
                </div>
                <a href="<?= PROJECT_ROOT ?>/logout" title="Cerrar sesión"
                    class="p-2 rounded-xl text-gray-400 hover:text-rose-400 hover:bg-gray-800 transition-colors">
                    <i class="bi bi-box-arrow-right text-base"></i>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden bg-slate-50">

        <!-- Top header for Mobile and Desktop context -->
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-gray-400">
                    <span>Plataforma</span>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                    <span class="text-gray-700 font-semibold"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Panel de Control' ?></span>
                </div>
            </div>

            <!-- Header Quick Actions & Status -->
            <div class="flex items-center gap-3">
                <!-- <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-medium">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Sistema Conectado</span>
                </div> -->
                <!-- <div class="text-xs font-medium text-gray-500 hidden sm:block">
                    <?= date('d M Y') ?>
                </div> -->
            </div>
        </header>

        <!-- Main content scrollable container -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto" id="contenido">
            <!-- Vistas dinámicas -->