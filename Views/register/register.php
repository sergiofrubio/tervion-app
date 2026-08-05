<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Cliente - Velion</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/logo-ventana.jpeg" type="image/jpeg">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/custom/css/app.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 font-sans antialiased min-h-screen flex flex-col justify-between" 
      x-data="registrationForm()" 
      x-cloak>

    <!-- Top Navigation / Header -->
    <header class="border-b border-gray-200 bg-white/80 backdrop-blur-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?= PROJECT_ROOT ?>/landing" class="flex items-center">
                <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-velion.jpeg" alt="Velion Logo" class="h-9 object-contain">
            </a>
            <div>
                <a href="<?= PROJECT_ROOT ?>/login" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    ¿Ya tienes cuenta? Iniciar Sesión
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

            <!-- Interactive Stepper Progress Indicator -->
            <div class="mb-10 px-4">
                <div class="flex items-center justify-between">
                    <template x-for="(stepInfo, index) in steps" :key="index">
                        <div class="flex items-center flex-1 last:flex-none">
                            <div class="flex flex-col items-center relative">
                                <button type="button" 
                                        @click="goToStep(index + 1)"
                                        :disabled="index + 1 > maxStepReached"
                                        class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                        :class="{
                                            'bg-gray-950 text-white shadow-lg shadow-gray-950/20 ring-4 ring-gray-950/10': currentStep === index + 1,
                                            'bg-indigo-500 text-white': currentStep > index + 1,
                                            'bg-white text-gray-400 border border-gray-200 hover:border-gray-400': currentStep < index + 1 && index + 1 <= maxStepReached,
                                            'bg-gray-100 text-gray-300 border border-gray-200 cursor-not-allowed': index + 1 > maxStepReached
                                        }">
                                    <span x-show="currentStep <= index + 1" x-text="index + 1"></span>
                                    <i x-show="currentStep > index + 1" class="bi bi-check-lg text-base"></i>
                                </button>
                                <span class="absolute top-12 text-[11px] font-medium text-gray-500 hidden md:block whitespace-nowrap"
                                      :class="{'text-gray-950 font-bold': currentStep === index + 1}"
                                      x-text="stepInfo.name"></span>
                            </div>
                            <div x-show="index < steps.length - 1" 
                                 class="h-1 flex-1 mx-2 rounded-full transition-all duration-300"
                                 :class="currentStep > index + 1 ? 'bg-indigo-500' : 'bg-gray-200'"></div>
                        </div>
                    </template>
                </div>
                <!-- Progress Bar Detail for mobile -->
                <div class="mt-6 md:hidden text-center">
                    <span class="text-xs font-semibold uppercase tracking-wider text-primary-600" x-text="'Paso ' + currentStep + ' de ' + steps.length"></span>
                    <h2 class="text-lg font-bold text-gray-900 mt-0.5" x-text="steps[currentStep - 1].name"></h2>
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

                <!-- Step 4: Datos Profesionales -->
                <div x-show="currentStep === 4" x-transition class="space-y-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Datos Profesionales</h2>
                        <p class="text-sm text-gray-500 mt-1">Registra tu número de afiliación y cuenta bancaria para la facturación.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="nss" class="block text-sm font-medium text-gray-700 mb-1">Nº Seguridad Social (NSS)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-shield-check"></i>
                                </span>
                                <input type="text" name="nss" id="nss" x-model="formData.nss"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="123456789012">
                            </div>
                        </div>
                        <div>
                            <label for="iban" class="block text-sm font-medium text-gray-700 mb-1">IBAN Cuenta Bancaria</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                    <i class="bi bi-credit-card"></i>
                                </span>
                                <input type="text" name="iban" id="iban" x-model="formData.iban"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                    placeholder="ES210000...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Clínica - Datos Generales -->
                <div x-show="currentStep === 5" x-transition class="space-y-6">
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
                                    placeholder="Clínica Velion Madrid">
                            </div>
                            <span class="text-xs text-red-500 mt-1 block" x-show="errors.nombre_comercial" x-text="errors.nombre_comercial"></span>
                        </div>
                        <div>
                            <label for="razon_social" class="block text-sm font-medium text-gray-700 mb-1">Razón Social</label>
                            <input type="text" name="razon_social" id="razon_social" x-model="formData.razon_social"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-all"
                                placeholder="Velion S.L. / Autónomo">
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
                                    placeholder="contacto@clinicavelion.com">
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
                                    placeholder="https://www.clinicavelion.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Clínica - Dirección / Ubicación -->
                <div x-show="currentStep === 6" x-transition class="space-y-6">
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
    <footer class="border-t border-gray-200 bg-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-500">
            &copy; 2026 Velion. Todos los derechos reservados.
        </div>
    </footer>

    <script>
        function registrationForm() {
            return {
                currentStep: 1,
                maxStepReached: 1,
                steps: [
                    { name: 'Credenciales' },
                    { name: 'Autónomo - Personales' },
                    { name: 'Autónomo - Dirección' },
                    { name: 'Datos Profesionales' },
                    { name: 'Clínica - Generales' },
                    { name: 'Clínica - Ubicación' },
                    { name: 'Resumen' }
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
                    pais: '<?= htmlspecialchars($data['pais'] ?? 'España') ?>'
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
                    
                    if (step === 5) {
                        if (!this.formData.nombre_comercial || this.formData.nombre_comercial.trim() === '') {
                            this.errors.nombre_comercial = 'El nombre comercial de la clínica es obligatorio.';
                        }
                        if (!this.formData.telefono_contacto || this.formData.telefono_contacto.trim() === '') {
                            this.errors.telefono_contacto = 'El teléfono de contacto de la clínica es obligatorio.';
                        }
                    }
                    
                    if (step === 6) {
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
                    // Validar paso final antes de enviar
                    if (!this.validateStep(1) || !this.validateStep(2) || !this.validateStep(5) || !this.validateStep(6)) {
                        e.preventDefault();
                        alert('Por favor, compruebe que todos los campos obligatorios están rellenos correctamente.');
                    }
                }
            }
        }
    </script>
</body>

</html>
