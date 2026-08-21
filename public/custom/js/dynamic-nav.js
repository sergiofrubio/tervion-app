/**
 * Tervion Dynamic SPA Navigation Engine
 * Permite la navegación fluida y sin recargas en el Dashboard interceptando
 * enlaces internos, actualizando el contenedor principal (#contenido),
 * el título, breadcrumbs y re-ejecutando scripts necesarios de forma reactiva.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Escuchar clics globales para interceptar navegación interna
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        // Comprobar si es un enlace elegible para navegación dinámica
        if (shouldIntercept(link)) {
            e.preventDefault();
            const targetUrl = link.href;
            navigateTo(targetUrl);
        }
    });

    // Manejar botones Atrás / Adelante del navegador
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.url) {
            loadContent(e.state.url, false);
        } else {
            loadContent(window.location.href, false);
        }
    });

    // Guardar el estado inicial en el history
    if (!history.state) {
        history.replaceState({ url: window.location.href, title: document.title }, document.title, window.location.href);
    }
});

/**
 * Determina si el enlace debe cargarse por AJAX o dejar la navegación por defecto
 */
function shouldIntercept(link) {
    const href = link.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
        return false;
    }

    // Excluir enlaces con target="_blank", atributos de descarga o explícitamente no dinámicos
    if (link.target === '_blank' || link.hasAttribute('download') || link.dataset.noDynamic !== undefined) {
        return false;
    }

    // Comprobar que pertenece al mismo origen
    const url = new URL(link.href, window.location.origin);
    if (url.origin !== window.location.origin) {
        return false;
    }

    // Excluir logout o descargas de pdf
    if (url.pathname.includes('/logout') || url.pathname.includes('/pdf') || url.pathname.includes('/login')) {
        return false;
    }

    return true;
}

/**
 * Transición y carga de una nueva URL
 */
async function navigateTo(url) {
    await loadContent(url, true);
}

/**
 * Carga el contenido de la URL mediante fetch y actualiza el DOM
 */
async function loadContent(url, pushToHistory = true) {
    const mainContainer = document.getElementById('contenido');
    if (!mainContainer) {
        window.location.href = url;
        return;
    }

    // Indicador visual de carga sutil
    showLoadingState(mainContainer);

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            // Si hay redirección a login u error HTTP severo, navegar normalmente
            window.location.href = url;
            return;
        }

        const htmlText = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlText, 'text/html');

        const newMain = doc.getElementById('contenido');
        if (!newMain) {
            // Si la página de destino no tiene el layout (ej. pantalla login), recargar nativo
            window.location.href = url;
            return;
        }

        // 1. Actualizar título de la página
        if (doc.title) {
            document.title = doc.title;
        }

        // 2. Actualizar breadcrumb o título del header si existe
        const newBreadcrumb = doc.getElementById('header-breadcrumb');
        const currentBreadcrumb = document.getElementById('header-breadcrumb');
        if (newBreadcrumb && currentBreadcrumb) {
            currentBreadcrumb.innerHTML = newBreadcrumb.innerHTML;
        }

        // 3. Actualizar contenido principal
        mainContainer.innerHTML = newMain.innerHTML;
        mainContainer.classList.remove('opacity-40', 'pointer-events-none');
        mainContainer.scrollTop = 0;

        // 4. Actualizar estado activo en el Sidebar Navigation
        updateActiveSidebarLinks(url);

        // 5. Gestionar el historial del navegador
        if (pushToHistory) {
            history.pushState({ url: url, title: doc.title }, doc.title, url);
        }

        // 6. Cerrar sidebar en móvil si está abierto (Alpine integration)
        if (window.Alpine) {
            const bodyEl = document.querySelector('body[x-data]');
            if (bodyEl && bodyEl._x_dataStack && bodyEl._x_dataStack[0]) {
                bodyEl._x_dataStack[0].sidebarOpen = false;
            }
        }

        // 7. Re-evaluar scripts embebidos en el nuevo contenido
        executeNewScripts(newMain);

        // 8. Re-inicializar componentes Alpine dentro de #contenido si aplica
        if (window.Alpine) {
            Alpine.initTree(mainContainer);
        }

        // 9. Disparar evento personalizado para extensiones
        window.dispatchEvent(new CustomEvent('tervion:navigated', { detail: { url } }));

    } catch (err) {
        console.error('Error cargando navegación dinámica:', err);
        window.location.href = url;
    } finally {
        hideLoadingState(mainContainer);
    }
}

function showLoadingState(container) {
    container.classList.add('transition-opacity', 'duration-150', 'opacity-40');
}

function hideLoadingState(container) {
    container.classList.remove('opacity-40');
}

/**
 * Actualiza las clases visuales de los links en la barra lateral
 */
function updateActiveSidebarLinks(targetUrl) {
    const urlObj = new URL(targetUrl, window.location.origin);
    const pathname = urlObj.pathname;

    const navLinks = document.querySelectorAll('aside nav a');
    navLinks.forEach(link => {
        const linkUrl = new URL(link.href, window.location.origin);
        const isMatch = (linkUrl.pathname === pathname) ||
            (pathname === '/' && linkUrl.pathname.endsWith('/inicio')) ||
            (linkUrl.pathname !== '/' && !linkUrl.pathname.endsWith('/inicio') && pathname.startsWith(linkUrl.pathname));

        const icon = link.querySelector('i');

        if (isMatch) {
            link.classList.remove('text-gray-300', 'hover:bg-gray-800', 'hover:text-white');
            link.classList.add('bg-primary-600', 'text-white', 'shadow-md', 'font-semibold');
            if (icon) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-white');
            }
        } else {
            link.classList.remove('bg-primary-600', 'text-white', 'shadow-md', 'font-semibold');
            link.classList.add('text-gray-300', 'hover:bg-gray-800', 'hover:text-white');
            if (icon) {
                icon.classList.remove('text-white');
                icon.classList.add('text-gray-400');
            }
        }
    });
}

/**
 * Ejecuta scripts que vienen dentro del contenido nuevo (ej. formularios de citas, gráficos)
 */
function executeNewScripts(container) {
    const scripts = container.querySelectorAll('script');
    scripts.forEach(oldScript => {
        const newScript = document.createElement('script');
        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
        newScript.textContent = oldScript.textContent;
        document.body.appendChild(newScript);
        // Limpieza tras ejecutar para no acumular etiquetas huérfanas
        setTimeout(() => newScript.remove(), 100);
    });

    // Re-ejecutar eventos de validaciones si existen
    if (typeof window.initFormValidation === 'function') {
        window.initFormValidation();
    }
}
