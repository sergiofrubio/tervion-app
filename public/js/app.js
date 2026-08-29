/**
 * Tervion App - Main Entry Point
 */

import { initSessionTimeout } from './core/timeout.js';
import { initDynamicNav } from './core/dynamic-nav.js';
import './core/validations.js'; // Carga validaciones globales y las expone
import './modules/appointment/appointment-list.js'; // Asegura que el componente appointmentCalendar esté registrado desde el inicio
import './modules/medical-report/report-list.js';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar control de inactividad de sesión (10 minutos)
    initSessionTimeout(600, '/logout');

    // 2. Inicializar motor de navegación SPA dinámica
    initDynamicNav({ containerId: 'contenido' });

    console.debug('Tervion App JS initialized.');
});

