<!DOCTYPE html>
<html lang="es" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>Tervion — Automatización y Gestión Integral para Clínicas y Profesionales Sanitarios</title>
    <meta name="description" content="Tervion es la plataforma SaaS que automatiza la gestión empresarial de clínicas y profesionales sanitarios: citas, WhatsApp, historial clínico, facturación Verifactu, nóminas y Contrat@ desde un único lugar.">
    <meta name="keywords" content="software clinicas, gestion clinica fisioterapia, automatizacion clinicas medicas, Verifactu AEAT, gestion laboral clinicas, citas whatsapp pacientes, historias clinicas, facturacion sanitaria">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Tervion">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Tervion — Automatización y Gestión Integral de Clínicas">
    <meta property="og:description" content="Menos administración, más tiempo para tus pacientes. Centraliza y conecta todos los procesos de tu clínica en un único lugar.">
    <meta property="og:image" content="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-sin-fondo.png">
    <meta property="og:url" content="https://tervion-app.com/">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tervion — Tu clínica, tu gestión, tu libertad">
    <meta name="twitter:description" content="La plataforma SaaS que automatiza la gestión empresarial de clínicas y profesionales sanitarios.">
    <meta name="twitter:image" content="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-sin-fondo.png">

    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion-sin-fondo.png" type="image/jpeg">

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
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-sin-fondo.png" alt="Tervion Logo" class="h-8 sm:h-9 object-contain">
            </a>

            <!-- Nav Links (Desktop) -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#como-funciona" class="hover:text-primary-500 transition-colors">Cómo funciona</a>
                <a href="#caracteristicas" class="hover:text-primary-500 transition-colors">Solución</a>
                <a href="#precios" class="hover:text-primary-500 transition-colors">Precios</a>
                <a href="#soporte" class="hover:text-primary-500 transition-colors">Soporte</a>
            </nav>

            <!-- Actions (Desktop) -->
            <div class="hidden sm:flex items-center gap-3 md:gap-4">
                <a href="<?= PROJECT_ROOT ?>/login" class="text-sm font-semibold text-gray-700 hover:text-primary-500 transition-colors px-3 py-2">
                    Iniciar sesión
                </a>
                <a href="<?= PROJECT_ROOT ?>/registro" class="rounded-full bg-primary-500 hover:bg-primary-600 px-5 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm transition-all hover:scale-105">
                    Solicitar ahora
                </a>
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="flex items-center md:hidden">
                <button type="button"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    onclick="const menu = document.getElementById('mobile-menu'); if(menu) menu.classList.toggle('hidden')"
                    class="p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-controls="mobile-menu"
                    aria-expanded="false">
                    <span class="sr-only">Abrir menú principal</span>
                    <!-- Icon Hamburger -->
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden md:hidden border-b border-gray-200 bg-white px-4 pt-2 pb-6 space-y-4 shadow-lg transition-all" id="mobile-menu">
            <nav class="flex flex-col space-y-3 font-medium text-gray-600 pt-2">
                <a href="#como-funciona" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="px-3 py-2 rounded-md hover:bg-gray-50 hover:text-primary-500 transition-colors">Cómo funciona</a>
                <a href="#caracteristicas" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="px-3 py-2 rounded-md hover:bg-gray-50 hover:text-primary-500 transition-colors">Solución</a>
                <a href="#precios" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="px-3 py-2 rounded-md hover:bg-gray-50 hover:text-primary-500 transition-colors">Precios</a>
                <a href="#soporte" onclick="document.getElementById('mobile-menu').classList.add('hidden')" class="px-3 py-2 rounded-md hover:bg-gray-50 hover:text-primary-500 transition-colors">Soporte</a>
            </nav>
            <div class="pt-4 border-t border-gray-100 flex flex-col space-y-2">
                <a href="<?= PROJECT_ROOT ?>/login" class="w-full text-center px-4 py-2.5 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors border border-gray-200 text-sm">
                    Iniciar sesión
                </a>
                <a href="<?= PROJECT_ROOT ?>/registro" class="w-full text-center px-4 py-2.5 rounded-full font-semibold text-white bg-primary-500 hover:bg-primary-600 transition-colors text-sm shadow-sm">
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
                <!-- <div class="pt-4 flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-gray-500 font-medium tracking-wide">
                    <span>🔒 Cumplimiento VERI*FACTU</span>
                    <span>📑 Comunicaciones Contrat@</span>
                    <span>⚡ 100% en la Nube</span>
                </div> -->
            </div>

            <!-- Hero Right -->
            <div class="relative flex justify-center lg:justify-end">
                <div class="relative w-full max-w-xl">
                    <!-- Glow effect behind mockup -->
                    <div class="absolute -inset-2 bg-gradient-to-tr from-primary-500/25 via-indigo-500/20 to-purple-500/15 rounded-3xl blur-2xl pointer-events-none"></div>

                    <!-- Application Window Mockup -->
                    <div class="relative bg-gray-900 rounded-2xl sm:rounded-3xl shadow-2xl border border-gray-800 overflow-hidden text-gray-800">

                        <!-- Top Window Title Bar -->
                        <div class="bg-gray-900/95 px-4 py-2.5 border-b border-gray-800 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 ml-2 hidden sm:inline-block">app.tervion.es / panel</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <!-- <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    <span>VERI*FACTU Activo</span>
                                </span> -->
                            </div>
                        </div>

                        <!-- App Layout (Sidebar + Main View) -->
                        <div class="flex bg-slate-50 min-h-[380px] sm:min-h-[420px]">

                            <!-- Mini App Sidebar -->
                            <div class="w-14 sm:w-16 bg-gray-900 border-r border-gray-800 flex flex-col items-center py-4 justify-between shrink-0 select-none">
                                <div class="space-y-4 flex flex-col items-center">
                                    <!-- Logo Icon -->
                                    <div class="w-8 h-8 rounded-xl bg-gray-800 border border-gray-700 p-1 flex items-center justify-center shadow-inner">
                                        <img src="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion-sin-fondo.png" alt="Tervion" class="w-full h-full object-contain rounded-md">
                                    </div>

                                    <!-- Nav Icons -->
                                    <div class="space-y-2 pt-2 flex flex-col items-center">
                                        <div class="w-9 h-9 rounded-xl bg-primary-600 text-white flex items-center justify-center text-sm shadow-md" title="Panel de Control">
                                            <i class="bi bi-grid-1x2"></i>
                                        </div>
                                        <div class="w-9 h-9 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 flex items-center justify-center text-sm transition-colors" title="Agenda / Citas">
                                            <i class="bi bi-calendar-week"></i>
                                        </div>
                                        <div class="w-9 h-9 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 flex items-center justify-center text-sm transition-colors" title="Pacientes">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="w-9 h-9 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 flex items-center justify-center text-sm transition-colors" title="Facturación">
                                            <i class="bi bi-receipt"></i>
                                        </div>
                                        <div class="w-9 h-9 rounded-xl text-gray-400 hover:text-white hover:bg-gray-800 flex items-center justify-center text-sm transition-colors" title="Nóminas">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Avatar in Sidebar -->
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-primary-600 to-indigo-500 text-white text-[11px] font-bold flex items-center justify-center shadow-sm">
                                    SR
                                </div>
                            </div>

                            <!-- Main Dashboard Area -->
                            <div class="flex-1 p-3.5 sm:p-5 space-y-3 sm:space-y-4 overflow-hidden flex flex-col justify-between">

                                <!-- Welcome / Header Banner -->
                                <div class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200/80 shadow-sm flex items-center justify-between gap-2">
                                    <div>
                                        <div class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-primary-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                                            <span>Resumen del Centro</span>
                                        </div>
                                        <h3 class="text-xs sm:text-sm font-bold text-gray-900 mt-0.5">
                                            Bienvenido, <span class="text-primary-600">Dr. Rubio</span>
                                        </h3>
                                    </div>
                                    <span class="rounded-full bg-primary-600 px-3 py-1.5 text-[10px] sm:text-xs font-semibold text-white shadow-sm flex items-center gap-1 shrink-0">
                                        <i class="bi bi-plus-lg text-[10px]"></i>
                                        <span>Nueva Cita</span>
                                    </span>
                                </div>

                                <!-- KPI Metric Cards -->
                                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                                    <!-- Card 1: Pacientes -->
                                    <div class="bg-white rounded-xl sm:rounded-2xl p-2.5 sm:p-3 border border-gray-200/80 shadow-sm">
                                        <div class="flex items-center justify-between text-gray-400 mb-1">
                                            <span class="text-[9px] font-bold uppercase tracking-wider">Pacientes</span>
                                            <i class="bi bi-people text-xs text-primary-500"></i>
                                        </div>
                                        <div class="text-sm sm:text-lg font-bold text-gray-900 leading-none">1.420</div>
                                        <div class="text-[9px] text-emerald-600 font-semibold mt-1 flex items-center gap-0.5">
                                            <i class="bi bi-arrow-up-short"></i> +12 este mes
                                        </div>
                                    </div>

                                    <!-- Card 2: Facturación -->
                                    <div class="bg-white rounded-xl sm:rounded-2xl p-2.5 sm:p-3 border border-gray-200/80 shadow-sm">
                                        <div class="flex items-center justify-between text-gray-400 mb-1">
                                            <span class="text-[9px] font-bold uppercase tracking-wider">Facturado</span>
                                            <i class="bi bi-shield-check text-xs text-emerald-500"></i>
                                        </div>
                                        <div class="text-sm sm:text-lg font-bold text-gray-900 leading-none">18.450 €</div>
                                        <div class="text-[9px] text-emerald-600 font-semibold mt-1 truncate">
                                            ✓ AEAT OK
                                        </div>
                                    </div>

                                    <!-- Card 3: Agenda Hoy -->
                                    <div class="bg-white rounded-xl sm:rounded-2xl p-2.5 sm:p-3 border border-gray-200/80 shadow-sm">
                                        <div class="flex items-center justify-between text-gray-400 mb-1">
                                            <span class="text-[9px] font-bold uppercase tracking-wider">Agenda Hoy</span>
                                            <i class="bi bi-calendar-check text-xs text-amber-500"></i>
                                        </div>
                                        <div class="text-sm sm:text-lg font-bold text-gray-900 leading-none">8 citas</div>
                                        <div class="text-[9px] text-primary-600 font-semibold mt-1 truncate">
                                            ● 2 pendientes
                                        </div>
                                    </div>
                                </div>

                                <!-- Appointments List Preview -->
                                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                                    <div class="px-3 py-2 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                                        <span class="text-[10px] font-bold text-gray-700 uppercase tracking-wider">Próximas Citas</span>
                                        <span class="text-[10px] font-semibold text-primary-600">Ver todas</span>
                                    </div>
                                    <div class="divide-y divide-gray-100 text-xs">
                                        <!-- Cita 1 -->
                                        <div class="p-2 sm:p-2.5 flex items-center justify-between gap-2 hover:bg-gray-50/70 transition-colors">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-[11px] font-bold text-gray-900 bg-gray-100 px-1.5 py-0.5 rounded-md shrink-0">09:30</span>
                                                <div class="min-w-0">
                                                    <div class="text-[11px] font-bold text-gray-900 truncate">Laura Sánchez</div>
                                                    <div class="text-[9px] text-gray-500 truncate">Fisioterapia · Sesión 3</div>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 shrink-0">
                                                Realizada
                                            </span>
                                        </div>

                                        <!-- Cita 2 -->
                                        <div class="p-2 sm:p-2.5 flex items-center justify-between gap-2 hover:bg-gray-50/70 transition-colors">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-[11px] font-bold text-primary-700 bg-primary-50 px-1.5 py-0.5 rounded-md shrink-0">11:00</span>
                                                <div class="min-w-0">
                                                    <div class="text-[11px] font-bold text-gray-900 truncate">Carlos Morales</div>
                                                    <div class="text-[9px] text-gray-500 truncate">Revisión General</div>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold bg-blue-50 text-blue-700 border border-blue-100 shrink-0">
                                                En consulta
                                            </span>
                                        </div>

                                        <!-- Cita 3 -->
                                        <div class="p-2 sm:p-2.5 flex items-center justify-between gap-2 hover:bg-gray-50/70 transition-colors">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="text-[11px] font-bold text-gray-900 bg-gray-100 px-1.5 py-0.5 rounded-md shrink-0">12:30</span>
                                                <div class="min-w-0">
                                                    <div class="text-[11px] font-bold text-gray-900 truncate">Dra. Carmen Vega</div>
                                                    <div class="text-[9px] text-gray-500 truncate">Primera Visita</div>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-green-50 text-green-700 border border-green-200 shrink-0">
                                                <i class="bi bi-whatsapp text-[9px]"></i> Confirmada
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Floating Live Notification Toast -->
                                <div class="bg-gray-900 text-white rounded-xl p-2 sm:p-2.5 shadow-lg flex items-center justify-between gap-2 border border-gray-800 text-[10px]">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-5 h-5 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center text-xs shrink-0">
                                            <i class="bi bi-whatsapp"></i>
                                        </div>
                                        <span class="truncate opacity-90">Recordatorio 12:30 confirmado por WhatsApp</span>
                                    </div>
                                    <span class="text-green-400 font-semibold shrink-0 text-[9px]">0% no-show</span>
                                </div>

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
                        <h3 class="text-lg font-bold text-gray-900">Recordatorios automáticos vía WhatsApp</h3>
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
                        <h3 class="text-lg font-bold text-gray-900">Historia Clínica</h3>
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
                        Por eso, en Tervion no nos conformamos con ofrecer una simple agenda médica. Estamos construyendo la plataforma definitiva para convertirnos progresivamente en el <strong>centro de gestión empresarial</strong> de los profesionales sanitarios.
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
                        <h4 class="text-xs font-bold uppercase tracking-wider text-primary-600">Próximamente en Tervion</h4>
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
                        <h3 class="text-xl font-bold text-gray-900">Deja que Tervion trabaje</h3>
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

            <!-- Cards Container -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">

                <!-- Plan Básico -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Básico</h3>
                            <p class="text-xs text-gray-500 mt-1">Para profesionales independientes que están comenzando.</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">17,99€</span>
                            <span class="text-xs font-normal text-gray-500">/mes</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-gray-600 mb-8">
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Agenda y gestión integral de citas</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Historia clínica y gestión de pacientes</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Cumplimiento normativo VERI*FACTU</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Recordatorios automáticos (WhatsApp/Email)</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400 line-through">
                                <i class="bi bi-x-circle text-gray-300 text-base shrink-0"></i>
                                <span>Multi-usuario y gestión de equipo</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400 line-through">
                                <i class="bi bi-x-circle text-gray-300 text-base shrink-0"></i>
                                <span>Gestión laboral y nóminas</span>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="w-full text-center rounded-xl bg-gray-50 hover:bg-gray-100 py-3 text-sm font-semibold text-gray-800 transition-all border border-gray-200">
                        Empezar
                    </a>
                </div>

                <!-- Plan Profesional (Destacado) -->
                <div class="bg-white rounded-3xl p-8 shadow-lg border-2 border-primary-500 flex flex-col justify-between relative transform lg:-translate-y-2">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-primary-500 text-white text-[11px] font-bold uppercase tracking-wider px-3.5 py-1 rounded-full shadow-sm">
                        Más Popular
                    </span>
                    <div>
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-primary-600">Profesional</h3>
                            <p class="text-xs text-gray-500 mt-1">La opción idónea para clínicas en crecimiento activo.</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">29,99€</span>
                            <span class="text-xs font-normal text-gray-500">/mes</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-gray-600 mb-8">
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Agenda y gestión integral de citas</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Historia clínica y gestión de pacientes</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Cumplimiento normativo VERI*FACTU</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Recordatorios automáticos (WhatsApp/Email)</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400 line-through">
                                <i class="bi bi-x-circle text-gray-300 text-base shrink-0"></i>
                                <span>Multi-usuario y gestión de equipo</span>
                            </li>
                            <li class="flex items-center gap-3 text-gray-400 line-through">
                                <i class="bi bi-x-circle text-gray-300 text-base shrink-0"></i>
                                <span>Gestión laboral y nóminas</span>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="w-full text-center rounded-xl bg-primary-500 hover:bg-primary-600 py-3 text-sm font-semibold text-white transition-all shadow-md">
                        Empezar ahora
                    </a>
                </div>

                <!-- Plan Premium -->
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-900">Premium</h3>
                            <p class="text-xs text-gray-500 mt-1">Gestión integral completa para clínicas avanzadas.</p>
                        </div>
                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-gray-900">79,99€</span>
                            <span class="text-xs font-normal text-gray-500">/mes</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-gray-600 mb-8">
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Agenda y gestión integral de citas</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Historia clínica y gestión de pacientes</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Cumplimiento normativo VERI*FACTU</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Recordatorios automáticos (WhatsApp/Email)</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Multi-usuario (Sanitarios y Recepción)</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Gestión laboral y nóminas automatizadas</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Pasarela de cobros online Redsys & TPV</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-emerald-500 text-base shrink-0"></i>
                                <span>Control financiero y conciliación</span>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="w-full text-center rounded-xl bg-gray-900 hover:bg-gray-800 py-3 text-sm font-semibold text-white transition-all shadow-sm">
                        Solicitar plan
                    </a>
                </div>

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
                        "Antes pasaba horas cuadrando facturas y enviando citas a mano. Tervion nos ha devuelto la tranquilidad y el tiempo para atender a nuestros pacientes."
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

    <!-- Contact Section -->
    <section id="contacto" class="border-t border-gray-100 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2">

            <!-- Left Contact Info -->
            <div class="bg-primary-500 p-12 sm:p-16 lg:p-20 text-white flex flex-col justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(0,158,255,0.25),transparent)]"></div>
                <div class="relative space-y-8">
                    <div class="space-y-3">
                        <span class="text-xs uppercase tracking-widest opacity-75 font-semibold">Contacto Directo</span>
                        <h2 class="text-3xl sm:text-4xl font-light leading-tight">
                            ¿Hablamos sobre tu clínica?
                        </h2>
                        <p class="text-sm opacity-80 leading-relaxed font-light max-w-md">
                            Nuestro equipo está a tu disposición para resolver cualquier duda, ofrecerte una demostración personalizada o asesorarte en la digitalización de tu centro sanitario.
                        </p>
                    </div>

                    <div class="space-y-6 pt-2 text-sm font-light">
                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase tracking-wider opacity-75 font-bold mb-1">Correo electrónico</h4>
                                <a href="mailto:soporte@tervion-app.com" class="hover:underline opacity-95">soporte@tervion-app.com</a>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.499-5.234-3.837-6.733-6.733l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase tracking-wider opacity-75 font-bold mb-1">Teléfono de atención</h4>
                                <a href="tel:+34900000000" class="hover:underline opacity-95">+34 900 000 000</a>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase tracking-wider opacity-75 font-bold mb-1">Oficinas centrales</h4>
                                <p class="opacity-95">Tervion Ibérica SLU — España</p>
                            </div>
                        </div>

                        <!-- Schedule -->
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-white/10 rounded-xl backdrop-blur-sm shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs uppercase tracking-wider opacity-75 font-bold mb-1">Horario de atención</h4>
                                <p class="opacity-95">Lunes a Viernes: 9:00 - 18:00 (CET)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Contact Form -->
            <div class="p-12 sm:p-16 lg:p-20 flex flex-col justify-center bg-white space-y-6">
                <div class="space-y-2 mb-2">
                    <h3 class="text-2xl font-bold text-gray-900">Envíanos un mensaje</h3>
                    <p class="text-xs text-gray-500">Rellena el formulario y te responderemos en menos de 24 horas laborables.</p>
                </div>
                <form class="space-y-5" onsubmit="event.preventDefault(); alert('¡Gracias por contactarnos! Te responderemos muy pronto.');">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="nombre" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nombre completo</label>
                            <input type="text" id="nombre" required placeholder="Tu nombre"
                                class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent">
                        </div>
                        <div>
                            <label for="telefono" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Teléfono</label>
                            <input type="tel" id="telefono" placeholder="+34 600 000 000"
                                class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Correo electrónico</label>
                        <input type="email" id="email" required placeholder="tuemail@ejemplo.com"
                            class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent">
                    </div>
                    <div>
                        <label for="perfil" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Perfil profesional</label>
                        <select id="perfil" class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent text-gray-600">
                            <option value="">Selecciona tu perfil (opcional)</option>
                            <option>Profesional sanitario independiente / Fisioterapeuta</option>
                            <option>Director / Propietario de clínica médica</option>
                            <option>Responsable de administración y gestión</option>
                            <option>Otro perfil profesional</option>
                        </select>
                    </div>
                    <div>
                        <label for="mensaje" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mensaje</label>
                        <textarea id="mensaje" rows="3" required placeholder="¿En qué podemos ayudarte?"
                            class="w-full border-b border-gray-300 focus:border-primary-500 py-2.5 outline-none text-sm transition-colors bg-transparent resize-none"></textarea>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="rounded-full bg-primary-500 hover:bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-md transition-all hover:scale-105">
                            Enviar mensaje
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-400 leading-normal">
                    Cumplimos estrictamente con la RGPD. Lee nuestra <a href="<?= PROJECT_ROOT ?>/privacidad" class="underline hover:text-primary-500">Política de privacidad</a>.
                </p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 pt-12 pb-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Columna 1: Logo y Redes Sociales -->
                <div class="flex flex-col items-start gap-4">
                    <a href="<?= PROJECT_ROOT ?>/" class="inline-block">
                        <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-claro-sin-fondo.png" alt="Tervion Logo" class="h-8 object-contain">
                    </a>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Automatización y gestión integral para clínicas y profesionales sanitarios.
                    </p>
                    <div class="flex items-center gap-4 text-gray-400 pt-2">
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

                <!-- Columna 2: Navegación de la página -->
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Navegación</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#como-funciona" class="hover:text-white transition-colors">Cómo funciona</a></li>
                        <li><a href="#caracteristicas" class="hover:text-white transition-colors">Solución</a></li>
                        <li><a href="#precios" class="hover:text-white transition-colors">Precios</a></li>
                        <li><a href="#soporte" class="hover:text-white transition-colors">Soporte</a></li>
                    </ul>
                </div>

                <!-- Columna 3: Enlaces legales -->
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Legal</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="<?= PROJECT_ROOT ?>/privacidad" class="hover:text-white transition-colors">Política de Privacidad</a></li>
                        <li><a href="<?= PROJECT_ROOT ?>/terminos" class="hover:text-white transition-colors">Términos de Servicio</a></li>
                        <li><a href="<?= PROJECT_ROOT ?>/cookies" class="hover:text-white transition-colors">Política de Cookies</a></li>
                    </ul>
                </div>

                <!-- Columna 4: Suscripción Newsletter -->
                <div>
                    <h3 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Suscríbete</h3>
                    <p class="text-xs text-gray-400 mb-3">Recibe las últimas novedades y consejos de gestión sanitaria en tu correo.</p>
                    <form action="#" method="POST" class="relative flex items-center">
                        <input type="email" placeholder="Tu correo electrónico" required class="w-full pl-3 pr-28 py-2.5 text-sm bg-gray-800 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <button type="submit" class="absolute right-1.5 bg-primary-500 hover:bg-primary-600 text-white font-medium text-xs py-1.5 px-3.5 rounded-lg transition-colors shadow-sm inline-flex items-center gap-1.5">
                            <!-- <span>Suscribirse</span> -->
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-800 text-center text-xs text-gray-500">
                <p>® 2026 Tervion Ibérica SLU. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Botón flotante de WhatsApp -->
    <a href="https://wa.me/34600000000?text=Hola,%20me%20gustar%C3%ADa%20recibir%20m%C3%A1s%20informaci%C3%B3n%20sobre%20Tervion"
        target="_blank"
        rel="noopener noreferrer"
        class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-110 group focus:outline-none focus:ring-4 focus:ring-emerald-300"
        aria-label="Contactar por WhatsApp">
        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.572-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
        </svg>
        <span class="absolute right-full mr-3 bg-gray-900 text-white text-xs font-medium px-3 py-1.5 rounded-lg shadow-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
            ¡Chatea con nosotros!
        </span>
    </a>

</body>

</html>