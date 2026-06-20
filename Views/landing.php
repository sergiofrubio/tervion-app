<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velion - Gestión de Citas Inteligente</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/VELION Logo Rounded.png" type="image/png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdfa',   // Teal 50
                            100: '#ccfbf1',  // Teal 100
                            200: '#99f6e4',  // Teal 200
                            300: '#5eead4',  // Teal 300
                            400: '#2dd4bf',  // Teal 400
                            500: '#14b8a6',  // Teal 500
                            600: '#0d9488',  // Teal 600
                            700: '#0f766e',  // Teal 700
                            800: '#115e59',  // Teal 800
                            900: '#134e4a',  // Teal 900
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            overflow-x: hidden;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
        }
        
        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(13, 148, 136, 0.15), transparent),
                        radial-gradient(circle at bottom left, rgba(6, 182, 212, 0.1), transparent);
        }

        .text-gradient {
            background: linear-gradient(135deg, #fff 0%, #2dd4bf 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-glow:hover {
            box-shadow: 0 0 20px rgba(13, 148, 136, 0.4);
        }

        .blob {
            position: absolute;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, rgba(13, 148, 136, 0.2) 0%, rgba(6, 182, 212, 0.2) 100%);
            filter: blur(80px);
            border-radius: 50%;
            z-index: -1;
        }
    </style>
