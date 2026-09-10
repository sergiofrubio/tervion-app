/**
 * Módulo: Medical Report Editor & Studio (TinyMCE, zoom y exportación PDF)
 */

export function initReportStudio(options = {}) {
    // 1. SNIPPETS CLÍNICOS DIRECTOS SOBRE TEXTAREAS
    document.querySelectorAll('.snippet-action-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const textToAppend = this.dataset.text;
            const textarea = document.getElementById(targetId);
            if (textarea) {
                const currentVal = textarea.value.trim();
                textarea.value = currentVal ? `${currentVal}\n\n${textToAppend}` : textToAppend;
                textarea.focus();
                // Desplazar el cursor al final
                textarea.scrollTop = textarea.scrollHeight;
            }
        });
    });

    // 2. ZOOM
    const folioWrapper = document.getElementById('folioReportWrapper');
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
        const viewport = document.getElementById('reportStudioViewport');
        if (viewport) {
            const availableWidth = viewport.clientWidth - 48;
            const a4WidthPx = 794;
            applyZoom(availableWidth / a4WidthPx);
        }
    });

    // 3. DESCARGA PDF
    document.getElementById('btnDownloadReportPdf')?.addEventListener('click', function() {
        const element = document.getElementById('printableReportFolioSheet');
        if (!element || typeof html2pdf === 'undefined') {
            if (options.pdfFallbackUrl) {
                window.location.href = options.pdfFallbackUrl;
            } else {
                window.print();
            }
            return;
        }

        const filename = options.pdfFilename || 'Informe_Clinico.pdf';
        const opt = {
            margin: 0,
            filename: filename,
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

    // 4. IMPRESIÓN DIRECTA
    document.getElementById('btnPrintReport')?.addEventListener('click', function() {
        window.print();
    });

    // 5. GUARDADO FORMULARIO
    document.getElementById('btnSubmitReportStudio')?.addEventListener('click', function() {
        const motivoInput = document.getElementById('folio_motivo_consulta');
        const diagTextarea = document.getElementById('diag_editor');
        const tratTextarea = document.getElementById('trat_editor');
        const obsTextarea = document.getElementById('obs_editor');

        const hiddenMotivo = document.getElementById('hidden_motivo_consulta');
        const hiddenDiag = document.getElementById('hidden_diagnostico');
        const hiddenTrat = document.getElementById('hidden_tratamiento');
        const hiddenObs = document.getElementById('hidden_observaciones');

        const motivoVal = (motivoInput?.value || '').trim();
        const diagVal = (diagTextarea?.value || '').trim();
        const tratVal = (tratTextarea?.value || '').trim();
        const obsVal = (obsTextarea?.value || '').trim();

        if (!motivoVal) {
            alert('Por favor, indica el motivo de la consulta.');
            motivoInput?.focus();
            return;
        }

        if (!diagVal) {
            alert('Por favor, completa el diagnóstico o evaluación clínica.');
            diagTextarea?.focus();
            return;
        }

        if (!tratVal) {
            alert('Por favor, completa el tratamiento realizado o prescripción.');
            tratTextarea?.focus();
            return;
        }

        if (hiddenMotivo) hiddenMotivo.value = motivoVal;
        if (hiddenDiag) hiddenDiag.value = diagVal;
        if (hiddenTrat) hiddenTrat.value = tratVal;
        if (hiddenObs) hiddenObs.value = obsVal;

        const form = document.getElementById('formMedicalReportFolio');
        if (form) form.submit();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initReportStudio());
} else {
    initReportStudio();
}
window.addEventListener('tervion:navigated', () => initReportStudio());
