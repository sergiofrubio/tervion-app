<?php
$pageTitle = "Configuración";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up" x-data="{ activeTab: localStorage.getItem('settings_active_tab') || 'clinica' }" x-init="$watch('activeTab', value => localStorage.setItem('settings_active_tab', value))">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Configuración del Sistema</h1>
            <p class="mt-1 text-sm text-gray-500">Gestiona la clínica, servicios y tipos de citas, salas y despachos, cupones de descuento, bonos y suscripción.</p>
        </div>
    </div>

    <!-- Mensajes de Estado -->
    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="rounded-2xl bg-green-50 p-4 border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
            <p class="text-sm font-medium text-green-800"><?= $_SESSION['success_message'];
                                                            unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="rounded-2xl bg-red-50 p-4 border border-red-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
            <p class="text-sm font-medium text-red-800"><?= $_SESSION['error_message'];
                                                        unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['warning_message'])) : ?>
        <div class="rounded-2xl bg-amber-50 p-4 border border-amber-200 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-exclamation-triangle-fill text-amber-500 text-lg"></i>
            <p class="text-sm font-medium text-amber-800"><?= $_SESSION['warning_message'];
                                                            unset($_SESSION['warning_message']); ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
        <!-- Tabs Header -->
        <div class="border-b border-gray-100 bg-gray-50/30 px-6 pt-4">
            <nav class="-mb-px flex space-x-6 sm:space-x-8 overflow-x-auto" aria-label="Tabs">
                <button
                    @click="activeTab = 'clinica'"
                    :class="activeTab === 'clinica' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-building"></i>
                    Clínica
                </button>
                <button
                    @click="activeTab = 'tipos_citas'"
                    :class="activeTab === 'tipos_citas' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-tags"></i>
                    Tipos de Citas
                </button>
                <button
                    @click="activeTab = 'despachos'"
                    :class="activeTab === 'despachos' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-door-open"></i>
                    Despachos
                </button>
                <button
                    @click="activeTab = 'descuentos'"
                    :class="activeTab === 'descuentos' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-percent"></i>
                    Descuentos
                </button>
                <button
                    @click="activeTab = 'bonos'"
                    :class="activeTab === 'bonos' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-ticket-perforated"></i>
                    Bonos
                </button>
                <button
                    @click="activeTab = 'sistema'"
                    :class="activeTab === 'sistema' ? 'border-primary-600 text-primary-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all flex items-center gap-2">
                    <i class="bi bi-info-circle"></i>
                    Sistema y Licencia
                </button>
            </nav>
        </div>

        <!-- Tab Panels -->
        <div class="p-6 flex-1">

            <!-- Clínica Tab -->
            <div x-show="activeTab === 'clinica'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Datos de la Clínica</h4>
                </div>

                <form action="<?= PROJECT_ROOT ?>/configuracion/clinica/actualizar" method="POST" enctype="multipart/form-data" class="max-w-4xl">
                    <?php if ($clinica) : ?>
                        <input type="hidden" name="clinica_id" value="<?= $clinica['clinica_id'] ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre Comercial</label>
                            <input type="text" name="nombre_comercial" value="<?= $clinica['nombre_comercial'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Clínica Tervion">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Razón Social</label>
                            <input type="text" name="razon_social" value="<?= $clinica['razon_social'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Tervion S.L.">
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dirección</label>
                            <input type="text" name="direccion" value="<?= $clinica['direccion'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Calle, número, piso...">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ciudad</label>
                            <input type="text" name="ciudad" value="<?= $clinica['ciudad'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Madrid">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Provincia / Estado</label>
                            <input type="text" name="provincia_estado" value="<?= $clinica['provincia_estado'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: Madrid">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Código Postal</label>
                            <input type="text" name="codigo_postal" value="<?= $clinica['codigo_postal'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: 28001">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">País</label>
                            <input type="text" name="pais" value="<?= $clinica['pais'] ?? 'España' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Teléfono</label>
                            <input type="text" name="telefono_contacto" value="<?= $clinica['telefono_contacto'] ?? '' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: 910 00 00 00">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</label>
                            <input type="email" name="email_contacto" value="<?= $clinica['email_contacto'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="contacto@clinica.com">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">NIF / CIF Emisor</label>
                            <input type="text" name="nif_cif" value="<?= $clinica['nif_cif'] ?? 'B12345678' ?>" required
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="Ej: B12345678">
                        </div>

                        <div class="space-y-1 md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sitio Web</label>
                            <input type="url" name="sitio_web" value="<?= $clinica['sitio_web'] ?? '' ?>"
                                class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                placeholder="https://www.clinica.com">
                        </div>
                    </div>

                    <!-- Configuración Verifactu -->
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-900">Configuración Verifactu (AEAT)</h5>
                                <p class="text-xs text-gray-500">Ajustes para la remisión de registros de facturación con la Agencia Tributaria.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Entorno de Trabajo</label>
                                <select name="verifactu_env" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                                    <option value="pruebas" <?= ($clinica['verifactu_env'] ?? 'pruebas') === 'pruebas' ? 'selected' : '' ?>>Pruebas / Sandbox (prewww1.aeat.es)</option>
                                    <option value="produccion" <?= ($clinica['verifactu_env'] ?? '') === 'produccion' ? 'selected' : '' ?>>Producción (www1.agenciatributaria.gob.es)</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Certificado Digital (.p12 / .pfx / .pem)</label>
                                <input type="file" name="verifactu_cert_file" accept=".p12,.pfx,.pem"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 transition-all border border-gray-200 rounded-xl bg-white">
                                <?php if (!empty($clinica['verifactu_cert_path'])) : ?>
                                    <p class="text-xs text-emerald-600 font-medium mt-1.5 flex items-center gap-1">
                                        <i class="bi bi-file-earmark-check-fill text-sm"></i> Certificado activo: <span class="font-mono bg-emerald-50 px-2 py-0.5 rounded text-emerald-800"><?= htmlspecialchars(basename($clinica['verifactu_cert_path'])) ?></span>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Contraseña Certificado</label>
                                <input type="password" name="verifactu_cert_password" value="<?= htmlspecialchars($clinica['verifactu_cert_password'] ?? '') ?>"
                                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                                    placeholder="••••••••">
                            </div>

                            <div class="space-y-1 flex items-center pt-5">
                                <label class="inline-flex items-center cursor-pointer gap-3">
                                    <input type="checkbox" name="verifactu_activo" value="1" <?= (!isset($clinica['verifactu_activo']) || $clinica['verifactu_activo']) ? 'checked' : '' ?> class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                    <span class="text-sm font-medium text-gray-900">Activar comunicación automática con AEAT</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            <i class="bi bi-save"></i>
                            <?= $clinica ? 'Guardar Cambios' : 'Crear Registro' ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tipos de Citas Tab -->
            <div x-show="activeTab === 'tipos_citas'" x-cloak x-transition x-data="{
                modalOpen: false,
                isEditing: false,
                form: { tipo_cita_id: '', nombre: '', duracion_minutos: 60, precio: '0.00', color: '#3b82f6', descripcion: '', estado: 'Activo' },
                openCreate() {
                    this.isEditing = false;
                    this.form = { tipo_cita_id: '', nombre: '', duracion_minutos: 60, precio: '50.00', color: '#3b82f6', descripcion: '', estado: 'Activo' };
                    this.modalOpen = true;
                },
                openEdit(item) {
                    this.isEditing = true;
                    this.form = {
                        tipo_cita_id: item.tipo_cita_id,
                        nombre: item.nombre,
                        duracion_minutos: item.duracion_minutos,
                        precio: item.precio,
                        color: item.color || '#3b82f6',
                        descripcion: item.descripcion || '',
                        estado: item.estado || 'Activo'
                    };
                    this.modalOpen = true;
                }
            }">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Tipos de Citas y Tratamientos</h4>
                        <p class="text-xs text-gray-500">Configura la duración, tarifa base y color identificativo en la agenda de cada servicio.</p>
                    </div>
                    <button @click="openCreate()" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Tipo de Cita
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-2xs">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Identificador</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre del Servicio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duración</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($tipos_citas)) : ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay tipos de citas definidos. Haz clic en "Nuevo Tipo de Cita" para añadir uno.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($tipos_citas as $tc) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm">
                                            <div class="flex items-center gap-2">
                                                <span class="w-4 h-4 rounded-full border border-black/10 shadow-2xs inline-block shrink-0" style="background-color: <?= htmlspecialchars($tc['color'] ?? '#3b82f6') ?>"></span>
                                                <span class="font-mono text-xs text-gray-500 font-medium"><?= htmlspecialchars($tc['color'] ?? '#3b82f6') ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            <div class="font-semibold text-gray-900"><?= htmlspecialchars($tc['nombre']) ?></div>
                                            <?php if (!empty($tc['descripcion'])) : ?>
                                                <div class="text-xs text-gray-500 line-clamp-1 max-w-sm"><?= htmlspecialchars($tc['descripcion']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            <span class="inline-flex items-center gap-1 font-medium bg-gray-100 px-2 py-0.5 rounded-lg text-xs text-gray-700">
                                                <i class="bi bi-clock"></i> <?= (int)$tc['duracion_minutos'] ?> min
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-semibold text-primary-600">
                                            <?= number_format((float)($tc['precio'] ?? 0), 2, ',', '.') ?> €
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <?php if (($tc['estado'] ?? 'Activo') === 'Activo') : ?>
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Activo</span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <div class="inline-flex items-center gap-1">
                                                <button @click="openEdit(<?= htmlspecialchars(json_encode($tc), ENT_QUOTES, 'UTF-8') ?>)" class="text-gray-400 hover:text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition-all" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="<?= PROJECT_ROOT ?>/configuracion/tipos-citas/eliminar" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este tipo de cita?');">
                                                    <input type="hidden" name="tipo_cita_id" value="<?= $tc['tipo_cita_id'] ?>">
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Tipo Cita -->
                <template x-teleport="body">
                    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100">
                                <form action="<?= PROJECT_ROOT ?>/configuracion/tipos-citas/guardar" method="POST" class="p-6 sm:p-8 space-y-5">
                                    <input type="hidden" name="tipo_cita_id" x-model="form.tipo_cita_id">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                        <h3 class="text-lg font-bold text-gray-900" x-text="isEditing ? 'Editar Tipo de Cita' : 'Nuevo Tipo de Cita'"></h3>
                                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre del Servicio *</label>
                                            <input type="text" name="nombre" x-model="form.nombre" required placeholder="Ej. Fisioterapia Deportiva" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Duración (minutos) *</label>
                                                <input type="number" name="duracion_minutos" x-model="form.duracion_minutos" min="5" step="5" required class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Precio Tarifa (€)</label>
                                                <input type="number" name="precio" x-model="form.precio" min="0" step="0.50" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Color Distintivo</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="color" name="color" x-model="form.color" class="h-10 w-14 rounded-xl border-gray-200 cursor-pointer p-0.5 bg-white">
                                                    <input type="text" x-model="form.color" class="w-full rounded-xl border-gray-200 text-xs font-mono uppercase focus:border-primary-500 focus:ring-primary-500">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                                                <select name="estado" x-model="form.estado" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <option value="Activo">Activo</option>
                                                    <option value="Inactivo">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Descripción / Indicaciones</label>
                                            <textarea name="descripcion" x-model="form.descripcion" rows="2" placeholder="Indicaciones para el paciente o el profesional..." class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                                        </div>
                                    </div>
                                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                                        <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">Cancelar</button>
                                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 shadow-sm inline-flex items-center gap-2">
                                            <i class="bi bi-check-lg"></i>
                                            <span x-text="isEditing ? 'Guardar Cambios' : 'Crear Tipo de Cita'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Despachos y Salas Tab -->
            <div x-show="activeTab === 'despachos'" x-cloak x-transition x-data="{
                modalOpen: false,
                isEditing: false,
                form: { despacho_id: '', nombre: '', ubicacion: '', capacidad: 1, equipamiento: '', color: '#6366f1', estado: 'Activo' },
                openCreate() {
                    this.isEditing = false;
                    this.form = { despacho_id: '', nombre: '', ubicacion: '', capacidad: 1, equipamiento: '', color: '#6366f1', estado: 'Activo' };
                    this.modalOpen = true;
                },
                openEdit(item) {
                    this.isEditing = true;
                    this.form = {
                        despacho_id: item.despacho_id,
                        nombre: item.nombre,
                        ubicacion: item.ubicacion || '',
                        capacidad: item.capacidad || 1,
                        equipamiento: item.equipamiento || '',
                        color: item.color || '#6366f1',
                        estado: item.estado || 'Activo'
                    };
                    this.modalOpen = true;
                }
            }">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Despachos, Cabinas y Salas</h4>
                        <p class="text-xs text-gray-500">Administra los espacios físicos de consulta, su aforo simultáneo y dotación de equipamiento.</p>
                    </div>
                    <button @click="openCreate()" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Despacho / Sala
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-2xs">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Espacio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Capacidad</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Equipamiento</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($despachos)) : ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay despachos o salas registradas. Añade tu primera sala de consulta.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($despachos as $despacho) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-3.5 h-3.5 rounded-md shrink-0 shadow-2xs" style="background-color: <?= htmlspecialchars($despacho['color'] ?? '#6366f1') ?>"></span>
                                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($despacho['nombre']) ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            <?= !empty($despacho['ubicacion']) ? htmlspecialchars($despacho['ubicacion']) : '<span class="text-gray-400 italic">No especificada</span>' ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            <span class="inline-flex items-center gap-1 font-medium bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg text-xs">
                                                <i class="bi bi-people-fill"></i> <?= (int)$despacho['capacidad'] ?> <?= ((int)$despacho['capacidad'] === 1 ? 'paciente' : 'pacientes') ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500 max-w-xs">
                                            <?= !empty($despacho['equipamiento']) ? htmlspecialchars($despacho['equipamiento']) : '<span class="text-gray-400 italic">Estándar</span>' ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <?php if (($despacho['estado'] ?? 'Activo') === 'Activo') : ?>
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Activo</span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <div class="inline-flex items-center gap-1">
                                                <button @click="openEdit(<?= htmlspecialchars(json_encode($despacho), ENT_QUOTES, 'UTF-8') ?>)" class="text-gray-400 hover:text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition-all" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="<?= PROJECT_ROOT ?>/configuracion/despachos/eliminar" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este despacho?');">
                                                    <input type="hidden" name="despacho_id" value="<?= $despacho['despacho_id'] ?>">
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Despacho -->
                <template x-teleport="body">
                    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100">
                                <form action="<?= PROJECT_ROOT ?>/configuracion/despachos/guardar" method="POST" class="p-6 sm:p-8 space-y-5">
                                    <input type="hidden" name="despacho_id" x-model="form.despacho_id">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                        <h3 class="text-lg font-bold text-gray-900" x-text="isEditing ? 'Editar Despacho / Sala' : 'Nuevo Despacho / Sala'"></h3>
                                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre de la Sala / Despacho *</label>
                                            <input type="text" name="nombre" x-model="form.nombre" required placeholder="Ej. Despacho 2 / Cabina Diatermia" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Ubicación / Planta</label>
                                                <input type="text" name="ubicacion" x-model="form.ubicacion" placeholder="Ej. Planta 1 - Sala 3" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Aforo / Capacidad *</label>
                                                <input type="number" name="capacidad" x-model="form.capacidad" min="1" required class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Color de Distintivo</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="color" name="color" x-model="form.color" class="h-10 w-14 rounded-xl border-gray-200 cursor-pointer p-0.5 bg-white">
                                                    <input type="text" x-model="form.color" class="w-full rounded-xl border-gray-200 text-xs font-mono uppercase focus:border-primary-500 focus:ring-primary-500">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                                                <select name="estado" x-model="form.estado" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <option value="Activo">Activo</option>
                                                    <option value="Inactivo">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Equipamiento y Notas</label>
                                            <textarea name="equipamiento" x-model="form.equipamiento" rows="2" placeholder="Camillas, ecógrafo, tecarterapia, etc..." class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                                        </div>
                                    </div>
                                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                                        <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">Cancelar</button>
                                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 shadow-sm inline-flex items-center gap-2">
                                            <i class="bi bi-check-lg"></i>
                                            <span x-text="isEditing ? 'Guardar Cambios' : 'Crear Despacho'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Códigos de Descuento Tab -->
            <div x-show="activeTab === 'descuentos'" x-cloak x-transition x-data="{
                modalOpen: false,
                isEditing: false,
                form: {
                    codigo_id: '',
                    codigo: '',
                    descripcion: '',
                    tipo_descuento: 'porcentaje',
                    valor: '10.00',
                    monto_minimo: '0.00',
                    usos_ilimitados: true,
                    usos_maximos: '',
                    fecha_inicio: '',
                    fecha_fin: '',
                    estado: 'Activo'
                },
                openCreate() {
                    this.isEditing = false;
                    this.form = {
                        codigo_id: '',
                        codigo: '',
                        descripcion: '',
                        tipo_descuento: 'porcentaje',
                        valor: '10.00',
                        monto_minimo: '0.00',
                        usos_ilimitados: true,
                        usos_maximos: '',
                        fecha_inicio: '',
                        fecha_fin: '',
                        estado: 'Activo'
                    };
                    this.modalOpen = true;
                },
                openEdit(item) {
                    this.isEditing = true;
                    this.form = {
                        codigo_id: item.codigo_id,
                        codigo: item.codigo,
                        descripcion: item.descripcion || '',
                        tipo_descuento: item.tipo_descuento || 'porcentaje',
                        valor: item.valor,
                        monto_minimo: item.monto_minimo || '0.00',
                        usos_ilimitados: item.usos_maximos === null || item.usos_maximos === '' || item.usos_maximos == 0,
                        usos_maximos: item.usos_maximos || '',
                        fecha_inicio: item.fecha_inicio ? item.fecha_inicio.substring(0, 10) : '',
                        fecha_fin: item.fecha_fin ? item.fecha_fin.substring(0, 10) : '',
                        estado: item.estado || 'Activo'
                    };
                    this.modalOpen = true;
                }
            }">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-base font-bold text-gray-900">Cupones y Códigos de Descuento</h4>
                        <p class="text-xs text-gray-500">Crea promociones con descuentos porcentuales o fijos, límites de canjes y fechas de validez.</p>
                    </div>
                    <button @click="openCreate()" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Código
                    </button>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-2xs">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Descuento</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Validez</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usos / Límite</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gasto Mínimo</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($descuentos)) : ?>
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay cupones de descuento activos. Crea uno para comenzar promociones.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($descuentos as $desc) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            <div class="inline-flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 text-indigo-800 font-mono font-bold px-2.5 py-1 rounded-lg text-xs">
                                                <i class="bi bi-tag-fill text-indigo-500"></i>
                                                <?= htmlspecialchars($desc['codigo']) ?>
                                            </div>
                                            <?php if (!empty($desc['descripcion'])) : ?>
                                                <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($desc['descripcion']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-bold text-gray-900">
                                            <?php if ($desc['tipo_descuento'] === 'porcentaje') : ?>
                                                <span class="text-emerald-600 font-semibold"><?= rtrim(rtrim(number_format((float)$desc['valor'], 2), '0'), '.') ?>% OFF</span>
                                            <?php else : ?>
                                                <span class="text-emerald-600 font-semibold"><?= number_format((float)$desc['valor'], 2, ',', '.') ?> € OFF</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600">
                                            <?php if (!empty($desc['fecha_inicio']) || !empty($desc['fecha_fin'])) : ?>
                                                <div><?= !empty($desc['fecha_inicio']) ? date('d/m/Y', strtotime($desc['fecha_inicio'])) : 'Desde siempre' ?></div>
                                                <div class="text-gray-400">hasta <?= !empty($desc['fecha_fin']) ? date('d/m/Y', strtotime($desc['fecha_fin'])) : 'Sin límite' ?></div>
                                            <?php else : ?>
                                                <span class="text-gray-400 italic">Permanente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-700">
                                            <div class="font-medium">
                                                <?= (int)$desc['usos_actuales'] ?>
                                                <?php if (!empty($desc['usos_maximos'])) : ?>
                                                    <span class="text-gray-400">/ <?= (int)$desc['usos_maximos'] ?></span>
                                                <?php else : ?>
                                                    <span class="text-emerald-600 font-medium">/ ∞</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600">
                                            <?= ((float)($desc['monto_minimo'] ?? 0) > 0) ? number_format((float)$desc['monto_minimo'], 2, ',', '.') . ' €' : '<span class="text-gray-400">Sin mínimo</span>' ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <?php if (($desc['estado'] ?? 'Activo') === 'Activo') : ?>
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Activo</span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center rounded-full bg-gray-50 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <div class="inline-flex items-center gap-1">
                                                <button @click="openEdit(<?= htmlspecialchars(json_encode($desc), ENT_QUOTES, 'UTF-8') ?>)" class="text-gray-400 hover:text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition-all" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="<?= PROJECT_ROOT ?>/configuracion/descuentos/eliminar" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este código de descuento?');">
                                                    <input type="hidden" name="codigo_id" value="<?= $desc['codigo_id'] ?>">
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Descuento -->
                <template x-teleport="body">
                    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="modalOpen = false"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100">
                                <form action="<?= PROJECT_ROOT ?>/configuracion/descuentos/guardar" method="POST" class="p-6 sm:p-8 space-y-5">
                                    <input type="hidden" name="codigo_id" x-model="form.codigo_id">
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                                        <h3 class="text-lg font-bold text-gray-900" x-text="isEditing ? 'Editar Cupón de Descuento' : 'Nuevo Cupón de Descuento'"></h3>
                                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Código Promocional *</label>
                                                <input type="text" name="codigo" x-model="form.codigo" @input="form.codigo = form.codigo.toUpperCase().replace(/[^A-Z0-9_-]/g, '')" required placeholder="EJ: BIENVENIDA20" class="w-full rounded-xl border-gray-200 text-sm font-mono uppercase font-bold focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tipo de Descuento *</label>
                                                <select name="tipo_descuento" x-model="form.tipo_descuento" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <option value="porcentaje">Porcentaje (%)</option>
                                                    <option value="fijo">Importe Fijo (€)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Valor *</label>
                                                <div class="relative">
                                                    <input type="number" name="valor" x-model="form.valor" min="0.01" step="0.01" required class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <span class="absolute right-3 top-2.5 text-xs text-gray-400 font-bold" x-text="form.tipo_descuento === 'porcentaje' ? '%' : '€'"></span>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Gasto Mínimo (€)</label>
                                                <input type="number" name="monto_minimo" x-model="form.monto_minimo" min="0" step="0.50" placeholder="0.00" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                        </div>
                                        <div class="p-3 bg-gray-50 rounded-xl space-y-2 border border-gray-100">
                                            <label class="inline-flex items-center cursor-pointer gap-2.5">
                                                <input type="checkbox" name="usos_ilimitados" value="1" x-model="form.usos_ilimitados" class="rounded text-primary-600 focus:ring-primary-500 h-4 w-4">
                                                <span class="text-xs font-semibold text-gray-800">Usos Ilimitados (Sin tope de canjes)</span>
                                            </label>
                                            <div x-show="!form.usos_ilimitados" x-transition>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Número Máximo de Usos</label>
                                                <input type="number" name="usos_maximos" x-model="form.usos_maximos" min="1" placeholder="Ej: 50" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 bg-white">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Válido Desde</label>
                                                <input type="date" name="fecha_inicio" x-model="form.fecha_inicio" class="w-full rounded-xl border-gray-200 text-xs focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Válido Hasta</label>
                                                <input type="date" name="fecha_fin" x-model="form.fecha_fin" class="w-full rounded-xl border-gray-200 text-xs focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Nombre / Campaña</label>
                                                <input type="text" name="descripcion" x-model="form.descripcion" placeholder="Ej. Campaña Black Friday 2026" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                                                <select name="estado" x-model="form.estado" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500">
                                                    <option value="Activo">Activo</option>
                                                    <option value="Inactivo">Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                                        <button type="button" @click="modalOpen = false" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50">Cancelar</button>
                                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 shadow-sm inline-flex items-center gap-2">
                                            <i class="bi bi-check-lg"></i>
                                            <span x-text="isEditing ? 'Guardar Cambios' : 'Crear Cupón'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Bonos Tab -->
            <div x-show="activeTab === 'bonos'" x-cloak x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-base font-bold text-gray-900">Bonos de Sesiones</h4>
                    <a href="<?= PROJECT_ROOT ?>/configuracion/bonos/crear" class="inline-flex items-center gap-1.5 rounded-xl bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-700 transition-all">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Bono
                    </a>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sesiones</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <?php if (empty($bonos)) : ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 italic">No hay bonos registrados.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($bonos as $bono) : ?>
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900"><?= $bono['nombre'] ?></td>
                                        <td class="px-4 py-3 text-sm text-gray-600"><?= $bono['numero_sesiones'] ?></td>
                                        <td class="px-4 py-3 text-sm font-medium text-primary-600"><?= number_format($bono['precio'], 2) ?> €</td>
                                        <td class="px-4 py-3 text-sm">
                                            <?php if ($bono['estado'] == 'Activo') : ?>
                                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                                            <?php else : ?>
                                                <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/20">Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm">
                                            <a href="<?= PROJECT_ROOT ?>/configuracion/bonos/editar?id=<?= $bono['bono_id'] ?>" class="text-gray-400 hover:text-amber-500 hover:bg-amber-50 p-1.5 rounded-lg transition-all inline-block" title="Editar"><i class="bi bi-pencil"></i></a>
                                            <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-1.5 rounded-lg transition-all" title="Eliminar"><i class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sistema y Licencia Tab (Open Source) -->
            <div x-show="activeTab === 'sistema'" x-cloak x-transition>
                <div class="max-w-4xl space-y-6">
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/80">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary-100 border border-primary-200 flex items-center justify-center text-primary-700 text-2xl shrink-0">
                                <i class="bi bi-patch-check"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-base font-bold text-slate-900">Tervion ERP — Edición Comunitaria (Open Source)</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Esta instancia está desplegada de forma autoalojada y libre. Tienes acceso al 100% de las funcionalidades del software médico y contable sin restricciones artificiales ni cuotas de licencia.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Licencia</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm">GNU LGPLv3</span>
                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">Libre</span>
                            </div>
                            <p class="text-xs text-slate-500">Permite libre uso, modificación y desarrollo de módulos adicionales.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Entorno Runtime</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm">PHP <?= phpversion() ?></span>
                                <span class="text-xs text-slate-400 font-mono"><?= APP_ENV ?></span>
                            </div>
                            <p class="text-xs text-slate-500">Ejecutándose en arquitectura de contenedores Docker / Caddy.</p>
                        </div>

                        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-2xs space-y-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Soberanía de Datos</span>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm">Base de Datos Local</span>
                                <i class="bi bi-shield-lock-fill text-emerald-600"></i>
                            </div>
                            <p class="text-xs text-slate-500">Tus historias clínicas y facturas se almacenan en tu propia infraestructura.</p>
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-2xs space-y-4">
                        <h5 class="text-xs font-bold uppercase tracking-wider text-slate-700">Enlaces y Recursos del Proyecto</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <a href="<?= PROJECT_ROOT ?>/documentos" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-primary-200 hover:bg-primary-50/50 transition-colors group">
                                <span class="font-medium text-slate-700 group-hover:text-primary-700 flex items-center gap-2">
                                    <i class="bi bi-journal-text text-primary-500"></i>
                                    Plantillas y Documentación RGPD
                                </span>
                                <i class="bi bi-arrow-right text-slate-400 group-hover:text-primary-600"></i>
                            </a>
                            <a href="<?= PROJECT_ROOT ?>/contabilidad" class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-primary-200 hover:bg-primary-50/50 transition-colors group">
                                <span class="font-medium text-slate-700 group-hover:text-primary-700 flex items-center gap-2">
                                    <i class="bi bi-cash-stack text-primary-500"></i>
                                    Módulo Fiscal y Verifactu
                                </span>
                                <i class="bi bi-arrow-right text-slate-400 group-hover:text-primary-600"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>