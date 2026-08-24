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

// Normalización estricta de la URI para determinar la sección activa
$cleanUri = parse_url($currentUri, PHP_URL_PATH) ?? '';
if (defined('PROJECT_ROOT') && PROJECT_ROOT !== '') {
    $rootPath = parse_url(PROJECT_ROOT, PHP_URL_PATH) ?? PROJECT_ROOT;
    if ($rootPath !== '' && strpos($cleanUri, $rootPath) === 0) {
        $cleanUri = substr($cleanUri, strlen($rootPath));
    }
}
$cleanUri = rtrim($cleanUri, '/') ?: '/';

// Comprobaciones exactas y por prefijo de rutas
$isPatientsActive    = ($cleanUri === '/pacientes' || strpos($cleanUri, '/pacientes/') === 0);
$isAgendasActive     = ($cleanUri === '/citas' || strpos($cleanUri, '/citas/') === 0);
$isRecepcionActive   = ($cleanUri === '/fichajes' || strpos($cleanUri, '/fichajes/') === 0);
$isHistoriasActive   = ($cleanUri === '/historial' || strpos($cleanUri, '/historial/') === 0 || strpos($cleanUri, '/medical-report') === 0);

$isFacultativosActive = ($cleanUri === '/nominas' || strpos($cleanUri, '/nominas/') === 0 || strpos($cleanUri, '/trabajadores') === 0);
$isContabilidadActive = ($cleanUri === '/contabilidad' || strpos($cleanUri, '/contabilidad/') === 0 || strpos($cleanUri, '/facturas') === 0);
$isDocumentosActive   = ($cleanUri === '/documentos' || strpos($cleanUri, '/documentos/') === 0);
$isConfiguracionActive = ($cleanUri === '/configuracion' || strpos($cleanUri, '/configuracion/') === 0);

$isAnyConfigActive = ($isFacultativosActive || $isContabilidadActive || $isDocumentosActive || $isConfiguracionActive);
?>

