<?php
$isEdit = isset($isEdit) && $isEdit && !empty($document);
$pageTitle = ($isEdit ? "Editar Plantilla — " : "Nueva Plantilla — ") . htmlspecialchars($document['titulo'] ?? 'Document Studio');
$fechaHoy = date('d/m/Y');
include TEMPLATE_DIR . 'header.php';
?>

<!-- Estilos para pantalla completa 100% Viewport y Document Studio en Formato Folio A4 -->
<style>
    /* Ocupar el 100% de la ventana sin restricciones de contenedor global */
    #contenido {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        height: calc(100vh - 3.5rem);
        overflow: hidden !important;
    }

    /* Hoja en Formato Folio / A4 Digital centrada */
    .folio-template-sheet {
        width: 210mm;
        min-height: 297mm;
        background: #ffffff;
        box-shadow: 0 12px 35px -5px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.06);
        margin: 0 auto;
        padding: 20mm 18mm;
        box-sizing: border-box;
        position: relative;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        transform-origin: top center;
        transition: transform 0.15s ease;
    }

    /* Inputs editables sobre el propio folio */
    .folio-title-input {
        background-color: transparent;
        border: none;
        border-bottom: 2px dashed #cbd5e1;
        border-radius: 0;
        padding: 4px 8px;
        font-size: 16px;
        font-weight: 800;
        text-transform: uppercase;
        color: #0f172a;
        width: 100%;
        text-align: center;
        transition: all 0.2s ease;
        outline: none;
    }
    .folio-title-input:focus {
        border-bottom-color: #2563eb;
        background-color: #f8fafc;
    }

    .folio-desc-input {
        background-color: transparent;
        border: none;
        border-bottom: 1px dashed #cbd5e1;
        border-radius: 0;
        padding: 2px 6px;
        font-size: 12px;
        font-style: italic;
        color: #64748b;
        width: 100%;
        text-align: center;
        transition: all 0.2s ease;
        outline: none;
    }
    .folio-desc-input:focus {
        border-bottom-color: #2563eb;
        background-color: #f8fafc;
        color: #1e293b;
    }

    /* Contenedor TinyMCE adaptado al folio */
    .tox-tinymce {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        box-shadow: none !important;
    }
    .tox-editor-header {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    /* Scrollbar personalizada */
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

    /* Impresión limpia */
    @media print {
        header, .studio-toolbar, .studio-ribbon, #system-alert-container, .no-print {
            display: none !important;
        }
        #contenido {
            height: auto !important;
            overflow: visible !important;
        }
        .studio-viewport {
            background: white !important;
            padding: 0 !important;
            overflow: visible !important;
        }
        .folio-template-sheet {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 12mm !important;
            width: 100% !important;
            min-height: auto !important;
            transform: none !important;
        }
        .folio-title-input, .folio-desc-input {
            border: none !important;
            background: transparent !important;
        }
    }
</style>

