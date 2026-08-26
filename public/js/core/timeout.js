/**
 * Core Session Timeout Manager
 */

export function initSessionTimeout(timeoutSeconds = 600, logoutUrl = '/logout') {
    let timer;

    function resetTimer() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            window.location.href = logoutUrl;
        }, timeoutSeconds * 1000);
    }

    window.addEventListener('mousemove', resetTimer, { passive: true });
    window.addEventListener('keydown', resetTimer, { passive: true });
    window.addEventListener('scroll', resetTimer, { passive: true });
    window.addEventListener('click', resetTimer, { passive: true });

    // Iniciar temporizador
    resetTimer();
}
