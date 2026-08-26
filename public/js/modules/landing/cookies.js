/**
 * Módulo: Landing Page (Banner de consentimiento de cookies y tracking)
 */

export function initCookieBanner() {
    const banner = document.getElementById('cookie-banner');
    const acceptBtn = document.getElementById('accept-cookies-btn');
    const rejectBtn = document.getElementById('reject-cookies-btn');
    const cookieConsent = localStorage.getItem('tervion_cookie_consent');

    if (!banner) return;

    if (!cookieConsent) {
        setTimeout(() => {
            banner.classList.remove('translate-y-full', 'opacity-0');
        }, 400);
    }

    function hideBanner() {
        banner.classList.add('translate-y-full', 'opacity-0');
    }

    if (acceptBtn) {
        acceptBtn.addEventListener('click', () => {
            localStorage.setItem('tervion_cookie_consent', 'accepted');
            hideBanner();
        });
    }

    if (rejectBtn) {
        rejectBtn.addEventListener('click', () => {
            localStorage.setItem('tervion_cookie_consent', 'rejected');
            hideBanner();
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCookieBanner);
} else {
    initCookieBanner();
}
