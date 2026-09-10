<?php
$pageTitle = "Nuevo Informe Clínico — " . htmlspecialchars(($paciente['nombre'] ?? '') . ' ' . ($paciente['apellidos'] ?? ''));
$fechaHoy = date('d/m/Y');
$horaHoy = date('H:i');
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
    .folio-report-sheet {
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

    /* Estilo para los campos editables integrados sobre el folio */
    .folio-input-field {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 12px;
        color: #0f172a;
        transition: all 0.15s ease;
        outline: none;
    }

    .folio-input-field:focus {
        background-color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }

    /* Área de redacción tipo LibreOffice Writer sobre el folio */
    .folio-editor-area {
        min-height: 110px;
        background-color: #fafbfd;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        line-height: 1.6;
        color: #1e293b;
        transition: all 0.15s ease;
        outline: none;
    }

    .folio-editor-area:focus-within {
        background-color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
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

        header,
        .studio-toolbar,
        .studio-ribbon,
        #system-alert-container,
        .no-print {
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

        .folio-report-sheet {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 12mm !important;
            width: 100% !important;
            min-height: auto !important;
            transform: none !important;
        }

        .folio-input-field,
        .folio-editor-area {
            border: none !important;
            background: transparent !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
    }
</style>

<!-- Librería html2pdf para generación de informes oficiales -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="h-full w-full flex flex-col bg-white text-slate-800 overflow-hidden font-sans select-none" id="medical-report-studio">

    <!-- 1. BARRA SUPERIOR / STUDIO TOPBAR -->
    <header class="h-14 bg-white border-b border-slate-200 px-4 flex items-center justify-between shrink-0 z-30 shadow-xs studio-toolbar">

        <!-- Izquierda: Volver & Título -->
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= urlencode($paciente['usuario_id']) ?>"
                onclick="if (history.length > 1) { history.back(); return false; }"
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs"
                title="Volver al Expediente">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="hidden sm:inline">Volver</span>
            </a>

            <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 border border-primary-100 flex items-center justify-center font-bold text-sm shadow-xs">
                    <i class="bi bi-file-earmark-medical"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-none truncate max-w-[200px] sm:max-w-xs md:max-w-md">
                        Nuevo Informe Clínico
                    </h1>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-none">
                        Paciente: <span class="text-slate-800 font-semibold"><?= htmlspecialchars(($paciente['nombre'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')) ?></span> (NHC: #<?= htmlspecialchars($paciente['usuario_id'] ?? '-') ?>)
                    </p>
                </div>
            </div>

            <!-- <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="bi bi-pencil-fill text-[9px] text-emerald-600"></i>
                Document Studio Activo
            </span> -->
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

        <!-- Derecha: Acciones de Impresión, Exportación y Guardado -->
        <div class="flex items-center gap-2">

            <!-- <button type="button" id="btnPrintReport" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Imprimir informe médico">
                <i class="bi bi-printer text-xs text-slate-600"></i>
                <span class="hidden md:inline">Imprimir</span>
            </button> -->

            <button type="button" id="btnDownloadReportPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Descargar Informe en PDF">
                <i class="bi bi-file-earmark-pdf text-xs text-rose-600"></i>
                <span class="hidden md:inline">Descargar PDF</span>
            </button>

            <button type="button" id="btnSubmitReportStudio" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                <i class="bi bi-check2-circle text-sm"></i>
                <span>Guardar Informe</span>
            </button>
        </div>
    </header>

    <!-- 3. WORKSPACE PRINCIPAL: VISOR Y EDITOR EN FORMATO FOLIO A4 -->
    <main class="flex-1 bg-slate-100/90 overflow-auto p-4 sm:p-8 flex justify-center items-start custom-scrollbar studio-viewport relative select-text" id="reportStudioViewport">

        <!-- Formulario Maestro para persistencia -->
        <form id="formMedicalReportFolio" action="<?= PROJECT_ROOT ?>/historial/crear" method="POST">
            <input type="hidden" name="paciente_id" value="<?= htmlspecialchars($paciente['usuario_id']) ?>">
            <input type="hidden" name="motivo_consulta" id="hidden_motivo_consulta">
            <input type="hidden" name="diagnostico" id="hidden_diagnostico">
            <input type="hidden" name="tratamiento" id="hidden_tratamiento">
            <input type="hidden" name="observaciones" id="hidden_observaciones">

            <!-- CONTENEDOR ESCALABLE CON ZOOM -->
            <div id="folioReportWrapper" class="transition-transform duration-100 ease-out origin-top my-auto sm:my-4">

                <!-- HOJA FOLIO A4 DIGITAL / DOCUMENT STUDIO -->
                <div id="printableReportFolioSheet" class="folio-report-sheet text-slate-900 rounded-sm select-text">

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
                                    Centro Clínico y Sanitario Autorizado • Fisioterapia & Salud Integral
                                </p>
                                <p class="text-[10px] text-slate-400">
                                    NIF: B-88992211 • Registro Sanitario: CS/49823 • Ley 41/2002
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <!-- <span class="inline-block px-3 py-1 bg-slate-900 text-white font-mono text-xs font-bold rounded">
                                INFORME CLÍNICO OFICIAL
                            </span> -->
                            <div class="text-[11px] text-slate-500 mt-1.5 flex items-center justify-end gap-1 font-medium">
                                <span>Fecha:</span>
                                <input type="text" id="folio_fecha_emision" value="<?= htmlspecialchars($fechaHoy . ' ' . $horaHoy) ?>" class="folio-input-field w-32 text-center font-bold font-mono">
                            </div>
                        </div>
                    </header>

                    <!-- 2. TÍTULO Y MOTIVO DE CONSULTA SOBRE EL FOLIO -->
                    <div class="my-4">
                        <div class="text-center mb-3">
                            <h1 class="text-base font-extrabold text-slate-900 tracking-tight uppercase underline decoration-primary-500 decoration-2 underline-offset-4">
                                INFORME DE EVALUACIÓN Y TRATAMIENTO FISIOTERÁPICO
                            </h1>
                        </div>

                        <!-- Motivo de consulta editable -->
                        <div class="p-3 bg-primary-50/60 border-l-4 border-primary-600 rounded-r-lg">
                            <label for="folio_motivo_consulta" class="block text-[11px] font-bold text-primary-900 uppercase tracking-wider mb-1">
                                Motivo de la Consulta / Anamnesis Principal <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="folio_motivo_consulta" required
                                class="w-full px-3 py-1.5 bg-white border border-primary-200 rounded-lg text-xs font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 shadow-2xs"
                                placeholder="Ej: Dolor lumbar agudo tras esfuerzo físico, recidiva de molestia cervical..."
                                value="Dolor y limitación funcional mecánica">
                        </div>
                    </div>

                    <!-- 3. FICHA DE IDENTIFICACIÓN DEL PACIENTE -->
                    <section class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 my-4 text-xs shadow-xs">
                        <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1 flex items-center justify-between">
                            <span>Datos Identificativos del Paciente</span>
                            <span class="text-[10px] text-slate-400 font-mono">NHC: #<?= htmlspecialchars($paciente['usuario_id'] ?? '') ?></span>
                        </div>

                        <div class="grid grid-cols-2 gap-y-1.5 gap-x-4 text-slate-700">
                            <div><strong class="text-slate-900">Paciente:</strong> <?= htmlspecialchars(($paciente['nombre'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')) ?></div>
                            <div><strong class="text-slate-900">DNI / NIE:</strong> <span class="font-mono"><?= htmlspecialchars($paciente['usuario_id'] ?? '-') ?></span></div>
                            <div><strong class="text-slate-900">Teléfono:</strong> <?= htmlspecialchars($paciente['telefono'] ?? '-') ?></div>
                            <div><strong class="text-slate-900">Email:</strong> <?= htmlspecialchars($paciente['email'] ?? '-') ?></div>
                        </div>
                    </section>

                    <!-- 4. SECCIONES DE REDACCIÓN CLÍNICA EN FORMATO FOLIO CON HERRAMIENTAS TINYMCE / WRITER -->

                    <!-- Sección 1: Diagnóstico / Evaluación -->
                    <div class="my-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-clipboard-pulse text-primary-600"></i>
                                1. Diagnóstico y Evaluación Clínica <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">Descripción clínica</span>
                        </div>
                        <textarea id="diag_editor" class="folio-editor-area w-full" rows="5" placeholder="Descripción detallada de la anamnesis, exploración física, balance articular, test ortopédicos y juicio diagnóstico..."></textarea>
                    </div>

                    <!-- Sección 2: Tratamiento Realizado -->
                    <div class="my-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-bandaid text-primary-600"></i>
                                2. Tratamiento Realizado y Prescripción <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">Técnicas y pautas</span>
                        </div>
                        <textarea id="trat_editor" class="folio-editor-area w-full" rows="5" placeholder="Terapia manual aplicada, movilizaciones, técnicas invasivas, electroterapia y pauta de ejercicios para realizar en domicilio..."></textarea>
                    </div>

                    <!-- Sección 3: Observaciones y Evolución -->
                    <div class="my-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-chat-left-text text-primary-600"></i>
                                3. Observaciones y Evolución Clínica
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">Opcional</span>
                        </div>
                        <textarea id="obs_editor" class="folio-editor-area w-full" rows="3" placeholder="Anotaciones de control interno, próxima fecha de revisión, precauciones especiales o evolución observada..."></textarea>
                    </div>

                    <!-- 5. FORMALIZACIÓN, SELLO Y FIRMA FACULTATIVA -->
                    <div class="mt-8 pt-4 border-t-2 border-slate-800">
                        <div class="grid grid-cols-2 gap-8 items-end">
                            <div class="text-xs text-slate-700 space-y-1">
                                <p>En <strong class="text-slate-900">Madrid</strong>, a <strong class="text-slate-900"><?= htmlspecialchars($fechaHoy) ?></strong></p>
                                <p class="text-[10px] text-slate-500">Documento emitido según los estándares de la Ley 41/2002</p>
                                <div class="p-2 bg-slate-50 border border-slate-200 rounded text-[10px] text-slate-500 mt-2">
                                    <span class="font-bold text-slate-700">Registro Clínico Electrónico Certificado</span>
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="w-full h-20 border border-dashed border-slate-300 rounded-lg bg-slate-50 flex flex-col items-center justify-center text-[10px] text-slate-400">
                                    <i class="bi bi-pen text-slate-300 text-lg"></i>
                                    <span>[Firma Digitalizada / Sello del Facultativo]</span>
                                </div>
                                <span class="text-[10px] font-bold text-slate-800 block mt-1.5">Fisioterapeuta / Facultativo Colegiado Responsable</span>
                                <span class="text-[9px] text-slate-400">Colegio Profesional de Fisioterapeutas</span>
                            </div>
                        </div>
                    </div>

                    <!-- 6. PIE DE PÁGINA OFICIAL -->
                    <footer class="mt-8 pt-3 border-t border-slate-200 text-[9px] text-slate-400 flex items-center justify-between">
                        <span>Historial Clínico Confidencial • Ley 41/2002 & RGPD (UE) 2016/679</span>
                        <span>Página 1 de 1</span>
                    </footer>

                </div>
            </div>
        </form>
    </main>

</div>

<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/medical-report/report-studio.js"></script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>