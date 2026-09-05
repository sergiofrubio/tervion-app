<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">

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
    <meta property="og:title" content="Tervion — Tu clínica, tu gestión, tu libertad">
    <meta property="og:description" content="Menos administración, más tiempo para tus pacientes. Centraliza y conecta todos los procesos de tu clínica en un único lugar.">
    <meta property="og:image" content="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png">
    <meta property="og:url" content="https://tervion-app.com/">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tervion — Automatización y Gestión Integral de Clínicas">
    <meta name="twitter:description" content="La plataforma SaaS que automatiza la gestión empresarial de clínicas y profesionales sanitarios.">
    <meta name="twitter:image" content="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png">

    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">

    <!-- Alpine.js Plugins & Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .hero-arch-mask {
            border-top-left-radius: 120px;
            border-top-right-radius: 120px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
        }

        .service-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .service-card:hover {
            transform: translateY(-4px);
        }

        .team-card {
            transition: all 0.3s ease;
        }

        .team-card:hover {
            transform: translateY(-4px);
        }
    </style>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="bg-white text-slate-800 font-sans antialiased selection:bg-primary-500 selection:text-white <?= $hasSystemAlert ? 'pt-7' : '' ?>" x-data="{ mobileMenuOpen: false }">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- ========================================================================= -->
    <!-- 1. NAVBAR (TOP NAVIGATION) -->
    <!-- ========================================================================= -->
    <header class="relative z-30 bg-slate-900 border-b border-slate-800/80">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                <!-- Logo -->
                <a href="<?= PROJECT_ROOT ?>/" class="flex items-center gap-3 group">
                    <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png" alt="Tervion" class="h-7 sm:h-8 w-auto object-contain transition-transform group-hover:scale-105" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-claro-sin-fondo.png';">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                    <a href="#inicio" class="text-white hover:text-indigo-400 transition-colors">Inicio</a>
                    <a href="#soluciones" class="hover:text-indigo-400 transition-colors">Soluciones</a>
                    <a href="#ventajas" class="hover:text-indigo-400 transition-colors">Ventajas</a>
                    <a href="#caracteristicas" class="hover:text-indigo-400 transition-colors">Funcionalidades</a>
                    <a href="#precios" class="hover:text-indigo-400 transition-colors">Precios</a>
                    <!-- <a href="#testimonios" class="hover:text-indigo-400 transition-colors">Opiniones</a>
                    <a href="<?= PROJECT_ROOT ?>/login" class="hover:text-indigo-400 transition-colors">Acceso</a> -->
                </div>

                <!-- Action Button -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="<?= PROJECT_ROOT ?>/login" class="px-6 py-2.5 rounded-full text-xs font-bold tracking-wide uppercase bg-primary-500 hover:bg-primary-600 text-white shadow-lg shadow-primary-500/25 transition-all transform hover:-translate-y-0.5">
                        Iniciar Sesión
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-slate-300 hover:text-white p-2 focus:outline-none" aria-label="Abrir menú">
                        <i class="bi" :class="mobileMenuOpen ? 'bi-x-lg text-2xl' : 'bi-list text-2xl'"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div x-show="mobileMenuOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                @click.away="mobileMenuOpen = false"
                class="md:hidden pt-2 pb-6 px-4 bg-slate-800 rounded-2xl border border-slate-700/50 shadow-2xl space-y-4 mb-4">
                <a href="#inicio" @click="mobileMenuOpen = false" class="block text-white hover:text-indigo-400 font-medium">Inicio</a>
                <a href="#soluciones" @click="mobileMenuOpen = false" class="block text-slate-300 hover:text-indigo-400 font-medium">Soluciones</a>
                <a href="#ventajas" @click="mobileMenuOpen = false" class="block text-slate-300 hover:text-indigo-400 font-medium">Ventajas</a>
                <a href="#caracteristicas" @click="mobileMenuOpen = false" class="block text-slate-300 hover:text-indigo-400 font-medium">Funcionalidades</a>
                <a href="#precios" @click="mobileMenuOpen = false" class="block text-slate-300 hover:text-indigo-400 font-medium">Precios</a>
                <!-- <a href="#testimonios" @click="mobileMenuOpen = false" class="block text-slate-300 hover:text-indigo-400 font-medium">Opiniones</a>
                <div class="pt-2 border-t border-slate-700 flex flex-col gap-2">
                    <a href="<?= PROJECT_ROOT ?>/login" class="text-center w-full py-3 rounded-full text-xs font-bold uppercase bg-primary-500 hover:bg-primary-600 text-white">
                        Iniciar Sesión
                    </a>
                </div> -->
            </div>
        </nav>
    </header>

    <!-- ========================================================================= -->
    <!-- HERO SECTION (DARK NAVY / PRIMARY-900 PALETTE) -->
    <!-- ========================================================================= -->
    <section class="bg-slate-900 relative overflow-hidden text-white pt-10 pb-20 lg:pb-28">
        <!-- Subtle background gradient and glow from primary/indigo -->
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3 w-[600px] h-[600px] bg-primary-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/4 w-[500px] h-[500px] bg-primary-500/15 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Hero Content Grid -->
        <div id="inicio" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 lg:pt-20 pb-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Column (Copy & Actions) -->
                <div class="lg:col-span-6 space-y-6 lg:pr-4">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-primary-500/20 text-indigo-300 border border-indigo-400/30">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        Gestión Clínica Inteligente
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-[56px] font-extrabold tracking-tight text-white leading-[1.12]">
                        Construye, Gestiona y Haz Crecer Tu Clínica Online.
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-xl font-normal">
                        La plataforma SaaS integral que simplifica el día a día de profesionales y centros sanitarios: agenda online, recordatorios por WhatsApp, historial clínico seguro, facturación Verifactu y control laboral en un solo lugar.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-7 py-3.5 rounded-full text-sm font-bold tracking-wide uppercase bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 shadow-xl shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                            <span>Comenzar Ahora</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                        <form action="<?= PROJECT_ROOT ?>/login" method="post" class="inline">
                            <input type="hidden" name="csrf_token" value="<?= \App\Core\Csrf::getToken() ?>">
                            <input type="hidden" name="email" value="admin@example.com">
                            <input type="hidden" name="pass" value="12345678">
                            <button type="submit" class="px-7 py-3.5 rounded-full text-sm font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all inline-flex items-center gap-2 backdrop-blur-sm cursor-pointer">
                                <i class="bi bi-play-circle text-indigo-400 text-base"></i>
                                <span>Ver Demostración</span>
                            </button>
                        </form>
                    </div>

                    <div class="pt-6 flex items-center gap-6 text-xs text-slate-400 border-t border-white/10">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-shield-check text-indigo-400 text-base"></i>
                            <span>Cumplimiento RGPD & Verifactu</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-indigo-400 text-base"></i>
                            <span>Puesta en marcha en 5 min</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Hero Featured Image with Arch Mask) -->
                <div class="lg:col-span-6 flex justify-center lg:justify-end relative">
                    <div class="relative w-full max-w-[480px]">
                        <!-- Decorative Frame Behind -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary-600/30 to-indigo-500/20 hero-arch-mask transform translate-x-3 translate-y-3 -z-10 blur-sm"></div>

                        <!-- Main Image Container with Distinctive Arch Curve -->
                        <div class="hero-arch-mask overflow-hidden border-2 border-indigo-500/30 shadow-2xl bg-slate-800 aspect-[4/5] relative group">
                            <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=1200&auto=format&fit=crop"
                                alt="Profesionales médicos y sanitarios gestionando clínica"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700">

                            <!-- Bottom Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>

                            <!-- Floating Metric Card -->
                            <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md rounded-2xl p-4 text-slate-900 shadow-xl border border-white/60">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                                            <i class="bi bi-calendar-check-fill text-lg"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Citas Confirmadas</div>
                                            <div class="text-base font-extrabold text-slate-900">+98.4% Asistencia</div>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 text-[11px] font-bold rounded-full">WhatsApp AI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 2. SECTION: WE HELP AMBITIOUS CLINICS GROW (LIGHT BACKGROUND) -->
    <!-- ========================================================================= -->
    <section id="ventajas" class="py-20 lg:py-28 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left: Team/Clinic Image with Rounded Rectangle Mask -->
                <div class="lg:col-span-6 order-2 lg:order-1">
                    <div class="relative max-w-[500px] mx-auto">
                        <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-100 aspect-[4/3] sm:aspect-[1/1] relative group">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop"
                                alt="Equipo de trabajo y gestión clínica"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -right-6 hidden sm:flex items-center gap-3 bg-slate-900 text-white px-5 py-4 rounded-2xl shadow-xl border border-slate-800">
                            <i class="bi bi-graph-up-arrow text-2xl text-indigo-400"></i>
                            <div>
                                <div class="text-xs text-slate-300 font-medium">Ahorro Administrativo</div>
                                <div class="text-lg font-bold text-white">-15 horas/semana</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Copy & Bullet Points -->
                <div class="lg:col-span-6 order-1 lg:order-2 space-y-6">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                        SOBRE TERVION
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Ayudamos a clínicas y profesionales sanitarios a crecer con soluciones digitales innovadoras.
                    </h2>

                    <p class="text-slate-600 text-base leading-relaxed">
                        Olvídate de programas dispersos y tareas manuales repetitivas. Tervion sincroniza toda la actividad asistencial y administrativa en un entorno seguro y fácil de usar para todo tu equipo.
                    </p>

                    <!-- Feature Check Items -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-check2 font-bold text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Automatización de Citas y Recordatorios</h3>
                                <p class="text-sm text-slate-600">Envío programado de avisos por WhatsApp y correo para erradicar el absentismo de pacientes.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-check2 font-bold text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Historias Clínicas y Consentimientos Digitales</h3>
                                <p class="text-sm text-slate-600">Evolutivos, plantillas personalizadas por especialidad y firma biométrica conforme al RGPD.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-check2 font-bold text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Cumplimiento Legal Verifactu & Facturación</h3>
                                <p class="text-sm text-slate-600">Generación de facturas electrónicas encadenadas y exportación contable en un solo clic.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-7 py-3.5 rounded-full text-sm font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 shadow-md transition-all">
                            <span>Conoce Nuestra Plataforma</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 3. SECTION: SERVICES GRID & DIGITAL SOLUTIONS -->
    <!-- ========================================================================= -->
    <section id="soluciones" class="py-20 lg:py-28 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-start">

                <!-- Left Title & Intro -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                        SERVICIOS CLÍNICOS
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Soluciones digitales adaptadas a tu clínica.
                    </h2>

                    <p class="text-slate-600 text-base leading-relaxed">
                        Diseñado específicamente para centros de fisioterapia, clínicas médicas, psicología, odontología y estética que buscan excelencia y rapidez en cada consulta.
                    </p>

                    <div class="pt-2">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-7 py-3.5 rounded-full text-sm font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 transition-all">
                            <span>Ver Todas las Funciones</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                    </div>
                </div>

                <!-- Right: 2-Columns Grid with 6 Feature Cards -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <!-- Card 1 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-calendar-week-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Agenda Médica Inteligente</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Gestión multi-terapeuta y multi-sala en tiempo real con sincronización de citas y bloqueo de festivos instantáneo.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-whatsapp text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Notificaciones WhatsApp</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Confirmaciones automáticas y recordatorios de cita directos al móvil del paciente con tasas de apertura del 98%.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-file-earmark-medical-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Historial Clínico Seguro</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Fichas de paciente completas, anamnesis, informes clínicos, evolución de sesiones y adjuntos radiológicos cifrados.
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-receipt-cutoff text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Facturación Verifactu & AEAT</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Cumple al 100% la normativa legal española con facturación inalterable, códigos QR y exportación a tu gestor.
                        </p>
                    </div>

                    <!-- Card 5 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-person-badge-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Control Horario & Laboral</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Fichaje digital de terapeutas y personal de recepción, gestión de ausencias, vacaciones y cálculo de comisiones.
                        </p>
                    </div>

                    <!-- Card 6 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-bar-chart-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Analítica & Rendimiento</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Panel directivo con métricas de facturación, tasa de retención de pacientes y rentabilidad por tratamiento.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 4. SECTION: TRUSTED PARTNER (DARK ACCORDION & METRICS) -->
    <!-- ========================================================================= -->
    <section id="caracteristicas" class="py-20 lg:py-28 bg-slate-900 text-white relative overflow-hidden" x-data="{ activeTab: 1 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left: Professional Sitting with Tablet Image -->
                <div class="lg:col-span-5">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-800 aspect-[4/5] bg-slate-800 relative group">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1000&auto=format&fit=crop"
                            alt="Especialista sanitaria gestionando clínica con tablet"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                </div>

                <!-- Right: Accordion & Description -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-primary-500/20 text-indigo-300 border border-indigo-400/30">
                        EFICIENCIA PROBADA
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                        Tu socio de confianza para la transformación digital sanitaria.
                    </h2>

                    <p class="text-slate-300 text-base leading-relaxed">
                        Acompañamos a clínicas en toda España a modernizar su operativa diaria, garantizando seguridad absoluta en el tratamiento de historiales y automatizando la relación con sus pacientes.
                    </p>

                    <!-- Interactive Accordion List -->
                    <div class="space-y-3 pt-2">
                        <!-- Item 1 -->
                        <div class="border border-slate-800 bg-slate-850 rounded-xl overflow-hidden bg-slate-800/80">
                            <button @click="activeTab = (activeTab === 1 ? null : 1)" type="button" class="w-full px-5 py-4 flex items-center justify-between text-left font-semibold text-white hover:text-indigo-400 transition-colors">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-shield-lock-fill text-indigo-400"></i>
                                    Seguridad y Protección de Datos Sanitarios (RGPD)
                                </span>
                                <i class="bi" :class="activeTab === 1 ? 'bi-chevron-up text-indigo-400' : 'bi-chevron-down text-slate-400'"></i>
                            </button>
                            <div x-show="activeTab === 1" x-collapse class="px-5 pb-4 text-sm text-slate-300 border-t border-slate-700/60 pt-3">
                                Cifrado de extremo a extremo, servidores ubicados en la Unión Europea y auditoría de accesos conforme a la legislación médica vigente.
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="border border-slate-800 bg-slate-850 rounded-xl overflow-hidden bg-slate-800/80">
                            <button @click="activeTab = (activeTab === 2 ? null : 2)" type="button" class="w-full px-5 py-4 flex items-center justify-between text-left font-semibold text-white hover:text-indigo-400 transition-colors">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-phone-fill text-indigo-400"></i>
                                    Portal del Paciente y Reserva Directa 24/7
                                </span>
                                <i class="bi" :class="activeTab === 2 ? 'bi-chevron-up text-indigo-400' : 'bi-chevron-down text-slate-400'"></i>
                            </button>
                            <div x-show="activeTab === 2" x-collapse class="px-5 pb-4 text-sm text-slate-300 border-t border-slate-700/60 pt-3">
                                Permite que tus pacientes agenden sus tratamientos desde cualquier dispositivo sin llamadas ni intermediarios, sincronizado al instante con tu agenda.
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="border border-slate-800 bg-slate-850 rounded-xl overflow-hidden bg-slate-800/80">
                            <button @click="activeTab = (activeTab === 3 ? null : 3)" type="button" class="w-full px-5 py-4 flex items-center justify-between text-left font-semibold text-white hover:text-indigo-400 transition-colors">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-headset text-indigo-400"></i>
                                    Migración Gratuita y Soporte Humano Dedicado
                                </span>
                                <i class="bi" :class="activeTab === 3 ? 'bi-chevron-up text-indigo-400' : 'bi-chevron-down text-slate-400'"></i>
                            </button>
                            <div x-show="activeTab === 3" x-collapse class="px-5 pb-4 text-sm text-slate-300 border-t border-slate-700/60 pt-3">
                                Nuestro equipo importa tus pacientes y citas de tu software actual sin interrupciones y forma a tu equipo de forma personalizada.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-16 lg:pt-24 mt-12 border-t border-slate-800">
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">250+</div>
                    <div class="text-sm font-medium text-slate-400">Clínicas Activas</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">150k+</div>
                    <div class="text-sm font-medium text-slate-400">Citas Gestionadas</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">20+</div>
                    <div class="text-sm font-medium text-slate-400">Herramientas Conectadas</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">98%</div>
                    <div class="text-sm font-medium text-slate-400">Satisfacción de Usuarios</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 5. SECTION: SEE HOW OUR TEAM BOOSTS RESULTS (STRATEGY & TECH) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header Centered -->
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                    MÉTODO TERVION
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Descubre cómo potenciamos los resultados de tu centro.
                </h2>
                <p class="text-slate-600 text-base">
                    Una metodología clara orientada a maximizar el tiempo dedicado a la consulta médica y reducir a cero la fricción burocrática.
                </p>
            </div>

            <!-- Strategy & Laptop Mockup Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200">
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-primary-700 bg-primary-100">
                        ESTRATEGIA & AUTOMATIZACIÓN
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                        Control total en tiempo real de tu clínica desde cualquier lugar.
                    </h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        Visualiza los ingresos diarios, el estado de las facturas enviadas a Hacienda, los pacientes atendidos y la ocupación de salas con cuadros de mando claros y fáciles de entender.
                    </p>
                    <div class="pt-2">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-6 py-3 rounded-full text-xs font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 transition-all">
                            <span>Solicitar Acceso</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200 aspect-[16/10] bg-slate-900 relative group">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1000&auto=format&fit=crop"
                            alt="Software de gestión clínica y analítica en pantalla"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 6. SECTION: MEET THE TEAM / EXPERTS BEHIND (DARK PALETTE) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header with CTA on right -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                <div class="space-y-4 max-w-xl">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-primary-500/20 text-indigo-300 border border-indigo-400/30">
                        EQUIPO & RESPALDO
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Conoce a los expertos detrás de cada funcionalidad.
                    </h2>
                </div>
                <div>
                    <a href="<?= PROJECT_ROOT ?>/login" class="px-6 py-3 rounded-full text-xs font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 whitespace-nowrap shadow-lg shadow-primary-500/25 transition-all">
                        <span>Ver Todo el Equipo</span>
                        <i class="bi bi-arrow-right font-bold"></i>
                    </a>
                </div>
            </div>

            <!-- Team Grid (3 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Member 1 -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700/60 overflow-hidden team-card group">
                    <div class="aspect-[4/5] overflow-hidden bg-slate-850">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=800&auto=format&fit=crop"
                            alt="Director de Producto Sanitario"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-white">David Ruiz</h3>
                        <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mt-1">Director de Tecnología Clínica</p>
                    </div>
                </div>

                <!-- Member 2 -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700/60 overflow-hidden team-card group">
                    <div class="aspect-[4/5] overflow-hidden bg-slate-850">
                        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=800&auto=format&fit=crop"
                            alt="Especialista en Experiencia Asistencial"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-white">Elena Martínez</h3>
                        <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mt-1">Especialista en Flujos Sanitarios</p>
                    </div>
                </div>

                <!-- Member 3 -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700/60 overflow-hidden team-card group">
                    <div class="aspect-[4/5] overflow-hidden bg-slate-850">
                        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=800&auto=format&fit=crop"
                            alt="Consultor Legal y Verifactu"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-white">Carlos Romero</h3>
                        <p class="text-xs text-indigo-400 uppercase tracking-wider font-semibold mt-1">Responsable Normativo & Verifactu</p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 7. SECTION: PRICING / PLANES Y PRECIOS -->
    <!-- ========================================================================= -->
    <section id="precios" class="py-20 lg:py-28 bg-white relative" x-data="{ billingAnnual: true }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header Centered -->
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-12">
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                    PLANES TRANSPARENTES
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Invierte en el crecimiento de tu centro sin sorpresas.
                </h2>
                <p class="text-slate-600 text-base">
                    Comienza con una prueba de 14 días gratis. Sin costes de instalación ni permanencia.
                </p>

                <!-- Billing Toggle -->
                <div class="pt-4 flex items-center justify-center gap-4">
                    <span class="text-sm font-medium" :class="!billingAnnual ? 'text-slate-900 font-bold' : 'text-slate-500'">Facturación Mensual</span>
                    <button @click="billingAnnual = !billingAnnual"
                        type="button"
                        class="relative inline-flex h-7 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-slate-800"
                        :class="billingAnnual ? 'bg-primary-500' : 'bg-slate-350 bg-slate-400'"
                        role="switch"
                        :aria-checked="billingAnnual">
                        <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-200 ease-in-out"
                            :class="billingAnnual ? 'translate-x-7' : 'translate-x-0'"></span>
                    </button>
                    <span class="text-sm font-medium flex items-center gap-2" :class="billingAnnual ? 'text-slate-900 font-bold' : 'text-slate-500'">
                        Facturación Anual
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-indigo-100 text-indigo-800 uppercase tracking-wide">2 meses gratis</span>
                    </span>
                </div>
            </div>

            <!-- Pricing Cards Grid (3 Columns) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch pt-4">

                <!-- Plan 1: Profesional Autónomo -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Individual</h3>
                            <p class="text-xs text-slate-500 mt-1">Ideal para profesionales sanitarios independientes.</p>
                        </div>
                        <div class="flex items-baseline gap-1 pt-2">
                            <span class="text-4xl font-extrabold text-slate-900" x-text="billingAnnual ? '29€' : '35€'">29€</span>
                            <span class="text-xs text-slate-500 font-medium">/ mes + IVA</span>
                        </div>
                        <p class="text-xs text-indigo-600 font-semibold" x-show="billingAnnual">Facturado anualmente (348€/año)</p>

                        <ul class="space-y-3 text-xs text-slate-600 pt-4 border-t border-slate-100">
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>1 Usuario / Profesional sanitario</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Agenda online & Citas ilimitadas</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Historias clínicas y consentimientos</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Facturación reglamentaria Verifactu</span>
                            </li>
                            <li class="flex items-center gap-2.5 text-slate-400">
                                <i class="bi bi-dash-circle text-slate-300 text-sm"></i>
                                <span>Recordatorios por WhatsApp automáticos</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="<?= PROJECT_ROOT ?>/login" class="w-full block text-center py-3.5 px-6 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors">
                            Empezar Gratis 14 Días
                        </a>
                    </div>
                </div>

                <!-- Plan 2: Clínica / Centro (Destacado) -->
                <div class="bg-slate-900 rounded-3xl p-8 border-2 border-primary-500 text-white shadow-2xl relative flex flex-col justify-between space-y-6 transform lg:-translate-y-2">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-widest bg-primary-500 text-white shadow-md">
                        MÁS POPULAR
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-white">Clínica Estándar</h3>
                            <p class="text-xs text-slate-300 mt-1">Para clínicas con equipo multidisciplinar y varias salas.</p>
                        </div>
                        <div class="flex items-baseline gap-1 pt-2">
                            <span class="text-4xl font-extrabold text-white" x-text="billingAnnual ? '59€' : '69€'">59€</span>
                            <span class="text-xs text-slate-300 font-medium">/ mes + IVA</span>
                        </div>
                        <p class="text-xs text-indigo-400 font-semibold" x-show="billingAnnual">Facturado anualmente (708€/año)</p>

                        <ul class="space-y-3 text-xs text-slate-200 pt-4 border-t border-slate-800">
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-indigo-400 text-sm"></i>
                                <span>Hasta 5 Terapeutas / Usuarios</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-indigo-400 text-sm"></i>
                                <span>Recordatorios de Cita por WhatsApp</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-indigo-400 text-sm"></i>
                                <span>Gestión de salas y festivos en tiempo real</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-indigo-400 text-sm"></i>
                                <span>Control horario y fichaje de personal</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-indigo-400 text-sm"></i>
                                <span>Soporte prioritario y migración de datos</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="<?= PROJECT_ROOT ?>/login" class="w-full block text-center py-3.5 px-6 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-500 hover:bg-primary-600 text-white shadow-lg shadow-primary-500/30 transition-all">
                            Empezar Prueba Gratuita
                        </a>
                    </div>
                </div>

                <!-- Plan 3: Centro Avanzado / Policlínica -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Policlínica & Pro</h3>
                            <p class="text-xs text-slate-500 mt-1">Para centros grandes con alta afluencia y múltiples sedes.</p>
                        </div>
                        <div class="flex items-baseline gap-1 pt-2">
                            <span class="text-4xl font-extrabold text-slate-900" x-text="billingAnnual ? '99€' : '119€'">99€</span>
                            <span class="text-xs text-slate-500 font-medium">/ mes + IVA</span>
                        </div>
                        <p class="text-xs text-indigo-600 font-semibold" x-show="billingAnnual">Facturado anualmente (1.188€/año)</p>

                        <ul class="space-y-3 text-xs text-slate-600 pt-4 border-t border-slate-100">
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Usuarios y terapeutas ilimitados</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Multi-sede y centros agrupados</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>WhatsApp corporativo personalizado</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Analítica avanzada de rentabilidad y comisiones</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="bi bi-check-circle-fill text-primary-500 text-sm"></i>
                                <span>Gestor de cuenta y onboarding 1 a 1</span>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <a href="<?= PROJECT_ROOT ?>/login" class="w-full block text-center py-3.5 px-6 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 hover:bg-slate-200 text-slate-800 transition-colors">
                            Contactar con Asesor
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 8. SECTION: TESTIMONIALS (COMMUNITY OF USERS) -->
    <!-- ========================================================================= -->
    <section id="testimonios" class="py-20 lg:py-28 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header Centered -->
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                    TESTIMONIOS REALES
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Confiado por una comunidad de +6,000 profesionales.
                </h2>
                <p class="text-slate-600 text-base">
                    Descubre cómo clínicas de fisioterapia, centros médicos y especialistas han multiplicado su rentabilidad y fidelizado a sus pacientes con Tervion.
                </p>
            </div>

            <!-- Testimonial Cards Grid (3 Columns) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="flex text-amber-400 gap-1 text-sm">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed italic">
                            "Tervion transformó la recepción de nuestra clínica. El absentismo de citas por olvido bajó un 85% gracias a las confirmaciones automáticas por WhatsApp."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop"
                            alt="Dra. Laura Morales"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Dra. Laura Morales</h4>
                            <p class="text-xs text-slate-500">Directora Médica — Clínica Fisiovida</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="flex text-amber-400 gap-1 text-sm">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed italic">
                            "La tranquilidad de estar 100% al día con Verifactu y no tener que preocuparme por cambios de la AEAT no tiene precio. Además, el soporte es rápido y muy profesional."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop"
                            alt="Marc Sender"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Marc Sender</h4>
                            <p class="text-xs text-slate-500">Gerente — Centro Odontológico Sender</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="flex text-amber-400 gap-1 text-sm">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed italic">
                            "Manejar a nuestros 8 terapeutas, los fichajes diarios y las historias clínicas desde el iPad es comodísimo. El mejor cambio que hemos hecho en el centro."
                        </p>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                        <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=200&auto=format&fit=crop"
                            alt="Sara Domínguez"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">Sara Domínguez</h4>
                            <p class="text-xs text-slate-500">Coordinadora — Terapia & Salud Integral</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. FINAL CTA BANNER & FOOTER (SLATE & PRIMARY BRAND) -->
    <!-- ========================================================================= -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Pre-Footer CTA Bar -->
            <div class="bg-slate-800 rounded-3xl p-8 sm:p-12 border border-slate-700/60 mb-16 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-2 max-w-xl text-center md:text-left">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">¿Listo para impulsar tu clínica?</h3>
                    <p class="text-sm text-slate-300">Empieza hoy mismo y descubre por qué cientos de profesionales confían en Tervion.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?= PROJECT_ROOT ?>/login" class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-500 hover:bg-primary-600 text-white shadow-xl shadow-primary-500/25 transition-all">
                        Acceder a la plataforma
                    </a>
                </div>
            </div>

            <!-- Footer Columns -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                <!-- Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png" alt="Tervion" class="h-8 w-auto object-contain" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-claro-sin-fondo.png';">
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Plataforma SaaS integral de automatización y gestión empresarial para clínicas y profesionales sanitarios en España.
                    </p>
                </div>

                <!-- Quick Navigation -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Navegación</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="#inicio" class="hover:text-indigo-400 transition-colors">Inicio</a></li>
                        <li><a href="#soluciones" class="hover:text-indigo-400 transition-colors">Soluciones</a></li>
                        <li><a href="#ventajas" class="hover:text-indigo-400 transition-colors">Ventajas</a></li>
                        <li><a href="#caracteristicas" class="hover:text-indigo-400 transition-colors">Funcionalidades</a></li>
                        <li><a href="#testimonios" class="hover:text-indigo-400 transition-colors">Testimonios</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Legal & Seguridad</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="<?= PROJECT_ROOT ?>/privacidad" class="hover:text-indigo-400 transition-colors">Política de Privacidad</a></li>
                        <li><a href="<?= PROJECT_ROOT ?>/terminos" class="hover:text-indigo-400 transition-colors">Términos de Servicio</a></li>
                        <li><a href="<?= PROJECT_ROOT ?>/cookies" class="hover:text-indigo-400 transition-colors">Política de Cookies</a></li>
                        <!-- <li><span class="text-indigo-400 flex items-center gap-1.5"><i class="bi bi-shield-lock-fill"></i> Cumplimiento RGPD</span></li> -->
                    </ul>
                </div>

                <!-- Contact & Support -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Contacto</h4>
                    <p class="text-xs text-slate-400">
                        Soporte técnico y comercial disponible de Lunes a Viernes de 9:00 a 19:00.
                    </p>
                    <div class="pt-2 text-xs text-slate-300 space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-envelope-fill text-indigo-400"></i>
                            <span>soporte@tervion-app.com</span>
                        </div>
                    </div>
                    <div class="pt-2 text-xs text-slate-300 space-y-1">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-telephone-fill text-indigo-400"></i>
                            <span>+34 600 000 000</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
                <p>&copy; 2026 Tervion. Todos los derechos reservados.</p>
                <div class="flex items-center gap-4">
                    <a href="<?= PROJECT_ROOT ?>/privacidad" class="hover:text-slate-300">Privacidad</a>
                    <span>•</span>
                    <a href="<?= PROJECT_ROOT ?>/terminos" class="hover:text-slate-300">Términos</a>
                    <span>•</span>
                    <a href="<?= PROJECT_ROOT ?>/cookies" class="hover:text-slate-300">Cookies</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- Botón Volver Arriba (Sticky / Fixed en la esquina inferior derecha) -->
    <div x-data="{ showScrollTop: false }"
        @scroll.window="showScrollTop = (window.pageYOffset > 400)"
        class="fixed bottom-6 right-6 z-50 pointer-events-none">
        <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            x-show="showScrollTop"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-90"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-90"
            type="button"
            class="pointer-events-auto w-12 h-12 rounded-full bg-primary-500 hover:bg-primary-600 text-white shadow-xl shadow-primary-500/30 flex items-center justify-center transition-all transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-slate-900"
            aria-label="Volver arriba">
            <i class="bi bi-chevron-up text-lg font-bold"></i>
        </button>
    </div>

</body>

</html>