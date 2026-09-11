<?php
$u = $usuario ?? [];
$f = $ficha ?? [];
$isEdit = !empty($u);
$pageTitle = $isEdit ? "Editar Paciente" : "Registrar Paciente";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-5xl mx-auto animate-fade-in-up">
    <!-- Header -->
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Paciente" : "Nuevo Paciente" ?></h1>
            <p class="mt-0.5 text-xs sm:text-sm text-gray-500"><?= $isEdit ? "Modifica la información personal y la ficha del paciente." : "Completa los datos para registrar un nuevo paciente y su ficha en la clínica." ?></p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/pacientes" onclick="if (history.length > 1) { history.back(); return false; }" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/pacientes/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="p-6 md:p-8 space-y-6">
            <?php if ($isEdit): ?>
                <input type="hidden" name="usuario_id" value="<?= $u['usuario_id'] ?>">
            <?php endif; ?>

            <!-- Información Personal Section -->
            <div>
                <div class="pb-2.5 mb-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-person text-primary-600"></i>
                        Información Personal
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-3.5">
                    <div class="lg:col-span-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="dni" class="block text-xs font-semibold text-gray-700">DNI / NIE</label>
                            <span class="text-[10px] text-gray-400 font-normal">Opcional para menores</span>
                        </div>
                        <input type="text" name="dni" id="dni" maxlength="20"
                            value="<?= htmlspecialchars($u['dni'] ?? ($u['usuario_id'] && !is_numeric($u['usuario_id']) ? $u['usuario_id'] : '')) ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. 12345678X (Vacío si no tiene)">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="nombre" class="block text-xs font-semibold text-gray-700">Nombre</label>
                        <input type="text" name="nombre" id="nombre" required
                            value="<?= htmlspecialchars($u['nombre'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. Juan">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="apellidos" class="block text-xs font-semibold text-gray-700">Apellidos</label>
                        <input type="text" name="apellidos" id="apellidos" required
                            value="<?= htmlspecialchars($u['apellidos'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. Pérez García">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="genero" class="block text-xs font-semibold text-gray-700">Género</label>
                        <select name="genero" id="genero" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all bg-white">
                            <option value="Hombre" <?= ($u['genero'] ?? '') === 'Hombre' ? 'selected' : '' ?>>Hombre</option>
                            <option value="Mujer" <?= ($u['genero'] ?? '') === 'Mujer' ? 'selected' : '' ?>>Mujer</option>
                            <option value="Otro" <?= ($u['genero'] ?? '') === 'Otro' ? 'selected' : '' ?>>Otro</option>
                        </select>
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="fecha_nacimiento" class="block text-xs font-semibold text-gray-700">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                            value="<?= $u['fecha_nacimiento'] ?? '' ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all">
                    </div>
                </div>
            </div>

            <!-- Contacto Section -->
            <div>
                <div class="pb-2.5 mb-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-envelope-at text-primary-600"></i>
                        Contacto y Ubicación
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-3.5">
                    <div class="lg:col-span-6 space-y-1">
                        <label for="email" class="block text-xs font-semibold text-gray-700">Correo Electrónico</label>
                        <input type="email" name="email" id="email" required
                            value="<?= htmlspecialchars($u['email'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="juan@ejemplo.com">
                    </div>

                    <div class="lg:col-span-6 space-y-1">
                        <label for="telefono" class="block text-xs font-semibold text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" id="telefono"
                            value="<?= htmlspecialchars($u['telefono'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. 600 000 000">
                    </div>

                    <div class="sm:col-span-2 lg:col-span-12 space-y-1">
                        <label for="direccion" class="block text-xs font-semibold text-gray-700">Dirección</label>
                        <input type="text" name="direccion" id="direccion"
                            value="<?= htmlspecialchars($u['direccion'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Calle, número, piso, puerta">
                    </div>

                    <div class="lg:col-span-5 space-y-1">
                        <label for="municipio" class="block text-xs font-semibold text-gray-700">Municipio</label>
                        <input type="text" name="municipio" id="municipio"
                            value="<?= htmlspecialchars($u['municipio'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. Madrid">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="provincia" class="block text-xs font-semibold text-gray-700">Provincia</label>
                        <input type="text" name="provincia" id="provincia"
                            value="<?= htmlspecialchars($u['provincia'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. Madrid">
                    </div>

                    <div class="lg:col-span-3 space-y-1">
                        <label for="cp" class="block text-xs font-semibold text-gray-700">Código Postal</label>
                        <input type="text" name="cp" id="cp" maxlength="5"
                            value="<?= htmlspecialchars($u['cp'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="28001">
                    </div>
                </div>
            </div>

            <!-- Ficha del Paciente (Datos Administrativos, Tutor y Cobertura) Section -->
            <div>
                <div class="pb-2.5 mb-4 border-b border-gray-100">
                    <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="bi bi-card-heading text-primary-600"></i>
                        Ficha de Paciente (Administrativa y Cobertura)
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-x-4 gap-y-3.5">
                    <div class="lg:col-span-4 space-y-1">
                        <label for="numero_expediente" class="block text-xs font-semibold text-gray-700">Nº Expediente / Ficha</label>
                        <input type="text" name="numero_expediente" id="numero_expediente"
                            value="<?= htmlspecialchars($f['numero_expediente'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. EXP-2026-0001 (Automático)">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="compania_seguro" class="block text-xs font-semibold text-gray-700">Mutua / Compañía de Seguro</label>
                        <input type="text" name="compania_seguro" id="compania_seguro"
                            value="<?= htmlspecialchars($f['compania_seguro'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. Sanitas, Adeslas, Privado">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="numero_poliza" class="block text-xs font-semibold text-gray-700">Nº de Póliza</label>
                        <input type="text" name="numero_poliza" id="numero_poliza"
                            value="<?= htmlspecialchars($f['numero_poliza'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. POL-123456">
                    </div>

                    <!-- Datos Tutor Legal (Para menores) -->
                    <div class="sm:col-span-2 lg:col-span-12 pt-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tutor Legal o Responsable (Especialmente para menores de edad)</p>
                    </div>

                    <div class="lg:col-span-5 space-y-1">
                        <label for="nombre_tutor" class="block text-xs font-semibold text-gray-700">Nombre Completo del Tutor</label>
                        <input type="text" name="nombre_tutor" id="nombre_tutor"
                            value="<?= htmlspecialchars($f['nombre_tutor'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Nombre del padre, madre o tutor">
                    </div>

                    <div class="lg:col-span-3 space-y-1">
                        <label for="dni_tutor" class="block text-xs font-semibold text-gray-700">DNI / NIE Tutor</label>
                        <input type="text" name="dni_tutor" id="dni_tutor" maxlength="20"
                            value="<?= htmlspecialchars($f['dni_tutor'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="12345678X">
                    </div>

                    <div class="lg:col-span-4 space-y-1">
                        <label for="telefono_tutor" class="block text-xs font-semibold text-gray-700">Teléfono Tutor</label>
                        <input type="text" name="telefono_tutor" id="telefono_tutor"
                            value="<?= htmlspecialchars($f['telefono_tutor'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. 600 000 000">
                    </div>

                    <!-- Contacto Emergencia -->
                    <div class="lg:col-span-6 space-y-1">
                        <label for="contacto_emergencia_nombre" class="block text-xs font-semibold text-gray-700">Contacto de Emergencia (Nombre)</label>
                        <input type="text" name="contacto_emergencia_nombre" id="contacto_emergencia_nombre"
                            value="<?= htmlspecialchars($f['contacto_emergencia_nombre'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. María Pérez (Madre)">
                    </div>

                    <div class="lg:col-span-6 space-y-1">
                        <label for="contacto_emergencia_telefono" class="block text-xs font-semibold text-gray-700">Contacto de Emergencia (Teléfono)</label>
                        <input type="text" name="contacto_emergencia_telefono" id="contacto_emergencia_telefono"
                            value="<?= htmlspecialchars($f['contacto_emergencia_telefono'] ?? '') ?>"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. 611 222 333">
                    </div>

                    <div class="sm:col-span-2 lg:col-span-6 space-y-1">
                        <label for="alergias_alertas" class="block text-xs font-semibold text-gray-700">Alertas / Alergias Rápidas de Ficha</label>
                        <textarea name="alergias_alertas" id="alergias_alertas" rows="2"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Ej. Alergia al látex, marcapasos..."><?= htmlspecialchars($f['alergias_alertas'] ?? '') ?></textarea>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-6 space-y-1">
                        <label for="observaciones_administrativas" class="block text-xs font-semibold text-gray-700">Observaciones Administrativas de Ficha</label>
                        <textarea name="observaciones_administrativas" id="observaciones_administrativas" rows="2"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"
                            placeholder="Notas de recepción, facturación o gestión..."><?= htmlspecialchars($f['observaciones_administrativas'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/pacientes" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                    <?= $isEdit ? "Guardar Cambios" : "Guardar Paciente" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/patient/patient-form.js"></script>

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