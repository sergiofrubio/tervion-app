<!DOCTYPE html>
<html lang="es" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>Velion — Automatización y Gestión Integral para Clínicas y Profesionales Sanitarios</title>
    <meta name="description" content="Velion es la plataforma SaaS que automatiza la gestión empresarial de clínicas y profesionales sanitarios: citas, WhatsApp, historial clínico, facturación Verifactu, nóminas y Contrat@ desde un único lugar.">
    <meta name="keywords" content="software clinicas, gestion clinica fisioterapia, automatizacion clinicas medicas, Verifactu AEAT, gestion laboral clinicas, citas whatsapp pacientes, historias clinicas, facturacion sanitaria">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Velion">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Velion — Automatización y Gestión Integral de Clínicas">
    <meta property="og:description" content="Menos administración, más tiempo para tus pacientes. Centraliza y conecta todos los procesos de tu clínica en un único lugar.">
    <meta property="og:image" content="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpg">
    <meta property="og:url" content="https://velion-app.com/">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Velion — Tu clínica, tu gestión, tu libertad">
    <meta name="twitter:description" content="La plataforma SaaS que automatiza la gestión empresarial de clínicas y profesionales sanitarios.">
    <meta name="twitter:image" content="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpg">

    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/logo-ventana.jpg" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/custom/css/app.css">
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="font-sans antialiased text-gray-900 bg-white <?= $hasSystemAlert ? 'pt-7' : '' ?>">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpg" alt="Velion Logo" class="h-9 object-contain">
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#como-funciona" class="hover:text-primary-500 transition-colors">Cómo funciona</a>
                <a href="#caracteristicas" class="hover:text-primary-500 transition-colors">Solución</a>
                <a href="#precios" class="hover:text-primary-500 transition-colors">Precios</a>
                <a href="#soporte" class="hover:text-primary-500 transition-colors">Soporte</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <a href="<?= PROJECT_ROOT ?>/login" class="text-sm font-semibold text-gray-700 hover:text-primary-500 transition-colors px-3 py-2">
                    Iniciar sesión
                </a>
                <a href="<?= PROJECT_ROOT ?>/registro" class="rounded-full bg-primary-500 hover:bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:scale-105">
                    Solicitar ahora
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-12 pb-20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            <!-- Hero Left -->
            <div class="space-y-8 max-w-xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary-50 text-primary-600 text-xs font-semibold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                    Automatización integral para clínicas y profesionales
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-light text-primary-500 leading-tight tracking-tight">
                    Menos administración,<br>
                    más tiempo para<br>
                    <span class="font-normal text-primary-600">tus pacientes</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light">
                    Gestionar una clínica no debería significar pasar horas entre citas, facturas, nóminas y tareas administrativas. Centraliza y automatiza toda la operativa de tu negocio desde un único lugar.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#como-funciona" class="rounded-full bg-gray-100 hover:bg-gray-200 px-8 py-3.5 text-sm font-semibold text-gray-800 transition-all">
                        Descubrir plataforma
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="rounded-full bg-primary-500 hover:bg-primary-600 px-8 py-3.5 text-sm font-semibold text-white shadow-md transition-all hover:scale-105">
                        Empezar ahora
                    </a>
                </div>
                <div class="pt-4 flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-gray-500 font-medium tracking-wide">
                    <span>🔒 Cumplimiento VERI*FACTU</span>
                    <span>📑 Comunicaciones Contrat@</span>
                    <span>⚡ 100% en la Nube</span>
                </div>
            </div>

            <!-- Hero Right -->
            <div class="relative flex justify-center lg:justify-end">
                <div class="relative w-full max-w-lg aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-tr from-primary-900 via-primary-800 to-indigo-900 flex items-center justify-center p-6 sm:p-8 border border-gray-100">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(0,158,255,0.2),transparent)]"></div>
                    <!-- Dashboard Mockup -->
                    <div class="relative w-full bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 shadow-lg text-white space-y-5">
                        <div class="flex justify-between items-center pb-2 border-b border-white/10">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                                <span class="text-xs uppercase tracking-widest opacity-80 font-semibold">Tranquilidad Administrativa</span>
                            </div>
                            <span class="text-[11px] bg-white/20 px-2.5 py-0.5 rounded-full">En tiempo real</span>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-white/10 rounded-xl p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-500/20 text-green-300 flex items-center justify-center text-sm font-bold">
                                        <i class="bi bi-whatsapp"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold">Recordatorios automáticos</div>
                                        <div class="text-[11px] opacity-70">WhatsApp & Email enviados</div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-green-300">0% ausencias</span>
                            </div>

                            <div class="bg-white/10 rounded-xl p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-300 flex items-center justify-center text-sm font-bold">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold">VERI*FACTU & Fiscalidad</div>
                                        <div class="text-[11px] opacity-70">Facturas encadenadas y remitidas</div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-blue-300">Automático</span>
                            </div>

                            <div class="bg-white/10 rounded-xl p-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-300 flex items-center justify-center text-sm font-bold">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold">Gestión Laboral & Nóminas</div>
                                        <div class="text-[11px] opacity-70">Profesionales y Contrat@ sincronizados</div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-purple-300">Al día</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs pt-1">
                            <div>
                                <div class="opacity-60 text-[10px] uppercase">Control del negocio</div>
                                <div class="font-medium text-sm">Sin tareas manuales</div>
                            </div>
                            <div class="text-right">
                                <div class="opacity-60 text-[10px] uppercase">Sincronización</div>
                                <div class="font-medium text-sm text-green-400">● Todo conectado</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Pillars / Automation Grid Section -->
    <section id="caracteristicas" class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-3">
                <span class="text-xs uppercase tracking-widest text-primary-600 font-bold">Todo lo que tu negocio necesita</span>
                <h2 class="text-3xl font-light text-primary-500">Centraliza y conecta los procesos de tu clínica</h2>
                <p class="text-sm text-gray-500 leading-relaxed font-light">
                    Diseñado para que sepas qué ocurre en tu centro sin perseguir datos, sin tareas repetitivas y sin depender de múltiples aplicaciones desconectadas.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Feature 1: Citas y Agenda -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Citas y Agenda Inteligente</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Organiza la actividad de tu centro, reduce el trabajo manual de recepción y optimiza la disponibilidad de cada profesional sanitario en tiempo real.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-50 flex items-center text-xs font-semibold text-primary-500">
                        <span>Menos fricción operativa</span>
                    </div>
                </div>

                <!-- Feature 2: Recordatorios Automáticos -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-chat-dots"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Recordatorios WhatsApp & Email</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Mantén informados a tus pacientes de manera 100% desatendida. Minimiza los olvidos y reduce radicalmente las ausencias en consulta.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-50 flex items-center text-xs font-semibold text-primary-500">
                        <span>Comunicación sin esfuerzo</span>
                    </div>
                </div>

                <!-- Feature 3: Historia Clínica y Pacientes -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-file-earmark-medical"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Historia Clínica y Pacientes</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Toda la información médica y de contacto centralizada, organizada y accesible al instante desde cualquier lugar con total privacidad.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-50 flex items-center text-xs font-semibold text-primary-500">
                        <span>Información siempre a mano</span>
                    </div>
                </div>

                <!-- Feature 4: Facturación y Cobros -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-credit-card-2-front"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Facturación y Cobros</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Mantén bajo control la actividad económica de tu clínica con cobros online mediante tarjeta y emisión instantánea de facturas vinculadas a citas y bonos.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-50 flex items-center text-xs font-semibold text-primary-500">
                        <span>Control financiero continuo</span>
                    </div>
                </div>

                <!-- Feature 5: Gestión Laboral -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Gestión Laboral de Equipo</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Facilita y agiliza los procesos vinculados a tus profesionales: turnos, ausencias, cálculo automatizado de nóminas y comunicaciones laborales.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-50 flex items-center text-xs font-semibold text-primary-500">
                        <span>Cero líos con tu personal</span>
                    </div>
                </div>

                <!-- Feature 6: Cumplimiento Normativo -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Cumplimiento Normativo</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Incorpora las obligaciones legales que afectan a tu negocio: normativa fiscal VERI*FACTU de la AEAT y comunicaciones laborales mediante Contrat@.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-50 flex items-center text-xs font-semibold text-primary-500">
                        <span>Tranquilidad jurídica absoluta</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Vision & Beyond: El Centro de Gestión Empresarial -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-xs font-semibold">
                    Visión Integral
                </div>
                <h2 class="text-3xl sm:text-4xl font-light text-primary-500 leading-tight">
                    Una clínica es mucho más que pacientes y citas.
                </h2>
                <div class="text-gray-600 space-y-4 font-light leading-relaxed">
                    <p>
                        Tu clínica también es facturación, gastos, trabajadores, impuestos, documentación y decisiones económicas clave.
                    </p>
                    <p>
                        Por eso, en Velion no nos conformamos con ofrecer una simple agenda médica. Estamos construyendo la plataforma definitiva para convertirnos progresivamente en el <strong>centro de gestión empresarial</strong> de los profesionales sanitarios.
                    </p>
                    <p>
                        Te ayudamos a saber con exactitud qué está pasando en tu negocio sin tener que perseguir datos, completar tareas manualmente o saltar entre múltiples aplicaciones incompatibles.
                    </p>
                </div>

                <div class="pt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-medium text-gray-700">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary-500"></i>
                        <span>Cero tareas manuales repetitivas</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary-500"></i>
                        <span>Toma de decisiones con datos reales</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary-500"></i>
                        <span>Todos tus procesos sincronizados</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-primary-500"></i>
                        <span>Enfoque total en tus pacientes</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <div class="w-full max-w-md bg-gradient-to-br from-slate-50 to-primary-50/40 p-8 rounded-3xl border border-gray-200/80 shadow-sm space-y-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-600">Próximamente en Velion</h4>
                        <span class="text-[10px] bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full font-semibold">En evolución</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Incorporamos continuamente nuevas herramientas de gestión financiera, contabilidad y fiscalidad para centralizar cada vez más procesos de autónomos y sociedades.
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="flex gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-500 flex items-center justify-center shrink-0">
                                <i class="bi bi-graph-up-arrow text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Control Financiero y Tesorería</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Visión global de ingresos, gastos previsibles y márgenes reales del negocio.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-500 flex items-center justify-center shrink-0">
                                <i class="bi bi-journal-check text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Fiscalidad y Contabilidad</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Facilidad en el cumplimiento de obligaciones fiscales periódicas para autónomos y SLs.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 p-3 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="w-10 h-10 rounded-lg bg-primary-50 text-primary-500 flex items-center justify-center shrink-0">
                                <i class="bi bi-cpu text-lg"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Automatización de Procesos</h5>
                                <p class="text-xs text-gray-500 mt-0.5">Conexión con agentes externos y flujos inteligentes que trabajan por ti.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cómo Funciona Section (Pasos hacia la tranquilidad) -->
    <section id="como-funciona" class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-16 space-y-2">
                <span class="text-xs uppercase tracking-widest text-primary-600 font-bold">Simplicidad en 3 pasos</span>
                <h2 class="text-3xl font-light text-primary-500">Cómo empezar a automatizar tu clínica</h2>
                <p class="text-sm text-gray-500 leading-relaxed font-light">
                    Una transición fluida diseñada para que ahorres horas de trabajo desde el primer día.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-80 relative overflow-hidden group">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                            Paso 1: Conecta tu actividad
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">Configura tu centro</h3>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">
                            Da de alta tus especialidades, equipo de profesionales, tarifas y horarios de forma ágil y guiada.
                        </p>
                    </div>
                    <div class="text-xs text-primary-500 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        <span>Puesta en marcha rápida</span> <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-80 relative overflow-hidden group">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                            Paso 2: Automatiza el día a día
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">Deja que Velion trabaje</h3>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">
                            Tus pacientes reciben recordatorios por WhatsApp/email, los cobros y facturas se generan solos y la agenda se actualiza al instante.
                        </p>
                    </div>
                    <div class="text-xs text-primary-500 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        <span>Operativa sin fricción</span> <i class="bi bi-arrow-right"></i>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-80 relative overflow-hidden group">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                            Paso 3: Tranquilidad absoluta
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">Control y cumplimiento</h3>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">
                            Cumple automáticamente con VERI*FACTU y las obligaciones laborales mientras tienes visión global y clara de tu negocio.
                        </p>
                    </div>
                    <div class="text-xs text-primary-500 font-semibold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                        <span>Cero preocupaciones</span> <i class="bi bi-arrow-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-5xl font-extralight text-primary-500">100%</div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Tranquilidad Administrativa</p>
            </div>
            <div>
                <div class="text-5xl font-extralight text-primary-500">+10h</div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Ahorradas a la semana en gestión</p>
            </div>
            <div>
                <div class="text-5xl font-extralight text-primary-500">0</div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Tareas duplicadas o manuales</p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="precios" class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest text-primary-600 font-bold">Inversión transparente</span>
                <h2 class="text-3xl font-light text-primary-500 mt-1">Planes a la medida de tu clínica</h2>
                <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">Elige el plan ideal para automatizar tu negocio y ganar tranquilidad en tu día a día.</p>
            </div>

            <!-- Table Container -->
            <div class="max-w-5xl mx-auto overflow-x-auto bg-white rounded-3xl shadow-sm border border-gray-100">
                <table class="w-full min-w-[650px] border-collapse text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="p-6 text-sm font-semibold text-gray-900 w-2/5"></th>
                            <th class="p-6 text-center w-1/5">
                                <div class="space-y-2">
                                    <div class="text-base font-bold text-gray-900">Básico</div>
                                    <div class="text-2xl font-extrabold text-gray-900">17,99€<span class="text-xs font-normal text-gray-500">/mes</span></div>
                                    <a href="<?= PROJECT_ROOT ?>/registro" class="inline-block w-full text-center rounded-full bg-gray-50 hover:bg-gray-100 py-2 text-xs font-semibold text-gray-800 transition-all border border-gray-200">
                                        Empezar
                                    </a>
                                </div>
                            </th>
                            <th class="p-6 text-center w-1/5 bg-primary-50/20">
                                <div class="space-y-2">
                                    <div class="text-base font-bold text-primary-500">Profesional</div>
                                    <div class="text-2xl font-extrabold text-gray-900">29,99€<span class="text-xs font-normal text-gray-500">/mes</span></div>
                                    <a href="<?= PROJECT_ROOT ?>/registro" class="inline-block w-full text-center rounded-full bg-gray-50 hover:bg-gray-100 py-2 text-xs font-semibold text-gray-800 transition-all border border-gray-200">
                                        Empezar
                                    </a>
                                </div>
                            </th>
                            <th class="p-6 text-center w-1/5 bg-primary-50/20">
                                <div class="space-y-2">
                                    <div class="text-base font-bold text-gray-900">Premium</div>
                                    <div class="text-2xl font-extrabold text-gray-900">79,99€<span class="text-xs font-normal text-gray-500">/mes</span></div>
                                    <a href="<?= PROJECT_ROOT ?>/registro" class="inline-block w-full text-center rounded-full bg-primary-500 hover:bg-primary-600 py-2 text-xs font-semibold text-white transition-all shadow-sm">
                                        Solicitar
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Agenda y gestión integral de citas</td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Historia clínica y gestión de pacientes</td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Cumplimiento normativo VERI*FACTU (AEAT)</td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Recordatorios automáticos por WhatsApp y Email</td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Multi-usuario (Sanitarios y Recepción/Administración)</td>
                            <td class="p-6 text-center"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Gestión laboral y cálculo de nóminas automatizado</td>
                            <td class="p-6 text-center"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Pasarela de cobros online Redsys & TPV</td>
                            <td class="p-6 text-center"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="p-6 text-sm text-gray-700 font-medium">Control financiero y conciliación de movimientos</td>
                            <td class="p-6 text-center"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center bg-primary-50/10"><i class="bi bi-x-lg text-rose-400 text-sm"></i></td>
                            <td class="p-6 text-center"><i class="bi bi-check-lg text-emerald-500 text-lg"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Quote 1 -->
                <div class="space-y-4">
                    <p class="text-lg text-primary-600 font-light leading-relaxed italic">
                        "Antes pasaba horas cuadrando facturas y enviando citas a mano. Velion nos ha devuelto la tranquilidad y el tiempo para atender a nuestros pacientes."
                    </p>
                    <div>
                        <h5 class="text-sm font-bold text-gray-900">Dra. Laura Morales</h5>
                        <p class="text-xs text-gray-400">Directora de Clínica Aquiles</p>
                    </div>
                </div>
                <!-- Quote 2 -->
                <div class="space-y-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-8 lg:pt-0 lg:pl-8">
                    <p class="text-lg text-primary-600 font-light leading-relaxed italic">
                        "Los recordatorios automáticos por WhatsApp redujeron las ausencias de golpe. Y saber que VERI*FACTU está resuelto nos da paz absoluta."
                    </p>
                    <div>
                        <h5 class="text-sm font-bold text-gray-900">Antonio G.</h5>
                        <p class="text-xs text-gray-400">Fisioterapeuta y Gerente de Centro Jardín</p>
                    </div>
                </div>
                <!-- Quote 3 -->
                <div class="space-y-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-8 lg:pt-0 lg:pl-8">
                    <p class="text-lg text-primary-600 font-light leading-relaxed italic">
                        "Centralizar la parte laboral, el cobro y las citas en un solo lugar nos ha permitido crecer sin ahogarnos en papeleo ni datos dispersos."
                    </p>
                    <div>
                        <h5 class="text-sm font-bold text-gray-900">Carlos R.</h5>
                        <p class="text-xs text-gray-400">Gestor de Clínica Sanitaria CPT</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Subscription Section -->
    <section class="border-t border-gray-100 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2">

            <!-- Left Banner -->
            <div class="bg-primary-500 p-12 sm:p-16 lg:p-20 text-white flex flex-col justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(0,158,255,0.25),transparent)]"></div>
                <div class="relative space-y-4">
                    <span class="text-xs uppercase tracking-widest opacity-75 font-semibold">Comunidad Velion</span>
                    <h2 class="text-3xl sm:text-4xl font-light leading-tight">
                        Gestión empresarial y automatización sanitaria
                    </h2>
                    <p class="text-sm opacity-80 leading-relaxed font-light max-w-xs">
                        Recibe consejos prácticos sobre optimización y automatización de clínicas, novedades normativas y claves para hacer crecer tu negocio.
                    </p>
                </div>
            </div>

            <!-- Right Form -->
            <div class="p-12 sm:p-16 lg:p-20 flex flex-col justify-center bg-white space-y-6">
                <form class="space-y-5" onsubmit="event.preventDefault(); alert('¡Gracias por suscribirte!');">
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo electrónico</label>
                        <input type="email" id="email" required placeholder="tuemail@ejemplo.com"
                            class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent">
                    </div>
                    <div>
                        <label for="perfil" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Perfil profesional</label>
                        <select id="perfil" class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent text-gray-600">
                            <option>Profesional sanitario independiente / Fisioterapeuta</option>
                            <option>Director / Propietario de clínica médica</option>
                            <option>Responsable de administración y gestión</option>
                            <option>Otro perfil profesional</option>
                        </select>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="rounded-full bg-primary-500 hover:bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-md transition-all hover:scale-105">
                            Suscribirse
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-400 leading-normal">
                    Puedes darte de baja en cualquier momento. Cumplimos estrictamente con la RGPD. Lee nuestra <a href="#" class="underline hover:text-primary-500">Política de privacidad</a>.
                </p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6 sm:gap-4">
            <p class="w-full sm:w-1/3 text-center sm:text-left text-sm">© 2026 Velion Ibérica SLU</p>

            <a href="<?= PROJECT_ROOT ?>/" class="w-full sm:w-1/3 flex items-center justify-center">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-ventana-oscuro.jpg" alt="Velion Logo" class="h-8 object-contain rounded-md p-1 shadow-sm">
            </a>

            <div class="w-full sm:w-1/3 flex items-center justify-center sm:justify-end gap-6 text-gray-400">
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Twitter">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="LinkedIn">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Instagram">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>

</body>

</html>