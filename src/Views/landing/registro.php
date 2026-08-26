<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Cliente - Tervion</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col justify-between <?= $hasSystemAlert ? 'pt-7' : '' ?>"
    x-data="registrationForm()"
    x-cloak>

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Top Navigation / Header -->
    <header class="border-b border-gray-200 bg-white/80 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-sin-fondo.png" alt="Tervion Logo" class="h-9 object-contain">
            </a>
            <div class="flex items-center gap-3">
                <span class="text-xs sm:text-sm font-medium text-gray-500">¿Ya tienes cuenta?</span>
                <a href="<?= PROJECT_ROOT ?>/login" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 border border-gray-200/80 shadow-2xs transition-all">
                    <span>Iniciar Sesión</span>
                    <i class="bi bi-box-arrow-in-right text-sm text-gray-500"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Registration Section -->
    <main class="flex-grow flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 relative">
        <div class="w-full max-w-4xl">

            <!-- Alert message if controller returns error -->
            <?php if (!empty($error)) : ?>
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 mb-6 shadow-sm animate-pulse">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Error en el registro</h3>
                            <div class="mt-1 text-sm text-red-700">
                                <p><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sleek Minimal Progress Bar (Ultra-Compact UX) -->
            <div class="mb-6 px-1 space-y-2">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 font-bold text-[11px] uppercase tracking-wider" x-text="'Paso ' + currentStep + ' de ' + steps.length"></span>
                        <span class="text-gray-300">•</span>
                        <span class="font-semibold text-gray-900 text-sm" x-text="steps[currentStep - 1].name"></span>
                    </div>
                    <div class="flex items-center gap-1 font-semibold text-gray-700">
                        <span class="text-indigo-600 font-bold text-xs" x-text="Math.round((currentStep / steps.length) * 100) + '%'"></span>
                    </div>
                </div>
                <!-- Micro Progress Bar Fill -->
                <div class="w-full bg-gray-200/80 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-500 ease-out"
                        :style="'width: ' + Math.round((currentStep / steps.length) * 100) + '%'"></div>
                </div>
            </div>

            <!-- Form Container -->
            <form action="<?= PROJECT_ROOT ?>/registro" method="POST" @submit="submitForm" class="glass-panel rounded-3xl border border-white shadow-xl overflow-hidden p-8 sm:p-10 transition-all">

                <!-- Step 1: Credenciales -->
                <div x-show="currentStep === 1" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Configura tus credenciales</h2>
                        <p class="text-sm text-gray-500 mt-1">Crea tu correo de acceso de administrador y una contraseña segura.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico del administrador *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email" x-model="formData.email" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="ejemplo@autonomo.com">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.email" x-text="errors.email"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="pass" class="block text-sm font-medium text-gray-700 mb-1">Contraseña * (mín. 8 caracteres)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="pass" id="pass" x-model="formData.pass" required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                        placeholder="••••••••">
                                </div>
                                <span class="text-xs text-red-500 mt-1 block" x-show="errors.pass" x-text="errors.pass"></span>
                            </div>
                            <div>
                                <label for="confirm_pass" class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña *</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" name="confirm_pass" id="confirm_pass" x-model="formData.confirm_pass" required
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                        placeholder="••••••••">
                                </div>
                                <span class="text-xs text-red-500 mt-1 block" x-show="errors.confirm_pass" x-text="errors.confirm_pass"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Datos Personales -->
                <div x-show="currentStep === 2" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Datos Personales del Autónomo</h2>
                        <p class="text-sm text-gray-500 mt-1">Completa tus datos identificativos como administrador del sistema.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-gray-700 mb-1">DNI / NIF * (9 caracteres)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input type="text" name="usuario_id" id="usuario_id" x-model="formData.usuario_id" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="12345678Z">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.usuario_id" x-text="errors.usuario_id"></span>
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono móvil</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input type="text" name="telefono" id="telefono" x-model="formData.telefono"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="600000000">
                            </div>
                        </div>
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" x-model="formData.nombre" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                placeholder="Tu nombre">
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.nombre" x-text="errors.nombre"></span>
                        </div>
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-gray-700 mb-1">Apellidos *</label>
                            <input type="text" name="apellidos" id="apellidos" x-model="formData.apellidos" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                placeholder="Tus apellidos">
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.apellidos" x-text="errors.apellidos"></span>
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" x-model="formData.fecha_nacimiento" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all">
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.fecha_nacimiento" x-text="errors.fecha_nacimiento"></span>
                        </div>
                        <div>
                            <label for="genero" class="block text-sm font-medium text-gray-700 mb-1">Género *</label>
                            <select name="genero" id="genero" x-model="formData.genero" required
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all">
                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Dirección Autónomo -->
                <div x-show="currentStep === 3" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Dirección del Autónomo</h2>
                        <p class="text-sm text-gray-500 mt-1">Lugar de residencia o domicilio fiscal del autónomo.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección fiscal / Calle</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-geo-alt"></i>
                                </span>
                                <input type="text" name="direccion" id="direccion" x-model="formData.direccion"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Av. Constitucion 123, 2B">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label for="municipio" class="block text-sm font-medium text-gray-700 mb-1">Municipio / Localidad</label>
                                <input type="text" name="municipio" id="municipio" x-model="formData.municipio"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Madrid">
                            </div>
                            <div>
                                <label for="provincia" class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                                <input type="text" name="provincia" id="provincia" x-model="formData.provincia"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Madrid">
                            </div>
                            <div>
                                <label for="cp" class="block text-sm font-medium text-gray-700 mb-1">Código Postal</label>
                                <input type="text" name="cp" id="cp" x-model="formData.cp"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="28001">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Clínica - Datos Generales -->
                <div x-show="currentStep === 4" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Datos de la Clínica</h2>
                        <p class="text-sm text-gray-500 mt-1">Introduce los datos comerciales y de contacto de tu centro de fisioterapia.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label for="nombre_comercial" class="block text-sm font-medium text-gray-700 mb-1">Nombre Comercial de la Clínica *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-building"></i>
                                </span>
                                <input type="text" name="nombre_comercial" id="nombre_comercial" x-model="formData.nombre_comercial" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Clínica Tervion Madrid">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.nombre_comercial" x-text="errors.nombre_comercial"></span>
                        </div>
                        <div>
                            <label for="razon_social" class="block text-sm font-medium text-gray-700 mb-1">Razón Social</label>
                            <input type="text" name="razon_social" id="razon_social" x-model="formData.razon_social"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                placeholder="Tervion S.L. / Autónomo">
                        </div>
                        <div>
                            <label for="telefono_contacto" class="block text-sm font-medium text-gray-700 mb-1">Teléfono de Contacto Clínica *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-telephone-fill"></i>
                                </span>
                                <input type="text" name="telefono_contacto" id="telefono_contacto" x-model="formData.telefono_contacto" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="910000000">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.telefono_contacto" x-text="errors.telefono_contacto"></span>
                        </div>
                        <div>
                            <label for="email_contacto" class="block text-sm font-medium text-gray-700 mb-1">Email Público de Contacto</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-envelope-at"></i>
                                </span>
                                <input type="email" name="email_contacto" id="email_contacto" x-model="formData.email_contacto"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="contacto@clinicatervion.com">
                            </div>
                        </div>
                        <div>
                            <label for="sitio_web" class="block text-sm font-medium text-gray-700 mb-1">Sitio Web (URL)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-globe"></i>
                                </span>
                                <input type="url" name="sitio_web" id="sitio_web" x-model="formData.sitio_web"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="https://www.clinicatervion.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Clínica - Dirección / Ubicación -->
                <div x-show="currentStep === 5" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Ubicación de la Clínica</h2>
                        <p class="text-sm text-gray-500 mt-1">Registra la dirección física donde se encuentra la clínica.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="direccion_calle" class="block text-sm font-medium text-gray-700 mb-1">Dirección de la clínica *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-geo-fill"></i>
                                </span>
                                <input type="text" name="direccion_calle" id="direccion_calle" x-model="formData.direccion_calle" required
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Calle Mayor 45, Planta Baja">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.direccion_calle" x-text="errors.direccion_calle"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                                <input type="text" name="ciudad" id="ciudad" x-model="formData.ciudad" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Madrid">
                                <span class="text-xs text-red-500 mt-1 block" x-show="errors.ciudad" x-text="errors.ciudad"></span>
                            </div>
                            <div>
                                <label for="provincia_estado" class="block text-sm font-medium text-gray-700 mb-1">Provincia o Estado</label>
                                <input type="text" name="provincia_estado" id="provincia_estado" x-model="formData.provincia_estado"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="Madrid">
                            </div>
                            <div>
                                <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-1">Código Postal</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" x-model="formData.codigo_postal"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="28001">
                            </div>
                            <div>
                                <label for="pais" class="block text-sm font-medium text-gray-700 mb-1">País *</label>
                                <input type="text" name="pais" id="pais" x-model="formData.pais" required
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="España">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Suscripción -->
                <div x-show="currentStep === 6" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Plan de Suscripción</h2>
                        <p class="text-sm text-gray-500 mt-1">El plan Premium se asigna por defecto para dar acceso a todas las funcionalidades avanzadas de la plataforma sin necesidad de ingresar datos bancarios.</p>
                    </div>

                    <!-- Plan Selection Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Plan Basico -->
                        <label class="relative flex flex-col p-5 bg-white border rounded-2xl cursor-pointer hover:border-indigo-500 transition-all opacity-60"
                            :class="formData.plan_suscripcion === 'Basico' ? 'border-indigo-500 ring-2 ring-indigo-500/20 opacity-100' : 'border-gray-200'">
                            <input type="radio" name="plan_radio" value="Basico" x-model="formData.plan_suscripcion" class="sr-only">
                            <span class="text-sm font-bold text-gray-900">Básico</span>
                            <span class="text-2xl font-black text-indigo-600 mt-2">29,99€<span class="text-xs text-gray-400 font-normal">/mes</span></span>
                            <span class="text-xs text-gray-500 mt-3">Para profesionales individuales o clínicas pequeñas. Hasta 2 fisios.</span>
                        </label>
                        <!-- Plan Profesional -->
                        <label class="relative flex flex-col p-5 bg-white border rounded-2xl cursor-pointer hover:border-indigo-500 transition-all opacity-60"
                            :class="formData.plan_suscripcion === 'Profesional' ? 'border-indigo-500 ring-2 ring-indigo-500/20 opacity-100' : 'border-gray-200'">
                            <input type="radio" name="plan_radio" value="Profesional" x-model="formData.plan_suscripcion" class="sr-only">
                            <span class="text-sm font-bold text-gray-900">Profesional</span>
                            <span class="text-2xl font-black text-indigo-600 mt-2">59,99€<span class="text-xs text-gray-400 font-normal">/mes</span></span>
                            <span class="text-xs text-gray-500 mt-3">Para clínicas medianas. Hasta 10 fisios, facturación avanzada.</span>
                        </label>
                        <!-- Plan Premium -->
                        <label class="relative flex flex-col p-5 bg-white border-2 border-indigo-500 rounded-2xl cursor-pointer ring-2 ring-indigo-500/20 transition-all shadow-sm"
                            :class="formData.plan_suscripcion === 'Premium' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-gray-200'">
                            <div class="absolute -top-3 right-4 px-2.5 py-0.5 bg-indigo-600 text-white rounded-full text-[10px] font-bold uppercase tracking-wider">Incluido por defecto</div>
                            <input checked type="radio" name="plan_radio" value="Premium" x-model="formData.plan_suscripcion" class="sr-only">
                            <span class="text-sm font-bold text-gray-900">Premium</span>
                            <span class="text-2xl font-black text-indigo-600 mt-2">99,99€<span class="text-xs text-gray-400 font-normal">/mes</span></span>
                            <span class="text-xs text-gray-500 mt-3">Para grandes clínicas. Fisios ilimitados y soporte prioritario.</span>
                        </label>
                    </div>
                </div>

                <!-- Step 7: Resumen -->
                <div x-show="currentStep === 7" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Verifica la información</h2>
                        <p class="text-sm text-gray-500 mt-1">Repasa los datos introducidos antes de confirmar el registro de alta.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Admin Summary Card -->
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                <i class="bi bi-person-fill text-primary-600 mr-2"></i>Datos del Administrador
                            </h3>
                            <dl class="space-y-2.5 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Nombre completo:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.nombre + ' ' + formData.apellidos"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">DNI / NIF:</dt>
                                    <dd class="font-medium text-gray-950" x-text="formData.usuario_id"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Email:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.email"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.telefono">
                                    <dt class="text-gray-500">Teléfono:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.telefono"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">F. Nacimiento:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.fecha_nacimiento"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Género:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.genero"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.direccion">
                                    <dt class="text-gray-500">Dirección:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.direccion + ' (' + formData.municipio + ')'"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.nss">
                                    <dt class="text-gray-500">NSS:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.nss"></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Clinic Summary Card -->
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                <i class="bi bi-hospital text-primary-600 mr-2"></i>Datos de la Clínica
                            </h3>
                            <dl class="space-y-2.5 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Clínica comercial:</dt>
                                    <dd class="font-medium text-gray-950" x-text="formData.nombre_comercial"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.razon_social">
                                    <dt class="text-gray-500">Razón Social:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.razon_social"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Teléfono contacto:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.telefono_contacto"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.email_contacto">
                                    <dt class="text-gray-500">Email contacto:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.email_contacto"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.sitio_web">
                                    <dt class="text-gray-500">Sitio Web:</dt>
                                    <dd class="font-medium text-gray-900 text-xs" x-text="formData.sitio_web"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Dirección:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.direccion_calle"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Ubicación:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.ciudad + ', ' + formData.pais"></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Subscription Summary Card -->
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 md:col-span-2">
                            <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">
                                <i class="bi bi-credit-card-fill text-primary-600 mr-2"></i>Suscripción y Pago
                            </h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <dt class="text-gray-500 font-medium">Plan Seleccionado:</dt>
                                    <dd class="font-bold text-indigo-600 text-base" x-text="formData.plan_suscripcion"></dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 font-medium">Titular de Tarjeta:</dt>
                                    <dd class="font-medium text-gray-900" x-text="formData.card_holder"></dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500 font-medium">Tarjeta Guardada:</dt>
                                    <dd class="font-medium text-gray-900" x-text="'ending in ' + formData.card_number.slice(-4)"></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Hidden inputs so the server actually receives the data in the form submission -->
                    <div>
                        <input type="hidden" name="email" :value="formData.email">
                        <input type="hidden" name="pass" :value="formData.pass">
                        <input type="hidden" name="confirm_pass" :value="formData.confirm_pass">
                        <input type="hidden" name="usuario_id" :value="formData.usuario_id">
                        <input type="hidden" name="nombre" :value="formData.nombre">
                        <input type="hidden" name="apellidos" :value="formData.apellidos">
                        <input type="hidden" name="telefono" :value="formData.telefono">
                        <input type="hidden" name="fecha_nacimiento" :value="formData.fecha_nacimiento">
                        <input type="hidden" name="genero" :value="formData.genero">
                        <input type="hidden" name="direccion" :value="formData.direccion">
                        <input type="hidden" name="municipio" :value="formData.municipio">
                        <input type="hidden" name="provincia" :value="formData.provincia">
                        <input type="hidden" name="cp" :value="formData.cp">
                        <input type="hidden" name="nss" :value="formData.nss">
                        <input type="hidden" name="iban" :value="formData.iban">
                        <input type="hidden" name="nombre_comercial" :value="formData.nombre_comercial">
                        <input type="hidden" name="razon_social" :value="formData.razon_social">
                        <input type="hidden" name="telefono_contacto" :value="formData.telefono_contacto">
                        <input type="hidden" name="email_contacto" :value="formData.email_contacto">
                        <input type="hidden" name="sitio_web" :value="formData.sitio_web">
                        <input type="hidden" name="direccion_calle" :value="formData.direccion_calle">
                        <input type="hidden" name="ciudad" :value="formData.ciudad">
                        <input type="hidden" name="provincia_estado" :value="formData.provincia_estado">
                        <input type="hidden" name="codigo_postal" :value="formData.codigo_postal">
                        <input type="hidden" name="pais" :value="formData.pais">
                        <input type="hidden" name="plan_suscripcion" :value="formData.plan_suscripcion">
                        <input type="hidden" name="card_holder" :value="formData.card_holder">
                        <input type="hidden" name="card_number" :value="formData.card_number">
                        <input type="hidden" name="card_expiry" :value="formData.card_expiry">
                        <input type="hidden" name="card_cvv" :value="formData.card_cvv">
                    </div>
                </div>

                <!-- Step actions footer -->
                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <button type="button"
                            x-show="currentStep > 1"
                            @click="prevStep()"
                            class="inline-flex justify-center items-center py-2.5 px-5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <i class="bi bi-arrow-left mr-2"></i> Anterior
                        </button>
                    </div>
                    <div>
                        <button type="button"
                            x-show="currentStep < steps.length"
                            @click="nextStep()"
                            class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-950 transition-colors">
                            Siguiente <i class="bi bi-arrow-right ml-2"></i>
                        </button>

                        <button type="submit"
                            x-show="currentStep === steps.length"
                            class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-primary-500 hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            Finalizar Registro <i class="bi bi-check-circle ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6 sm:gap-4">
            <p class="w-full sm:w-1/3 text-center sm:text-left text-sm">© 2026 Tervion Ibérica SLU</p>

            <a href="<?= PROJECT_ROOT ?>/" class="w-full sm:w-1/3 flex items-center justify-center">
                <img src="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" alt="Tervion Logo" class="h-8 object-contain rounded-md p-1 shadow-sm">
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

    <script>
        function registrationForm() {
            return {
                currentStep: 1,
                maxStepReached: 1,
                steps: [{
                        name: 'Credenciales'
                    },
                    {
                        name: 'Autónomo - Personales'
                    },
                    {
                        name: 'Autónomo - Dirección'
                    },
                    {
                        name: 'Clínica - Generales'
                    },
                    {
                        name: 'Clínica - Ubicación'
                    },
                    {
                        name: 'Suscripción'
                    },
                    {
                        name: 'Resumen'
                    }
                ],
                formData: {
                    email: '<?= htmlspecialchars($data['email'] ?? '') ?>',
                    pass: '',
                    confirm_pass: '',
                    usuario_id: '<?= htmlspecialchars($data['usuario_id'] ?? '') ?>',
                    nombre: '<?= htmlspecialchars($data['nombre'] ?? '') ?>',
                    apellidos: '<?= htmlspecialchars($data['apellidos'] ?? '') ?>',
                    telefono: '<?= htmlspecialchars($data['telefono'] ?? '') ?>',
                    fecha_nacimiento: '<?= htmlspecialchars($data['fecha_nacimiento'] ?? '') ?>',
                    genero: '<?= htmlspecialchars($data['genero'] ?? 'Hombre') ?>',
                    direccion: '<?= htmlspecialchars($data['direccion'] ?? '') ?>',
                    municipio: '<?= htmlspecialchars($data['municipio'] ?? '') ?>',
                    provincia: '<?= htmlspecialchars($data['provincia'] ?? '') ?>',
                    cp: '<?= htmlspecialchars($data['cp'] ?? '') ?>',
                    nss: '<?= htmlspecialchars($data['nss'] ?? '') ?>',
                    iban: '<?= htmlspecialchars($data['iban'] ?? '') ?>',
                    nombre_comercial: '<?= htmlspecialchars($data['nombre_comercial'] ?? '') ?>',
                    razon_social: '<?= htmlspecialchars($data['razon_social'] ?? '') ?>',
                    telefono_contacto: '<?= htmlspecialchars($data['telefono_contacto'] ?? '') ?>',
                    email_contacto: '<?= htmlspecialchars($data['email_contacto'] ?? '') ?>',
                    sitio_web: '<?= htmlspecialchars($data['sitio_web'] ?? '') ?>',
                    direccion_calle: '<?= htmlspecialchars($data['direccion_calle'] ?? '') ?>',
                    ciudad: '<?= htmlspecialchars($data['ciudad'] ?? '') ?>',
                    provincia_estado: '<?= htmlspecialchars($data['provincia_estado'] ?? '') ?>',
                    codigo_postal: '<?= htmlspecialchars($data['codigo_postal'] ?? '') ?>',
                    pais: '<?= htmlspecialchars($data['pais'] ?? 'España') ?>',
                    plan_suscripcion: 'Premium',
                    card_holder: '',
                    card_number: '',
                    card_expiry: '',
                    card_cvv: ''
                },
                errors: {},
                goToStep(step) {
                    if (step <= this.maxStepReached) {
                        this.currentStep = step;
                    }
                },
                validateStep(step) {
                    this.errors = {};

                    if (step === 1) {
                        if (!this.formData.email || !this.formData.email.includes('@')) {
                            this.errors.email = 'Introduce un email de administrador válido.';
                        }
                        if (!this.formData.pass || this.formData.pass.length < 8) {
                            this.errors.pass = 'La contraseña debe tener al menos 8 caracteres.';
                        }
                        if (this.formData.pass !== this.formData.confirm_pass) {
                            this.errors.confirm_pass = 'Las contraseñas no coinciden.';
                        }
                    }

                    if (step === 2) {
                        if (!this.formData.usuario_id || this.formData.usuario_id.trim().length !== 9) {
                            this.errors.usuario_id = 'El DNI / NIF debe tener exactamente 9 caracteres.';
                        }
                        if (!this.formData.nombre || this.formData.nombre.trim() === '') {
                            this.errors.nombre = 'El nombre es obligatorio.';
                        }
                        if (!this.formData.apellidos || this.formData.apellidos.trim() === '') {
                            this.errors.apellidos = 'Los apellidos son obligatorios.';
                        }
                        if (!this.formData.fecha_nacimiento) {
                            this.errors.fecha_nacimiento = 'La fecha de nacimiento es obligatoria.';
                        }
                    }

                    if (step === 4) {
                        if (!this.formData.nombre_comercial || this.formData.nombre_comercial.trim() === '') {
                            this.errors.nombre_comercial = 'El nombre comercial de la clínica es obligatorio.';
                        }
                        if (!this.formData.telefono_contacto || this.formData.telefono_contacto.trim() === '') {
                            this.errors.telefono_contacto = 'El teléfono de contacto de la clínica es obligatorio.';
                        }
                    }

                    if (step === 5) {
                        if (!this.formData.direccion_calle || this.formData.direccion_calle.trim() === '') {
                            this.errors.direccion_calle = 'La dirección de la clínica es obligatoria.';
                        }
                        if (!this.formData.ciudad || this.formData.ciudad.trim() === '') {
                            this.errors.ciudad = 'La ciudad es obligatoria.';
                        }
                    }

                    return Object.keys(this.errors).length === 0;
                },
                nextStep() {
                    if (this.validateStep(this.currentStep)) {
                        this.currentStep++;
                        if (this.currentStep > this.maxStepReached) {
                            this.maxStepReached = this.currentStep;
                        }
                    }
                },
                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                },
                submitForm(e) {
                    // Validar pasos requeridos antes de enviar
                    if (!this.validateStep(1) || !this.validateStep(2) || !this.validateStep(4) || !this.validateStep(5)) {
                        e.preventDefault();
                        alert('Por favor, compruebe que todos los campos obligatorios están rellenos correctamente.');
                    }
                }
            }
        }
    </script>
</body>

</html>