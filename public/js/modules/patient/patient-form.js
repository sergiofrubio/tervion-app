/**
 * Módulo: Patient Form (Firma digital en Canvas)
 */

export function initPatientForm() {
    const canvas = document.getElementById('signatureCanvas');
    const hiddenInput = document.getElementById('firma_paciente');
    const clearBtn = document.getElementById('clearSignatureBtn');
    const statusText = document.getElementById('signatureStatus');

    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let hasSignature = false;

    if (hiddenInput && hiddenInput.value && hiddenInput.value.startsWith('data:image')) {
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
        if (statusText) statusText.textContent = 'Firma capturada';
        e.preventDefault();
    }

    function stopDrawing() {
        if (isDrawing) {
            isDrawing = false;
            if (hasSignature && hiddenInput) {
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

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            if (hiddenInput) hiddenInput.value = '';
            hasSignature = false;
            if (statusText) statusText.textContent = 'Dibuje la firma dentro del recuadro';
        });
    }
}

// Auto-inicialización para carga directa o por SPA
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPatientForm);
} else {
    initPatientForm();
}
window.addEventListener('tervion:navigated', initPatientForm);
