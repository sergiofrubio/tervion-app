/**
 * Módulo: Document Template Studio (TinyMCE, variables dinámicas, exportación)
 */

export function initDocumentTemplateStudio() {
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

    if (document.getElementById('folio_contenido_editor')) {
        initTemplateEditor();
    }

    // 2. INSERCIÓN DE VARIABLES DINÁMICAS
    document.querySelectorAll('.insert-tag-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tag = this.dataset.tag;
            const editor = typeof tinymce !== 'undefined' ? tinymce.get('folio_contenido_editor') : null;
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

    // 3. SINCRONIZACIÓN DE TÍTULO
    const folioTitulo = document.getElementById('folio_titulo');
    const topbarDocTitle = document.getElementById('topbarDocTitle');
    if (folioTitulo && topbarDocTitle) {
        folioTitulo.addEventListener('input', function() {
            topbarDocTitle.textContent = this.value.trim() || 'Nueva Plantilla de Documento';
        });
    }

    // 4. ZOOM
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
            const a4WidthPx = 794;
            applyZoom(availableWidth / a4WidthPx);
        }
    });

    // 5. DESCARGA PDF
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

    // 7. GUARDADO DEL FORMULARIO
    document.getElementById('btnSubmitTemplateStudio')?.addEventListener('click', function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.triggerSave();
        }

        const tituloInput = document.getElementById('folio_titulo');
        const descInput = document.getElementById('folio_descripcion');
        const editor = typeof tinymce !== 'undefined' ? tinymce.get('folio_contenido_editor') : null;

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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDocumentTemplateStudio);
} else {
    initDocumentTemplateStudio();
}
window.addEventListener('tervion:navigated', initDocumentTemplateStudio);
