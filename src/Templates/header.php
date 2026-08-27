<!doctype html>
<html lang="es" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — Tervion' : 'Panel de Gestión — Tervion' ?></title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">

    <!-- Global Project Root Configuration -->
    <script>
        window.PROJECT_ROOT = '<?= defined('PROJECT_ROOT') ? PROJECT_ROOT : '' ?>';
    </script>

    <!-- Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- App Core JS (ES Module) -->
    <script type="module" src="<?= PROJECT_ROOT ?>/public/js/app.js"></script>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';

$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$userEmail = $_SESSION['email'] ?? '';
$userName = $_SESSION['nombre'] ?? (explode('@', $userEmail)[0] ?? 'Usuario');
$userRole = $_SESSION['rol'] ?? 'Usuario';

// Normalización estricta de la URI para determinar la sección activa
$cleanUri = parse_url($currentUri, PHP_URL_PATH) ?? '';
if (defined('PROJECT_ROOT') && PROJECT_ROOT !== '') {
    $rootPath = parse_url(PROJECT_ROOT, PHP_URL_PATH) ?? PROJECT_ROOT;
    if ($rootPath !== '' && strpos($cleanUri, $rootPath) === 0) {
        $cleanUri = substr($cleanUri, strlen($rootPath));
    }
}
$cleanUri = rtrim($cleanUri, '/') ?: '/';

// Comprobaciones exactas y por prefijo de rutas para Clientes (Clínicas y sus usuarios)
$isPatientsActive    = ($cleanUri === '/pacientes' || strpos($cleanUri, '/pacientes/') === 0);
$isAgendasActive     = ($cleanUri === '/citas' || strpos($cleanUri, '/citas/') === 0);
$isRecepcionActive   = ($cleanUri === '/fichajes' || strpos($cleanUri, '/fichajes/') === 0);
$isHistoriasActive   = ($cleanUri === '/historial' || strpos($cleanUri, '/historial/') === 0 || strpos($cleanUri, '/medical-report') === 0);

$isFacultativosActive = ($cleanUri === '/nominas' || strpos($cleanUri, '/nominas/') === 0 || strpos($cleanUri, '/trabajadores') === 0 || strpos($cleanUri, '/terapeutas') === 0);
$isContabilidadActive = ($cleanUri === '/contabilidad' || strpos($cleanUri, '/contabilidad/') === 0 || strpos($cleanUri, '/facturas') === 0);
$isDocumentosActive   = ($cleanUri === '/documentos' || strpos($cleanUri, '/documentos/') === 0);
$isConfiguracionActive = ($cleanUri === '/configuracion' || strpos($cleanUri, '/configuracion/') === 0);

$isAnyConfigActive = ($isFacultativosActive || $isContabilidadActive || $isDocumentosActive || $isConfiguracionActive);

// Comprobaciones de rutas para SuperAdmin
$isSuperAdmin = ($userRole === 'SuperAdmin');
$isSaasDashboardActive = ($cleanUri === '/inicio');
$isSaasClientesActive  = (strpos($cleanUri, '/superadmin/clientes') === 0);
$isSaasFacturasActive  = (strpos($cleanUri, '/superadmin/facturas') === 0);
?>

