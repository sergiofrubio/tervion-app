<?php
$title = "Política de Privacidad — Tervion";
?>
<!DOCTYPE html>
<html lang="es" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <meta name="description" content="Política de Privacidad y protección de datos personales de Tervion Ibérica SLU.">
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
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6">Política de Privacidad</h1>
        <p class="text-sm text-gray-500 mb-8">Última actualización: 22 de agosto de 2026</p>

        <div class="prose prose-blue max-w-none text-gray-600 space-y-6 text-sm leading-relaxed">
            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">1. Responsable del Tratamiento</h2>
                <p><strong>Tervion Ibérica SLU</strong> (en adelante, "Tervion"), con domicilio social en España, es responsable del tratamiento de los datos personales recogidos a través de nuestra plataforma SaaS y sitio web corporativo, cumpliendo estrictamente con el Reglamento General de Protección de Datos (RGPD UE 2016/679) y la Ley Orgánica 3/2018 (LOPDGDD).</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">2. Datos que recopilamos</h2>
                <p>Recopilamos información necesaria para la prestación y mejora de nuestras soluciones de automatización clínica:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li><strong>Datos identificativos y de contacto:</strong> Nombre, apellidos, correo electrónico, teléfono, CIF/NIF y datos de la clínica o centro sanitario.</li>
                    <li><strong>Datos de facturación y suscripción:</strong> Información de pago, datos bancarios e historial de facturación.</li>
                    <li><strong>Datos de uso de la plataforma:</strong> Dirección IP, registros de acceso, tipo de navegador y métricas de uso de las funcionalidades.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">3. Finalidad del Tratamiento</h2>
                <p>Tratamos los datos recogidos con las siguientes finalidades principales:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Gestión de la suscripción, alta de cuentas de usuario y acceso a la plataforma.</li>
                    <li>Atención a consultas, soporte técnico y asistencia al usuario.</li>
                    <li>Envío de notificaciones operativas, confirmaciones de citas y boletines informativos (newsletter) en caso de suscripción explícita.</li>
                    <li>Cumplimiento de obligaciones legales y fiscales (Verifactu / AEAT).</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">4. Legitimación del Tratamiento</h2>
                <p>La base legal para el tratamiento de tus datos es la ejecución del contrato de prestación de servicios, el cumplimiento de obligaciones legales aplicables a Tervion, y el consentimiento explícito prestado al enviar formularios de contacto o suscripción a la newsletter.</p>
            </section>

            <section class="space-y-3">
                <h2 class="text-xl font-bold text-gray-800">5. Derechos de los Usuarios</h2>
                <p>Cualquier usuario tiene derecho a acceder, rectificar, suprimir, limitar el tratamiento, oponerse al mismo y solicitar la portabilidad de sus datos personales. Para ejercitar estos derechos, puede enviar una solicitud por escrito adjuntando copia de su documento de identidad a <strong>privacidad@tervion-app.com</strong>.</p>
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
