/**
 * Módulo: Appointment List / Calendar (FullCalendar, AlpineJS y acciones de citas)
 */

export function initAppointmentList(options = {}) {
    const root = options.rootUrl || window.PROJECT_ROOT || '';
    
    // Función creadora del componente Alpine
    window.appointmentCalendar = function() {
        const calendarContainer = document.getElementById('calendar');
        let initialEvents = [];
        
        if (calendarContainer && calendarContainer.dataset.events) {
            try {
                initialEvents = JSON.parse(calendarContainer.dataset.events);
            } catch (e) {
                console.error('Error al parsear eventos de citas:', e);
                initialEvents = [];
            }
        }

        return {
            calendar: null,
            slotDuration: localStorage.getItem('calendar_slot_duration') || '30',
            events: initialEvents,
            selectedEvent: null,
            copiedEvent: null,
            cutMode: false,

            init() {
                this.renderCalendar();

                // Atajos globales de teclado para Copiar/Cortar/Pegar
                window.addEventListener('keydown', (e) => {
                    if (e.ctrlKey || e.metaKey) {
                        if (e.key === 'c' && this.selectedEvent) {
                            this.copyEvent();
                            e.preventDefault();
                        } else if (e.key === 'x' && this.selectedEvent) {
                            this.cutEvent();
                            e.preventDefault();
                        } else if (e.key === 'v' && this.copiedEvent) {
                            alert('Para pegar, haz clic en el hueco deseado del calendario tras haber copiado.');
                        }
                    }
                });
            },

            renderCalendar() {
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl || typeof FullCalendar === 'undefined') return;

                if (this.calendar) {
                    this.calendar.destroy();
                }

                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    locale: 'es',
                    firstDay: 1, // Comenzar en Lunes
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    },
                    slotDuration: '00:' + this.slotDuration + ':00',
                    slotMinTime: '07:00:00',
                    slotMaxTime: '22:00:00',
                    allDaySlot: false,
                    events: this.events,
                    editable: true,
                    droppable: true,
                    selectable: true,
                    selectMirror: true,

                    select: (info) => {
                        let fecha = info.startStr.substring(0, 16);
                        if (this.copiedEvent) {
                            if (confirm(`¿Pegar cita de ${this.copiedEvent.title} aquí?`)) {
                                window.location.href = `${root}/citas/crear?fecha_hora=${fecha}&paciente_id=${this.copiedEvent.extendedProps.paciente_id}`;
                                this.copiedEvent = null;
                                this.cutMode = false;
                            }
                        } else {
                            window.location.href = `${root}/citas/crear?fecha_hora=${fecha}`;
                        }
                        this.calendar.unselect();
                    },

                    eventClick: (info) => {
                        this.selectedEvent = info.event;
                    },

                    eventDrop: (info) => {
                        if (confirm(`¿Mover cita al ${this.formatDateTime(info.event.start)}?`)) {
                            let fecha = info.event.startStr.substring(0, 16);
                            window.location.href = `${root}/citas/editar?id=${info.event.id}&fecha_hora=${fecha}`;
                        } else {
                            info.revert();
                        }
                    },

                    eventResize: (info) => {
                        alert('Para cambiar la duración de la cita, por favor hazlo desde "Editar".');
                        info.revert();
                    }
                });

                this.calendar.render();
            },

            updateCalendarConfig() {
                localStorage.setItem('calendar_slot_duration', this.slotDuration);
                let formattedDuration = '00:' + this.slotDuration + ':00';
                if (this.calendar) {
                    this.calendar.setOption('slotDuration', formattedDuration);
                }
            },

            copyEvent() {
                this.copiedEvent = this.selectedEvent;
                this.cutMode = false;
                this.selectedEvent = null;
            },

            cutEvent() {
                this.copiedEvent = this.selectedEvent;
                this.cutMode = true;
                this.selectedEvent = null;
            },

            formatDateTime(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                return d.toLocaleString('es-ES', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };
    };

    // Si Alpine ya está disponible y el elemento existe en el DOM, inicializar árbol si no está ya
    if (window.Alpine && document.getElementById('calendar')) {
        const rootEl = document.querySelector('[x-data="appointmentCalendar()"]');
        if (rootEl && !rootEl._x_dataStack) {
            window.Alpine.initTree(rootEl);
        }
    }
}

// Inicializar inmediatamente para que la función appointmentCalendar esté definida antes de que Alpine parse el DOM
initAppointmentList({ rootUrl: window.PROJECT_ROOT || '' });

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initAppointmentList({ rootUrl: window.PROJECT_ROOT || '' }));
}
window.addEventListener('tervion:navigated', () => initAppointmentList({ rootUrl: window.PROJECT_ROOT || '' }));

