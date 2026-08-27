<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Cliente - Tervion</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    <script>
        window.registrationInitialData = <?= json_encode([
                                                'email' => $data['email'] ?? '',
                                                'usuario_id' => $data['usuario_id'] ?? '',
                                                'nombre' => $data['nombre'] ?? '',
                                                'apellidos' => $data['apellidos'] ?? '',
                                                'telefono' => $data['telefono'] ?? '',
                                                'fecha_nacimiento' => $data['fecha_nacimiento'] ?? '',
                                                'genero' => $data['genero'] ?? 'Hombre',
                                                'direccion' => $data['direccion'] ?? '',
                                                'municipio' => $data['municipio'] ?? '',
                                                'provincia' => $data['provincia'] ?? '',
                                                'cp' => $data['cp'] ?? '',
                                                'nss' => $data['nss'] ?? '',
                                                'iban' => $data['iban'] ?? '',
                                                'nombre_comercial' => $data['nombre_comercial'] ?? '',
                                                'razon_social' => $data['razon_social'] ?? '',
                                                'telefono_contacto' => $data['telefono_contacto'] ?? '',
                                                'email_contacto' => $data['email_contacto'] ?? '',
                                                'sitio_web' => $data['sitio_web'] ?? '',
                                                'direccion_calle' => $data['direccion_calle'] ?? '',
                                                'ciudad' => $data['ciudad'] ?? '',
                                                'provincia_estado' => $data['provincia_estado'] ?? '',
                                                'codigo_postal' => $data['codigo_postal'] ?? '',
                                                'pais' => $data['pais'] ?? 'España',
                                                'plan_suscripcion' => $data['plan_suscripcion'] ?? 'Profesional',
                                            ]) ?>;
    </script>

    <!-- Registration Module Logic -->
    <script src="<?= PROJECT_ROOT ?>/public/js/modules/landing/registration.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col justify-between <?= $hasSystemAlert ? 'pt-7' : '' ?>"
    x-data="registrationForm(window.registrationInitialData || {})"
    x-cloak>

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Top Navigation / Header -->
    <header class="border-b border-slate-200 bg-white/90 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?= PROJECT_ROOT ?>/" class="flex items-center gap-3">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-azul-sin-fondo.svg" alt="Tervion Logo" class="h-8 sm:h-9 object-contain" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-azul-sin-fondo.svg';">
            </a>
            <div class="flex items-center gap-3">
                <span class="text-xs sm:text-sm font-medium text-slate-500 hidden sm:inline">¿Ya tienes cuenta?</span>
                <a href="<?= PROJECT_ROOT ?>/login" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 border border-slate-200 shadow-xs transition-all">
                    <span>Iniciar Sesión</span>
                    <i class="bi bi-box-arrow-in-right text-sm text-slate-500"></i>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Registration Section -->
    <main class="flex-grow flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 relative">
        <div class="w-full max-w-4xl">

            <!-- Alert message if controller returns error -->
            <?php if (!empty($error)) : ?>
                <div class="rounded-2xl border border-red-200 bg-red-50 p-4 mb-6 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Error en el registro</h3>
                            <div class="mt-1 text-sm text-red-700">
                                <p><?= htmlspecialchars($error) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sleek Minimal Progress Bar -->
            <div class="mb-6 px-1 space-y-2">
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-md bg-primary-50 text-primary-600 font-bold text-[11px] uppercase tracking-wider" x-text="'Paso ' + currentStep + ' de ' + steps.length"></span>
                        <span class="text-slate-300">•</span>
                        <span class="font-bold text-slate-900 text-sm" x-text="steps[currentStep - 1].name"></span>
                    </div>
                    <div class="flex items-center gap-1 font-semibold text-slate-700">
                        <span class="text-primary-600 font-bold text-xs" x-text="Math.round((currentStep / steps.length) * 100) + '%'"></span>
                    </div>
                </div>
                <!-- Micro Progress Bar Fill -->
                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                    <div class="bg-primary-500 h-full rounded-full transition-all duration-300 ease-out"
                        :style="'width: ' + Math.round((currentStep / steps.length) * 100) + '%'"></div>
                </div>
            </div>

            <!-- Form Container -->
            <form action="<?= PROJECT_ROOT ?>/registro" method="POST" @submit="submitForm" class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden p-8 sm:p-10 transition-all">

                <!-- Step 1: Credenciales -->
                <div x-show="currentStep === 1" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Configura tus credenciales</h2>
                        <p class="text-sm text-slate-500 mt-1">Crea tu correo de acceso de administrador y una contraseña segura.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico del administrador *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" id="email" x-model="formData.email"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="ejemplo@autonomo.com">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.email" x-text="errors.email"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="pass" class="block text-sm font-medium text-slate-700 mb-1">Contraseña * (mín. 8 caracteres)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" name="pass" id="pass" x-model="formData.pass"
                                        class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                        placeholder="••••••••">
                                </div>
                                <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.pass" x-text="errors.pass"></span>
                            </div>
                            <div>
                                <label for="confirm_pass" class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña *</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" name="confirm_pass" id="confirm_pass" x-model="formData.confirm_pass"
                                        class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                        placeholder="••••••••">
                                </div>
                                <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.confirm_pass" x-text="errors.confirm_pass"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Datos Personales -->
                <div x-show="currentStep === 2" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Datos Personales del Autónomo</h2>
                        <p class="text-sm text-slate-500 mt-1">Completa tus datos identificativos como administrador del sistema.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="usuario_id" class="block text-sm font-medium text-slate-700 mb-1">DNI / NIF * (9 caracteres)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-card-text"></i>
                                </span>
                                <input type="text" name="usuario_id" id="usuario_id" x-model="formData.usuario_id"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all uppercase"
                                    placeholder="12345678Z" maxlength="9">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.usuario_id" x-text="errors.usuario_id"></span>
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-slate-700 mb-1">Teléfono móvil</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-telephone"></i>
                                </span>
                                <input type="text" name="telefono" id="telefono" x-model="formData.telefono"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="600000000">
                            </div>
                        </div>
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-slate-700 mb-1">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" x-model="formData.nombre"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                placeholder="Tu nombre">
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.nombre" x-text="errors.nombre"></span>
                        </div>
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-slate-700 mb-1">Apellidos *</label>
                            <input type="text" name="apellidos" id="apellidos" x-model="formData.apellidos"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                placeholder="Tus apellidos">
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.apellidos" x-text="errors.apellidos"></span>
                        </div>
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" x-model="formData.fecha_nacimiento"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all">
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.fecha_nacimiento" x-text="errors.fecha_nacimiento"></span>
                        </div>
                        <div>
                            <label for="genero" class="block text-sm font-medium text-slate-700 mb-1">Género *</label>
                            <select name="genero" id="genero" x-model="formData.genero"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all">
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
                        <h2 class="text-xl font-bold text-slate-900">Dirección del Autónomo</h2>
                        <p class="text-sm text-slate-500 mt-1">Lugar de residencia o domicilio fiscal del autónomo.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-slate-700 mb-1">Dirección fiscal / Calle</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-geo-alt"></i>
                                </span>
                                <input type="text" name="direccion" id="direccion" x-model="formData.direccion"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Av. Constitucion 123, 2B">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label for="municipio" class="block text-sm font-medium text-slate-700 mb-1">Municipio / Localidad</label>
                                <input type="text" name="municipio" id="municipio" x-model="formData.municipio"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Madrid">
                            </div>
                            <div>
                                <label for="provincia" class="block text-sm font-medium text-slate-700 mb-1">Provincia</label>
                                <input type="text" name="provincia" id="provincia" x-model="formData.provincia"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Madrid">
                            </div>
                            <div>
                                <label for="cp" class="block text-sm font-medium text-slate-700 mb-1">Código Postal</label>
                                <input type="text" name="cp" id="cp" x-model="formData.cp"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="28001">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Clínica - Datos Generales -->
                <div x-show="currentStep === 4" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Datos de la Clínica</h2>
                        <p class="text-sm text-slate-500 mt-1">Introduce los datos comerciales y de contacto de tu centro de fisioterapia.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label for="nombre_comercial" class="block text-sm font-medium text-slate-700 mb-1">Nombre Comercial de la Clínica *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-building"></i>
                                </span>
                                <input type="text" name="nombre_comercial" id="nombre_comercial" x-model="formData.nombre_comercial"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Clínica Tervion Madrid">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.nombre_comercial" x-text="errors.nombre_comercial"></span>
                        </div>
                        <div>
                            <label for="razon_social" class="block text-sm font-medium text-slate-700 mb-1">Razón Social</label>
                            <input type="text" name="razon_social" id="razon_social" x-model="formData.razon_social"
                                class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                placeholder="Tervion S.L. / Autónomo">
                        </div>
                        <div>
                            <label for="telefono_contacto" class="block text-sm font-medium text-slate-700 mb-1">Teléfono de Contacto Clínica *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-telephone-fill"></i>
                                </span>
                                <input type="text" name="telefono_contacto" id="telefono_contacto" x-model="formData.telefono_contacto"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="910000000">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.telefono_contacto" x-text="errors.telefono_contacto"></span>
                        </div>
                        <div>
                            <label for="email_contacto" class="block text-sm font-medium text-slate-700 mb-1">Email Público de Contacto</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-envelope-at"></i>
                                </span>
                                <input type="email" name="email_contacto" id="email_contacto" x-model="formData.email_contacto"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="contacto@clinicatervion.com">
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="sitio_web" class="block text-sm font-medium text-slate-700 mb-1">Sitio Web (URL)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-globe"></i>
                                </span>
                                <input type="url" name="sitio_web" id="sitio_web" x-model="formData.sitio_web"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="https://www.clinicatervion.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Clínica - Dirección / Ubicación -->
                <div x-show="currentStep === 5" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Ubicación de la Clínica</h2>
                        <p class="text-sm text-slate-500 mt-1">Registra la dirección física donde se encuentra la clínica.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label for="direccion_calle" class="block text-sm font-medium text-slate-700 mb-1">Dirección de la clínica *</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <i class="bi bi-geo-fill"></i>
                                </span>
                                <input type="text" name="direccion_calle" id="direccion_calle" x-model="formData.direccion_calle"
                                    class="block w-full pl-10 pr-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Calle Mayor 45, Planta Baja">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.direccion_calle" x-text="errors.direccion_calle"></span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-slate-700 mb-1">Ciudad *</label>
                                <input type="text" name="ciudad" id="ciudad" x-model="formData.ciudad"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Madrid">
                                <span class="text-xs text-red-500 mt-1 block font-medium" x-show="errors.ciudad" x-text="errors.ciudad"></span>
                            </div>
                            <div>
                                <label for="provincia_estado" class="block text-sm font-medium text-slate-700 mb-1">Provincia o Estado</label>
                                <input type="text" name="provincia_estado" id="provincia_estado" x-model="formData.provincia_estado"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="Madrid">
                            </div>
                            <div>
                                <label for="codigo_postal" class="block text-sm font-medium text-slate-700 mb-1">Código Postal</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" x-model="formData.codigo_postal"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="28001">
                            </div>
                            <div>
                                <label for="pais" class="block text-sm font-medium text-slate-700 mb-1">País *</label>
                                <input type="text" name="pais" id="pais" x-model="formData.pais"
                                    class="block w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white text-sm transition-all"
                                    placeholder="España">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Suscripción -->
                <div x-show="currentStep === 6" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Plan de Suscripción</h2>
                        <p class="text-sm text-slate-500 mt-1">El plan se asigna con una prueba gratuita de 14 días sin permanencia.</p>
                    </div>

                    <!-- Plan Selection Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Plan Basico -->
                        <label class="relative flex flex-col p-5 bg-white border rounded-2xl cursor-pointer hover:border-primary-500 transition-all"
                            :class="formData.plan_suscripcion === 'Basico' ? 'border-primary-500 ring-2 ring-primary-500/20 bg-primary-50/20' : 'border-slate-200'">
                            <input type="radio" name="plan_suscripcion" value="Basico" x-model="formData.plan_suscripcion" class="sr-only">
                            <span class="text-sm font-bold text-slate-900">Individual</span>
                            <span class="text-2xl font-extrabold text-primary-600 mt-2">29€<span class="text-xs text-slate-400 font-normal">/mes</span></span>
                            <span class="text-xs text-slate-500 mt-3">Para profesionales individuales o consultas privadas.</span>
                        </label>
                        <!-- Plan Profesional -->
                        <label class="relative flex flex-col p-5 bg-white border-2 rounded-2xl cursor-pointer transition-all shadow-sm"
                            :class="formData.plan_suscripcion === 'Profesional' ? 'border-primary-500 ring-2 ring-primary-500/20 bg-primary-50/30' : 'border-slate-200'">
                            <div class="absolute -top-3 right-4 px-2.5 py-0.5 bg-primary-600 text-white rounded-full text-[10px] font-bold uppercase tracking-wider">Más Popular</div>
                            <input type="radio" name="plan_suscripcion" value="Profesional" x-model="formData.plan_suscripcion" class="sr-only">
                            <span class="text-sm font-bold text-slate-900">Clínica Estándar</span>
                            <span class="text-2xl font-extrabold text-primary-600 mt-2">59€<span class="text-xs text-slate-400 font-normal">/mes</span></span>
                            <span class="text-xs text-slate-500 mt-3">Hasta 5 terapeutas, recordatorios por WhatsApp y salas.</span>
                        </label>
                        <!-- Plan Premium -->
                        <label class="relative flex flex-col p-5 bg-white border rounded-2xl cursor-pointer hover:border-primary-500 transition-all"
                            :class="formData.plan_suscripcion === 'Premium' ? 'border-primary-500 ring-2 ring-primary-500/20 bg-primary-50/20' : 'border-slate-200'">
                            <input type="radio" name="plan_suscripcion" value="Premium" x-model="formData.plan_suscripcion" class="sr-only">
                            <span class="text-sm font-bold text-slate-900">Policlínica & Pro</span>
                            <span class="text-2xl font-extrabold text-primary-600 mt-2">99€<span class="text-xs text-slate-400 font-normal">/mes</span></span>
                            <span class="text-xs text-slate-500 mt-3">Terapeutas ilimitados, multi-sede y analítica avanzada.</span>
                        </label>
                    </div>
                </div>

                <!-- Step 7: Resumen -->
                <div x-show="currentStep === 7" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Verifica la información</h2>
                        <p class="text-sm text-slate-500 mt-1">Repasa los datos introducidos antes de confirmar el registro de alta.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Admin Summary Card -->
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <i class="bi bi-person-fill text-primary-600"></i>Datos del Administrador
                            </h3>
                            <dl class="space-y-2.5 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Nombre completo:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.nombre + ' ' + formData.apellidos"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">DNI / NIF:</dt>
                                    <dd class="font-bold text-slate-900" x-text="formData.usuario_id"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Email:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.email"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.telefono">
                                    <dt class="text-slate-500">Teléfono:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.telefono"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">F. Nacimiento:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.fecha_nacimiento"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Género:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.genero"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.direccion">
                                    <dt class="text-slate-500">Dirección:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.direccion + (formData.municipio ? ' (' + formData.municipio + ')' : '')"></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Clinic Summary Card -->
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <i class="bi bi-hospital text-primary-600"></i>Datos de la Clínica
                            </h3>
                            <dl class="space-y-2.5 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Clínica comercial:</dt>
                                    <dd class="font-bold text-slate-900" x-text="formData.nombre_comercial"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.razon_social">
                                    <dt class="text-slate-500">Razón Social:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.razon_social"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Teléfono contacto:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.telefono_contacto"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.email_contacto">
                                    <dt class="text-slate-500">Email contacto:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.email_contacto"></dd>
                                </div>
                                <div class="flex justify-between" x-show="formData.sitio_web">
                                    <dt class="text-slate-500">Sitio Web:</dt>
                                    <dd class="font-medium text-slate-900 text-xs truncate max-w-[200px]" x-text="formData.sitio_web"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Dirección:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.direccion_calle"></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-slate-500">Ubicación:</dt>
                                    <dd class="font-medium text-slate-900" x-text="formData.ciudad + ', ' + formData.pais"></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Subscription Summary Card -->
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 md:col-span-2">
                            <h3 class="font-bold text-slate-900 border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                                <i class="bi bi-credit-card-fill text-primary-600"></i>Suscripción Seleccionada
                            </h3>
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs text-slate-500">Plan Seleccionado:</div>
                                    <div class="font-extrabold text-primary-600 text-lg" x-text="formData.plan_suscripcion"></div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                                        <i class="bi bi-check2"></i> 14 Días de Prueba Gratis
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step actions footer -->
                <div class="mt-8 pt-6 border-t border-slate-200 flex items-center justify-between">
                    <div>
                        <button type="button"
                            x-show="currentStep > 1"
                            @click="prevStep()"
                            class="inline-flex justify-center items-center py-2.5 px-5 border border-slate-300 rounded-xl shadow-xs text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                            <i class="bi bi-arrow-left mr-2"></i> Anterior
                        </button>
                    </div>
                    <div>
                        <button type="button"
                            x-show="currentStep < steps.length"
                            @click="nextStep()"
                            class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 transition-colors">
                            Siguiente <i class="bi bi-arrow-right ml-2"></i>
                        </button>

                        <button type="submit"
                            x-show="currentStep === steps.length"
                            class="inline-flex justify-center items-center py-2.5 px-6 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-primary-500 hover:bg-primary-600 transition-all transform hover:-translate-y-0.5">
                            Finalizar Registro <i class="bi bi-check-circle ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6 sm:gap-4 text-xs">
            <p class="w-full sm:w-1/3 text-center sm:text-left">© 2026 Tervion. Todos los derechos reservados.</p>

            <a href="<?= PROJECT_ROOT ?>/" class="w-full sm:w-1/3 flex items-center justify-center">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-claro-sin-fondo.png" alt="Tervion Logo" class="h-7 object-contain" onerror="this.onerror=null; this.src='<?= PROJECT_ROOT ?>/public/img/nuevo-logo/logo-tervion-claro-sin-fondo.png';">
            </a>

            <div class="w-full sm:w-1/3 flex items-center justify-center sm:justify-end gap-4 text-slate-400">
                <a href="<?= PROJECT_ROOT ?>/privacidad" class="hover:text-slate-300">Privacidad</a>
                <span>•</span>
                <a href="<?= PROJECT_ROOT ?>/terminos" class="hover:text-slate-300">Términos</a>
                <span>•</span>
                <a href="<?= PROJECT_ROOT ?>/cookies" class="hover:text-slate-300">Cookies</a>
            </div>
        </div>
    </footer>

</body>

</html>