<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Cliente - Tervion</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    <script>
        window.registrationInitialData = <?= json_encode([
                                                'nombre' => $data['nombre'] ?? '',
                                                'email' => $data['email'] ?? '',
                                            ]) ?>;
    </script>

    <!-- Registration Module Logic -->
    <script src="<?= PROJECT_ROOT ?>/public/js/modules/landing/registration.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col justify-between <?= $hasSystemAlert ? 'pt-7' : '' ?>"
    x-data="registrationForm(window.registrationInitialData || {})"
    x-cloak>

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Top Navigation / Header -->
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center gap-3">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-azul-sin-fondo.svg" alt="Tervion Logo" class="h-8 sm:h-9 object-contain" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-azul-sin-fondo.svg';">
            </a>
            <div class="flex items-center gap-3">
                <span class="text-xs sm:text-sm font-medium text-slate-500 hidden sm:inline">¿Ya tienes cuenta?</span>
                <a href="<?= PROJECT_ROOT ?>/login" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 border border-slate-200 shadow-xs transition-all">
                    <span>Iniciar Sesión</span>
                    <i class="bi bi-box-arrow-in-right text-sm text-slate-500"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Registration Section -->
    <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="w-full max-w-lg">

            <!-- Alert message if controller returns error -->
            <?php if (!empty($error)) : ?>
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 mb-6 shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Error en el registro</h3>
                            <div class="mt-1 text-sm text-red-700">
                                <p><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Container -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-10 transition-all">
                <div class="text-center mb-8">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary-50 text-primary-700 text-xs font-bold rounded-full border border-primary-200 mb-3">
                        <i class="bi bi-shield-check"></i> Cuenta de Administrador
                    </span>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Crea tu cuenta en Tervion</h1>
                    <p class="text-sm text-slate-500 mt-1.5">Introduce tus datos básicos para comenzar tu prueba gratuita de 14 días.</p>
                </div>

                <form action="<?= PROJECT_ROOT ?>/registro" method="POST" @submit="submitForm" class="space-y-5">
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-slate-700 mb-1">Nombre completo *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="nombre" id="nombre" x-model="formData.nombre" required
                                class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all shadow-xs"
                                placeholder="Tu nombre y apellidos">
                        </div>
                        <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.nombre" x-text="errors.nombre"></span>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" x-model="formData.email" required
                                class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all shadow-xs"
                                placeholder="ejemplo@clinica.com">
                        </div>
                        <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.email" x-text="errors.email"></span>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="pass" class="block text-sm font-medium text-slate-700 mb-1">Contraseña * (mín. 8 caracteres)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="pass" id="pass" x-model="formData.pass" required minlength="8"
                                class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all shadow-xs"
                                placeholder="••••••••">
                        </div>
                        <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.pass" x-text="errors.pass"></span>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="confirm_pass" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña *</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" name="confirm_pass" id="confirm_pass" x-model="formData.confirm_pass" required minlength="8"
                                class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all shadow-xs"
                                placeholder="••••••••">
                        </div>
                        <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.confirm_pass" x-text="errors.confirm_pass"></span>
                    </div>

                    <!-- Botón Enviar -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center items-center py-3.5 px-6 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all transform hover:-translate-y-0.5 cursor-pointer">
                            <span>Crear Cuenta de Administrador</span>
                            <i class="bi bi-arrow-right ml-2 font-bold"></i>
                        </button>
                    </div>

                    <p class="text-center text-xs text-slate-400 mt-4">
                        Al registrarte, aceptas nuestros <a href="<?= PROJECT_ROOT ?>/terminos" class="underline hover:text-slate-600">Términos</a> y <a href="<?= PROJECT_ROOT ?>/privacidad" class="underline hover:text-slate-600">Política de Privacidad</a>.
                    </p>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6 sm:gap-4 text-xs">
            <p class="w-full sm:w-1/3 text-center sm:text-left">Tervion ERP — Software Libre bajo licencia <a href="https://www.gnu.org/licenses/lgpl-3.0.html" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-300">GNU LGPLv3</a>.</p>

            <a href="<?= PROJECT_ROOT ?>/" class="w-full sm:w-1/3 flex items-center justify-center">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png" alt="Tervion Logo" class="h-7 object-contain" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-claro-sin-fondo.png';">
            </a>

            <div class="w-full sm:w-1/3 flex items-center justify-center sm:justify-end gap-4 text-slate-400">
                <a href="<?= PROJECT_ROOT ?>/privacidad" class="hover:text-slate-300">Privacidad</a>
                <span>•</span>
                <a href="<?= PROJECT_ROOT ?>/terminos" class="hover:text-slate-300">Términos</a>
                <span>•</span>
                <a href="<?= PROJECT_ROOT ?>/cookies" class="hover:text-slate-300">Cookies</a>
            </div>
        </div>
    </footer>

</body>

</html>