<!-- Librería Open Source TinyMCE y html2pdf para generación de plantillas -->
<script src="<?= PROJECT_ROOT ?>/public/vendor/tinymce/tinymce.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="h-full w-full flex flex-col bg-white text-slate-800 overflow-hidden font-sans select-none" id="template-studio-app">

    <!-- 1. BARRA SUPERIOR / STUDIO TOPBAR -->
    <header class="h-14 bg-white border-b border-slate-200 px-4 flex items-center justify-between shrink-0 z-30 shadow-xs studio-toolbar">
        
        <!-- Izquierda: Volver & Título -->
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/documentos"
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs"
                title="Volver al Listado de Plantillas">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="hidden sm:inline">Volver</span>
            </a>

            <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 border border-primary-100 flex items-center justify-center font-bold text-sm shadow-xs">
                    <i class="bi bi-file-earmark-code"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-none truncate max-w-[200px] sm:max-w-xs md:max-w-md" id="topbarDocTitle">
                        <?= $isEdit ? htmlspecialchars($document['titulo']) : 'Nueva Plantilla de Documento' ?>
                    </h1>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-none">
                        <?= $isEdit ? 'Diseño y edición en formato folio digital' : 'Estudio de creación de plantilla clínica' ?>
                    </p>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold <?= $isEdit ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' ?>">
                <i class="bi bi-<?= $isEdit ? 'pencil-square text-blue-600' : 'plus-circle text-emerald-600' ?>"></i>
                <?= $isEdit ? 'Modo Edición' : 'Nueva Plantilla' ?>
            </span>
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
            <button type="button" id="btnZoomReset" class="px-2 py-1 hover:bg-white rounded-lg text-[11px] text-slate-700 font-semibold transition-colors shadow-xs" title="Escala 1:1">
                1:1
            </button>
            <button type="button" id="btnZoomFit" class="px-2 py-1 hover:bg-white rounded-lg text-[11px] text-slate-700 font-semibold transition-colors shadow-xs" title="Ajustar al ancho">
                <i class="bi bi-arrows-expand"></i>
            </button>
        </div>

        <!-- Derecha: Acciones de Guardado, Impresión y Cancelación -->
        <div class="flex items-center gap-2">
            
            <button type="button" id="btnPrintTemplate" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Imprimir modelo">
                <i class="bi bi-printer text-xs text-slate-600"></i>
                <span class="hidden md:inline">Imprimir</span>
            </button>

            <button type="button" id="btnDownloadTemplatePdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Descargar en PDF">
                <i class="bi bi-file-earmark-pdf text-xs text-rose-600"></i>
                <span class="hidden md:inline">Descargar PDF</span>
            </button>

            <button type="button" id="btnSubmitTemplateStudio" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                <i class="bi bi-check2-circle text-sm"></i>
                <span><?= $isEdit ? 'Guardar Cambios' : 'Crear Plantilla' ?></span>
            </button>
        </div>
    </header>

    <!-- 2. BARRA RIBBON DE VARIABLES DINÁMICAS CLÍNICAS (1-CLIC PARA INSERTAR EN EL EDITOR) -->
    <div class="h-11 bg-slate-50 border-b border-slate-200 px-4 flex items-center justify-between shrink-0 overflow-x-auto text-xs gap-3 studio-ribbon">
        <div class="flex items-center gap-1.5 shrink-0">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center gap-1 mr-1">
                <i class="bi bi-braces text-primary-600"></i>
                Variables Dinámicas:
            </span>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{nombre_paciente}}">
                <span class="text-primary-600">+</span> {{nombre_paciente}}
            </button>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{apellidos_paciente}}">
                <span class="text-primary-600">+</span> {{apellidos_paciente}}
            </button>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{dni_paciente}}">
                <span class="text-primary-600">+</span> {{dni_paciente}}
            </button>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{telefono_paciente}}">
                <span class="text-primary-600">+</span> {{telefono_paciente}}
            </button>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{email_paciente}}">
                <span class="text-primary-600">+</span> {{email_paciente}}
            </button>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{fecha_hoy}}">
                <span class="text-primary-600">+</span> {{fecha_hoy}}
            </button>

            <button type="button" class="insert-tag-btn px-2.5 py-1 bg-white hover:bg-primary-50 hover:border-primary-300 text-slate-700 hover:text-primary-700 border border-slate-200 rounded-lg text-xs font-mono font-bold transition-all shadow-2xs flex items-center gap-1" data-tag="{{nombre_clinica}}">
                <span class="text-primary-600">+</span> {{nombre_clinica}}
            </button>
        </div>

        <div class="flex items-center gap-3 shrink-0 text-slate-500 text-[11px]">
            <span class="flex items-center gap-1 font-medium">
                <i class="bi bi-info-circle text-slate-400"></i>
                Se sustituirán automáticamente al asignar a un paciente
            </span>
        </div>
    </div>

    <!-- 3. WORKSPACE PRINCIPAL: VISOR Y EDITOR EN FORMATO FOLIO A4 -->
    <main class="flex-1 bg-slate-100/90 overflow-auto p-4 sm:p-8 flex justify-center items-start custom-scrollbar studio-viewport relative select-text" id="templateStudioViewport">
        
        <!-- Formulario Maestro para persistencia -->
        <form id="formTemplateFolioStudio" action="<?= PROJECT_ROOT ?>/documentos/<?= $isEdit ? 'editar' : 'crear' ?>" method="POST">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($document['documento_id']) ?>">
            <?php endif; ?>
            <input type="hidden" name="titulo" id="hidden_titulo">
            <input type="hidden" name="descripcion" id="hidden_descripcion">
            <input type="hidden" name="contenido" id="hidden_contenido">

            <!-- CONTENEDOR ESCALABLE CON ZOOM -->
            <div id="folioTemplateWrapper" class="transition-transform duration-100 ease-out origin-top my-auto sm:my-4">
                
                <!-- HOJA FOLIO A4 DIGITAL / DOCUMENT STUDIO -->
                <div id="printableTemplateFolioSheet" class="folio-template-sheet text-slate-900 rounded-sm select-text">

                    <!-- 1. ENCABEZADO OFICIAL DEL CENTRO CLÍNICO -->
                    <header class="border-b-2 border-slate-800 pb-4 mb-5 flex items-start justify-between">
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
                                    NIF: B-88992211 • Registro Sanitario: CS/49823 • Ley 41/2002
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-800 font-mono text-xs font-bold rounded border border-slate-300">
                                <?= $isEdit ? 'DOC-' . str_pad((string)$document['documento_id'], 5, '0', STR_PAD_LEFT) : 'NUEVO-DOC' ?>
                            </span>
                            <div class="text-[11px] text-slate-500 mt-1.5 font-medium">
                                <span>Fecha:</span> <span class="font-bold text-slate-800"><?= $fechaHoy ?></span>
                            </div>
                        </div>
                    </header>

                    <!-- 2. TÍTULO Y DESCRIPCIÓN EDITABLES SOBRE EL PROPIO FOLIO -->
                    <div class="my-5 space-y-1.5 text-center">
                        <div>
                            <input type="text" id="folio_titulo" required
                                value="<?= htmlspecialchars($document['titulo'] ?? '') ?>"
                                placeholder="Escribe aquí el Título de la Plantilla (ej: Consentimiento Informado Fisioterapia)"
                                class="folio-title-input">
                        </div>
                        <div>
                            <input type="text" id="folio_descripcion"
                                value="<?= htmlspecialchars($document['descripcion'] ?? '') ?>"
                                placeholder="Descripción corta o especialidad (ej: Tratamiento manual, osteopatía y rehabilitación)"
                                class="folio-desc-input">
                        </div>
                    </div>

                    <!-- 3. FICHA DE DATOS IDENTIFICATIVOS DEL PACIENTE (MODELO INTEGRADO) -->
                    <section class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 my-4 text-xs shadow-xs">
                        <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1 flex items-center justify-between">
                            <span>Datos Identificativos del Paciente</span>
                            <span class="text-[10px] text-slate-400 font-medium">Cumplimentación automática</span>
                        </div>
                        <div class="grid grid-cols-2 gap-y-1.5 gap-x-4 text-slate-700">
                            <div><span class="font-semibold text-slate-900">Paciente:</span> <span class="font-mono text-primary-700 font-bold">{{nombre_paciente}} {{apellidos_paciente}}</span></div>
                            <div><span class="font-semibold text-slate-900">DNI / NIE:</span> <span class="font-mono text-primary-700 font-bold">{{dni_paciente}}</span></div>
                            <div><span class="font-semibold text-slate-900">Teléfono:</span> <span class="font-mono text-primary-700 font-bold">{{telefono_paciente}}</span></div>
                            <div><span class="font-semibold text-slate-900">Email:</span> <span class="font-mono text-primary-700 font-bold">{{email_paciente}}</span></div>
                        </div>
                    </section>

                    <!-- 4. CONTENIDO / CLÁUSULAS EDITABLES CON TINYMCE LIBREOFFICE WRITER STYLE -->
                    <div class="my-5">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-file-text text-primary-600"></i>
                                Cláusulas y Contenido del Documento <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">Editor WYSIWYG completo</span>
                        </div>
                        <textarea id="folio_contenido_editor" rows="16" class="w-full"><?= htmlspecialchars($document['contenido'] ?? '') ?></textarea>
                    </div>

                    <!-- 5. DECLARACIÓN Y ÁREA DE FIRMA PREVIEW -->
                    <div class="mt-8 pt-4 border-t border-slate-300">
                        <p class="text-[11px] text-slate-600 leading-snug mb-4">
                            Y para que así conste a los efectos oportunos, leído y comprendido el presente documento en su totalidad, el paciente presta su consentimiento y formaliza con su firma manuscrita digital:
                        </p>

                        <div class="grid grid-cols-2 gap-6 items-end mt-4">
                            <div class="text-xs text-slate-700 space-y-1">
                                <p>En <strong class="text-slate-900">Clínica Tervion</strong>, a <strong class="text-slate-900">{{fecha_hoy}}</strong></p>
                                <p class="text-[10px] text-slate-500">Formalización de firma digitalizada con valor legal</p>
                            </div>

                            <div class="flex flex-col items-center">
                                <div class="w-full h-24 border-2 border-dashed border-slate-300 rounded-xl bg-slate-100 flex items-center justify-center p-2 text-center text-slate-400">
                                    <span class="text-xs font-medium">[Área de Firma Manuscrita / Digital]</span>
                                </div>
                                <span class="text-[10px] text-slate-500 font-medium mt-1">Firma del Paciente / Tutor Legal</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. PIE DE PÁGINA OFICIAL -->
                    <footer class="mt-8 pt-3 border-t border-slate-200 text-[9px] text-slate-400 flex items-center justify-between">
                        <span>Documento Clínico Oficial • RGPD (UE) 2016/679 & LOPDGDD 3/2018</span>
                        <span>Página 1 de 1</span>
                    </footer>

                </div>
            </div>
        </form>
    </main>

