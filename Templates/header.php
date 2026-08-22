<!doctype html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Tervion' : 'Panel de Gestión — Tervion' ?></title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion-sin-fondo.png" type="image/jpeg">

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

    <!-- Dynamic SPA Navigation without full page reloads -->
    <script src="<?= PROJECT_ROOT ?>/public/custom/js/dynamic-nav.js" defer></script>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$userEmail = $_SESSION['email'] ?? '';
$userName = $_SESSION['nombre'] ?? (explode('@', $userEmail)[0] ?? 'Usuario');
$userRole = $_SESSION['rol'] ?? 'Usuario';
?>

<body class="h-full flex overflow-hidden font-sans antialiased text-slate-900 bg-slate-50 <?= $hasSystemAlert ? 'pt-7' : '' ?>"
    x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('tervion_sidebar_collapsed') === 'true',
          toggleCollapse() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('tervion_sidebar_collapsed', this.sidebarCollapsed);
          }
      }">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-gray-950/70 backdrop-blur-sm transition-opacity lg:hidden"
        @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <!-- Sidebar (Color secundario bg-gray-900) -->
    <aside :class="sidebarCollapsed ? 'w-20' : 'w-60'"
        class="fixed inset-y-0 left-0 z-50 bg-gray-900 border-r border-gray-800 transition-[width] duration-300 ease-in-out lg:static lg:inset-0 flex flex-col shadow-2xl lg:shadow-none text-white select-none shrink-0 overflow-x-hidden"
        :style="window.innerWidth < 1024 ? (sidebarOpen ? 'transform: translateX(0);' : 'transform: translateX(-100%);') : ''">

        <!-- Sidebar Brand Header -->
        <div class="flex items-center justify-between h-20 border-b border-gray-800 bg-gray-900 transition-all duration-300 relative px-4"
            :class="sidebarCollapsed ? 'justify-center px-2' : 'px-5'">
            <a href="<?= PROJECT_ROOT ?>/inicio" class="flex items-center gap-3 group overflow-hidden shrink-0" :title="sidebarCollapsed ? 'Tervion' : ''">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-claro-sin-fondo.png" alt="Tervion Logo" class="h-8 w-auto object-contain transition-all duration-300" :class="sidebarCollapsed ? 'h-6' : 'h-8'">
            </a>

            <!-- Botón cerrar móvil -->
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white focus:outline-none p-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Links -->
        <nav class="flex-1 px-3 py-6 overflow-y-auto overflow-x-hidden transition-all duration-300"
            :class="sidebarCollapsed ? 'space-y-1' : 'space-y-6'">

            <!-- Grupo: Principal -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 overflow-hidden whitespace-nowrap"
                    :class="sidebarCollapsed ? 'opacity-0 h-0 mb-0 py-0 pointer-events-none' : 'opacity-100 h-auto'">
                    Principal
                </div>
                <div class="space-y-1">
                    <?php $isHomeActive = (strpos($currentUri, '/inicio') !== false || $currentUri === PROJECT_ROOT . '/' || $currentUri === PROJECT_ROOT); ?>
                    <a href="<?= PROJECT_ROOT ?>/inicio"
                        :title="sidebarCollapsed ? 'Panel de Control' : ''"
                        :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                        class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isHomeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-grid-1x2 text-base shrink-0 <?= $isHomeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                        <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                            :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Panel de Control</span>
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Paciente') : ?>
                <!-- Menú para Pacientes -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 overflow-hidden whitespace-nowrap"
                        :class="sidebarCollapsed ? 'opacity-0 h-0 mb-0 py-0 pointer-events-none' : 'opacity-100 h-auto'">
                        Mi Área Personal
                    </div>
                    <div class="space-y-1">
                        <?php $isAppointmentsActive = strpos($currentUri, '/paciente/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/citas"
                            :title="sidebarCollapsed ? 'Mis Citas' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar-week text-base shrink-0 <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Mis Citas</span>
                        </a>

                        <?php $isInvoicesActive = strpos($currentUri, '/paciente/facturas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/facturas"
                            :title="sidebarCollapsed ? 'Mis Facturas' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-receipt text-base shrink-0 <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Mis Facturas</span>
                        </a>

                        <?php $isShopActive = strpos($currentUri, '/paciente/tienda') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/tienda"
                            :title="sidebarCollapsed ? 'Tienda & Bonos' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isShopActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-bag text-base shrink-0 <?= $isShopActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Tienda & Bonos</span>
                        </a>

                        <?php $isProfileActive = strpos($currentUri, '/paciente/perfil') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/perfil"
                            :title="sidebarCollapsed ? 'Mi Perfil' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isProfileActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-person-badge text-base shrink-0 <?= $isProfileActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Mi Perfil</span>
                        </a>
                    </div>
                </div>

            <?php else : ?>
                <!-- Menú para Personal Sanitario / Administración -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 overflow-hidden whitespace-nowrap"
                        :class="sidebarCollapsed ? 'opacity-0 h-0 mb-0 py-0 pointer-events-none' : 'opacity-100 h-auto'">
                        Atención y Citas
                    </div>
                    <div class="space-y-1">
                        <?php $isPatientsActive = strpos($currentUri, '/pacientes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/pacientes"
                            :title="sidebarCollapsed ? 'Pacientes' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isPatientsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-people text-base shrink-0 <?= $isPatientsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Pacientes</span>
                        </a>

                        <?php $isAppointmentsActive = strpos($currentUri, '/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/citas"
                            :title="sidebarCollapsed ? 'Agenda' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar3 text-base shrink-0 <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Agenda</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 overflow-hidden whitespace-nowrap"
                        :class="sidebarCollapsed ? 'opacity-0 h-0 mb-0 py-0 pointer-events-none' : 'opacity-100 h-auto'">
                        Equipo y Horarios
                    </div>
                    <div class="space-y-1">
                        <?php $isTimeActive = strpos($currentUri, '/fichajes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/fichajes"
                            :title="sidebarCollapsed ? 'Control Horario' : ''"
                            :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                            class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isTimeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-clock-history text-base shrink-0 <?= $isTimeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Control Horario</span>
                        </a>
                    </div>
                </div>

                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') : ?>
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400 transition-all duration-300 overflow-hidden whitespace-nowrap"
                            :class="sidebarCollapsed ? 'opacity-0 h-0 mb-0 py-0 pointer-events-none' : 'opacity-100 h-auto'">
                            Administración
                        </div>
                        <div class="space-y-1">
                            <?php $isInvoicesActive = strpos($currentUri, '/facturas') !== false; ?>
                            <a href="<?= PROJECT_ROOT ?>/facturas"
                                :title="sidebarCollapsed ? 'Facturas' : ''"
                                :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                                class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                                <i class="bi bi-receipt-cutoff text-base shrink-0 <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                                <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                    :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Facturas</span>
                            </a>

                            <?php $isConfigActive = strpos($currentUri, '/configuracion') !== false; ?>
                            <a href="<?= PROJECT_ROOT ?>/configuracion"
                                :title="sidebarCollapsed ? 'Configuración' : ''"
                                :class="sidebarCollapsed ? 'justify-center px-5' : 'px-3.5 gap-3'"
                                class="flex items-center py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isConfigActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                                <i class="bi bi-sliders text-base shrink-0 <?= $isConfigActive ? 'text-white' : 'text-gray-400' ?>"></i>
                                <span class="whitespace-nowrap transition-all duration-300 overflow-hidden"
                                    :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">Configuración</span>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </nav>

        <!-- Sidebar User Card & Footer -->
        <div class="p-3 border-t border-gray-800 bg-gray-950">
            <div class="flex items-center justify-between gap-3 p-2 rounded-2xl bg-gray-900 border border-gray-800 transition-all duration-300 overflow-hidden"
                :class="sidebarCollapsed ? 'p-1 justify-center' : 'p-2'">
                <div class="flex items-center gap-3 min-w-0 overflow-hidden" :title="sidebarCollapsed ? '<?= htmlspecialchars($userName) ?> (<?= htmlspecialchars($userRole) ?>)' : ''">
                    <div class="w-9 h-9 rounded-xl bg-primary-600/20 text-primary-400 border border-primary-500/30 flex items-center justify-center font-bold text-xs shrink-0">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <div class="min-w-0 flex-1 transition-all duration-300 overflow-hidden whitespace-nowrap"
                        :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none' : 'opacity-100 max-w-[200px]'">
                        <p class="text-xs font-semibold text-white truncate"><?= htmlspecialchars($userName) ?></p>
                        <span class="inline-block text-[10px] text-gray-400 font-medium truncate max-w-full"><?= htmlspecialchars($userRole) ?></span>
                    </div>
                </div>
                <a href="<?= PROJECT_ROOT ?>/logout" title="Cerrar sesión"
                    class="p-2 rounded-xl text-gray-400 hover:text-rose-400 hover:bg-gray-800 transition-all duration-300 shrink-0"
                    :class="sidebarCollapsed ? 'opacity-0 max-w-0 pointer-events-none hidden' : 'opacity-100'">
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
                <!-- Mobile toggle button -->
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-2 rounded-xl hover:bg-gray-100 transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <!-- Desktop collapse/expand button -->
                <button @click="toggleCollapse()"
                    class="hidden lg:flex items-center justify-center w-9 h-9 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors focus:outline-none cursor-pointer"
                    :title="sidebarCollapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'">
                    <i class="bi text-lg transition-transform duration-200" :class="sidebarCollapsed ? 'bi-layout-sidebar-inset' : 'bi-layout-sidebar-inset-reverse'"></i>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-xs font-medium text-gray-400">
                    <span>Plataforma</span>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                    <span id="header-breadcrumb" class="text-gray-700 font-semibold"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Panel de Control' ?></span>
                </div>
            </div>

            <!-- Header Quick Actions & Status -->
            <div class="flex items-center gap-3">
                <!-- Botón de Notificaciones -->
                <div class="relative" x-data="{ notificationsOpen: false }">
                    <button @click="notificationsOpen = !notificationsOpen"
                        @click.away="notificationsOpen = false"
                        class="relative flex items-center justify-center w-9 h-9 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-colors focus:outline-none cursor-pointer"
                        title="Notificaciones">
                        <i class="bi bi-bell text-lg"></i>
                        <!-- Indicador/Badge de notificaciones sin leer -->
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-primary-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Desplegable de Notificaciones -->
                    <div x-show="notificationsOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-100 py-3 z-50 overflow-hidden"
                        style="display: none;">
                        <div class="px-4 pb-2 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-800">Notificaciones</h3>
                            <span class="text-xs font-medium text-primary-600 bg-primary-50 px-2 py-0.5 rounded-full">Nuevas</span>
                        </div>
                        <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                            <div class="px-4 py-3 hover:bg-gray-50 transition-colors flex items-start gap-3 cursor-pointer">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center shrink-0 text-sm">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-800">Nueva cita reservada</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Se ha agendado una cita para hoy a las 17:00.</p>
                                    <span class="text-[10px] text-gray-400 mt-1 block">Hace 10 min</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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