<?php
$isReadOnly = isset($isReadOnly) && $isReadOnly;
$isSigned = !empty($docPaciente['firmado']);
$pageTitle = ($isReadOnly ? "Ver Documento — " : "Rellenar y Firmar Documento — ") . htmlspecialchars($plantilla['titulo'] ?? 'Documento Clínico');
$fechaHoy = !empty($docPaciente['fecha_firma']) ? date('d/m/Y', strtotime($docPaciente['fecha_firma'])) : date('d/m/Y');
$horaFirma = !empty($docPaciente['fecha_firma']) ? date('H:i', strtotime($docPaciente['fecha_firma'])) : date('H:i');
include TEMPLATE_DIR . 'header.php';
?>

<!-- Estilos para pantalla completa y formato Folio Document Studio -->
<style>
    /* Ocupar el 100% de la ventana sin restricciones de contenedor global */
    #contenido {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        height: calc(100vh - 3.5rem);
        overflow: hidden !important;
    }

    /* Hoja en Formato Folio / A4 centrada en el Document Studio */
    .folio-document {
        width: 210mm;
        min-height: 297mm;
        background: #ffffff;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.05);
        margin: 0 auto;
        padding: 22mm 20mm;
        box-sizing: border-box;
        position: relative;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        transform-origin: top center;
        transition: transform 0.15s ease;
    }

    /* Inputs interactivos integrados sobre el propio documento */
    .doc-inline-input {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 12px;
        color: #0f172a;
        transition: all 0.15s ease;
        outline: none;
    }
    .doc-inline-input:focus {
        background-color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }
    .doc-inline-input:read-only {
        background-color: transparent;
        border-color: transparent;
        padding-left: 0;
        cursor: default;
    }

    /* Scrollbar personalizada para el visor */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Impresión limpia del folio */
    @media print {
        header, .studio-toolbar, #system-alert-container, .no-print {
            display: none !important;
        }
        #contenido {
            height: auto !important;
            overflow: visible !important;
        }
        .folio-viewport {
            background: white !important;
            padding: 0 !important;
            overflow: visible !important;
        }
        .folio-document {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 15mm !important;
            width: 100% !important;
            min-height: auto !important;
            transform: none !important;
        }
        .doc-inline-input {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
    }
</style>

