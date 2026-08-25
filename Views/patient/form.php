<?php
$u = $usuario ?? [];
$isEdit = !empty($u);
$pageTitle = $isEdit ? "Editar Paciente" : "Registrar Paciente";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-5xl mx-auto animate-fade-in-up">
    <!-- Header -->
    <div class="mb-5 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Paciente" : "Nuevo Paciente" ?></h1>
            <p class="mt-0.5 text-xs sm:text-sm text-gray-500"><?= $isEdit ? "Modifica la información personal del paciente." : "Completa los datos para registrar un nuevo paciente en la clínica." ?></p>
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
                        <label for="usuario_id" class="block text-xs font-semibold text-gray-700">DNI / NIE / ID</label>
                        <input type="text" name="usuario_id" id="usuario_id" required maxlength="9"
                            value="<?= htmlspecialchars($u['usuario_id'] ?? '') ?>"
                            <?= $isEdit ? 'disabled class="block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-500 text-sm border px-3 py-2 cursor-not-allowed"' : 'class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm border px-3 py-2 transition-all"' ?>
                            placeholder="Ej. 12345678X">
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="usuario_id" value="<?= $u['usuario_id'] ?>">
                        <?php endif; ?>
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