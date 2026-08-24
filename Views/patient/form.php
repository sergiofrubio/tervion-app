<?php
$u = $usuario ?? [];
$isEdit = !empty($u);
$pageTitle = $isEdit ? "Editar Paciente" : "Registrar Paciente";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto animate-fade-in-up">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Paciente" : "Nuevo Paciente" ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= $isEdit ? "Modifica la información personal del paciente." : "Completa los datos para registrar un nuevo paciente en la clínica." ?></p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/pacientes" onclick="if (history.length > 1) { history.back(); return false; }" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/pacientes/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="p-8">
            <?php if ($isEdit): ?>
                <input type="hidden" name="usuario_id" value="<?= $u['usuario_id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Información Personal Section -->
                <div class="space-y-6 md:col-span-2 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person text-primary-600"></i>
                        Información Personal
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="usuario_id" class="block text-sm font-medium text-gray-700">DNI / NIE / ID</label>
                    <input type="text" name="usuario_id" id="usuario_id" required maxlength="9"
                        value="<?= htmlspecialchars($u['usuario_id'] ?? '') ?>"
                        <?= $isEdit ? 'disabled class="block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 sm:text-sm border p-3 cursor-not-allowed"' : 'class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"' ?>
                        placeholder="Ej. 12345678X">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="usuario_id" value="<?= $u['usuario_id'] ?>">
                    <?php endif; ?>
                </div>

                <div class="space-y-2">
                    <label for="genero" class="block text-sm font-medium text-gray-700">Género</label>
                    <select name="genero" id="genero" required
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all bg-white">
                        <option value="Hombre" <?= ($u['genero'] ?? '') === 'Hombre' ? 'selected' : '' ?>>Hombre</option>
                        <option value="Mujer" <?= ($u['genero'] ?? '') === 'Mujer' ? 'selected' : '' ?>>Mujer</option>
                        <option value="Otro" <?= ($u['genero'] ?? '') === 'Otro' ? 'selected' : '' ?>>Otro</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="nombre" id="nombre" required
                        value="<?= htmlspecialchars($u['nombre'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. Juan">
                </div>

                <div class="space-y-2">
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" required
                        value="<?= htmlspecialchars($u['apellidos'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. Pérez García">
                </div>

                <div class="space-y-2">
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required
                        value="<?= $u['fecha_nacimiento'] ?? '' ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all">
                </div>

                <!-- Contacto Section -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-envelope-at text-primary-600"></i>
                        Contacto y Ubicación
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" name="email" id="email" required
                        value="<?= htmlspecialchars($u['email'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="juan@ejemplo.com">
                </div>

                <div class="space-y-2">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" name="telefono" id="telefono"
                        value="<?= htmlspecialchars($u['telefono'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. 600 000 000">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="direccion" id="direccion"
                        value="<?= htmlspecialchars($u['direccion'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Calle, número, piso, puerta">
                </div>

                <div class="space-y-2">
                    <label for="municipio" class="block text-sm font-medium text-gray-700">Municipio</label>
                    <input type="text" name="municipio" id="municipio"
                        value="<?= htmlspecialchars($u['municipio'] ?? '') ?>"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="Ej. Madrid">
                </div>

                <div class="flex gap-4">
                    <div class="flex-1 space-y-2">
                        <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                        <input type="text" name="provincia" id="provincia"
                            value="<?= htmlspecialchars($u['provincia'] ?? '') ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                            placeholder="Ej. Madrid">
                    </div>
                    <div class="w-24 space-y-2">
                        <label for="cp" class="block text-sm font-medium text-gray-700">C.P.</label>
                        <input type="text" name="cp" id="cp" maxlength="5"
                            value="<?= htmlspecialchars($u['cp'] ?? '') ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                            placeholder="28001">
                    </div>
                </div>

                <!-- Seguridad Section
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-shield-lock text-primary-600"></i>
                        Seguridad
                    </h2>
                </div>

                <div class="space-y-2">
                    <label for="pass" class="block text-sm font-medium text-gray-700"><?= $isEdit ? "Cambiar Contraseña" : "Contraseña de Acceso" ?></label>
                    <input type="password" name="pass" id="pass"
                        class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 transition-all"
                        placeholder="<?= $isEdit ? 'Nueva contraseña' : 'Mínimo 6 caracteres' ?>">
                    <p class="text-xs text-gray-500"><?= $isEdit ? "Dejar en blanco para mantener la contraseña actual." : "Si se deja vacío, será '123456' por defecto." ?></p>
                </div> -->
                <!-- Protección de Datos (RGPD / LOPD) Section -->
                <div class="space-y-6 md:col-span-2 pt-6 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-shield-check text-primary-600"></i>
                        Protección de Datos (RGPD / LOPD-GDD)
                    </h2>
                </div>

                <div class="md:col-span-2 space-y-4 bg-gray-50/70 p-6 rounded-2xl border border-gray-100">
                    <div class="text-xs text-gray-600 leading-relaxed space-y-2">
                        <p class="font-medium text-gray-800 text-sm">Cláusula informativa de consentimiento de datos de salud y atención clínica:</p>
                        <p>En cumplimiento de lo dispuesto en el Reglamento General de Protección de Datos (UE 2016/679) y la LOPDGDD 3/2018, los datos personales recabados y los generados en su historia clínica serán tratados por la clínica con la finalidad de prestarle la asistencia sanitaria solicitada, gestionar sus citas y facturación. Sus datos clínicos no serán cedidos a terceros salvo obligación legal o necesidad técnica justificada.</p>
                    </div>

                    <div class="flex items-start gap-3 pt-2">
                        <input type="checkbox" name="rgpd_aceptado" id="rgpd_aceptado" value="1" required
                            <?= !empty($u['rgpd_aceptado']) ? 'checked' : '' ?>
                            class="mt-1 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 cursor-pointer">
                        <label for="rgpd_aceptado" class="text-sm font-medium text-gray-800 cursor-pointer">
                            Confirmo que el paciente ha sido informado y <strong>acepta de forma expresa el tratamiento de sus datos personales y clínicos</strong> en los términos indicados.
                        </label>
                    </div>

                    <!-- Canvas de Firma Digital -->
                    <div class="pt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Firma Digital del Paciente / Tutor</label>
                        <div class="relative bg-white rounded-xl border border-gray-300 p-2 shadow-inner">
                            <canvas id="signatureCanvas" width="550" height="150" class="w-full h-36 touch-none cursor-crosshair rounded-lg bg-gray-50/50"></canvas>
                            <input type="hidden" name="firma_paciente" id="firma_paciente" value="<?= htmlspecialchars($u['firma_paciente'] ?? '') ?>">
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <button type="button" id="clearSignatureBtn" class="text-xs text-red-600 hover:text-red-800 font-medium inline-flex items-center gap-1">
                                <i class="bi bi-eraser"></i> Limpiar
                            </button>
                            <span id="signatureStatus" class="text-xs text-gray-500">
                                <?= !empty($u['firma_paciente']) ? 'Firma cargada previamente' : 'Dibuje la firma dentro del recuadro' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/pacientes" class="px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all">
                    <?= $isEdit ? "Guardar Cambios" : "Guardar Paciente" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('signatureCanvas');
        const hiddenInput = document.getElementById('firma_paciente');
        const clearBtn = document.getElementById('clearSignatureBtn');
        const statusText = document.getElementById('signatureStatus');

        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let isDrawing = false;
        let hasSignature = false;

        if (hiddenInput.value && hiddenInput.value.startsWith('data:image')) {
            const img = new Image();
            img.onload = function() {
                ctx.drawImage(img, 0, 0);
                hasSignature = true;
            };
            img.src = hiddenInput.value;
        }

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            let clientX = e.clientX;
            let clientY = e.clientY;

            if (e.touches && e.touches.length > 0) {
                clientX = e.touches[0].clientX;
                clientY = e.touches[0].clientY;
            }

            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        function startDrawing(e) {
            isDrawing = true;
            const pos = getPos(e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            ctx.lineWidth = 2.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.strokeStyle = '#1e293b';
            e.preventDefault();
        }

        function draw(e) {
            if (!isDrawing) return;
            const pos = getPos(e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            hasSignature = true;
            statusText.textContent = 'Firma capturada';
            e.preventDefault();
        }

        function stopDrawing() {
            if (isDrawing) {
                isDrawing = false;
                if (hasSignature) {
                    hiddenInput.value = canvas.toDataURL('image/png');
                }
            }
        }

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseleave', stopDrawing);

        canvas.addEventListener('touchstart', startDrawing);
        canvas.addEventListener('touchmove', draw);
        canvas.addEventListener('touchend', stopDrawing);

        clearBtn.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            hiddenInput.value = '';
            hasSignature = false;
            statusText.textContent = 'Dibuje la firma dentro del recuadro';
        });
    });
</script>

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