<body class="h-full flex flex-col font-sans antialiased text-slate-900 bg-slate-50 <?= $hasSystemAlert ? 'pt-7' : '' ?>"
    x-data="{ mobileMenuOpen: false, configMenuOpen: false }">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Topbar Horizontal -->
    <header class="bg-gray-900 border-b border-gray-800 text-white shadow-md z-40 sticky top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                <!-- Izquierda: Logo -->
                <div class="flex items-center shrink-0">
                    <a href="<?= PROJECT_ROOT ?>/inicio" class="flex items-center gap-2 group">
                        <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-claro-sin-fondo.png" alt="Tervion Logo" class="h-6 sm:h-7 w-auto object-contain transition-all duration-300">
                    </a>
                </div>

                <!-- Derecha (Navegación Desktop) -->
                <nav class="hidden md:flex items-center gap-1 sm:gap-1.5">
                    <a href="<?= PROJECT_ROOT ?>/pacientes"
                        class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-1.5 <?= $isPatientsActive ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-people text-sm"></i>
                        <span>Pacientes</span>
                    </a>

                    <a href="<?= PROJECT_ROOT ?>/citas"
                        class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-1.5 <?= $isAgendasActive ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-calendar3 text-sm"></i>
                        <span>Agendas</span>
                    </a>

                    <a href="<?= PROJECT_ROOT ?>/fichajes"
                        class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-1.5 <?= $isRecepcionActive ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-clock-history text-sm"></i>
                        <span>Fichajes</span>
                    </a>

                    <a href="<?= PROJECT_ROOT ?>/historial/crear"
                        class="px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-1.5 <?= $isHistoriasActive ? 'bg-primary-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                        <i class="bi bi-file-earmark-medical text-sm"></i>
                        <span>Historias</span>
                    </a>

                    <!-- Desplegable Rueda Configuración -->
                    <div class="relative" @click.away="configMenuOpen = false">
                        <button @click="configMenuOpen = !configMenuOpen"
                            type="button"
                            class="px-2.5 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 focus:outline-none flex items-center gap-1 cursor-pointer <?= $isAnyConfigActive ? 'bg-primary-600 text-white shadow-md' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>"
                            title="Configuración">
                            <i class="bi bi-gear-fill text-sm"></i>
                            <i class="bi bi-chevron-down text-[10px] transition-transform duration-200" :class="{ 'rotate-180': configMenuOpen }"></i>
                        </button>

                        <!-- Menú Desplegable -->
                        <div x-show="configMenuOpen"
                            x-cloak
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-1.5 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-50 overflow-hidden text-slate-800">

                            <a href="<?= PROJECT_ROOT ?>/terapeutas"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold hover:bg-slate-50 transition-colors <?= $isFacultativosActive ? 'text-primary-600 bg-primary-50/50' : 'text-slate-700 hover:text-primary-600' ?>">
                                <i class="bi bi-person-badge text-sm text-gray-400"></i>
                                <span>Facultativos</span>
                            </a>

                            <a href="<?= PROJECT_ROOT ?>/contabilidad"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold hover:bg-slate-50 transition-colors <?= $isContabilidadActive ? 'text-primary-600 bg-primary-50/50' : 'text-slate-700 hover:text-primary-600' ?>">
                                <i class="bi bi-cash-stack text-sm text-gray-400"></i>
                                <span>Contabilidad</span>
                            </a>

                            <a href="<?= PROJECT_ROOT ?>/documentos"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold hover:bg-slate-50 transition-colors <?= $isDocumentosActive ? 'text-primary-600 bg-primary-50/50' : 'text-slate-700 hover:text-primary-600' ?>">
                                <i class="bi bi-file-earmark-text text-sm text-gray-400"></i>
                                <span>Documentos</span>
                            </a>

                            <div class="my-1 border-t border-gray-100"></div>

                            <a href="<?= PROJECT_ROOT ?>/configuracion"
                                class="flex items-center gap-2.5 px-3.5 py-2 text-xs font-semibold hover:bg-slate-50 transition-colors <?= $isConfiguracionActive ? 'text-primary-600 bg-primary-50/50' : 'text-slate-700 hover:text-primary-600' ?>">
                                <i class="bi bi-sliders text-sm text-gray-400"></i>
                                <span>Más opciones de configuración</span>
                            </a>
                        </div>
                    </div>

                    <!-- Botón Cerrar Sesión -->
                    <a href="<?= PROJECT_ROOT ?>/logout"
                        class="p-1.5 rounded-lg text-gray-400 hover:bg-rose-500/20 hover:text-rose-400 transition-all duration-200 flex items-center justify-center ml-0.5"
                        title="Cerrar sesión">
                        <i class="bi bi-box-arrow-right text-base"></i>
                    </a>
                </nav>

                <!-- Botón Menú Móvil -->
                <div class="flex items-center md:hidden gap-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-300 hover:text-white p-1.5 rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="bi bi-list text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="bi bi-x-lg text-lg" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Menú Desplegable Móvil -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-800 bg-gray-900 px-4 pt-2 pb-4 space-y-1.5">
            <a href="<?= PROJECT_ROOT ?>/pacientes" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isPatientsActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <i class="bi bi-people text-base"></i>
                <span>Pacientes</span>
            </a>

            <a href="<?= PROJECT_ROOT ?>/citas" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isAgendasActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <i class="bi bi-calendar3 text-base"></i>
                <span>Agendas</span>
            </a>

            <a href="<?= PROJECT_ROOT ?>/fichajes" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isRecepcionActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <i class="bi bi-clock-history text-base"></i>
                <span>Fichajes</span>
            </a>

            <a href="<?= PROJECT_ROOT ?>/historial/crear" @click="mobileMenuOpen = false"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors <?= $isHistoriasActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                <i class="bi bi-file-earmark-medical text-base"></i>
                <span>Historias</span>
            </a>

            <div class="pt-2 border-t border-gray-800 space-y-1">
                <div class="px-3 py-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Configuración</div>
                <a href="<?= PROJECT_ROOT ?>/terapeutas" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isFacultativosActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                    <i class="bi bi-person-badge text-base"></i>
                    <span>Facultativos</span>
                </a>
                <a href="<?= PROJECT_ROOT ?>/contabilidad" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isContabilidadActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                    <i class="bi bi-cash-stack text-base"></i>
                    <span>Contabilidad</span>
                </a>
                <a href="<?= PROJECT_ROOT ?>/documentos" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isDocumentosActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                    <i class="bi bi-file-earmark-text text-base"></i>
                    <span>Documentos</span>
                </a>
                <a href="<?= PROJECT_ROOT ?>/configuracion" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-semibold transition-colors <?= $isConfiguracionActive ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' ?>">
                    <i class="bi bi-sliders text-base"></i>
                    <span>Más opciones de configuración</span>
                </a>
            </div>

            <div class="pt-2 border-t border-gray-800">
                <a href="<?= PROJECT_ROOT ?>/logout" @click="mobileMenuOpen = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/20">
                    <i class="bi bi-box-arrow-right text-base"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-slate-50">
        <main class="flex-1 p-4 sm:p-6 lg:p-8 w-full max-w-7xl mx-auto flex flex-col" id="contenido">
            <!-- Vistas dinámicas -->