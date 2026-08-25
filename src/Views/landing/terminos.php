<?php
$title = "Términos de Servicio — Tervion";
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <meta name="description" content="Términos y Condiciones del Servicio de la plataforma SaaS Tervion.">
    <meta name="robots" content="index, follow">

    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion-sin-fondo.png" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/custom/css/app.css">
</head>

<body class="font-sans antialiased text-gray-900 bg-white min-h-screen flex flex-col justify-between">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center gap-2">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-sin-fondo.png" alt="Tervion Logo" class="h-8 sm:h-9 object-contain">
            </a>
            <div class="flex items-center gap-4">
                <a href="<?= PROJECT_ROOT ?>/" class="text-sm font-medium text-gray-600 hover:text-primary-500 transition-colors">Volver a Inicio</a>
                <a href="<?= PROJECT_ROOT ?>/login" class="text-sm font-semibold text-gray-700 hover:text-primary-500 transition-colors hidden sm:block">Iniciar sesión</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-12 lg:py-16 flex-grow">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">Términos de Servicio</h1>
        <p class="text-sm text-gray-500 mb-8">Última actualización: 22 de agosto de 2026</p>

        <div class="prose prose-blue max-w-none text-gray-600 space-y-6 text-sm leading-relaxed">
            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">1. Aceptación de los Términos</h2>
                <p>Al acceder y utilizar la plataforma <strong>Tervion</strong> (prestada por Tervion Ibérica SLU), el usuario o entidad suscriptora acepta quedar vinculado por los presentes Términos de Servicio. Si no está de acuerdo con alguno de los términos, no deberá acceder ni utilizar nuestros servicios.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">2. Descripción del Servicio</h2>
                <p>Tervion provee una plataforma de software como servicio (SaaS) destinada a la automatización de la gestión integral de clínicas y profesionales de la salud, abarcando agendamiento de citas, historias clínicas electrónicas, facturación acorde a normativa Verifactu, integración laboral y mensajería.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">3. Cuentas de Usuario y Responsabilidad</h2>
                <p>El cliente es totalmente responsable de mantener la confidencialidad de sus credenciales de acceso, así como de todas las actividades realizadas bajo su cuenta. Asimismo, garantiza que los datos facilitados a Tervion son veraces, precisos y actualizados.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">4. Planes de Precios y Pagos</h2>
                <p>El acceso a las funcionalidades completas del software requiere una suscripción activa bajo los términos estipulados en la tarifa seleccionada. Tervion se reserva el derecho de actualizar los precios avisando con al menos 30 días de antelación.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">5. Propiedad Intelectual</h2>
                <p>Todos los derechos sobre el software, marcas, logotipos, diseño y código fuente pertenecen en exclusividad a Tervion Ibérica SLU. El uso de la plataforma no otorga ningún derecho de propiedad intelectual sobre la misma.</p>
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