</head>
<body class="font-sans antialiased">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 px-6 py-4 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass-card px-6 py-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
                    <span class="text-2xl font-bold font-heading">V</span>
                </div>
                <span class="text-2xl font-bold font-heading tracking-tight text-white">Velion</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-gray-300 hover:text-white transition-colors">Funcionalidades</a>
                <a href="#how-it-works" class="text-gray-300 hover:text-white transition-colors">Cómo funciona</a>
                <a href="#pricing" class="text-gray-300 hover:text-white transition-colors">Precios</a>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="<?= PROJECT_ROOT ?>/login" class="text-gray-300 hover:text-white px-4 py-2 transition-colors">Acceder</a>
                <a href="<?= PROJECT_ROOT ?>/login" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all btn-glow">Empezar gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 px-6 hero-gradient overflow-hidden min-h-screen flex items-center">
        <div class="blob top-[-10%] right-[-5%] animate-pulse-slow"></div>
        <div class="blob bottom-[-10%] left-[-5%] animate-pulse-slow" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right" data-aos-duration="1000">
                <span class="inline-block px-4 py-1.5 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-semibold mb-6">
                    ✨ Gestión de citas redefinida
                </span>
                <h1 class="text-5xl md:text-7xl font-bold font-heading mb-6 leading-tight text-gradient">
                    Lleva tu clínica al <br> <span class="text-white">siguiente nivel.</span>
                </h1>
                <p class="text-xl text-gray-400 mb-10 leading-relaxed max-w-lg">
                    Optimiza tu tiempo, mejora la experiencia de tus pacientes y automatiza tu agenda con la plataforma más avanzada del mercado.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?= PROJECT_ROOT ?>/login" class="bg-brand-600 hover:bg-brand-700 text-white px-8 py-4 rounded-2xl font-bold text-lg transition-all btn-glow flex items-center justify-center gap-2">
                        Prueba gratuita 14 días <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#demo" class="glass-card px-8 py-4 rounded-2xl font-bold text-lg hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                        Ver Demo <i class="bi bi-play-circle"></i>
                    </a>
                </div>
                <div class="mt-12 flex items-center gap-4 text-sm text-gray-500">
                    <div class="flex -space-x-3">
                        <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-gray-700 flex items-center justify-center text-[10px]">JD</div>
                        <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-gray-800 flex items-center justify-center text-[10px]">MS</div>
                        <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-brand-600 flex items-center justify-center text-[10px]"><i class="bi bi-plus"></i></div>
                    </div>
                    <span>+500 profesionales ya confían en Velion</span>
                </div>
            </div>
            
            <div class="relative" data-aos="fade-left" data-aos-duration="1000">
                <div class="relative z-10 animate-float">
                    <img src="<?= PROJECT_ROOT ?>/public/custom/img/landing/dashboard.png" alt="Velion Dashboard" class="rounded-3xl shadow-2xl border border-white/10">
                </div>
                <!-- Decorative elements -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-500/20 rounded-full blur-2xl animate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 px-6 relative">
        <div class="max-w-7xl mx-auto text-center mb-20" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold font-heading mb-6">Todo lo que necesitas para crecer</h2>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">Una solución integral diseñada específicamente para profesionales de la salud y bienestar.</p>
        </div>

        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="glass-card p-8 hover:bg-white/5 transition-all group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-14 h-14 rounded-2xl bg-brand-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="bi bi-calendar-check text-3xl text-brand-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Agenda Inteligente</h3>
                <p class="text-gray-400 leading-relaxed">
                    Calendario intuitivo con drag-and-drop, gestión de múltiples salas y profesionales en tiempo real.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="glass-card p-8 hover:bg-white/5 transition-all group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="bi bi-bell text-3xl text-blue-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Recordatorios Automáticos</h3>
                <p class="text-gray-400 leading-relaxed">
                    Reduce el ausentismo hasta en un 40% con notificaciones automáticas por WhatsApp, email y SMS.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="glass-card p-8 hover:bg-white/5 transition-all group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="bi bi-file-earmark-medical text-3xl text-emerald-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Historias Clínicas</h3>
                <p class="text-gray-400 leading-relaxed">
                    Expedientes digitales seguros, centralizados y accesibles desde cualquier dispositivo 24/7.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="glass-card p-8 hover:bg-white/5 transition-all group" data-aos="fade-up" data-aos-delay="400">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="bi bi-credit-card text-3xl text-amber-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Pagos y Facturación</h3>
                <p class="text-gray-400 leading-relaxed">
                    Genera facturas automáticamente y acepta pagos online de forma segura con Stripe o PayPal.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="glass-card p-8 hover:bg-white/5 transition-all group" data-aos="fade-up" data-aos-delay="500">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="bi bi-graph-up-arrow text-3xl text-purple-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">Analítica Avanzada</h3>
                <p class="text-gray-400 leading-relaxed">
                    Visualiza el rendimiento de tu negocio con reportes detallados de ingresos, citas y recurrencia.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="glass-card p-8 hover:bg-white/5 transition-all group" data-aos="fade-up" data-aos-delay="600">
                <div class="w-14 h-14 rounded-2xl bg-pink-500/10 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="bi bi-phone text-3xl text-pink-400"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4">App para Pacientes</h3>
                <p class="text-gray-400 leading-relaxed">
                    Ofrece a tus pacientes una experiencia premium con una app para reservar y gestionar sus citas.
                </p>
            </div>
        </div>
    </section>

    <!-- Social Proof Section -->
    <section class="py-24 bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-6 flex flex-wrap justify-center gap-12 md:gap-24 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
            <span class="text-2xl font-bold">PHYSIO-PRO</span>
            <span class="text-2xl font-bold">HEALTH-CENTER</span>
            <span class="text-2xl font-bold">DENTAL-LUX</span>
            <span class="text-2xl font-bold">ESTHETIC-PLUS</span>
            <span class="text-2xl font-bold">VITAL-CARE</span>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 px-6 relative">
        <div class="blob top-[20%] left-[-10%] opacity-20"></div>
        
        <div class="max-w-7xl mx-auto text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl md:text-5xl font-bold font-heading mb-6">Planes que crecen contigo</h2>
            <p class="text-xl text-gray-400">Escoge el plan que mejor se adapte a tu volumen de pacientes.</p>
        </div>

        <div class="max-w-5xl mx-auto grid md:grid-cols-3 gap-8">
            <!-- Plan Starter -->
            <div class="glass-card p-8 flex flex-col" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-xl font-bold mb-2">Starter</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">29€</span>
                    <span class="text-gray-500">/mes</span>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Hasta 100 citas/mes</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> 1 Profesional</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Recordatorios Email</li>
                    <li class="flex items-center gap-3 text-gray-500 italic"><i class="bi bi-x text-red-500/50"></i> App Pacientes</li>
                </ul>
                <a href="#" class="w-full py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-all text-center font-semibold">Empezar ahora</a>
            </div>

            <!-- Plan Professional -->
            <div class="glass-card p-8 flex flex-col border-brand-500/30 ring-2 ring-brand-500/20 scale-105 bg-brand-500/5 relative" data-aos="fade-up" data-aos-delay="200">
                <div class="absolute top-0 right-8 -translate-y-1/2 bg-brand-600 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Más popular</div>
                <h3 class="text-xl font-bold mb-2">Professional</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">59€</span>
                    <span class="text-gray-500">/mes</span>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Citas ilimitadas</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Hasta 5 Profesionales</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Recordatorios WhatsApp</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Historias Clínicas</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> App Pacientes</li>
                </ul>
                <a href="#" class="w-full py-3 rounded-xl bg-brand-600 hover:bg-brand-700 transition-all text-center font-semibold btn-glow">Probar Professional</a>
            </div>

            <!-- Plan Enterprise -->
            <div class="glass-card p-8 flex flex-col" data-aos="fade-up" data-aos-delay="300">
                <h3 class="text-xl font-bold mb-2">Enterprise</h3>
                <div class="mb-6">
                    <span class="text-4xl font-bold text-white">99€</span>
                    <span class="text-gray-500">/mes</span>
                </div>
                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Profesionales ilimitados</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Multi-clínica</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> API Personalizada</li>
                    <li class="flex items-center gap-3 text-gray-300"><i class="bi bi-check2 text-brand-400"></i> Soporte 24/7 VIP</li>
                </ul>
                <a href="#" class="w-full py-3 rounded-xl border border-white/10 hover:bg-white/5 transition-all text-center font-semibold">Contactar ventas</a>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="py-24 px-6">
        <div class="max-w-5xl mx-auto glass-card p-12 md:p-20 text-center relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute inset-0 bg-brand-600/10 pointer-events-none"></div>
            <h2 class="text-4xl md:text-6xl font-bold font-heading mb-8 relative z-10">¿Listo para transformar <br> tu negocio?</h2>
            <p class="text-xl text-gray-300 mb-10 max-w-2xl mx-auto relative z-10">Únete a cientos de profesionales que ya están ahorrando 10+ horas semanales con Velion.</p>
            <div class="relative z-10">
                <a href="<?= PROJECT_ROOT ?>/login" class="bg-brand-600 hover:bg-brand-700 text-white px-10 py-5 rounded-2xl font-bold text-xl transition-all btn-glow inline-block">
                    Empezar mi prueba gratuita
                </a>
                <p class="mt-6 text-gray-500">No requiere tarjeta de crédito • Cancela cuando quieras</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-6 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-12">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-brand-600 flex items-center justify-center text-white font-bold">V</div>
                    <span class="text-xl font-bold font-heading tracking-tight">Velion</span>
                </div>
                <p class="text-gray-500 max-w-xs mb-8">
                    La plataforma líder en gestión de citas y pacientes para profesionales de la salud.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-white text-xl"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="font-bold mb-6">Producto</h4>
                <ul class="space-y-4 text-gray-500">
                    <li><a href="#" class="hover:text-white transition-colors">Funcionalidades</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">App Pacientes</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Precios</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Seguridad</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-bold mb-6">Compañía</h4>
                <ul class="space-y-4 text-gray-500">
                    <li><a href="#" class="hover:text-white transition-colors">Sobre nosotros</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Términos y Privacidad</a></li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-white/5 text-center text-gray-600 text-sm">
            <p>© 2026 Velion Software S.L. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('py-2');
                nav.querySelector('div').classList.add('bg-slate-900/80');
            } else {
                nav.classList.remove('py-2');
                nav.querySelector('div').classList.remove('bg-slate-900/80');
            }
        });
    </script>
</body>
</html>