</div>

<!-- SCRIPT DE INICIALIZACIÓN DE TINYMCE, ZOOM Y EXPORTACIÓN PDF -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. INICIALIZACIÓN DE TINYMCE
    function initTemplateEditor() {
        if (typeof tinymce === 'undefined') {
            setTimeout(initTemplateEditor, 50);
            return;
        }

        if (tinymce.get('folio_contenido_editor')) {
            tinymce.remove('#folio_contenido_editor');
        }

        tinymce.init({
            selector: '#folio_contenido_editor',
            license_key: 'gpl',
            height: 480,
            menubar: 'edit insert format table tools',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat code fullscreen',
            content_style: 'body { font-family: Inter, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.6; color: #1e293b; padding: 12px; }',
            branding: false,
            promotion: false,
            placeholder: 'Escribe aquí las cláusulas del documento, condiciones, consentimiento o autorizaciones...',
            setup: function(editor) {
                editor.on('change keyup', function() {
                    editor.save();
                });
            }
        });
    }

    initTemplateEditor();

    // 2. INSERCIÓN DE VARIABLES DINÁMICAS CON 1-CLIC
    document.querySelectorAll('.insert-tag-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tag = this.dataset.tag;
            const editor = tinymce.get('folio_contenido_editor');
            if (editor) {
                editor.insertContent('<strong>' + tag + '</strong> ');
                editor.focus();
            } else {
                const textarea = document.getElementById('folio_contenido_editor');
                if (textarea) {
                    textarea.value += ' ' + tag + ' ';
                }
            }
        });
    });

    // 3. SINCRONIZACIÓN DE TÍTULO CON LA BARRA SUPERIOR
    const folioTitulo = document.getElementById('folio_titulo');
    const topbarDocTitle = document.getElementById('topbarDocTitle');
    if (folioTitulo && topbarDocTitle) {
        folioTitulo.addEventListener('input', function() {
            topbarDocTitle.textContent = this.value.trim() || 'Nueva Plantilla de Documento';
        });
    }

    // 4. CONTROLES DE ZOOM DEL FOLIO
    const folioWrapper = document.getElementById('folioTemplateWrapper');
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
        const viewport = document.getElementById('templateStudioViewport');
        if (viewport) {
            const availableWidth = viewport.clientWidth - 48;
            const a4WidthPx = 794; // ~210mm a 96dpi
            applyZoom(availableWidth / a4WidthPx);
        }
    });

    // 5. DESCARGA EN PDF (HTML2PDF.JS)
    document.getElementById('btnDownloadTemplatePdf')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

        const element = document.getElementById('printableTemplateFolioSheet');
        if (!element || typeof html2pdf === 'undefined') {
            window.print();
            return;
        }

        const tituloVal = (folioTitulo?.value || 'Plantilla_Documento').replace(/[^A-Za-z0-9_-]/g, '_');
        const opt = {
            margin: 0,
            filename: `Modelo_${tituloVal}.pdf`,
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
            window.print();
        });
    });

    // 6. IMPRESIÓN DIRECTA
    document.getElementById('btnPrintTemplate')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }
        window.print();
    });

    // 7. ENVÍO Y GUARDADO DEL FORMULARIO
    document.getElementById('btnSubmitTemplateStudio')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

        const tituloInput = document.getElementById('folio_titulo');
        const descInput = document.getElementById('folio_descripcion');
        const editor = tinymce.get('folio_contenido_editor');

        const tituloVal = (tituloInput?.value || '').trim();
        const descVal = (descInput?.value || '').trim();
        const contenidoVal = editor ? editor.getContent() : (document.getElementById('folio_contenido_editor')?.value || '');

        if (!tituloVal) {
            alert('Por favor, introduce el título de la plantilla.');
            tituloInput?.focus();
            return;
        }

        if (!contenidoVal.trim()) {
            alert('Por favor, redacta el contenido de la plantilla.');
            if (editor) editor.focus();
            return;
        }

        const hiddenTitulo = document.getElementById('hidden_titulo');
        const hiddenDesc = document.getElementById('hidden_descripcion');
        const hiddenContenido = document.getElementById('hidden_contenido');

        if (hiddenTitulo) hiddenTitulo.value = tituloVal;
        if (hiddenDesc) hiddenDesc.value = descVal;
        if (hiddenContenido) hiddenContenido.value = contenidoVal;

        const form = document.getElementById('formTemplateFolioStudio');
        if (form) form.submit();
    });

});
</script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>