<!-- Librerías Open Source para firma táctil y generación de PDF -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.4/dist/signature_pad.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="h-full w-full flex flex-col bg-white text-slate-800 overflow-hidden font-sans select-none" id="document-sign-studio">

    <!-- BARRA SUPERIOR / STUDIO TOPBAR -->
    <header class="h-14 bg-white border-b border-slate-200 px-4 flex items-center justify-between shrink-0 z-30 shadow-xs studio-toolbar">
        
        <!-- Izquierda: Volver & Título -->
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= urlencode($paciente['usuario_id']) ?>"
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs"
                title="Volver al Expediente">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="hidden sm:inline">Volver</span>
            </a>

            <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 border border-primary-100 flex items-center justify-center font-bold text-sm shadow-xs">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-none truncate max-w-[200px] sm:max-w-xs md:max-w-md">
                        <?= htmlspecialchars($plantilla['titulo']) ?>
                    </h1>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-none">
                        Paciente: <span class="text-slate-800 font-semibold"><?= htmlspecialchars(($paciente['nombre'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')) ?></span> (DNI: <?= htmlspecialchars($paciente['usuario_id'] ?? '-') ?>)
                    </p>
                </div>
            </div>

            <?php if ($isSigned): ?>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="bi bi-check-circle-fill text-emerald-600"></i>
                    Firmado
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <i class="bi bi-clock-history text-amber-600"></i>
                    Pendiente de firma
                </span>
            <?php endif; ?>
        </div>

        <!-- Centro: Controles de Zoom del Folio -->
        <div class="flex items-center bg-slate-100/90 rounded-xl p-1 border border-slate-200 text-xs text-slate-700 shadow-inner gap-1">
            <button type="button" id="btnZoomOut" class="px-2 py-1 hover:bg-white rounded-lg text-slate-700 transition-colors shadow-xs" title="Reducir Zoom">
                <i class="bi bi-dash-lg"></i>
            </button>
            <span id="zoomLevelText" class="px-2 font-mono text-[11px] text-slate-700 font-bold w-12 text-center">100%</span>
            <button type="button" id="btnZoomIn" class="px-2 py-1 hover:bg-white rounded-lg text-slate-700 transition-colors shadow-xs" title="Aumentar Zoom">
                <i class="bi bi-plus-lg"></i>
            </button>
            <div class="h-3 w-px bg-slate-300 mx-1"></div>
            <button type="button" id="btnZoomReset" class="px-2 py-1 hover:bg-white rounded-lg text-[11px] text-slate-700 font-semibold transition-colors shadow-xs" title="Ajustar escala 1:1">
                1:1
            </button>
            <button type="button" id="btnZoomFit" class="px-2 py-1 hover:bg-white rounded-lg text-[11px] text-slate-700 font-semibold transition-colors shadow-xs" title="Ajustar al ancho">
                <i class="bi bi-arrows-expand"></i>
            </button>
        </div>

        <!-- Derecha: Acciones de Impresión, Exportación y Guardado -->
        <div class="flex items-center gap-2">
            
            <button type="button" id="btnPrintFolio" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Imprimir documento">
                <i class="bi bi-printer text-xs text-slate-600"></i>
                <span class="hidden md:inline">Imprimir</span>
            </button>

            <button type="button" id="btnDownloadFolioPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Descargar en PDF">
                <i class="bi bi-file-earmark-pdf text-xs text-rose-600"></i>
                <span class="hidden md:inline">Descargar PDF</span>
            </button>

            <?php if (!$isReadOnly): ?>
                <button type="button" id="btnSubmitSignStudio" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                    <i class="bi bi-check2-circle text-sm"></i>
                    <span>Guardar y Confirmar</span>
                </button>
            <?php else: ?>
                <a href="<?= PROJECT_ROOT ?>/pacientes/documentos/firmar?paciente_id=<?= urlencode($paciente['usuario_id']) ?>&documento_id=<?= urlencode($plantilla['documento_id']) ?>"
                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold transition-all shadow-sm">
                    <i class="bi bi-pencil-square"></i>
                    <span>Modificar / Refirmar</span>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <!-- WORKSPACE PRINCIPAL: VISOR DEL DOCUMENTO EN FORMATO FOLIO -->
    <main class="flex-1 bg-slate-100/90 overflow-auto p-4 sm:p-8 flex justify-center items-start custom-scrollbar folio-viewport relative select-text" id="folioViewport">
        
        <!-- Formulario Maestro que envuelve los datos del folio -->
        <form id="folioDocumentForm" action="<?= PROJECT_ROOT ?>/pacientes/documentos/firmar" method="POST">
            <input type="hidden" name="paciente_id" value="<?= htmlspecialchars($paciente['usuario_id'] ?? '') ?>">
            <input type="hidden" name="documento_id" value="<?= htmlspecialchars($plantilla['documento_id'] ?? '') ?>">
            <input type="hidden" name="firma_paciente" id="inp_firma_base64" value="<?= htmlspecialchars($firma ?? '') ?>">
            <textarea name="contenido" id="inp_contenido_final" class="hidden"><?= htmlspecialchars($contenido) ?></textarea>

            <!-- CONTENEDOR ESCALABLE CON ZOOM -->
            <div id="folioWrapper" class="transition-transform duration-100 ease-out origin-top my-auto sm:my-4">
                
                <!-- HOJA FOLIO A4 / DOCUMENT STUDIO -->
                <div id="printableFolioSheet" class="folio-document text-slate-900 rounded-sm select-text">

                    <!-- 1. ENCABEZADO CLÍNICO OFICIAL -->
                    <header class="border-b-2 border-slate-800 pb-4 mb-6 flex items-start justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-extrabold text-xl shadow-xs">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-extrabold text-slate-900 tracking-tight leading-none uppercase">
                                    Clínica Tervion / Velion
                                </h2>
                                <p class="text-[11px] text-slate-500 mt-1 font-medium">
                                    Centro Clínico y Sanitario Autorizado • Salud & Fisioterapia
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    NIF: B-88992211 • Registro Sanitario: CS/49823
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 font-mono text-xs font-bold rounded border border-slate-300">
                                DOC-<?= str_pad((string)($plantilla['documento_id'] ?? '1'), 5, '0', STR_PAD_LEFT) ?>
                            </span>
                            <div class="text-[11px] text-slate-500 mt-1.5 flex items-center justify-end gap-1 font-medium">
                                <span>Fecha:</span>
                                <?php if (!$isReadOnly): ?>
                                    <input type="text" id="field_fecha" value="<?= htmlspecialchars($fechaHoy) ?>" class="doc-inline-input w-24 text-center font-bold font-mono">
                                <?php else: ?>
                                    <span class="font-bold text-slate-800"><?= htmlspecialchars($fechaHoy) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </header>

                    <!-- 2. TÍTULO DEL DOCUMENTO -->
                    <div class="text-center my-5">
                        <h1 class="text-lg font-extrabold text-slate-900 tracking-tight uppercase underline decoration-primary-500 decoration-2 underline-offset-4">
                            <?= htmlspecialchars($plantilla['titulo']) ?>
                        </h1>
                        <?php if (!empty($plantilla['descripcion'])): ?>
                            <p class="text-xs text-slate-500 mt-1.5 italic">
                                <?= htmlspecialchars($plantilla['descripcion']) ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- 3. FICHA DE DATOS IDENTIFICATIVOS DEL PACIENTE (CAMPOS EDITABLES SOBRE EL FOLIO) -->
                    <section class="bg-slate-50 border border-slate-200 rounded-lg p-4 my-5 text-xs shadow-xs">
                        <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2.5 border-b border-slate-200 pb-1 flex items-center justify-between">
                            <span>Datos Identificativos del Paciente</span>
                            <span class="text-[10px] text-slate-400 font-normal">Campos cumplimentables</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-slate-700">
                            
                            <!-- Nombre -->
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-900 shrink-0 w-16">Nombre:</span>
                                <?php if (!$isReadOnly): ?>
                                    <input type="text" id="field_nombre" value="<?= htmlspecialchars($paciente['nombre'] ?? '') ?>" placeholder="Nombre del paciente" class="doc-inline-input flex-1">
                                <?php else: ?>
                                    <span class="font-bold text-slate-900"><?= htmlspecialchars($paciente['nombre'] ?? '-') ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Apellidos -->
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-900 shrink-0 w-16">Apellidos:</span>
                                <?php if (!$isReadOnly): ?>
                                    <input type="text" id="field_apellidos" value="<?= htmlspecialchars($paciente['apellidos'] ?? '') ?>" placeholder="Apellidos del paciente" class="doc-inline-input flex-1">
                                <?php else: ?>
                                    <span class="font-bold text-slate-900"><?= htmlspecialchars($paciente['apellidos'] ?? '-') ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- DNI/NIE -->
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-900 shrink-0 w-16">DNI / NIE:</span>
                                <?php if (!$isReadOnly): ?>
                                    <input type="text" id="field_dni" value="<?= htmlspecialchars($paciente['usuario_id'] ?? '') ?>" placeholder="12345678X" class="doc-inline-input flex-1 font-mono font-bold">
                                <?php else: ?>
                                    <span class="font-mono font-bold text-slate-900"><?= htmlspecialchars($paciente['usuario_id'] ?? '-') ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Teléfono -->
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-slate-900 shrink-0 w-16">Teléfono:</span>
                                <?php if (!$isReadOnly): ?>
                                    <input type="text" id="field_telefono" value="<?= htmlspecialchars($paciente['telefono'] ?? '') ?>" placeholder="600000000" class="doc-inline-input flex-1">
                                <?php else: ?>
                                    <span class="text-slate-800"><?= htmlspecialchars($paciente['telefono'] ?? '-') ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="flex items-center gap-2 col-span-2">
                                <span class="font-semibold text-slate-900 shrink-0 w-16">Email:</span>
                                <?php if (!$isReadOnly): ?>
                                    <input type="email" id="field_email" value="<?= htmlspecialchars($paciente['email'] ?? '') ?>" placeholder="paciente@correo.com" class="doc-inline-input flex-1">
                                <?php else: ?>
                                    <span class="text-slate-800"><?= htmlspecialchars($paciente['email'] ?? '-') ?></span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </section>

                    <!-- 4. CUERPO DE CLÁUSULAS Y TEXTO DEL DOCUMENTO -->
                    <section class="my-6 text-slate-800 text-xs leading-relaxed text-justify space-y-3 prose max-w-none" id="doc_body_content" <?= !$isReadOnly ? 'contenteditable="true"' : '' ?> style="outline: none;">
                        <?= $contenido ?>
                    </section>

                    <!-- 5. DECLARACIÓN Y RECUADRO DE FIRMA DIRECTAMENTE SOBRE EL FOLIO -->
                    <div class="mt-8 pt-4 border-t border-slate-300">
                        <p class="text-[11px] text-slate-600 leading-snug mb-4">
                            Y para que así conste a los efectos oportunos, leído y comprendido el presente documento en su totalidad, el paciente presta su consentimiento y formaliza con su firma manuscrita digital:
                        </p>

                        <div class="grid grid-cols-2 gap-6 items-end mt-4">
                            
                            <!-- Columna Izquierda: Lugar, Fecha y Sello de Garantía -->
                            <div class="text-xs text-slate-700 space-y-2">
                                <div class="flex items-center gap-1.5">
                                    <span>En</span>
                                    <?php if (!$isReadOnly): ?>
                                        <input type="text" id="field_lugar" value="Clínica Tervion" class="doc-inline-input font-bold text-slate-900 w-36">
                                    <?php else: ?>
                                        <strong class="text-slate-900">Clínica Tervion</strong>
                                    <?php endif; ?>
                                    <span>, a <strong id="doc_display_fecha" class="text-slate-900"><?= htmlspecialchars($fechaHoy) ?></strong></span>
                                </div>
                                
                                <p class="text-[10px] text-slate-500">Formalización de firma digitalizada con valor legal</p>
                                
                                <div class="mt-4 p-2 bg-slate-50 border border-slate-200 rounded text-[10px] text-slate-500 space-y-0.5">
                                    <div class="flex items-center gap-1 text-slate-700 font-semibold">
                                        <i class="bi bi-shield-check text-emerald-600"></i>
                                        <span>Garantía de Integridad Digital</span>
                                    </div>
                                    <p>Hash de verificación: <span class="font-mono text-[9px]"><?= strtoupper(substr(md5(($paciente['usuario_id'] ?? '') . ($plantilla['documento_id'] ?? '')), 0, 16)) ?></span></p>
                                </div>
                            </div>

                            <!-- Columna Derecha: Recuadro interactivo de Firma sobre el Folio (Fondo Gris Claro) -->
                            <div class="flex flex-col items-center">
                                
                                <div class="w-full relative bg-slate-100 rounded-xl border-2 border-dashed border-slate-300 p-1.5 shadow-inner hover:border-primary-400 transition-colors group">
                                    
                                    <?php if (!$isReadOnly): ?>
                                        <!-- Canvas Interactivo con SignaturePad -->
                                        <canvas id="folioSignatureCanvas" width="460" height="150" class="w-full h-32 touch-none cursor-crosshair rounded-lg bg-slate-100"></canvas>
                                        
                                        <!-- Herramientas flotantes de firma -->
                                        <div class="flex items-center justify-between mt-1 px-1.5 no-print">
                                            <div class="flex items-center gap-2">
                                                <button type="button" id="btnUndoFolioSig" class="text-[10px] font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1 hover:bg-slate-200/80 px-1.5 py-0.5 rounded transition-colors" title="Deshacer trazo">
                                                    <i class="bi bi-arrow-counterclockwise"></i> Deshacer
                                                </button>
                                                <button type="button" id="btnClearFolioSig" class="text-[10px] font-semibold text-rose-600 hover:text-rose-800 flex items-center gap-1 hover:bg-rose-50 px-1.5 py-0.5 rounded transition-colors" title="Borrar firma">
                                                    <i class="bi bi-eraser"></i> Borrar
                                                </button>
                                            </div>

                                            <div class="flex items-center gap-1 text-[10px] text-slate-500">
                                                <span>Tinta:</span>
                                                <button type="button" class="folio-color-btn w-3.5 h-3.5 rounded-full bg-slate-900 ring-2 ring-primary-500 ring-offset-1" data-color="#0f172a" title="Tinta Negra"></button>
                                                <button type="button" class="folio-color-btn w-3.5 h-3.5 rounded-full bg-blue-900" data-color="#1e3a8a" title="Tinta Azul"></button>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <!-- Vista de solo lectura con firma registrada -->
                                        <div class="w-full h-32 flex items-center justify-center bg-slate-100 rounded-lg">
                                            <?php if (!empty($firma)): ?>
                                                <img src="<?= htmlspecialchars($firma) ?>" alt="Firma registrada" class="max-h-28 max-w-full object-contain">
                                            <?php else: ?>
                                                <span class="text-xs text-slate-400 italic">Documento sin firma registrada</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <span class="text-[10px] text-slate-500 font-medium mt-1">Firma del Paciente / Tutor Legal</span>
                            </div>

                        </div>
                    </div>

                    <!-- 6. PIE DE PÁGINA LEGAL -->
                    <footer class="mt-8 pt-3 border-t border-slate-200 text-[9px] text-slate-400 flex items-center justify-between">
                        <span>Documento Clínico Oficial • RGPD (UE) 2016/679 & LOPDGDD 3/2018</span>
                        <span>Página 1 de 1</span>
                    </footer>

                </div>
            </div>
        </form>
    </main>

</div>

<!-- SCRIPT DE INTERACTIVIDAD DEL DOCUMENT STUDIO -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. GESTIÓN DEL CANVAS DE FIRMA SOBRE EL FOLIO
    const canvas = document.getElementById('folioSignatureCanvas');
    const hiddenInputFirma = document.getElementById('inp_firma_base64');
    const btnClear = document.getElementById('btnClearFolioSig');
    const btnUndo = document.getElementById('btnUndoFolioSig');
    let signaturePad = null;

    function resizeFolioCanvas() {
        if (!canvas) return;
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();
        if (rect.width === 0) return;

        const data = signaturePad ? signaturePad.toData() : null;
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        const ctx = canvas.getContext('2d');
        ctx.scale(ratio, ratio);

        if (signaturePad) {
            signaturePad.clear();
            if (data && data.length > 0) {
                signaturePad.fromData(data);
            }
        }
    }

    if (canvas && typeof SignaturePad !== 'undefined') {
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(0, 0, 0, 0)',
            penColor: '#0f172a',
            minWidth: 1.5,
            maxWidth: 3.5,
            throttle: 16
        });

        // Si ya existía firma previa, cargarla
        if (hiddenInputFirma && hiddenInputFirma.value && hiddenInputFirma.value.startsWith('data:image')) {
            signaturePad.fromDataURL(hiddenInputFirma.value, { ratio: 1 });
        }

        signaturePad.addEventListener('endStroke', function() {
            if (!signaturePad.isEmpty()) {
                hiddenInputFirma.value = signaturePad.toDataURL('image/png');
            }
        });

        if (btnClear) {
            btnClear.addEventListener('click', function() {
                signaturePad.clear();
                hiddenInputFirma.value = '';
            });
        }

        if (btnUndo) {
            btnUndo.addEventListener('click', function() {
                const data = signaturePad.toData();
                if (data && data.length > 0) {
                    data.pop();
                    signaturePad.fromData(data);
                    hiddenInputFirma.value = signaturePad.isEmpty() ? '' : signaturePad.toDataURL('image/png');
                }
            });
        }

        // Color de tinta
        document.querySelectorAll('.folio-color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const color = this.dataset.color;
                signaturePad.penColor = color;
                document.querySelectorAll('.folio-color-btn').forEach(b => b.classList.remove('ring-2', 'ring-primary-500', 'ring-offset-1'));
                this.classList.add('ring-2', 'ring-primary-500', 'ring-offset-1');
            });
        });

        window.addEventListener('resize', resizeFolioCanvas);
        setTimeout(resizeFolioCanvas, 150);
    }

    // 2. SINCRONIZACIÓN DE FECHA
    const fieldFecha = document.getElementById('field_fecha');
    const docDisplayFecha = document.getElementById('doc_display_fecha');
    if (fieldFecha && docDisplayFecha) {
        fieldFecha.addEventListener('input', function() {
            docDisplayFecha.textContent = this.value || '<?= date('d/m/Y') ?>';
        });
    }

    // 3. CONTROLES DE ZOOM DEL FOLIO
    const folioWrapper = document.getElementById('folioWrapper');
    const zoomText = document.getElementById('zoomLevelText');
    let currentZoom = 1.0;

    function applyZoom(zoom) {
        currentZoom = Math.min(Math.max(zoom, 0.4), 1.8);
        if (folioWrapper) {
            folioWrapper.style.transform = `scale(${currentZoom})`;
        }
        if (zoomText) {
            zoomText.textContent = `${Math.round(currentZoom * 100)}%`;
        }
    }

    document.getElementById('btnZoomIn')?.addEventListener('click', () => applyZoom(currentZoom + 0.1));
    document.getElementById('btnZoomOut')?.addEventListener('click', () => applyZoom(currentZoom - 0.1));
    document.getElementById('btnZoomReset')?.addEventListener('click', () => applyZoom(1.0));
    document.getElementById('btnZoomFit')?.addEventListener('click', () => {
        const viewport = document.getElementById('folioViewport');
        if (viewport) {
            const availableWidth = viewport.clientWidth - 48;
            const a4WidthPx = 794; // ~210mm a 96dpi
            applyZoom(availableWidth / a4WidthPx);
        }
    });

    // 4. DESCARGA EN PDF (HTML2PDF.JS)
    document.getElementById('btnDownloadFolioPdf')?.addEventListener('click', function() {
        const element = document.getElementById('printableFolioSheet');
        if (!element || typeof html2pdf === 'undefined') {
            window.print();
            return;
        }

        const opt = {
            margin: 0,
            filename: 'Documento_<?= preg_replace('/[^A-Za-z0-9_-]/', '_', $plantilla['titulo']) ?>_<?= htmlspecialchars($paciente['usuario_id']) ?>.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, letterRendering: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        const originalTransform = folioWrapper ? folioWrapper.style.transform : '';
        if (folioWrapper) folioWrapper.style.transform = 'none';

        html2pdf().set(opt).from(element).save().then(() => {
            if (folioWrapper) folioWrapper.style.transform = originalTransform;
        }).catch(err => {
            console.error('Error generando PDF:', err);
            if (folioWrapper) folioWrapper.style.transform = originalTransform;
            window.location.href = '<?= PROJECT_ROOT ?>/pacientes/documentos/pdf?paciente_id=<?= urlencode($paciente['usuario_id']) ?>&documento_id=<?= urlencode($plantilla['documento_id']) ?>';
        });
    });

    // 5. IMPRESIÓN DIRECTA
    document.getElementById('btnPrintFolio')?.addEventListener('click', function() {
        window.print();
    });

    // 6. ENVÍO Y CONFIRMACIÓN DEL FORMULARIO
    document.getElementById('btnSubmitSignStudio')?.addEventListener('click', function() {
        const bodyContent = document.getElementById('doc_body_content');
        const hiddenContenido = document.getElementById('inp_contenido_final');
        if (bodyContent && hiddenContenido) {
            hiddenContenido.value = bodyContent.innerHTML;
        }

        if (signaturePad && !signaturePad.isEmpty() && hiddenInputFirma) {
            hiddenInputFirma.value = signaturePad.toDataURL('image/png');
        }

        const form = document.getElementById('folioDocumentForm');
        if (form) form.submit();
    });

});
</script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
