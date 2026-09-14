<!DOCTYPE html>
<html lang="es" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <title>Tervion — Cumple Verifactu y Reduce No-Shows con WhatsApp</title>
    <meta name="description" content="Software de gestión clínica para autónomos y clínicas en España. Facturación Verifactu (RD 1007/2023), recordatorios automáticos por WhatsApp y menos carga administrativa.">
    <meta name="keywords" content="software clinicas, Verifactu RD 1007/2023, recordatorios citas whatsapp, reducir no shows clinica, facturacion sanitaria verifactu, gestion clinica autonomos, historial clinico rgpd">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Tervion">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Tervion — Cumple Verifactu antes de 2026 y deja de perder citas">
    <meta property="og:description" content="Adapta tu consulta al RD 1007/2023, reduce los no-shows con avisos automáticos por WhatsApp y ahorra horas de papeleo cada semana.">
    <meta property="og:image" content="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png">
    <meta property="og:url" content="https://tervion-app.com/">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Tervion — Software Clínico con Verifactu y WhatsApp Anti No-Shows">
    <meta name="twitter:description" content="Facturación adaptada al RD 1007/2023 y recordatorios automáticos por WhatsApp para clínicas y sanitarios en España.">
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
                    <a href="#despliegue" class="hover:text-indigo-400 transition-colors">Autoalojamiento</a>
                </div>

                <!-- Action Button -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="<?= PROJECT_ROOT ?>/login" class="px-6 py-2.5 rounded-full text-xs font-bold tracking-wide uppercase bg-primary-500 hover:bg-primary-600 text-white shadow-lg shadow-primary-500/25 transition-all transform hover:-translate-y-0.5">
                        Iniciar sesión
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
                <a href="#despliegue" @click="mobileMenuOpen = false" class="block text-slate-300 hover:text-indigo-400 font-medium">Autoalojamiento</a>
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
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>100% CÓDIGO ABIERTO · LICENCIA GNU LGPLv3</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold text-white tracking-tight leading-[1.15]">
                        El ERP clínico libre que garantiza el control de tus datos y cumple Verifactu.
                    </h1>

                    <p class="text-slate-300 text-base sm:text-lg leading-relaxed max-w-xl">
                        Software de gestión integral para clínicas y autónomos de la salud. Sin suscripciones forzadas, autoalojable en tu propio servidor y preparado para el RD 1007/2023.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-7 py-3.5 rounded-full text-sm font-bold tracking-wide uppercase bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 shadow-xl shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                            <span>Acceder a la Plataforma</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                        <a href="https://github.com/sergiofrubio/tervion-app" target="_blank" rel="noopener noreferrer" class="px-7 py-3.5 rounded-full text-sm font-semibold text-white bg-white/10 hover:bg-white/20 border border-white/20 transition-all inline-flex items-center gap-2 backdrop-blur-sm">
                            <i class="bi bi-github text-white text-base"></i>
                            <span>Ver Código en GitHub</span>
                        </a>
                    </div>

                    <div class="pt-6 flex items-center gap-6 text-xs text-slate-400 border-t border-white/10">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-shield-check text-indigo-400 text-base"></i>
                            <span>Adaptado a Verifactu & RGPD</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-indigo-400 text-base"></i>
                            <span>Sin tarjeta · Activación inmediata</span>
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
                                alt="Profesional sanitario gestionando agenda y facturación"
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
                                            <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirmación de Cita</div>
                                            <div class="text-base font-extrabold text-slate-900">Menos ausencias imprevistas</div>
                                        </div>
                                    </div>
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 text-[11px] font-bold rounded-full">WhatsApp</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 2. SECTION: VENTAJAS -->
    <!-- ========================================================================= -->
    <section id="ventajas" class="py-20 lg:py-28 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                <!-- Left: Image -->
                <div class="lg:col-span-6 order-2 lg:order-1">
                    <div class="relative max-w-[500px] mx-auto">
                        <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-100 aspect-[4/3] sm:aspect-[1/1] relative group">
                            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop"
                                alt="Gestión clínica simplificada"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-6 -right-6 hidden sm:flex items-center gap-3 bg-slate-900 text-white px-5 py-4 rounded-2xl shadow-xl border border-slate-800">
                            <i class="bi bi-graph-up-arrow text-2xl text-indigo-400"></i>
                            <div>
                                <div class="text-xs text-slate-300 font-medium">Carga administrativa</div>
                                <div class="text-lg font-bold text-white">Horas liberadas cada semana</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Copy -->
                <div class="lg:col-span-6 order-1 lg:order-2 space-y-6">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                        VENTAJAS CLAVE
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Todo lo que exige Hacienda y lo que tu consulta necesita para no perder citas.
                    </h2>

                    <p class="text-slate-600 text-base leading-relaxed">
                        Deja de perder tiempo en llamadas de confirmación y evita el estrés del nuevo reglamento de facturación. Una sola plataforma pensada para la realidad de los sanitarios autónomos y clínicas pequeñas en España.
                    </p>

                    <!-- Feature Check Items -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-check2 font-bold text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Recordatorios automáticos por WhatsApp</h3>
                                <p class="text-sm text-slate-600">Avisos directos al móvil del paciente para confirmar asistencia y recuperar huecos de agenda sin que tengas que llamar.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-check2 font-bold text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Facturación Verifactu (RD 1007/2023)</h3>
                                <p class="text-sm text-slate-600">Registros inalterables, hash encadenado y código QR exigidos por la AEAT. Listo desde el primer día.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="bi bi-check2 font-bold text-base"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Historias clínicas y consentimientos RGPD</h3>
                                <p class="text-sm text-slate-600">Notas de evolución, plantillas por especialidad y firma digital de consentimientos con almacenamiento cifrado en la UE.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-7 py-3.5 rounded-full text-sm font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 shadow-md transition-all">
                            <span>Empezar prueba gratuita</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 3. SECTION: SOLUCIONES / FUNCIONALIDADES -->
    <!-- ========================================================================= -->
    <section id="soluciones" class="py-20 lg:py-28 bg-slate-50 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-start">

                <!-- Left Title & Intro -->
                <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                        HERRAMIENTAS PARA TU CONSULTA
                    </div>

                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        Funcionalidades pensadas para la práctica clínica real.
                    </h2>

                    <p class="text-slate-600 text-base leading-relaxed">
                        Desarrollado para fisioterapeutas, psicólogos, dentistas y clínicas de 1 a 10 profesionales que necesitan cumplir la ley y recuperar tiempo de consulta.
                    </p>

                    <div class="pt-2">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-7 py-3.5 rounded-full text-sm font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 transition-all">
                            <span>Probar todas las funciones</span>
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
                        <h3 class="text-lg font-bold text-slate-900">Agenda y horarios</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Organización por profesionales y gabinetes en tiempo real, con control de disponibilidad y prevención de solapamientos.
                        </p>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-whatsapp text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Avisos y recordatorios WhatsApp</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Notificaciones automáticas antes de la cita para que el paciente confirme o avise. Menos huecos vacíos sin llamadas manuales.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-file-earmark-medical-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Historial clínico cifrado</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Fichas de pacientes, antecedentes, evolución de sesiones y archivos adjuntos con almacenamiento seguro conforme al RGPD.
                        </p>
                    </div>

                    <!-- Card 4 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-receipt-cutoff text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Facturación Verifactu</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Emisión de facturas con registros inalterables y códigos QR según el RD 1007/2023, listas para la AEAT.
                        </p>
                    </div>

                    <!-- Card 5 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-person-badge-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Control horario y personal</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Registro de jornada de sanitarios y recepción, control de ausencias y asignación clara de turnos.
                        </p>
                    </div>

                    <!-- Card 6 -->
                    <div class="bg-white p-7 rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md service-card space-y-4 hover:border-primary-200">
                        <div class="w-12 h-12 rounded-full bg-primary-500 text-white flex items-center justify-center shadow-md">
                            <i class="bi bi-bar-chart-fill text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Informes de ingresos y asistencia</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Resúmenes automáticos de facturación, tasa de no-shows y datos listos para tu asesoría.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 4. SECTION: CUMPLIMIENTO Y CONTROL -->
    <!-- ========================================================================= -->
    <section id="caracteristicas" class="py-20 lg:py-28 bg-slate-900 text-white relative overflow-hidden" x-data="{ activeTab: 1 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                <!-- Left: Image -->
                <div class="lg:col-span-5">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-slate-800 aspect-[4/5] bg-slate-800 relative group">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=1000&auto=format&fit=crop"
                            alt="Profesional sanitario usando la plataforma"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                </div>

                <!-- Right: Accordion -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-primary-500/20 text-indigo-300 border border-indigo-400/30">
                        CUMPLIMIENTO Y CONTROL
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight">
                        Seguridad jurídica y menos ausencias desde el primer día.
                    </h2>

                    <p class="text-slate-300 text-base leading-relaxed">
                        Prepara tu consulta para las exigencias de Hacienda sin cambiar tu forma de atender pacientes. Cumplimiento nativo y menos fricción en recepción.
                    </p>

                    <!-- Interactive Accordion List -->
                    <div class="space-y-3 pt-2">
                        <!-- Item 1 -->
                        <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-800/80">
                            <button @click="activeTab = (activeTab === 1 ? null : 1)" type="button" class="w-full px-5 py-4 flex items-center justify-between text-left font-semibold text-white hover:text-indigo-400 transition-colors">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-shield-lock-fill text-indigo-400"></i>
                                    Protección de datos sanitarios y RGPD
                                </span>
                                <i class="bi" :class="activeTab === 1 ? 'bi-chevron-up text-indigo-400' : 'bi-chevron-down text-slate-400'"></i>
                            </button>
                            <div x-show="activeTab === 1" x-collapse class="px-5 pb-4 text-sm text-slate-300 border-t border-slate-700/60 pt-3">
                                Almacenamiento cifrado en servidores europeos, trazabilidad de accesos y firma de consentimientos informados conforme a la normativa médica.
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-800/80">
                            <button @click="activeTab = (activeTab === 2 ? null : 2)" type="button" class="w-full px-5 py-4 flex items-center justify-between text-left font-semibold text-white hover:text-indigo-400 transition-colors">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-phone-fill text-indigo-400"></i>
                                    Confirmación automática de citas por WhatsApp
                                </span>
                                <i class="bi" :class="activeTab === 2 ? 'bi-chevron-up text-indigo-400' : 'bi-chevron-down text-slate-400'"></i>
                            </button>
                            <div x-show="activeTab === 2" x-collapse class="px-5 pb-4 text-sm text-slate-300 border-t border-slate-700/60 pt-3">
                                Notificaciones previas a la cita para que los pacientes confirmen o avisen con antelación, protegiendo las horas de tus profesionales.
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="border border-slate-800 rounded-xl overflow-hidden bg-slate-800/80">
                            <button @click="activeTab = (activeTab === 3 ? null : 3)" type="button" class="w-full px-5 py-4 flex items-center justify-between text-left font-semibold text-white hover:text-indigo-400 transition-colors">
                                <span class="flex items-center gap-3">
                                    <i class="bi bi-lightning-charge-fill text-indigo-400"></i>
                                    Puesta en marcha rápida
                                </span>
                                <i class="bi" :class="activeTab === 3 ? 'bi-chevron-up text-indigo-400' : 'bi-chevron-down text-slate-400'"></i>
                            </button>
                            <div x-show="activeTab === 3" x-collapse class="px-5 pb-4 text-sm text-slate-300 border-t border-slate-700/60 pt-3">
                                Configuración sencilla para importar pacientes e iniciar la facturación reglamentaria sin detener la actividad de la consulta.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-16 lg:pt-24 mt-12 border-t border-slate-800">
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">100%</div>
                    <div class="text-sm font-medium text-slate-400">Adaptado a Verifactu</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">&lt;24h</div>
                    <div class="text-sm font-medium text-slate-400">Activación de la consulta</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">RD 1007</div>
                    <div class="text-sm font-medium text-slate-400">Cumplimiento tributario</div>
                </div>
                <div class="space-y-1">
                    <div class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight">0€</div>
                    <div class="text-sm font-medium text-slate-400">Coste de instalación</div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 5. SECTION: CONTROL EN TIEMPO REAL -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header Centered -->
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                    GESTIÓN CLÍNICA EFICIENTE
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Recupera el control sobre tu agenda y tu facturación.
                </h2>
                <p class="text-slate-600 text-base">
                    Sustituye hojas de cálculo y recordatorios manuales por una operativa que cumple la legislación española y reduce huecos vacíos.
                </p>
            </div>

            <!-- Strategy & Laptop Mockup Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center bg-slate-50 rounded-3xl p-8 sm:p-12 border border-slate-200">
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-primary-700 bg-primary-100">
                        FACTURACIÓN Y CITAS EN UN SOLO LUGAR
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                        Consulta el estado de tus citas y tus facturas en tiempo real.
                    </h3>
                    <p class="text-slate-600 text-base leading-relaxed">
                        Revisa citas confirmadas, registra pagos, genera facturas con registro inalterable y exporta la información fiscal a tu asesoría sin duplicar trabajo.
                    </p>
                    <div class="pt-2">
                        <a href="<?= PROJECT_ROOT ?>/login" class="px-6 py-3 rounded-full text-xs font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 transition-all">
                            <span>Probar 14 Días Gratis</span>
                            <i class="bi bi-arrow-right font-bold"></i>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-6">
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-slate-200 aspect-[16/10] bg-slate-900 relative group">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1000&auto=format&fit=crop"
                            alt="Panel de control de gestión clínica"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 6. SECTION: PILARES (sin equipo ficticio) -->
    <!-- ========================================================================= -->
    <section class="py-20 lg:py-28 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16">
                <div class="space-y-4 max-w-xl">
                    <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-semibold bg-primary-500/20 text-indigo-300 border border-indigo-400/30">
                        PILARES DE LA PLATAFORMA
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Tres ejes que resuelven los problemas reales de tu consulta.
                    </h2>
                </div>
                <div>
                    <a href="<?= PROJECT_ROOT ?>/login" class="px-6 py-3 rounded-full text-xs font-bold uppercase tracking-wide bg-primary-500 hover:bg-primary-600 text-white inline-flex items-center gap-2 whitespace-nowrap shadow-lg shadow-primary-500/25 transition-all">
                        <span>Empezar prueba gratuita</span>
                        <i class="bi bi-arrow-right font-bold"></i>
                    </a>
                </div>
            </div>

            <!-- Three pillars (no human faces) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Pillar 1 -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700/60 p-8 team-card">
                    <div class="w-14 h-14 rounded-2xl bg-primary-500/20 flex items-center justify-center mb-6">
                        <i class="bi bi-shield-check text-3xl text-indigo-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Cumplimiento Verifactu</h3>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Normativa RD 1007/2023 y facturación electrónica lista para la AEAT. Sin sorpresas ni adaptaciones de última hora.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700/60 p-8 team-card">
                    <div class="w-14 h-14 rounded-2xl bg-primary-500/20 flex items-center justify-center mb-6">
                        <i class="bi bi-whatsapp text-3xl text-indigo-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Automatización de citas</h3>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Recordatorios por WhatsApp y confirmaciones que reducen no-shows y liberan tiempo de recepción.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-slate-800 rounded-2xl border border-slate-700/60 p-8 team-card">
                    <div class="w-14 h-14 rounded-2xl bg-primary-500/20 flex items-center justify-center mb-6">
                        <i class="bi bi-lock-fill text-3xl text-indigo-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Seguridad y RGPD clínico</h3>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Custodia de datos de salud, cifrado y firma digital de consentimientos conforme a la legislación vigente.
                    </p>
                </div>

            </div>
        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 7. SECTION: DESPLIEGUE Y AUTOALOJAMIENTO OPEN SOURCE -->
    <!-- ========================================================================= -->
    <section id="despliegue" class="py-20 lg:py-28 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header Centered -->
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    LIBERTAD TOTAL · SIN ATADURAS
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    100% Código Abierto. Despliega en tu propia infraestructura.
                </h2>
                <p class="text-slate-600 text-base">
                    Sin costes de licencia por usuario, sin enviar los datos de tus pacientes a la nube de terceros y con la garantía de auditar cada línea de código.
                </p>
            </div>

            <!-- Deployment Cards Grid (3 Columns) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">

                <!-- Columna 1: Despliegue con Docker -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 border border-blue-200 flex items-center justify-center text-blue-600 text-2xl">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Docker & Compose</h3>
                            <p class="text-xs text-slate-500 mt-1">Listo para producción en minutos con un solo comando.</p>
                        </div>
                        <div class="bg-slate-900 text-slate-200 p-4 rounded-xl text-xs font-mono overflow-x-auto space-y-1">
                            <p class="text-slate-500"># Clonar e iniciar</p>
                            <p>git clone https://github.com/sergiofrubio/tervion-app.git</p>
                            <p>cd tervion-app</p>
                            <p>docker compose up -d</p>
                        </div>
                        <ul class="space-y-2 text-xs text-slate-600 pt-2">
                            <li class="flex items-center gap-2"><i class="bi bi-check-lg text-emerald-600"></i> MariaDB, PHP-FPM y Caddy Web Server</li>
                            <li class="flex items-center gap-2"><i class="bi bi-check-lg text-emerald-600"></i> Certificados SSL HTTPS automáticos</li>
                        </ul>
                    </div>
                </div>

                <!-- Columna 2: Soberanía de Datos y RGPD (Destacada) -->
                <div class="bg-slate-900 rounded-3xl p-8 border-2 border-primary-500 text-white shadow-2xl flex flex-col justify-between space-y-6 transform lg:-translate-y-2">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-primary-500/20 border border-primary-400/30 flex items-center justify-center text-indigo-300 text-2xl">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Privacidad y Ley</span>
                            <h3 class="text-xl font-bold text-white mt-1">Soberanía Sanitaria (RGPD)</h3>
                            <p class="text-xs text-slate-300 mt-1">Tus historias clínicas y diagnósticos nunca salen de tu servidor.</p>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-200 pt-4 border-t border-slate-800">
                            <li class="flex items-center gap-2.5"><i class="bi bi-check-circle-fill text-indigo-400"></i> Sin riesgo de filtraciones en nubes centralizadas</li>
                            <li class="flex items-center gap-2.5"><i class="bi bi-check-circle-fill text-indigo-400"></i> Copias de seguridad locales y bajo tu control</li>
                            <li class="flex items-center gap-2.5"><i class="bi bi-check-circle-fill text-indigo-400"></i> Cumplimiento estricto del RGPD sanitario español</li>
                            <li class="flex items-center gap-2.5"><i class="bi bi-check-circle-fill text-indigo-400"></i> Verifactu nativo con encadenamiento criptográfico</li>
                        </ul>
                    </div>
                    <div>
                        <a href="https://github.com/sergiofrubio/tervion-app" target="_blank" rel="noopener noreferrer" class="w-full block text-center py-3.5 px-6 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-500 hover:bg-primary-600 text-white shadow-lg shadow-primary-500/30 transition-all">
                            Ver Documentación en GitHub
                        </a>
                    </div>
                </div>

                <!-- Columna 3: Arquitectura Modular -->
                <div class="bg-slate-50 rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600 text-2xl">
                            <i class="bi bi-puzzle"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-900">Ecosistema Extensible</h3>
                            <p class="text-xs text-slate-500 mt-1">Crea tus propios módulos sin tocar el núcleo libre.</p>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Gracias a la licencia **LGPLv3** y al sistema de hooks del núcleo, tu equipo o terceros pueden desarrollar integraciones propietarias o abiertas en la carpeta <code class="text-[11px] bg-slate-200 px-1 py-0.5 rounded">modules/</code>.
                        </p>
                        <ul class="space-y-2 text-xs text-slate-600 pt-2 border-t border-slate-200/60">
                            <li class="flex items-center gap-2"><i class="bi bi-check-lg text-emerald-600"></i> Rutas y controladores desacoplados</li>
                            <li class="flex items-center gap-2"><i class="bi bi-check-lg text-emerald-600"></i> Eventos y filtros en tiempo de ejecución</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- ========================================================================= -->
    <!-- 8. SECTION: LO QUE VALORAN LOS USUARIOS (sin caras inventadas) -->
    <!-- ========================================================================= -->
    <section id="testimonios" class="py-20 lg:py-28 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section Header Centered -->
            <div class="text-center max-w-3xl mx-auto space-y-4 mb-16">
                <div class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-primary-50 text-primary-600 border border-primary-100">
                    LO QUE MÁS VALORAN
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Los problemas que resolvemos cada día.
                </h2>
                <p class="text-slate-600 text-base">
                    Profesionales sanitarios destacan la sencillez para adaptarse a Verifactu y la reducción de citas vacías.
                </p>
            </div>

            <!-- Three value cards (no personal photos or invented names) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                            <i class="bi bi-whatsapp text-xl"></i>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed">
                            “Los recordatorios por WhatsApp nos han permitido confirmar citas de forma automática. Se acabaron los huecos imprevistos a mitad de la tarde.”
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-sm font-bold text-slate-900">Fisioterapia</p>
                        <p class="text-xs text-slate-500">Consulta privada</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                            <i class="bi bi-receipt text-xl"></i>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed">
                            “Tener la tranquilidad de que cada factura generada cumple Verifactu sin depender de programas complejos nos ahorra un tiempo enorme cada mes.”
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-sm font-bold text-slate-900">Odontología</p>
                        <p class="text-xs text-slate-500">Centro clínico</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between space-y-6 hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                            <i class="bi bi-clock-history text-xl"></i>
                        </div>
                        <p class="text-slate-700 text-sm leading-relaxed">
                            “Organizar la agenda del equipo y las historias clínicas desde una sola pantalla nos ha permitido dedicar más tiempo a los pacientes y menos al papeleo.”
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-sm font-bold text-slate-900">Psicología y salud</p>
                        <p class="text-xs text-slate-500">Equipo multidisciplinar</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- FOOTER -->
    <!-- ========================================================================= -->
    <footer class="bg-slate-900 text-slate-300 border-t border-slate-800 pt-16 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Pre-Footer CTA Bar -->
            <div class="bg-slate-800 rounded-3xl p-8 sm:p-12 border border-slate-700/60 mb-16 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="space-y-2 max-w-xl text-center md:text-left">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">Adapta tu clínica a Verifactu hoy mismo</h3>
                    <p class="text-sm text-slate-300">Empieza tu prueba gratuita de 14 días y reduce los no-shows de tu consulta sin compromiso.</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="<?= PROJECT_ROOT ?>/login" class="px-8 py-3.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-500 hover:bg-primary-600 text-white shadow-xl shadow-primary-500/25 transition-all">
                        Empezar prueba gratuita
                    </a>
                </div>
            </div>

            <!-- Footer Columns -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                <!-- Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png" alt="Tervion" class="h-8 w-auto object-contain" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-claro-sin-fondo.png';">
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Plataforma de gestión clínica adaptada a Verifactu (RD 1007/2023) y recordatorios automáticos por WhatsApp para profesionales sanitarios en España.
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
                        <li><a href="#precios" class="hover:text-indigo-400 transition-colors">Precios</a></li>
                    </ul>
                </div>

                <!-- Legal Links -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Legal & Seguridad</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="<?= PROJECT_ROOT ?>/privacidad" class="hover:text-indigo-400 transition-colors">Política de Privacidad</a></li>
                        <li><a href="<?= PROJECT_ROOT ?>/terminos" class="hover:text-indigo-400 transition-colors">Términos de Servicio</a></li>
                        <li><a href="<?= PROJECT_ROOT ?>/cookies" class="hover:text-indigo-400 transition-colors">Política de Cookies</a></li>
                    </ul>
                </div>

                <!-- Contact & Support -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Contacto</h4>
                    <p class="text-xs text-slate-400">
                        Atención a clínicas y profesionales de lunes a viernes de 9:00 a 19:00.
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
                <p>Tervion ERP — Software Libre bajo licencia <a href="https://www.gnu.org/licenses/lgpl-3.0.html" target="_blank" rel="noopener noreferrer" class="underline hover:text-slate-300">GNU LGPLv3</a>.</p>
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

    <!-- Botón Volver Arriba -->
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