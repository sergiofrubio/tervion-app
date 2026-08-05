<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio de sesión - Velion</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f5ff',
                            100: '#d9e6ff',
                            200: '#bacfff',
                            300: '#91b1ff',
                            400: '#5e88ff',
                            500: '#0052d9', // Royal Blue (from logo)
                            600: '#0040b3',
                            700: '#00308c',
                            800: '#002266',
                            900: '#001640',
                        },
                        indigo: {
                            50: '#ecf9ff',
                            100: '#d9f1ff',
                            200: '#bde7ff',
                            300: '#8fd7ff',
                            400: '#4cbaff',
                            500: '#009eff', // Cyan/Light Blue (from logo)
                            600: '#007ee6',
                            700: '#0063b8',
                            800: '#004f99',
                            900: '#004280',
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

</head>

<body class="bg-gray-50 font-sans antialiased min-h-screen flex items-center justify-center relative" x-data="{ showModal: false }" x-cloak>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 sm:p-10 transition-all">

            <div class="text-center mb-8">
                <!-- <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg" alt="Velion Logo" class="w-24 mx-auto rounded-2xl shadow-sm mb-4"> -->
                 <a href="<?= PROJECT_ROOT ?>/landing" class="w-24 mx-auto flex items-center mb-4">
                    <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg" alt="Velion Logo" class="h-9 object-contain">
                 </a>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Bienvenido de nuevo</h2>
                <p class="text-sm text-gray-500 mt-1">Inicia sesión en tu cuenta</p>
            </div>

            <?php
            // Verificar si hay una alerta de usuario
            if (isset($_GET['alert']) && isset($_GET['message'])) {
                $alert_type = $_GET['alert'] === 'danger' ? 'bg-red-50 text-red-800 border-red-200' :
                              ($_GET['alert'] === 'success' ? 'bg-green-50 text-green-800 border-green-200' :
                              'bg-blue-50 text-blue-800 border-blue-200');

                echo '<div class="rounded-xl border p-4 mb-6 ' . $alert_type . '" role="alert" x-data="{ show: true }" x-show="show">
                    <div class="flex justify-between items-start">
                        <div class="text-sm font-medium">' . htmlspecialchars($_GET['message']) . '</div>
                        <button @click="show = false" class="text-current opacity-70 hover:opacity-100 ml-3 focus:outline-none">
                            <i class="bi bi-x-lg text-sm"></i>
                        </button>
                    </div>
                </div>';
            }
            ?>

            <form action="<?= PROJECT_ROOT . '/login' ?>" method="post" class="space-y-5">
                <input type="hidden" id="actionType" name="action" value="iniciar_sesion">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" required
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-colors shadow-sm"
                            placeholder="tu@email.com">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <a href="#" @click.prevent="showModal = true" class="text-sm font-medium text-primary-600 hover:text-primary-500 transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="pass" id="pass" required
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-colors shadow-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" id="loginButton" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                        <!-- Spinner (hidden by default) -->
                        <span id="loginSpinner" class="hidden mr-3 inline-flex items-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                            </svg>
                        </span>
                        <span id="loginButtonText">Iniciar Sesión</span>
                        <i class="bi bi-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Recuperar Contraseña (Alpine.js) -->
    <div x-show="showModal" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showModal"
                     @click.away="showModal = false"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-100">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-primary-50 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="bi bi-key text-primary-600 text-lg"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Recuperar Contraseña</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Ingresa tu correo electrónico y te enviaremos instrucciones para restablecer tu contraseña.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="<?= PROJECT_ROOT . '/login/reset-password' ?>" method="post">
                        <div class="px-4 py-5 sm:p-6">
                            <label for="resetEmail" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                            <input type="email" id="resetEmail" name="resetEmail" required
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-2.5">
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <input type="hidden" name="action" value="solicitar_nueva_contraseña">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 sm:ml-3 sm:w-auto transition-colors">
                                Enviar
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    (function(){
        var form = document.querySelector('form[action="<?= PROJECT_ROOT . '\/login' ?>"]');
        if (!form) form = document.querySelector('form');
        var btn = document.getElementById('loginButton');
        var spinner = document.getElementById('loginSpinner');
        var btnText = document.getElementById('loginButtonText');

        if (form && btn && spinner) {
            form.addEventListener('submit', function(e){
                // Mostrar spinner, deshabilitar botón y conservar envío
                spinner.classList.remove('hidden');
                btn.classList.add('opacity-80');
                btn.disabled = true;
                if (btnText) btnText.textContent = 'Iniciando...';
            });
        }
    })();
</script>