/**
 * Módulo: Medical Report Editor & Studio (TinyMCE, zoom y exportación PDF)
 */

export function initReportStudio(options = {}) {
    // 1. INICIALIZACIÓN DE TINYMCE
    function initWriterEditors() {
        if (typeof tinymce === 'undefined') {
            setTimeout(initWriterEditors, 50);
            return;
        }

        const editorConfig = {
            license_key: 'gpl',
            menubar: 'edit insert format table',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'charmap', 'table', 
                'searchreplace', 'visualblocks', 'code', 'insertdatetime', 'wordcount'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link | removeformat',
            toolbar_mode: 'sliding',
            content_style: 'body { font-family: Inter, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.6; color: #1e293b; padding: 6px; }',
            branding: false,
            promotion: false,
            statusbar: false,
            height: 160
        };

        if (document.getElementById('diag_editor')) {
            tinymce.init({
                ...editorConfig,
                selector: '#diag_editor',
                placeholder: 'Redacta aquí la anamnesis, palpación, balance articular y juicio diagnóstico...'
            });
        }

        if (document.getElementById('trat_editor')) {
            tinymce.init({
                ...editorConfig,
                selector: '#trat_editor',
                placeholder: 'Terapia manual aplicada, vendajes, electroterapia, ejercicios prescritos y pauta...'
            });
        }

        if (document.getElementById('obs_editor')) {
            tinymce.init({
                ...editorConfig,
                selector: '#obs_editor',
                height: 120,
                placeholder: 'Anotaciones de control interno, próxima revisión recomendada, precauciones...'
            });
        }
    }

    initWriterEditors();

    // 2. SNIPPETS CLÍNICOS
    document.querySelectorAll('.snippet-action-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.dataset.target;
            const textToAppend = this.dataset.text;
            const editor = typeof tinymce !== 'undefined' ? tinymce.get(targetId) : null;

            if (editor) {
                const currentContent = editor.getContent({ format: 'text' }).trim();
                if (currentContent.length > 0) {
                    editor.insertContent('<p>' + textToAppend + '</p>');
                } else {
                    editor.setContent('<p>' + textToAppend + '</p>');
                }
                editor.focus();
            } else {
                const textarea = document.getElementById(targetId);
                if (textarea) {
                    textarea.value = (textarea.value ? textarea.value + '\n\n' : '') + textToAppend;
                }
            }
        });
    });

    // 3. ZOOM
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

    // 4. DESCARGA PDF
    document.getElementById('btnDownloadReportPdf')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

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

    // 5. IMPRESIÓN DIRECTA
    document.getElementById('btnPrintReport')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }
        window.print();
    });

    // 6. GUARDADO FORMULARIO
    document.getElementById('btnSubmitReportStudio')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

        const motivoInput = document.getElementById('folio_motivo_consulta');
        const hiddenMotivo = document.getElementById('hidden_motivo_consulta');
        const hiddenDiag = document.getElementById('hidden_diagnostico');
        const hiddenTrat = document.getElementById('hidden_tratamiento');
        const hiddenObs = document.getElementById('hidden_observaciones');

        const diagEditor = typeof tinymce !== 'undefined' ? tinymce.get('diag_editor') : null;
        const tratEditor = typeof tinymce !== 'undefined' ? tinymce.get('trat_editor') : null;
        const obsEditor = typeof tinymce !== 'undefined' ? tinymce.get('obs_editor') : null;

        const motivoVal = (motivoInput?.value || '').trim();
        const diagVal = diagEditor ? diagEditor.getContent({ format: 'text' }).trim() : (document.getElementById('diag_editor')?.value || '').trim();
        const tratVal = tratEditor ? tratEditor.getContent({ format: 'text' }).trim() : (document.getElementById('trat_editor')?.value || '').trim();
        const obsVal = obsEditor ? obsEditor.getContent({ format: 'text' }).trim() : (document.getElementById('obs_editor')?.value || '').trim();

        if (!motivoVal) {
            alert('Por favor, indica el motivo de la consulta.');
            motivoInput?.focus();
            return;
        }

        if (!diagVal) {
            alert('Por favor, completa el diagnóstico o evaluación clínica.');
            if (diagEditor) diagEditor.focus();
            return;
        }

        if (!tratVal) {
            alert('Por favor, completa el tratamiento realizado o prescripción.');
            if (tratEditor) tratEditor.focus();
            return;
        }

        if (hiddenMotivo) hiddenMotivo.value = motivoVal;
        if (hiddenDiag) hiddenDiag.value = diagEditor ? diagEditor.getContent() : diagVal;
        if (hiddenTrat) hiddenTrat.value = tratEditor ? tratEditor.getContent() : tratVal;
        if (hiddenObs) hiddenObs.value = obsEditor ? obsEditor.getContent() : obsVal;

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
