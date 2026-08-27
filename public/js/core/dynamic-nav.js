/**
 * Tervion SPA Navigation Engine
 */

export function initDynamicNav(options = {}) {
    const containerId = options.containerId || 'contenido';

    // Interceptar navegación por enlaces internos
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        if (shouldIntercept(link)) {
            e.preventDefault();
            navigateTo(link.href, containerId);
        }
    });

    // Manejar historial (Atrás / Adelante)
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.url) {
            loadContent(e.state.url, false, containerId);
        } else {
            loadContent(window.location.href, false, containerId);
        }
    });

    // Guardar estado inicial
    if (!history.state) {
        history.replaceState({ url: window.location.href, title: document.title }, document.title, window.location.href);
    }
}

function shouldIntercept(link) {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
        return false;
    }

    if (link.target === '_blank' || link.hasAttribute('download') || link.dataset.noDynamic !== undefined) {
        return false;
    }

    const url = new URL(link.href, window.location.origin);
    if (url.origin !== window.location.origin) {
        return false;
    }

    if (url.pathname.includes('/logout') || url.pathname.includes('/pdf') || url.pathname.includes('/login')) {
        return false;
    }

    return true;
}

export async function navigateTo(url, containerId = 'contenido') {
    await loadContent(url, true, containerId);
}

async function loadContent(url, pushToHistory = true, containerId = 'contenido') {
    const mainContainer = document.getElementById(containerId);
    if (!mainContainer) {
        window.location.href = url;
        return;
    }

    mainContainer.classList.add('transition-opacity', 'duration-150', 'opacity-40');

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            window.location.href = url;
            return;
        }

        const htmlText = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlText, 'text/html');

        const newMain = doc.getElementById(containerId);
        if (!newMain) {
            window.location.href = url;
            return;
        }

        if (doc.title) {
            document.title = doc.title;
        }

        const newBreadcrumb = doc.getElementById('header-breadcrumb');
        const currentBreadcrumb = document.getElementById('header-breadcrumb');
        if (newBreadcrumb && currentBreadcrumb) {
            currentBreadcrumb.innerHTML = newBreadcrumb.innerHTML;
        }

        mainContainer.innerHTML = newMain.innerHTML;
        mainContainer.classList.remove('opacity-40', 'pointer-events-none');
        mainContainer.scrollTop = 0;

        updateActiveSidebarLinks(url);

        if (pushToHistory) {
            history.pushState({ url: url, title: doc.title }, doc.title, url);
        }

        if (window.Alpine) {
            const bodyEl = document.querySelector('body[x-data]');
            if (bodyEl && bodyEl._x_dataStack && bodyEl._x_dataStack[0]) {
                bodyEl._x_dataStack[0].mobileMenuOpen = false;
                bodyEl._x_dataStack[0].configMenuOpen = false;
            }
        }

        executeNewScripts(newMain);

        if (window.Alpine) {
            window.Alpine.initTree(mainContainer);
        }

        window.dispatchEvent(new CustomEvent('tervion:navigated', { detail: { url } }));

    } catch (err) {
        console.error('Error cargando navegación dinámica:', err);
        window.location.href = url;
    } finally {
        mainContainer.classList.remove('opacity-40');
    }
}

function updateActiveSidebarLinks(targetUrl) {
    const urlObj = new URL(targetUrl, window.location.origin);
    const pathname = rtrimSlash(urlObj.pathname);

    // Selecciona todos los enlaces de navegación del Sidebar Desktop y Móvil
    const navLinks = document.querySelectorAll('aside a, [x-show="mobileMenuOpen"] a');

    navLinks.forEach(link => {
        const linkUrl = new URL(link.href, window.location.origin);
        const linkPath = rtrimSlash(linkUrl.pathname);
        if (linkPath.endsWith('/logout')) return;

        // Comprobación exacta o por prefijo de ruta activa
        const isMatch = (linkPath === pathname) ||
            (linkPath !== '/' && linkPath.length > 1 && pathname.startsWith(linkPath));

        const icon = link.querySelector('i');

        if (isMatch) {
            // Estilo Activo (Teal MedServ Pill)
            link.classList.remove('text-slate-600', 'hover:bg-slate-50', 'hover:text-slate-900');
            link.classList.add('bg-primary-50', 'text-primary-700', 'ring-1', 'ring-primary-500/20');
            if (icon) {
                icon.classList.remove('text-slate-400', 'group-hover:text-slate-600');
                icon.classList.add('text-primary-600');
            }
        } else {
            // Estilo Inactivo
            link.classList.remove('bg-primary-50', 'text-primary-700', 'ring-1', 'ring-primary-500/20');
            link.classList.add('text-slate-600', 'hover:bg-slate-50', 'hover:text-slate-900');
            if (icon) {
                icon.classList.remove('text-primary-600');
                icon.classList.add('text-slate-400', 'group-hover:text-slate-600');
            }
        }
    });
}

function rtrimSlash(str) {
    return str.endsWith('/') && str.length > 1 ? str.slice(0, -1) : str;
}

function executeNewScripts(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(oldScript => {
        const isModule = oldScript.type === 'module';
        const src = oldScript.getAttribute('src');

        if (src) {
            if (isModule) {
                // Los ES Modules se importan dinámicamente
                import(src + '?t=' + Date.now()).catch(err => console.error('Error cargando módulo dinámico:', src, err));
            } else {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                document.body.appendChild(newScript);
                setTimeout(() => newScript.remove(), 100);
            }
        } else {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.textContent = oldScript.textContent;
            document.body.appendChild(newScript);
            setTimeout(() => newScript.remove(), 100);
        }
    });

    if (typeof window.initFormValidation === 'function') {
        window.initFormValidation();
    }
}
