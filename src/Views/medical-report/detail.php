<?php
$pageTitle = "Informe Clínico #" . str_pad((string)$report['historial_id'], 5, '0', STR_PAD_LEFT) . " — " . htmlspecialchars($report['paciente_nombre'] . ' ' . $report['paciente_apellidos']);
$fechaConsulta = date('d/m/Y', strtotime($report['fecha_consulta']));
$horaConsulta = date('H:i', strtotime($report['fecha_consulta']));
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
    }
</style>

<!-- Librería Open Source html2pdf para generación de informes en PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="h-full w-full flex flex-col bg-white text-slate-800 overflow-hidden font-sans select-none" id="medical-report-detail-studio">

    <!-- 1. BARRA SUPERIOR / STUDIO TOPBAR -->
    <header class="h-14 bg-white border-b border-slate-200 px-4 flex items-center justify-between shrink-0 z-30 shadow-xs studio-toolbar">

        <!-- Izquierda: Volver & Título -->
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/pacientes/detalle?usuario_id=<?= urlencode($report['paciente_id']) ?>"
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs"
                title="Volver al Expediente">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="hidden sm:inline">Volver</span>
            </a>

            <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-600 border border-primary-100 flex items-center justify-center font-bold text-sm shadow-xs">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 leading-none truncate max-w-[200px] sm:max-w-xs md:max-w-md">
                        Informe Clínico #<?= str_pad((string)$report['historial_id'], 5, '0', STR_PAD_LEFT) ?>
                    </h1>
                    <p class="text-[11px] text-slate-500 mt-0.5 leading-none">
                        Paciente: <span class="text-slate-800 font-semibold"><?= htmlspecialchars($report['paciente_nombre'] . ' ' . $report['paciente_apellidos']) ?></span> • <span class="text-slate-600"><?= $fechaConsulta ?></span>
                    </p>
                </div>
            </div>

            <!-- <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <i class="bi bi-shield-check text-emerald-600"></i>
                Registrado
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

        <!-- Derecha: Acciones de Impresión, Exportación y Nuevo Informe -->
        <div class="flex items-center gap-2">

            <!-- <button type="button" id="btnPrintReport" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Imprimir informe médico">
                <i class="bi bi-printer text-xs text-slate-600"></i>
                <span class="hidden md:inline">Imprimir</span>
            </button> -->

            <button type="button" id="btnDownloadReportPdf" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-xs font-semibold text-slate-700 hover:text-slate-900 transition-all border border-slate-200 shadow-xs" title="Descargar Informe en PDF">
                <i class="bi bi-file-earmark-pdf text-xs text-rose-600"></i>
                <span class="hidden md:inline">Descargar PDF</span>
            </button>

            <a href="<?= PROJECT_ROOT ?>/historial/crear?paciente_id=<?= urlencode($report['paciente_id']) ?>"
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                <i class="bi bi-plus-lg text-sm"></i>
                <span>Nuevo Informe</span>
            </a>
        </div>
    </header>

    <!-- 2. WORKSPACE PRINCIPAL: VISOR EN FORMATO FOLIO A4 -->
    <main class="flex-1 bg-slate-100/90 overflow-auto p-4 sm:p-8 flex justify-center items-start custom-scrollbar studio-viewport relative select-text" id="reportStudioViewport">

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
                            REF-INF-<?= str_pad((string)$report['historial_id'], 5, '0', STR_PAD_LEFT) ?>
                        </span> -->
                        <div class="text-[11px] text-slate-600 mt-1.5 font-medium">
                            <span>Fecha Consulta:</span> <strong class="text-slate-900 font-mono"><?= $fechaConsulta ?> <?= $horaConsulta ?></strong>
                        </div>
                    </div>
                </header>

                <!-- 2. TÍTULO DEL INFORME -->
                <div class="my-4">
                    <div class="text-center mb-3">
                        <h1 class="text-base font-extrabold text-slate-900 tracking-tight uppercase underline decoration-primary-500 decoration-2 underline-offset-4">
                            INFORME CLÍNICO DE FISIOTERAPIA Y EVOLUCIÓN
                        </h1>
                    </div>

                    <!-- Motivo de consulta -->
                    <div class="p-3.5 bg-primary-50/70 border-l-4 border-primary-600 rounded-r-lg shadow-2xs">
                        <span class="block text-[11px] font-bold text-primary-900 uppercase tracking-wider mb-0.5">
                            Motivo de la Consulta / Anamnesis Principal
                        </span>
                        <p class="text-xs font-semibold text-slate-800 leading-relaxed">
                            <?= nl2br(htmlspecialchars_decode($report['motivo_consulta'] ?? 'Consulta clínica')) ?>
                        </p>
                    </div>
                </div>

                <!-- 3. FICHA DE IDENTIFICACIÓN DEL PACIENTE -->
                <section class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 my-4 text-xs shadow-xs">
                    <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2 border-b border-slate-200 pb-1 flex items-center justify-between">
                        <span>Datos Identificativos del Paciente</span>
                        <span class="text-[10px] text-slate-400 font-mono">NHC: #<?= htmlspecialchars($report['paciente_id']) ?></span>
                    </div>

                    <div class="grid grid-cols-2 gap-y-1.5 gap-x-4 text-slate-700">
                        <div><strong class="text-slate-900">Paciente:</strong> <?= htmlspecialchars($report['paciente_nombre'] . ' ' . $report['paciente_apellidos']) ?></div>
                        <div><strong class="text-slate-900">DNI / NIE:</strong> <span class="font-mono"><?= htmlspecialchars($report['paciente_id']) ?></span></div>
                        <?php if (!empty($report['paciente_fecha_nacimiento'])): ?>
                            <div><strong class="text-slate-900">F. Nacimiento:</strong> <?= date('d/m/Y', strtotime($report['paciente_fecha_nacimiento'])) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($report['paciente_genero'])): ?>
                            <div><strong class="text-slate-900">Género:</strong> <?= htmlspecialchars($report['paciente_genero']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($paciente['telefono'])): ?>
                            <div><strong class="text-slate-900">Teléfono:</strong> <?= htmlspecialchars($paciente['telefono']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($paciente['email'])): ?>
                            <div><strong class="text-slate-900">Email:</strong> <?= htmlspecialchars($paciente['email']) ?></div>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- 4. CONTENIDO CLÍNICO DEL INFORME -->

                <!-- Sección 1: Diagnóstico / Evaluación -->
                <div class="my-5">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1 mb-2 flex items-center gap-1.5">
                        <i class="bi bi-clipboard-pulse text-primary-600"></i>
                        1. Diagnóstico y Evaluación Clínica
                    </h3>
                    <div class="text-xs text-slate-800 leading-relaxed text-justify bg-slate-50/50 p-3 rounded-lg border border-slate-200/80 prose max-w-none">
                        <?= !empty($report['diagnostico']) ? htmlspecialchars_decode($report['diagnostico']) : '<p class="text-slate-400 italic">Sin diagnóstico registrado.</p>' ?>
                    </div>
                </div>

                <!-- Sección 2: Tratamiento Realizado -->
                <div class="my-5">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1 mb-2 flex items-center gap-1.5">
                        <i class="bi bi-bandaid text-primary-600"></i>
                        2. Tratamiento Realizado y Prescripción Terapéutica
                    </h3>
                    <div class="text-xs text-slate-800 leading-relaxed text-justify bg-slate-50/50 p-3 rounded-lg border border-slate-200/80 prose max-w-none">
                        <?= !empty($report['tratamiento']) ? htmlspecialchars_decode($report['tratamiento']) : '<p class="text-slate-400 italic">Sin tratamiento registrado.</p>' ?>
                    </div>
                </div>

                <!-- Sección 3: Observaciones y Evolución -->
                <?php if (!empty($report['observaciones'])): ?>
                    <div class="my-5">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-200 pb-1 mb-2 flex items-center gap-1.5">
                            <i class="bi bi-chat-left-text text-primary-600"></i>
                            3. Observaciones y Evolución Clínica
                        </h3>
                        <div class="text-xs text-slate-800 leading-relaxed text-justify bg-slate-50/50 p-3 rounded-lg border border-slate-200/80 prose max-w-none">
                            <?= htmlspecialchars_decode($report['observaciones']) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- 5. FORMALIZACIÓN, SELLO Y FIRMA FACULTATIVA -->
                <div class="mt-8 pt-4 border-t-2 border-slate-800">
                    <div class="grid grid-cols-2 gap-8 items-end">
                        <div class="text-xs text-slate-700 space-y-1">
                            <p>En <strong class="text-slate-900">Madrid</strong>, a <strong class="text-slate-900"><?= $fechaConsulta ?></strong></p>
                            <p class="text-[10px] text-slate-500">Documento expedido y firmado en el Sistema de Gestión Clínica</p>
                            <!-- <div class="p-2 bg-slate-50 border border-slate-200 rounded text-[10px] text-slate-500 mt-2 space-y-0.5">
                                <div class="flex items-center gap-1 text-slate-700 font-semibold">
                                    <i class="bi bi-shield-check text-emerald-600"></i>
                                    <span>Certificación de Integridad Clínica</span>
                                </div>
                                <p>Hash de Verificación: <span class="font-mono text-[9px]"><?= strtoupper(substr(md5($report['historial_id'] . $report['paciente_id'] . $report['fecha_consulta']), 0, 16)) ?></span></p>
                            </div> -->
                        </div>

                        <div class="text-center">
                            <div class="w-full h-20 border border-dashed border-slate-300 rounded-lg bg-slate-50 flex flex-col items-center justify-center text-[10px] text-slate-400">
                                <i class="bi bi-pen text-slate-400 text-base"></i>
                                <span class="font-bold text-slate-700 mt-0.5"><?= htmlspecialchars($report['fisioterapeuta_nombre'] . ' ' . $report['fisioterapeuta_apellidos']) ?></span>
                                <span class="text-[9px] text-slate-400">Fisioterapeuta Responsable • Colegiado Oficial</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-800 block mt-1.5">Facultativo Responsable</span>
                            <span class="text-[9px] text-slate-400">Clínica Tervion / Velion</span>
                        </div>
                    </div>
                </div>

                <!-- 6. PIE DE PÁGINA OFICIAL -->
                <footer class="mt-8 pt-3 border-t border-slate-200 text-[9px] text-slate-400 flex items-center justify-between">
                    <span>Historial Clínico Oficial • Ley 41/2002 de Autonomía del Paciente & RGPD</span>
                    <span>Página 1 de 1</span>
                </footer>

            </div>
        </div>
    </main>

</div>

<!-- SCRIPT DE INTERACTIVIDAD Y EXPORTACIÓN PDF -->
<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/medical-report/report-studio.js"></script>

<?php include TEMPLATE_DIR . 'footer.php'; ?>