<body class="h-full flex flex-col font-sans antialiased text-slate-900 bg-slate-50 <?= $hasSystemAlert ? 'pt-7' : '' ?>"
    x-data="{ 
        sidebarCollapsed: localStorage.getItem('tervion_sidebar_collapsed') !== 'false',
        mobileMenuOpen: false, 
        configMenuOpen: false,
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('tervion_sidebar_collapsed', this.sidebarCollapsed);
        }
    }">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- 1. TOPBAR HORIZONTAL (MedServ Clean Header) -->
    <header class="bg-white border-b border-slate-200/90 text-slate-800 shadow-sm z-30 sticky top-0 h-16 shrink-0">
        <div class="w-full px-4 sm:px-6 flex items-center justify-between h-full">

            <!-- Izquierda: Toggle Sidebar + Logo + Breadcrumbs -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Botón colapsar Sidebar Desktop -->
                <!-- <button @click="toggleSidebar()" 
                    type="button" 
                    class="hidden md:flex w-9 h-9 items-center justify-center rounded-xl text-slate-500 hover:text-primary-600 hover:bg-slate-100 transition-colors focus:outline-none"
                    :title="sidebarCollapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'">
                    <i class="bi" :class="sidebarCollapsed ? 'bi-text-indent-left text-xl' : 'bi-text-indent-right text-xl'"></i>
                </button> -->

                <!-- Botón Menú Móvil -->
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    type="button"
                    class="md:hidden w-9 h-9 flex items-center justify-center text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none"
                    aria-label="Abrir menú">
                    <i class="bi text-xl" :class="mobileMenuOpen ? 'bi-x-lg' : 'bi-list'"></i>
                </button>

                <!-- Logo Marca -->
                <a href="<?= PROJECT_ROOT ?>/inicio" class="flex items-center gap-2 group shrink-0">
                    <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-azul-sin-fondo.svg" alt="Tervion Logo" class="h-7 sm:h-8 w-auto object-contain transition-transform group-hover:scale-105">
                </a>

                <!-- Breadcrumb Contextual Clínico (MedServ Style) -->
                <div id="header-breadcrumb" class="hidden sm:flex items-center gap-2 text-xs font-medium text-slate-400 pl-3 border-l border-slate-200">
                    <span class="text-slate-500 font-semibold">Tervion</span>
                    <i class="bi bi-chevron-right text-[10px] text-slate-300"></i>
                    <span id="header-breadcrumb-title" class="text-primary-600 font-semibold">
                        <?php
                        if ($isSuperAdmin) {
                            if ($isSaasClientesActive) echo 'Clientes (Clínicas)';
                            elseif ($isSaasFacturasActive) echo 'Facturación SaaS';
                            else echo 'Dashboard Global';
                        } else {
                            if ($isPatientsActive) echo 'Pacientes';
                            elseif ($isAgendasActive) echo 'Agendas de Citas';
                            elseif ($isRecepcionActive) echo 'Control de Fichajes';
                            elseif ($isHistoriasActive) echo 'Historias Clínicas';
                            elseif ($isFacultativosActive) echo 'Facultativos';
                            elseif ($isContabilidadActive) echo 'Contabilidad y Facturas';
                            elseif ($isDocumentosActive) echo 'Documentos';
                            elseif ($isConfiguracionActive) echo 'Configuración';
                            else echo 'Panel Clínico';
                        }
                        ?>
                    </span>
                </div>
            </div>

            <!-- Derecha: Acciones Rápidas & Perfil de Usuario -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Botón Ayuda / Documentación -->
                <button type="button" class="w-9 h-9 rounded-full hidden sm:flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="Ayuda">
                    <i class="bi bi-question-circle text-base"></i>
                </button>

                <!-- Botón Notificaciones -->
                <div class="relative">
                    <button type="button" class="w-9 h-9 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors" title="Notificaciones">
                        <i class="bi bi-bell text-base"></i>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-primary-500 rounded-full ring-2 ring-white"></span>
                    </button>
                </div>

                <!-- Divisor sutil -->
                <div class="h-6 w-px bg-slate-200 hidden sm:block mx-0.5"></div>

                <!-- Perfil del Profesional / Usuario -->
                <div class="flex items-center gap-2.5 pl-1">
                    <div class="relative">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 text-white flex items-center justify-center font-bold text-xs shadow-sm ring-2 ring-white">
                            <?= strtoupper(substr($userName, 0, 2)) ?>
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="hidden lg:flex flex-col text-left">
                        <span class="text-xs font-bold text-slate-800 leading-tight"><?= htmlspecialchars($userName) ?></span>
                        <span class="text-[11px] font-medium text-slate-400"><?= htmlspecialchars($userRole) ?></span>
                    </div>
                </div>

                <!-- Botón Cerrar Sesión -->
                <a href="<?= PROJECT_ROOT ?>/logout"
                    class="w-9 h-9 rounded-full text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all duration-200 flex items-center justify-center ml-0.5"
                    title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right text-base"></i>
                </a>
            </div>

        </div>
    </header>

    <!-- 2. CONTENEDOR PRINCIPAL (SIDEBAR + ÁREA DE CONTENIDO) -->
    <div class="flex-1 flex overflow-hidden relative">

        <!-- SIDEBAR DESKTOP (Contraíble, Altura Completa) -->
        <aside
            class="hidden md:flex flex-col bg-white border-r border-slate-200/90 transition-all duration-300 ease-in-out shrink-0 select-none z-20"
            :class="sidebarCollapsed ? 'w-20' : 'w-64'">

            <!-- Lista de Enlaces de Navegación -->
            <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5 scrollbar-thin">

                <?php if ($isSuperAdmin): ?>
                    <!-- Sección Dashboard -->
                    <a href="<?= PROJECT_ROOT ?>/inicio"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isSaasDashboardActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Dashboard Global' : ''">
                        <i class="bi bi-speedometer2 text-lg shrink-0 <?= $isSaasDashboardActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Dashboard Global
                        </span>
                    </a>

                    <!-- Clientes -->
                    <a href="<?= PROJECT_ROOT ?>/superadmin/clientes"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isSaasClientesActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Clientes (Clínicas)' : ''">
                        <i class="bi bi-building text-lg shrink-0 <?= $isSaasClientesActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Clientes (Clínicas)
                        </span>
                    </a>

                    <!-- Facturación SaaS -->
                    <a href="<?= PROJECT_ROOT ?>/superadmin/facturas"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isSaasFacturasActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Facturación SaaS' : ''">
                        <i class="bi bi-receipt text-lg shrink-0 <?= $isSaasFacturasActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Facturación SaaS
                        </span>
                    </a>

                <?php else: ?>
                    <!-- Etiqueta Menú (sólo expandido) -->
                    <div class="px-3.5 pt-2 pb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider" x-show="!sidebarCollapsed">
                        Gestión Clínica
                    </div>

                    <!-- Pacientes -->
                    <a href="<?= PROJECT_ROOT ?>/pacientes"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isPatientsActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Pacientes' : ''">
                        <i class="bi bi-people text-lg shrink-0 <?= $isPatientsActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Pacientes
                        </span>
                    </a>

                    <!-- Agendas -->
                    <a href="<?= PROJECT_ROOT ?>/citas"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isAgendasActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Agendas de Citas' : ''">
                        <i class="bi bi-calendar3 text-lg shrink-0 <?= $isAgendasActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Agendas
                        </span>
                    </a>

                    <!-- Fichajes -->
                    <a href="<?= PROJECT_ROOT ?>/fichajes"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isRecepcionActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Fichajes' : ''">
                        <i class="bi bi-clock-history text-lg shrink-0 <?= $isRecepcionActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Fichajes
                        </span>
                    </a>

                    <!-- Historias -->
                    <a href="<?= PROJECT_ROOT ?>/historial"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isHistoriasActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Historias Clínicas' : ''">
                        <i class="bi bi-file-earmark-medical text-lg shrink-0 <?= $isHistoriasActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Historias
                        </span>
                    </a>

                    <!-- Separador Ajustes -->
                    <div class="my-2 border-t border-slate-100" x-show="!sidebarCollapsed"></div>
                    <div class="px-3.5 pt-2 pb-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider" x-show="!sidebarCollapsed">
                        Configuración
                    </div>

                    <!-- Facultativos -->
                    <a href="<?= PROJECT_ROOT ?>/terapeutas"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isFacultativosActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Facultativos' : ''">
                        <i class="bi bi-person-badge text-lg shrink-0 <?= $isFacultativosActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Facultativos
                        </span>
                    </a>

                    <!-- Contabilidad -->
                    <a href="<?= PROJECT_ROOT ?>/contabilidad"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isContabilidadActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Contabilidad' : ''">
                        <i class="bi bi-cash-stack text-lg shrink-0 <?= $isContabilidadActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Contabilidad
                        </span>
                    </a>

                    <!-- Documentos -->
                    <a href="<?= PROJECT_ROOT ?>/documentos"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isDocumentosActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Documentos' : ''">
                        <i class="bi bi-file-earmark-text text-lg shrink-0 <?= $isDocumentosActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Documentos
                        </span>
                    </a>

                    <!-- Opciones de Configuración General -->
                    <a href="<?= PROJECT_ROOT ?>/configuracion"
                        class="flex items-center gap-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group <?= $isConfiguracionActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>"
                        :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3.5'"
                        :title="sidebarCollapsed ? 'Configuración del Sistema' : ''">
                        <i class="bi bi-sliders text-lg shrink-0 <?= $isConfiguracionActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600' ?>"></i>
                        <span class="truncate whitespace-nowrap" x-show="!sidebarCollapsed" x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                            Configuración
                        </span>
                    </a>
                <?php endif; ?>

            </div>

            <!-- Footer del Sidebar (Botón colapsar inferior) -->
            <div class="p-3 border-t border-slate-100 flex items-center justify-between">
                <button @click="toggleSidebar()"
                    type="button"
                    class="w-full flex items-center gap-3 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-primary-600 hover:bg-slate-50 transition-colors"
                    :class="sidebarCollapsed ? 'justify-center px-0' : 'px-3'">
                    <i class="bi text-lg shrink-0" :class="sidebarCollapsed ? 'bi-arrow-bar-right' : 'bi-arrow-bar-left'"></i>
                    <span class="truncate" x-show="!sidebarCollapsed">Contraer menú</span>
                </button>
            </div>

        </aside>

        <!-- SIDEBAR MÓVIL (Off-canvas Drawer) -->
        <div x-show="mobileMenuOpen"
            x-cloak
            class="fixed inset-0 z-50 md:hidden flex">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity"
                @click="mobileMenuOpen = false"
                x-show="mobileMenuOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"></div>

            <!-- Panel Drawer Lateral -->
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-2xl z-50"
                x-show="mobileMenuOpen"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">

                <!-- Header Drawer Móvil -->
                <div class="flex items-center justify-between p-4 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-azul-sin-fondo.svg" alt="Tervion" class="h-7 w-auto object-contain">
                    </div>
                    <button @click="mobileMenuOpen = false" type="button" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <i class="bi bi-x-lg text-base"></i>
                    </button>
                </div>

                <!-- Perfil en Móvil -->
                <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        <?= strtoupper(substr($userName, 0, 2)) ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-800"><?= htmlspecialchars($userName) ?></span>
                        <span class="text-[11px] text-slate-400"><?= htmlspecialchars($userRole) ?></span>
                    </div>
                </div>

                <!-- Enlaces Navegación Móvil -->
                <div class="flex-1 overflow-y-auto p-4 space-y-1.5">
                    <?php if ($isSuperAdmin): ?>
                        <a href="<?= PROJECT_ROOT ?>/inicio" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isSaasDashboardActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-speedometer2 text-base <?= $isSaasDashboardActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Dashboard Global</span>
                        </a>

                        <a href="<?= PROJECT_ROOT ?>/superadmin/clientes" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isSaasClientesActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-building text-base <?= $isSaasClientesActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Clientes (Clínicas)</span>
                        </a>

                        <a href="<?= PROJECT_ROOT ?>/superadmin/facturas" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isSaasFacturasActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-receipt text-base <?= $isSaasFacturasActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Facturación SaaS</span>
                        </a>

                    <?php else: ?>
                        <a href="<?= PROJECT_ROOT ?>/pacientes" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isPatientsActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-people text-base <?= $isPatientsActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Pacientes</span>
                        </a>

                        <a href="<?= PROJECT_ROOT ?>/citas" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isAgendasActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-calendar3 text-base <?= $isAgendasActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Agendas</span>
                        </a>

                        <a href="<?= PROJECT_ROOT ?>/fichajes" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isRecepcionActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-clock-history text-base <?= $isRecepcionActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Fichajes</span>
                        </a>

                        <a href="<?= PROJECT_ROOT ?>/historial" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isHistoriasActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                            <i class="bi bi-file-earmark-medical text-base <?= $isHistoriasActive ? 'text-primary-600' : 'text-slate-400' ?>"></i>
                            <span>Historias</span>
                        </a>

                        <div class="pt-2.5 border-t border-slate-100 space-y-1">
                            <div class="px-3.5 py-1 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Configuración</div>
                            <a href="<?= PROJECT_ROOT ?>/terapeutas" @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isFacultativosActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                                <i class="bi bi-person-badge text-base text-slate-400"></i>
                                <span>Facultativos</span>
                            </a>
                            <a href="<?= PROJECT_ROOT ?>/contabilidad" @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isContabilidadActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                                <i class="bi bi-cash-stack text-base text-slate-400"></i>
                                <span>Contabilidad</span>
                            </a>
                            <a href="<?= PROJECT_ROOT ?>/documentos" @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isDocumentosActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                                <i class="bi bi-file-earmark-text text-base text-slate-400"></i>
                                <span>Documentos</span>
                            </a>
                            <a href="<?= PROJECT_ROOT ?>/configuracion" @click="mobileMenuOpen = false"
                                class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isConfiguracionActive ? 'bg-primary-50 text-primary-700 ring-1 ring-primary-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
                                <i class="bi bi-sliders text-base text-slate-400"></i>
                                <span>Más opciones de configuración</span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="pt-4 border-t border-slate-100">
                        <a href="<?= PROJECT_ROOT ?>/logout" @click="mobileMenuOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-500 hover:bg-rose-50">
                            <i class="bi bi-box-arrow-right text-base"></i>
                            <span>Cerrar Sesión</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3. MAIN VIEWPORT (Área de Trabajo con Scroll Independiente) -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-slate-50/50">
            <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto flex flex-col" id="contenido">
                <!-- Vistas dinámicas -->