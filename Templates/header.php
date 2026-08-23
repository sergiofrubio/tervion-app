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
          sidebarOpen: false
      }">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-gray-950/70 backdrop-blur-sm transition-opacity lg:hidden"
        @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <!-- Sidebar (Color secundario bg-gray-900) -->
    <aside class="w-60 fixed inset-y-0 left-0 z-50 bg-gray-900 border-r border-gray-800 transition-[width] duration-300 ease-in-out lg:static lg:inset-0 flex flex-col shadow-2xl lg:shadow-none text-white select-none shrink-0 overflow-x-hidden"
        :style="window.innerWidth < 1024 ? (sidebarOpen ? 'transform: translateX(0);' : 'transform: translateX(-100%);') : ''">

        <!-- Sidebar Brand Header -->
        <div class="flex items-center justify-between h-20 border-b border-gray-800 bg-gray-900 transition-all duration-300 relative px-5">
            <a href="<?= PROJECT_ROOT ?>/inicio" class="flex items-center gap-3 group overflow-hidden shrink-0">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-claro-sin-fondo.png" alt="Tervion Logo" class="h-8 w-auto object-contain transition-all duration-300">
            </a>

            <!-- Botón cerrar móvil -->
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white focus:outline-none p-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                <i class="bi bi-x-lg text-lg"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Links -->
        <nav class="flex-1 px-3 py-6 overflow-y-auto overflow-x-hidden space-y-6">

            <!-- Grupo: Principal -->
            <div>
                <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                    Principal
                </div>
                <div class="space-y-1">
                    <?php
                    $cleanUri = parse_url($currentUri, PHP_URL_PATH) ?? '';
                    if (defined('PROJECT_ROOT') && PROJECT_ROOT !== '') {
                        $rootPath = parse_url(PROJECT_ROOT, PHP_URL_PATH) ?? PROJECT_ROOT;
                        if ($rootPath !== '' && strpos($cleanUri, $rootPath) === 0) {
                            $cleanUri = substr($cleanUri, strlen($rootPath));
                        }
                    }
                    $cleanUri = rtrim($cleanUri, '/') ?: '/';
                    $isHomeActive = ($cleanUri === '/inicio' || $cleanUri === '/');
                    ?>
                    <a href="<?= PROJECT_ROOT ?>/inicio"
                        class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isHomeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-grid-1x2 text-base shrink-0 <?= $isHomeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                        <span class="whitespace-nowrap">Panel de Control</span>
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'SuperAdmin') : ?>
                <!-- Menú para SuperAdmin SaaS -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-primary-400">
                        Administración SaaS
                    </div>
                    <div class="space-y-1">
                        <?php
                        // $isSaasDashActive = ($cleanUri === '/superadmin' || $cleanUri === '/superadmin/dashboard');
                        $isSaasClientsActive = (strpos($cleanUri, '/superadmin/clientes') === 0);
                        $isSaasPlanesActive = (strpos($cleanUri, '/superadmin/planes') === 0);
                        $isSaasFacturasActive = (strpos($cleanUri, '/superadmin/facturas') === 0);
                        ?>

                        <a href="<?= PROJECT_ROOT ?>/superadmin/clientes"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isSaasClientsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-building text-base shrink-0 <?= $isSaasClientsActive ? 'text-white' : 'text-primary-400' ?>"></i>
                            <span class="whitespace-nowrap">Gestión Clientes</span>
                        </a>

                        <!-- <a href="<?= PROJECT_ROOT ?>/superadmin/planes"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isSaasPlanesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-box text-base shrink-0 <?= $isSaasPlanesActive ? 'text-white' : 'text-primary-400' ?>"></i>
                            <span class="whitespace-nowrap">Planes & Suscripciones</span>
                        </a> -->

                        <a href="<?= PROJECT_ROOT ?>/superadmin/facturas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isSaasFacturasActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-receipt-cutoff text-base shrink-0 <?= $isSaasFacturasActive ? 'text-white' : 'text-primary-400' ?>"></i>
                            <span class="whitespace-nowrap">Facturación B2B</span>
                        </a>
                    </div>
                </div>

            <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Paciente') : ?>
                <!-- Menú para Pacientes -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Mi Área Personal
                    </div>
                    <div class="space-y-1">
                        <?php $isAppointmentsActive = strpos($currentUri, '/paciente/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/citas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar-week text-base shrink-0 <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Mis Citas</span>
                        </a>

                        <?php $isInvoicesActive = strpos($currentUri, '/paciente/facturas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/facturas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-receipt text-base shrink-0 <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Mis Facturas</span>
                        </a>

                        <?php $isShopActive = strpos($currentUri, '/paciente/tienda') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/paciente/tienda"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isShopActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-bag text-base shrink-0 <?= $isShopActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Tienda</span>
                        </a>
                    </div>
                </div>

            <?php elseif (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') : ?>
                <!-- Menú para Administradores -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Atención y Citas
                    </div>
                    <div class="space-y-1">
                        <?php $isPatientsActive = strpos($currentUri, '/pacientes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/pacientes"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isPatientsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-people text-base shrink-0 <?= $isPatientsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Pacientes</span>
                        </a>

                        <?php $isAppointmentsActive = strpos($currentUri, '/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/citas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar3 text-base shrink-0 <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Agenda</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Equipo y Horarios
                    </div>
                    <div class="space-y-1">
                        <?php $isTimeActive = strpos($currentUri, '/fichajes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/fichajes"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isTimeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-clock-history text-base shrink-0 <?= $isTimeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Control Horario</span>
                        </a>

                        <?php $isPayrollActive = strpos($currentUri, '/nominas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/nominas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isPayrollActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-file-earmark-text text-base shrink-0 <?= $isPayrollActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Nóminas y Contratos</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Administración
                    </div>
                    <div class="space-y-1">
                        <!-- <?php $isInvoicesActive = strpos($currentUri, '/facturas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/facturas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-receipt-cutoff text-base shrink-0 <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Facturas</span>
                        </a> -->

                        <?php $isContabilidadActive = strpos($currentUri, '/contabilidad') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/contabilidad"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isContabilidadActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-cash-stack text-base shrink-0 <?= $isContabilidadActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Contabilidad</span>
                        </a>

                        <?php $isConfigActive = strpos($currentUri, '/configuracion') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/configuracion"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isConfigActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-sliders text-base shrink-0 <?= $isConfigActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Configuración</span>
                        </a>
                    </div>
                </div>

            <?php elseif (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'Fisioterapeuta' || $_SESSION['rol'] === 'Secretario')) : ?>
                <!-- Menú para Fisioterapeutas y Secretarios -->
                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Atención y Citas
                    </div>
                    <div class="space-y-1">
                        <?php $isPatientsActive = strpos($currentUri, '/pacientes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/pacientes"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isPatientsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-people text-base shrink-0 <?= $isPatientsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Pacientes</span>
                        </a>

                        <?php $isAppointmentsActive = strpos($currentUri, '/citas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/citas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isAppointmentsActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-calendar3 text-base shrink-0 <?= $isAppointmentsActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Agenda</span>
                        </a>
                    </div>
                </div>

                <div>
                    <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Gestión
                    </div>
                    <div class="space-y-1">
                        <?php $isInvoicesActive = strpos($currentUri, '/facturas') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/facturas"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isInvoicesActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-receipt-cutoff text-base shrink-0 <?= $isInvoicesActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Facturas</span>
                        </a>

                        <?php $isTimeActive = strpos($currentUri, '/fichajes') !== false; ?>
                        <a href="<?= PROJECT_ROOT ?>/fichajes"
                            class="flex items-center px-3.5 gap-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 <?= $isTimeActive ? 'bg-primary-600 text-white shadow-md font-semibold' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                            <i class="bi bi-clock-history text-base shrink-0 <?= $isTimeActive ? 'text-white' : 'text-gray-400' ?>"></i>
                            <span class="whitespace-nowrap">Control Horario</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

        </nav>

        <!-- Sidebar User Card & Footer -->
        <!-- <div class="p-3 border-t border-gray-800 bg-gray-950">
            <div class="flex items-center justify-between gap-3 p-2 rounded-2xl bg-gray-900 border border-gray-800">
                <div class="flex items-center gap-3 min-w-0 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-primary-600/20 text-primary-400 border border-primary-500/30 flex items-center justify-center font-bold text-xs shrink-0">
                        <?= strtoupper(substr($userName, 0, 1)) ?>
                    </div>
                    <div class="min-w-0 flex-1 whitespace-nowrap">
                        <p class="text-xs font-semibold text-white truncate"><?= htmlspecialchars($userName) ?></p>
                        <span class="inline-block text-[10px] text-gray-400 font-medium truncate max-w-full"><?= htmlspecialchars($userRole) ?></span>
                    </div>
                </div>
                <a href="<?= PROJECT_ROOT ?>/logout" title="Cerrar sesión"
                    class="p-2 rounded-xl text-gray-400 hover:text-rose-400 hover:bg-gray-800 transition-all duration-300 shrink-0">
                    <i class="bi bi-box-arrow-right text-base"></i>
                </a>
            </div>
        </div> -->
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
                <?php
                // Generar breadcrumbs dinámicos si no han sido definidos en la vista
                if (!isset($breadcrumbs) || !is_array($breadcrumbs)) {
                    $breadcrumbItems = [];
                    $breadcrumbItems[] = ['title' => 'Inicio', 'url' => PROJECT_ROOT . '/inicio'];

                    $uriPath = parse_url($currentUri, PHP_URL_PATH) ?? '';
                    if (defined('PROJECT_ROOT') && PROJECT_ROOT !== '') {
                        $rootPath = parse_url(PROJECT_ROOT, PHP_URL_PATH) ?? PROJECT_ROOT;
                        if ($rootPath !== '' && strpos($uriPath, $rootPath) === 0) {
                            $uriPath = substr($uriPath, strlen($rootPath));
                        }
                    }

                    $cleanPath = trim($uriPath, '/');
                    $segments = $cleanPath !== '' ? array_values(array_filter(explode('/', $cleanPath))) : [];

                    $sectionNames = [
                        'superadmin' => 'Administración SaaS',
                        'clientes' => 'Clientes SaaS',
                        'planes' => 'Planes & Suscripciones',
                        'inicio' => 'Panel de Control',
                        'pacientes' => 'Pacientes',
                        'citas' => 'Agenda',
                        'fichajes' => 'Control Horario',
                        'nominas' => 'Nóminas y Contratos',
                        'contabilidad' => 'Contabilidad',
                        'configuracion' => 'Configuración',
                        'facturas' => 'Facturas',
                        'paciente' => 'Mi Área Personal',
                        'perfil' => 'Perfil',
                        'tienda' => 'Tienda',
                        'create' => 'Nuevo Registro',
                        'edit' => 'Editar',
                        'detail' => 'Detalle'
                    ];

                    if (!empty($segments) && $segments[0] !== 'inicio') {
                        $accumulatedPath = defined('PROJECT_ROOT') ? PROJECT_ROOT : '';
                        $totalSegs = count($segments);

                        foreach ($segments as $idx => $seg) {
                            $accumulatedPath .= '/' . $seg;
                            $isLastSeg = ($idx === $totalSegs - 1);

                            $segTitle = $sectionNames[strtolower($seg)] ?? ucfirst(str_replace(['-', '_'], ' ', $seg));
                            if ($isLastSeg && !empty($pageTitle)) {
                                $segTitle = $pageTitle;
                            }

                            $breadcrumbItems[] = [
                                'title' => $segTitle,
                                'url' => $isLastSeg ? null : $accumulatedPath
                            ];
                        }
                    } else {
                        $breadcrumbItems[0]['url'] = null;
                        if (!empty($pageTitle) && $pageTitle !== 'Panel de Control') {
                            $breadcrumbItems[0]['title'] = $pageTitle;
                        }
                    }
                } else {
                    $breadcrumbItems = $breadcrumbs;
                }
                ?>
                <div id="header-breadcrumb" class="hidden sm:flex items-center gap-2 text-xs font-medium text-gray-400">
                    <?php foreach ($breadcrumbItems as $i => $item): ?>
                        <?php if ($i > 0): ?>
                            <i class="bi bi-chevron-right text-[10px] text-gray-300"></i>
                        <?php endif; ?>

                        <?php if (!empty($item['url'])): ?>
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="hover:text-primary-600 hover:underline transition-colors flex items-center gap-1">
                                <?php if ($i === 0): ?>
                                    <i class="bi bi-house-door text-xs"></i>
                                <?php endif; ?>
                                <span><?= htmlspecialchars($item['title']) ?></span>
                            </a>
                        <?php else: ?>
                            <span class="text-gray-700 font-semibold truncate max-w-[220px]" title="<?= htmlspecialchars($item['title']) ?>">
                                <?php if ($i === 0 && count($breadcrumbItems) === 1): ?>
                                    <i class="bi bi-house-door text-xs mr-1 text-gray-500"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($item['title']) ?>
                            </span>
                        <?php endif; ?>
                    <?php endforeach; ?>
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

                <!-- Desplegable del Perfil del Usuario -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen"
                        @click.away="userMenuOpen = false"
                        class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-gray-100 transition-colors focus:outline-none cursor-pointer"
                        title="Menú de usuario">
                        <div class="w-8 h-8 rounded-xl bg-primary-600/20 text-primary-600 border border-primary-500/30 flex items-center justify-center font-bold text-xs shrink-0">
                            <?= strtoupper(substr($userName, 0, 1)) ?>
                        </div>
                        <div class="hidden md:flex flex-col text-left">
                            <span class="text-xs font-semibold text-gray-800 leading-tight"><?= htmlspecialchars($userName) ?></span>
                            <span class="text-[10px] text-gray-500 font-medium leading-tight"><?= htmlspecialchars($userRole) ?></span>
                        </div>
                        <i class="bi bi-chevron-down text-xs text-gray-400 ml-0.5"></i>
                    </button>

                    <!-- Menú Desplegable -->
                    <div x-show="userMenuOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-auto bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden"
                        style="display: none;">

                        <!-- Info de usuario en móvil -->
                        <div class="px-4 py-2 border-b border-gray-100 md:hidden">
                            <p class="text-xs font-semibold text-gray-800 truncate"><?= htmlspecialchars($userName) ?></p>
                            <p class="text-[10px] text-gray-500 font-medium truncate"><?= htmlspecialchars($userRole) ?></p>
                        </div>

                        <a href="<?= PROJECT_ROOT ?>/perfil"
                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600 transition-colors">
                            <i class="bi bi-person text-base text-gray-400"></i>
                            <span>Ver Perfil</span>
                        </a>

                        <div class="my-1 border-t border-gray-100"></div>

                        <a href="<?= PROJECT_ROOT ?>/logout"
                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors">
                            <i class="bi bi-box-arrow-right text-base text-rose-500"></i>
                            <span>Salir</span>
                        </a>
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