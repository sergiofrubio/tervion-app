/**
 * Módulo: Document Sign Studio (Firma digital de consentimientos en folio y PDF)
 */

export function initDocumentSignStudio(options = {}) {
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

        if (hiddenInputFirma && hiddenInputFirma.value && hiddenInputFirma.value.startsWith('data:image')) {
            signaturePad.fromDataURL(hiddenInputFirma.value, { ratio: 1 });
        }

        signaturePad.addEventListener('endStroke', function() {
            if (!signaturePad.isEmpty() && hiddenInputFirma) {
                hiddenInputFirma.value = signaturePad.toDataURL('image/png');
            }
        });

        if (btnClear) {
            btnClear.addEventListener('click', function() {
                signaturePad.clear();
                if (hiddenInputFirma) hiddenInputFirma.value = '';
            });
        }

        if (btnUndo) {
            btnUndo.addEventListener('click', function() {
                const data = signaturePad.toData();
                if (data && data.length > 0) {
                    data.pop();
                    signaturePad.fromData(data);
                    if (hiddenInputFirma) {
                        hiddenInputFirma.value = signaturePad.isEmpty() ? '' : signaturePad.toDataURL('image/png');
                    }
                }
            });
        }

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
            docDisplayFecha.textContent = this.value;
        });
    }

    // 3. ZOOM
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
            const a4WidthPx = 794;
            applyZoom(availableWidth / a4WidthPx);
        }
    });

    // 4. DESCARGA PDF
    document.getElementById('btnDownloadFolioPdf')?.addEventListener('click', function() {
        const element = document.getElementById('printableFolioSheet');
        if (!element || typeof html2pdf === 'undefined') {
            if (options.pdfFallbackUrl) {
                window.location.href = options.pdfFallbackUrl;
            } else {
                window.print();
            }
            return;
        }

        const filename = options.pdfFilename || 'Documento_Firmado.pdf';
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
            if (options.pdfFallbackUrl) {
                window.location.href = options.pdfFallbackUrl;
            } else {
                window.print();
            }
        });
    });

    // 5. IMPRESIÓN DIRECTA
    document.getElementById('btnPrintFolio')?.addEventListener('click', function() {
        window.print();
    });

    // 6. ENVÍO FORMULARIO
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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDocumentSignStudio);
} else {
    initDocumentSignStudio();
}
window.addEventListener('tervion:navigated', initDocumentSignStudio);
