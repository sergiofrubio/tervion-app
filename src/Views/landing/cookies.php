<?php
$title = "Política de Cookies — Tervion";
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <meta name="description" content="Política de Cookies de la plataforma Tervion. Informamos sobre el uso de cookies y tecnologías similares.">
    <meta name="robots" content="index, follow">

    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">
</head>

<body class="font-sans antialiased text-gray-900 bg-white min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center gap-2">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-sin-fondo.png" alt="Tervion Logo" class="h-8 sm:h-9 object-contain">
            </a>
            <div class="flex items-center gap-4">
                <a href="<?= PROJECT_ROOT ?>/" class="text-sm font-medium text-gray-600 hover:text-primary-500 transition-colors">Volver a Inicio</a>
                <a href="<?= PROJECT_ROOT ?>/login" class="text-sm font-semibold text-gray-700 hover:text-primary-500 transition-colors hidden sm:block">Iniciar sesión</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-12 lg:py-16 flex-grow">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">Política de Cookies</h1>
        <p class="text-sm text-gray-500 mb-8">Última actualización: 22 de agosto de 2026</p>

        <div class="prose prose-blue max-w-none text-gray-600 space-y-6 text-sm leading-relaxed">
            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">1. ¿Qué son las Cookies?</h2>
                <p>Una cookie es un pequeño archivo de texto que se almacena en su navegador cuando visita casi cualquier página web. Su utilidad es que la web sea capaz de recordar su visita cuando vuelva a navegar por esa página.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">2. Cookies que utilizamos</h2>
                <p>En <strong>Tervion</strong> utilizamos las siguientes categorías de cookies:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Cookies estrictamente necesarias:</strong> Permiten la autenticación de usuarios, la gestión de sesiones de trabajo seguras y el almacenamiento de preferencias de interfaz.</li>
                    <li><strong>Cookies analíticas:</strong> Ayudan a medir la interacción de los usuarios de forma anónima para optimizar la velocidad y rendimiento de la plataforma.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">3. Gestión y Desactivación de Cookies</h2>
                <p>Usted puede permitir, bloquear o eliminar las cookies instaladas en su equipo mediante la configuración de las opciones de su navegador de Internet (Google Chrome, Mozilla Firefox, Safari, Microsoft Edge).</p>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-6 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs">
            <p>® 2026 Tervion Ibérica SLU. Todos los derechos reservados.</p>
        </div>
    </footer>

</body>
</html>
