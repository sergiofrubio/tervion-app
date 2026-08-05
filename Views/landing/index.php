<!DOCTYPE html>
<html lang="es" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- SEO Meta Tags -->
    <title>Velion — Tu clínica, tu gestión, tu libertad</title>
    <meta name="description" content="Velion es el ERP y software de gestión en la nube para clínicas de fisioterapia y centros de salud. Automatiza tus citas, cobros con Redsys y cumple con la normativa Verifactu de la AEAT.">
    <meta name="keywords" content="ERP clinicas, software fisioterapia, gestion de citas medicas, Verifactu AEAT, pasarela Redsys, clinica medica, historiales medicos, nominas fisioterapeutas">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Velion ERP">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Velion — Tu clínica, tu gestión, tu libertad">
    <meta property="og:description" content="El software de gestión definitivo para clínicas. Controla tus citas, automatiza la facturación con Verifactu y recibe cobros online de forma segura.">
    <meta property="og:image" content="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg">
    <meta property="og:url" content="https://velion-app.com/landing">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Velion — Tu clínica, tu gestión, tu libertad">
    <meta name="twitter:description" content="Software de gestión ERP y facturación Verifactu para clínicas de fisioterapia y salud.">
    <meta name="twitter:image" content="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg">

    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                            50: '#f0f5ff',
                            100: '#d9e6ff',
                            200: '#bacfff',
                            300: '#91b1ff',
                            400: '#5e88ff',
                            500: '#0052d9', // Azul Real (del logo)
                            600: '#0040b3',
                            700: '#00308c',
                            800: '#002266',
                            900: '#001640',
                        },
                        secondary: {
                            50: '#ecf9ff',
                            100: '#d9f1ff',
                            200: '#bde7ff',
                            300: '#8fd7ff',
                            400: '#4cbaff',
                            500: '#009eff', // Cyan (del logo)
                            600: '#007ee6',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="font-sans antialiased text-gray-900 bg-white">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <a href="<?= PROJECT_ROOT ?>/landing" class="flex items-center">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg" alt="Velion Logo" class="h-9 object-contain">
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#como-funciona" class="hover:text-primary-500 transition-colors">Cómo funciona</a>
                <a href="#caracteristicas" class="hover:text-primary-500 transition-colors">Características</a>
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
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-light text-primary-500 leading-tight tracking-tight">
                    tu gestión digital,<br>
                    tu clínica,<br>
                    <span class="font-normal text-primary-600">tu libertad</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light">
                    Crea, gestiona y desarrolla tu clínica médica o de fisioterapia en la nube de manera fácil y segura. Automatiza citas, nóminas y remisión directa de facturas a la AEAT.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#como-funciona" class="rounded-full bg-gray-100 hover:bg-gray-200 px-8 py-3.5 text-sm font-semibold text-gray-800 transition-all">
                        Cómo funciona
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="rounded-full bg-primary-500 hover:bg-primary-600 px-8 py-3.5 text-sm font-semibold text-white shadow-md transition-all hover:scale-105">
                        Empezar gratis
                    </a>
                </div>
                <div class="pt-4 text-xs text-gray-400 font-medium tracking-wide uppercase">
                    🔒 Conforme con la normativa Verifactu de la AEAT
                </div>
            </div>

            <!-- Hero Right (Inspirado en la imagen de Estonia) -->
            <div class="relative flex justify-center lg:justify-end">
                <div class="relative w-full max-w-lg aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-tr from-primary-900 to-indigo-900 flex items-center justify-center p-8 border border-gray-100">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(0,158,255,0.15),transparent)]"></div>
                    <!-- Mockup de Tarjeta o Dashboard -->
                    <div class="relative w-full bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/10 shadow-lg text-white space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs uppercase tracking-widest opacity-60 font-semibold">Velion Health Card</span>
                            <i class="bi bi-cpu text-2xl text-secondary-300"></i>
                        </div>
                        <div class="py-4">
                            <div class="text-2xl font-bold tracking-wider">1024 8872 9012 3345</div>
                            <div class="text-xs opacity-60 mt-1">Clínica de Fisioterapia Autorizada</div>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <div>
                                <div class="opacity-50 text-[10px] uppercase">Titular</div>
                                <div class="font-medium text-sm">Dr. Alejandro Ruiz</div>
                            </div>
                            <div class="text-right">
                                <div class="opacity-50 text-[10px] uppercase">Estado</div>
                                <div class="font-medium text-sm text-green-400">● Conectado AEAT</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid Section -->
    <section id="caracteristicas" class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Agenda Inteligente</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Programa citas fácilmente, gestiona los horarios del personal y envía confirmaciones automáticas por correo electrónico a tus pacientes.
                        </p>
                    </div>
                    <a href="#como-funciona" class="inline-flex items-center text-xs font-semibold text-primary-500 hover:text-primary-600 mt-6 group-hover:translate-x-1 transition-transform">
                        Descubre cómo funciona <i class="bi bi-chevron-right ml-1 text-[10px]"></i>
                    </a>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Plena Seguridad Fiscal</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Generación de facturas con firma digital y encadenamiento criptográfico SHA-256 acorde a la normativa Verifactu de la AEAT.
                        </p>
                    </div>
                    <a href="#como-funciona" class="inline-flex items-center text-xs font-semibold text-primary-500 hover:text-primary-600 mt-6 group-hover:translate-x-1 transition-transform">
                        Descubre el cumplimiento <i class="bi bi-chevron-right ml-1 text-[10px]"></i>
                    </a>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Pasarela Redsys</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Cobros directos con tarjeta desde el panel de pacientes de forma automatizada mediante pasarelas del TPV seguro de Redsys.
                        </p>
                    </div>
                    <a href="#como-funciona" class="inline-flex items-center text-xs font-semibold text-primary-500 hover:text-primary-600 mt-6 group-hover:translate-x-1 transition-transform">
                        Ver pasarela de pagos <i class="bi bi-chevron-right ml-1 text-[10px]"></i>
                    </a>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between group hover:shadow-md transition-shadow">
                    <div class="space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center text-xl">
                            <i class="bi bi-file-spreadsheet"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Nóminas y Gastos</h3>
                        <p class="text-sm text-gray-500 leading-relaxed font-light">
                            Generación mensual automática de nóminas los días 25, control de costes de ausencias y carga rápida de gastos trimestrales.
                        </p>
                    </div>
                    <a href="#como-funciona" class="inline-flex items-center text-xs font-semibold text-primary-500 hover:text-primary-600 mt-6 group-hover:translate-x-1 transition-transform">
                        Ver contabilidad <i class="bi bi-chevron-right ml-1 text-[10px]"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- Detailed Explanation Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6">
                <h2 class="text-3xl font-light text-primary-500">¿Qué es el ecosistema Velion?</h2>
                <div class="text-gray-600 space-y-4 font-light leading-relaxed">
                    <p>
                        Velion es más que una simple agenda médica. Es una solución ERP empresarial diseñada específicamente para clínicas de salud y bienestar de cualquier tamaño que buscan digitalizar su operativa diaria sin lidiar con software obsoleto.
                    </p>
                    <p>
                        La plataforma centraliza la gestión de historiales de pacientes, citas y recordatorios inteligentes, facturación instantánea con remisión a Hacienda de forma transparente, y administración de contabilidad con conciliación de cuentas.
                    </p>
                    <p>
                        Conecta a fisioterapeutas, médicos, secretarios y pacientes bajo un mismo ecosistema accesible desde cualquier dispositivo, garantizando rapidez en tu día a día y total cumplimiento fiscal.
                    </p>
                </div>
            </div>
            <div class="flex justify-center">
                <div class="w-full max-w-md bg-slate-50 p-8 rounded-3xl border border-gray-100 space-y-6">
                    <h4 class="text-sm font-semibold uppercase tracking-wider text-primary-500">Cumplimiento Legal Destacado</h4>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <i class="bi bi-qr-code text-3xl text-primary-500"></i>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Códigos QR AEAT</h5>
                                <p class="text-xs text-gray-500 mt-1">Cada factura generada contiene un código QR y una URL de remisión que permite verificar de inmediato su validez ante Hacienda.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <i class="bi bi-link-45deg text-3xl text-primary-500"></i>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Encadenamiento Inmutable</h5>
                                <p class="text-xs text-gray-500 mt-1">Los registros se entrelazan mediante algoritmos criptográficos inalterables que impiden manipulaciones o borrados ilegales.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cómo Funciona Section (Pasos) -->
    <section id="como-funciona" class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-3xl font-light text-primary-500 text-center mb-16">Cómo funciona</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-80 relative overflow-hidden group">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                            Paso 1: Setup inicial
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">Configura tu clínica</h3>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">
                            Introduce los datos de tu clínica, define los profesionales sanitarios, asigna sus horarios laborales y da de alta los bonos de sesiones activos.
                        </p>
                    </div>
                    <i class="bi bi-arrow-right text-gray-400 group-hover:text-primary-500 transition-colors text-xl"></i>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-80 relative overflow-hidden group">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                            Paso 2: Operación diaria
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">Agenda, asiste y cobra</h3>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">
                            Los pacientes agendan citas desde su portal. Reciben recordatorios por email de forma automatizada y compran bonos pagando mediante tarjeta en Redsys.
                        </p>
                    </div>
                    <i class="bi bi-arrow-right text-gray-400 group-hover:text-primary-500 transition-colors text-xl"></i>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between h-80 relative overflow-hidden group">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold px-3 py-1 rounded-full mb-6">
                            Paso 3: Automatización legal
                        </span>
                        <h3 class="text-xl font-bold text-gray-900">Cumplimiento transparente</h3>
                        <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">
                            Las facturas se envían automáticamente a la AEAT a través de Verifactu. Los días 25 de cada mes se generan las nóminas del personal médico sin retrasos.
                        </p>
                    </div>
                    <i class="bi bi-arrow-right text-gray-400 group-hover:text-primary-500 transition-colors text-xl"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="text-5xl font-extralight text-primary-500">142,300+</div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Citas Programadas</p>
            </div>
            <div>
                <div class="text-5xl font-extralight text-primary-500">43,000+</div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Facturas Verificadas AEAT</p>
            </div>
            <div>
                <div class="text-5xl font-extralight text-primary-500">99.98%</div>
                <p class="text-xs uppercase tracking-wider text-gray-400 font-bold mt-2">Disponibilidad del Servidor</p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="precios" class="py-20 bg-gray-50 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-16">
                <h2 class="text-3xl font-light text-primary-500">Planes a tu medida</h2>
                <p class="text-sm text-gray-500 mt-3 leading-relaxed font-light">Elige el plan ideal para automatizar y proteger fiscalmente la operativa de tu centro de salud.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                
                <!-- Plan 1 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between space-y-8">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Plan Profesional</h3>
                            <p class="text-xs text-gray-400 font-light mt-1">Ideal para profesionales sanitarios independientes</p>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-4xl font-extrabold text-gray-900">39€</span>
                            <span class="text-sm text-gray-500 ml-1">/ mes</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-gray-600 font-light">
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Agenda médica completa
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Historiales médicos ilimitados
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Integración Verifactu (AEAT)
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Notificaciones automáticas por email
                            </li>
                            <li class="flex items-center gap-2 text-gray-300">
                                <i class="bi bi-x-lg text-[10px]"></i> Soporte multi-profesional y nóminas
                            </li>
                        </ul>
                    </div>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="block w-full text-center rounded-full bg-gray-100 hover:bg-gray-200 py-3 text-sm font-semibold text-gray-800 transition-all">
                        Empezar ahora
                    </a>
                </div>

                <!-- Plan 2 -->
                <div class="bg-white p-10 rounded-3xl shadow-md border-2 border-primary-500 flex flex-col justify-between space-y-8 relative">
                    <div class="absolute top-0 right-8 -translate-y-1/2 bg-primary-500 text-white text-[10px] uppercase font-bold tracking-wider px-3.5 py-1.5 rounded-full">
                        Recomendado
                    </div>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Plan Premium</h3>
                            <p class="text-xs text-gray-400 font-light mt-1">Diseñado para clínicas y centros de especialidades</p>
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-4xl font-extrabold text-gray-900">69€</span>
                            <span class="text-sm text-gray-500 ml-1">/ mes</span>
                        </div>
                        <ul class="space-y-3.5 text-sm text-gray-600 font-light">
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Todo lo incluido en el Plan Profesional
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Roles múltiples (Sanitarios y Secretarios)
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Módulo de Nóminas mensual automático
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Pasarela de pagos online con Redsys
                            </li>
                            <li class="flex items-center gap-2">
                                <i class="bi bi-check2 text-primary-500"></i> Importación de movimientos bancarios
                            </li>
                        </ul>
                    </div>
                    <a href="<?= PROJECT_ROOT ?>/registro" class="block w-full text-center rounded-full bg-primary-500 hover:bg-primary-600 py-3 text-sm font-semibold text-white shadow-md transition-all">
                        Solicitar ahora
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
                        "La integración con Verifactu nos quitó un gran peso de encima. Ahora facturamos a pacientes con total tranquilidad legal."
                    </p>
                    <div>
                        <h5 class="text-sm font-bold text-gray-900">Mercedes Gil Hernández</h5>
                        <p class="text-xs text-gray-400">Directora de Montessori British, España</p>
                    </div>
                </div>
                <!-- Quote 2 -->
                <div class="space-y-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-8 lg:pt-0 lg:pl-8">
                    <p class="text-lg text-primary-600 font-light leading-relaxed italic">
                        "El sistema de reservas online ha reducido las inasistencias en un 35%. Los recordatorios automáticos por correo son clave."
                    </p>
                    <div>
                        <h5 class="text-sm font-bold text-gray-900">Christelle Sidoine</h5>
                        <p class="text-xs text-gray-400">Fisioterapeuta, Easy Assistant, Sudáfrica</p>
                    </div>
                </div>
                <!-- Quote 3 -->
                <div class="space-y-4 border-t lg:border-t-0 lg:border-l border-gray-100 pt-8 lg:pt-0 lg:pl-8">
                    <p class="text-lg text-primary-600 font-light leading-relaxed italic">
                        "Gestionar las nóminas de mis sanitarios el día 25 y los gastos con importaciones del banco nunca había sido tan ágil."
                    </p>
                    <div>
                        <h5 class="text-sm font-bold text-gray-900">Georg Klausner</h5>
                        <p class="text-xs text-gray-400">Gerente de Clínicas Mansiontech, Austria</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Subscription Section (Inspirado en la de Estonia) -->
    <section class="border-t border-gray-100 bg-gray-50 overflow-hidden">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2">
            
            <!-- Left Banner (Azul real con grilla) -->
            <div class="bg-primary-500 p-12 sm:p-16 lg:p-20 text-white flex flex-col justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_left,rgba(0,158,255,0.25),transparent)]"></div>
                <div class="relative space-y-4">
                    <h2 class="text-3xl sm:text-4xl font-light leading-tight">
                        Suscríbete a la<br>
                        newsletter de<br>
                        Velion ERP
                    </h2>
                    <p class="text-sm opacity-80 leading-relaxed font-light max-w-xs">
                        Recibe consejos sobre optimización de clínicas de fisioterapia, novedades sobre la normativa fiscal Verifactu y guías prácticas de software.
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
                            <option>Fisioterapeuta independiente</option>
                            <option>Director de clínica médica</option>
                            <option>Secretario / Administración</option>
                            <option>Estudiante / Otro</option>
                        </select>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="rounded-full bg-primary-500 hover:bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-md transition-all hover:scale-105">
                            Suscribirse
                        </button>
                    </div>
                </form>
                <p class="text-xs text-gray-400 leading-normal">
                    Puedes darte de baja en cualquier momento de forma gratuita. Cumplimos estrictamente con la RGPD. Lee nuestra <a href="#" class="underline hover:text-primary-500">Política de privacidad</a>.
                </p>
            </div>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-8 text-xs font-medium">
                <a href="#" class="hover:text-white transition-colors">Política de privacidad</a>
                <a href="#" class="hover:text-white transition-colors">Política de cookies</a>
                <a href="#" class="hover:text-white transition-colors">Términos del servicio</a>
                <a href="#" class="hover:text-white transition-colors">Contacto soporte</a>
            </div>
            
            <div class="flex items-center gap-6 text-lg">
                <a href="#" class="hover:text-white transition-colors"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="bi bi-facebook"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="bi bi-instagram"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </footer>

</body>

</html>
