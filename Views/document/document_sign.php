<?php
$isReadOnly = isset($isReadOnly) && $isReadOnly;
$isSigned = !empty($docPaciente['firmado']);
$pageTitle = ($isReadOnly ? "Ver Documento — " : "Rellenar y Firmar Documento — ") . htmlspecialchars($plantilla['titulo']);
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">

    <!-- Top Breadcrumb & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $paciente['usuario_id'] ?>"
                class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i class="bi bi-arrow-left"></i>
                <span>Volver al Expediente</span>
            </a>
            <div class="h-4 w-px bg-gray-300 hidden sm:block"></div>
            <span class="text-xs font-medium text-gray-400 hidden sm:inline-block">Documentación Clínica Digital</span>
        </div>

        <div class="flex items-center gap-3">
            <?php if ($isSigned): ?>
                <a href="<?= PROJECT_ROOT ?>/pacientes/documentos/pdf?paciente_id=<?= $paciente['usuario_id'] ?>&documento_id=<?= $plantilla['documento_id'] ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition-all shadow-sm">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span>Descargar PDF</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center font-bold text-2xl shrink-0 border border-primary-100 shadow-sm">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                            <?= htmlspecialchars($plantilla['titulo']) ?>
                        </h1>
                        <?php if ($isSigned): ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <i class="bi bi-check-circle-fill text-emerald-500"></i>
                                Firmado
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <i class="bi bi-clock-history text-amber-500"></i>
                                Pendiente de firma
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Paciente: <strong class="text-gray-800"><?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?></strong> (DNI: <?= htmlspecialchars($paciente['usuario_id']) ?>)
                        <?php if (!empty($docPaciente['fecha_firma'])): ?>
                            — Firmado el: <span class="text-gray-700 font-medium"><?= date('d/m/Y H:i', strtotime($docPaciente['fecha_firma'])) ?></span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Content & Signature Form -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden p-6 sm:p-8">
        <?php if ($isReadOnly): ?>
            <!-- Vista de Solo Lectura -->
            <div class="space-y-6">
                <div class="prose max-w-none text-gray-700 text-sm p-6 bg-gray-50/70 rounded-2xl border border-gray-200 min-h-[250px] leading-relaxed">
                    <?= $contenido ?>
                </div>

                <?php if (!empty($firma)): ?>
                    <div class="pt-6 border-t border-gray-200">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Firma Registrada del Paciente</label>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 inline-block">
                            <img src="<?= htmlspecialchars($firma) ?>" alt="Firma del paciente" class="h-24 object-contain">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $paciente['usuario_id'] ?>"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold transition-colors">
                        Volver
                    </a>
                    <a href="<?= PROJECT_ROOT ?>/pacientes/documentos/firmar?paciente_id=<?= $paciente['usuario_id'] ?>&documento_id=<?= $plantilla['documento_id'] ?>"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold shadow-md transition-all">
                        <i class="bi bi-pencil-square"></i>
                        <span>Modificar o Refirmar</span>
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Formulario de Relleno y Firma -->
            <form action="<?= PROJECT_ROOT ?>/pacientes/documentos/firmar" method="POST" class="space-y-6" id="form-firmar-documento">
                <input type="hidden" name="paciente_id" value="<?= htmlspecialchars($paciente['usuario_id']) ?>">
                <input type="hidden" name="documento_id" value="<?= htmlspecialchars($plantilla['documento_id']) ?>">

                <!-- Document Body Editor -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="contenido_editor" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">
                            Contenido del Documento para el Paciente <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[11px] text-gray-400">Puedes personalizar el texto antes de firmar</span>
                    </div>
                    <div class="rounded-xl overflow-hidden border border-gray-200">
                        <textarea name="contenido" id="contenido_editor" rows="16" class="w-full p-4 text-sm focus:outline-none"><?= htmlspecialchars($contenido) ?></textarea>
                    </div>
                </div>

                <!-- Digital Signature Block -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">
                            Firma Digital Manuscrita del Paciente / Tutor
                        </label>
                        <span class="text-xs text-gray-500" id="signatureStatus">
                            <?= !empty($firma) ? 'Firma cargada previamente' : 'Dibuje la firma dentro del recuadro' ?>
                        </span>
                    </div>

                    <div class="relative bg-white rounded-2xl border-2 border-dashed border-gray-300 p-2 shadow-inner hover:border-primary-400 transition-colors">
                        <canvas id="signatureCanvas" width="700" height="180" class="w-full h-40 touch-none cursor-crosshair rounded-xl bg-gray-50/50"></canvas>
                        <input type="hidden" name="firma_paciente" id="firma_paciente" value="<?= htmlspecialchars($firma ?? '') ?>">
                    </div>

                    <div class="mt-2.5 flex items-center justify-between">
                        <button type="button" id="clearSignatureBtn" class="text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-rose-50 transition-colors">
                            <i class="bi bi-eraser"></i> Limpiar firma
                        </button>
                        <span class="text-[11px] text-gray-400">
                            Formatos táctiles o ratón admitidos.
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= $paciente['usuario_id'] ?>" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-md transition-all hover:scale-105 active:scale-95 cursor-pointer">
                        <i class="bi bi-check-lg text-sm"></i>
                        <span>Guardar y Confirmar Documento</span>
                    </button>
                </div>
            </form>

            <!-- TinyMCE Script & Initializer -->
            <script src="<?= PROJECT_ROOT ?>/public/vendor/tinymce/tinymce.min.js"></script>
            <script>
                (function() {
                    function initTinyMCE() {
                        const textarea = document.getElementById('contenido_editor');
                        if (!textarea) return;

                        if (typeof tinymce === 'undefined') {
                            setTimeout(initTinyMCE, 50);
                            return;
                        }

                        if (tinymce.get('contenido_editor')) {
                            tinymce.remove('#contenido_editor');
                        }

                        tinymce.init({
                            selector: '#contenido_editor',
                            license_key: 'gpl',
                            height: 400,
                            menubar: false,
                            plugins: [
                                'advlist', 'autolink', 'lists', 'link', 'charmap', 'table', 'code', 'help', 'wordcount'
                            ],
                            toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist | table | removeformat code',
                            content_style: 'body { font-family: Inter, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #334155; }',
                            branding: false,
                            promotion: false,
                            setup: function(editor) {
                                editor.on('change keyup', function() {
                                    editor.save();
                                });
                            }
                        });

                        const form = document.getElementById('form-firmar-documento');
                        if (form && !form.dataset.tinymceBound) {
                            form.dataset.tinymceBound = 'true';
                            form.addEventListener('submit', function() {
                                if (typeof tinymce !== 'undefined' && tinymce.get('contenido_editor')) {
                                    tinymce.get('contenido_editor').save();
                                }
                            });
                        }
                    }

                    initTinyMCE();
                })();

                // Signature Canvas logic
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
                        ctx.strokeStyle = '#0f172a';
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

                    canvas.addEventListener('touchstart', startDrawing, { passive: false });
                    canvas.addEventListener('touchmove', draw, { passive: false });
                    canvas.addEventListener('touchend', stopDrawing);

                    if (clearBtn) {
                        clearBtn.addEventListener('click', function() {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            hiddenInput.value = '';
                            hasSignature = false;
                            statusText.textContent = 'Dibuje la firma dentro del recuadro';
                        });
                    }
                });
            </script>
        <?php endif; ?>
    </div>

</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
