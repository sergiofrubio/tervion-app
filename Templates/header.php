<!doctype html>
<html lang="es" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de gestión</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/VELION Logo Rounded.png" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdfa',   // Teal 50
                            100: '#ccfbf1',  // Teal 100
                            500: '#0d9488',  // Teal 600
                            600: '#0f766e',  // Teal 700
                            700: '#115e59',  // Teal 800
                        },
                        indigo: {
                            50: '#ecfeff',   // Cyan 50
                            100: '#cffafe',  // Cyan 100
                            200: '#a5f3fc',  // Cyan 200
                            300: '#67e8f9',  // Cyan 300
                            400: '#22d3ee',  // Cyan 400
                            500: '#06b6d4',  // Cyan 500
                            600: '#0891b2',  // Cyan 600
                            700: '#0e7490',  // Cyan 700
                            800: '#155e75',  // Cyan 800
                            900: '#164e63',  // Cyan 900
                            950: '#083344',  // Cyan 950
                        },
                        purple: {
                            50: '#f0fdf4',   // Emerald 50
                            100: '#dcfce7',  // Emerald 100
                            200: '#bbf7d0',  // Emerald 200
                            300: '#86efac',  // Emerald 300
                            400: '#4ade80',  // Emerald 400
                            500: '#10b981',  // Emerald 500
                            600: '#059669',  // Emerald 600
                            700: '#047857',  // Emerald 700
                            800: '#065f46',  // Emerald 800
                            900: '#064e3b',  // Emerald 900
                            950: '#022c22',  // Emerald 950
                        }
                    }
                }
            }
        }
    </script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS (if needed) -->
    <style>
        [x-cloak] { display: none !important; }
        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
    </style>

    <script src="<?= PROJECT_ROOT ?>/public/custom/js/timeout.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="<?= PROJECT_ROOT ?>/public/custom/js/validaciones.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <!-- Alpine.js for interactive UI -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full flex overflow-hidden font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-gray-800 bg-opacity-50 backdrop-blur-sm transition-opacity lg:hidden"
        @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"></div>

    <!-- Sidebar -->
    <div :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col shadow-xl lg:shadow-none">
        
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    V
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-800">Velion</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <a href="<?= PROJECT_ROOT ?>/inicio"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= (strpos($_SERVER['REQUEST_URI'], '/inicio') !== false || $_SERVER['REQUEST_URI'] == PROJECT_ROOT . '/') ? 'bg-primary-50 text-primary-700' : '' ?>">
                <i class="bi bi-house-door text-lg mr-3 transition-colors <?= (strpos($_SERVER['REQUEST_URI'], '/inicio') !== false || $_SERVER['REQUEST_URI'] == PROJECT_ROOT . '/') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                Inicio
            </a>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Paciente') : ?>
                <!-- Menú para Pacientes -->
                <a href="<?= PROJECT_ROOT ?>/paciente/citas"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/paciente/citas') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-calendar3 text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/paciente/citas') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Mis Citas
                </a>
                <a href="<?= PROJECT_ROOT ?>/paciente/facturas"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/paciente/facturas') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-receipt text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/paciente/facturas') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Mis Facturas
                </a>
                <a href="<?= PROJECT_ROOT ?>/paciente/tienda"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/paciente/tienda') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-bag text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/tienda') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Tienda
                </a>
                <a href="<?= PROJECT_ROOT ?>/paciente/perfil"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/paciente/perfil') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-person text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/paciente/perfil') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Mi Perfil
                </a>
            <?php else : ?>
                <!-- Menú para Administradores / Fisioterapeutas / Secretarios -->
                 <a href="<?= PROJECT_ROOT ?>/pacientes"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/pacientes') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-people text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/pacientes') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Pacientes
                </a>
                <a href="<?= PROJECT_ROOT ?>/citas"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/citas') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-calendar3 text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/citas') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Citas
                </a>
                <a href="<?= PROJECT_ROOT ?>/nominas"
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/nominas') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                    <i class="bi bi-cash-coin text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/nominas') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                    Nóminas
                </a>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') : ?>
                    <a href="<?= PROJECT_ROOT ?>/facturas"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/facturas') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                        <i class="bi bi-receipt text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/facturas') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                        Facturas
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/contabilidad"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/contabilidad') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                        <i class="bi bi-calculator text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/contabilidad') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                        Contabilidad
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/configuracion"
                        class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-all group <?= strpos($_SERVER['REQUEST_URI'], '/configuracion') !== false ? 'bg-primary-50 text-primary-700' : '' ?>">
                        <i class="bi bi-gear text-lg mr-3 transition-colors <?= strpos($_SERVER['REQUEST_URI'], '/configuracion') !== false ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-500' ?>"></i>
                        Configuración
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>


        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-gray-100">
            <a href="<?= PROJECT_ROOT ?>/logout"
                class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl text-red-600 hover:bg-red-50 hover:text-red-700 transition-all group">
                <i class="bi bi-box-arrow-right text-lg mr-3 text-red-400 group-hover:text-red-500 transition-colors"></i>
                Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden bg-gray-50/50">
        <!-- Top header for mobile -->
        <header class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-200 lg:hidden shadow-sm">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-primary-600 focus:outline-none transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <span class="text-lg font-bold text-gray-800">Velion</span>
            </div>
        </header>

        <!-- Main content -->
        <main class="flex-1 overflow-y-auto p-4 lg:p-8 w-full max-w-7xl mx-auto" id="contenido">
                <!-- Aquí se cargarán automaticamente las vistas -->