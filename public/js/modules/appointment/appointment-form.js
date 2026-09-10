/**
 * Módulo: Appointment Form (Calendario de disponibilidad, slots y búsqueda de paciente)
 */

export function initAppointmentForm(options = {}) {
    const root = options.rootUrl || '';
    const hiddenFisioInput = document.getElementById('terapeuta_id');
    const fisioCards = document.querySelectorAll('.fisio-card');
    const diasContainer = document.getElementById('dias-container');
    const slotsContainer = document.getElementById('slots-container');
    const fechaHoraHidden = document.getElementById('fecha_hora_hidden') || document.getElementById('fecha_hora');
    const tipoCitaSelect = document.getElementById('tipo_cita_id');

    if (!diasContainer || !slotsContainer) return;

    function generateCalendarHtml(year, month, availableDaysMap, selectedDateStr) {
        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        const daysOfWeek = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

        const firstDayDate = new Date(year, month, 1);
        let startDayIndex = firstDayDate.getDay() - 1;
        if (startDayIndex < 0) startDayIndex = 6;

        const totalDays = new Date(year, month + 1, 0).getDate();

        let html = `
        <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm flex-1">
            <h3 class="text-sm font-extrabold text-gray-900 mb-4 text-center capitalize">${monthNames[month]} ${year}</h3>
            <div class="grid grid-cols-7 gap-2 text-center text-[10px] font-black text-gray-400 uppercase tracking-wider mb-3">
        `;

        daysOfWeek.forEach(day => {
            html += `<div class="py-1">${day}</div>`;
        });

        for (let i = 0; i < startDayIndex; i++) {
            html += `<div></div>`;
        }

        for (let day = 1; day <= totalDays; day++) {
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const dayInfo = availableDaysMap[dateStr];
            const isAvailable = !!dayInfo;
            const isSelected = dateStr === selectedDateStr;

            let cellClass = "";
            let btnDisabled = "";

            if (isAvailable) {
                if (isSelected) {
                    cellClass = "bg-primary-600 text-white font-extrabold shadow-md hover:bg-primary-700";
                } else {
                    cellClass = "border-2 border-primary-500/20 text-primary-700 bg-primary-50/20 font-bold hover:border-primary-500 hover:bg-primary-50 hover:text-primary-800 transition-all";
                }
            } else {
                cellClass = "text-gray-300 cursor-not-allowed pointer-events-none";
                btnDisabled = "disabled";
            }

            html += `
            <button type="button" ${btnDisabled} data-date="${dateStr}"
                class="w-full aspect-square flex items-center justify-center text-xs rounded-xl focus:outline-none transition-all active:scale-95 ${cellClass}">
                ${day}
            </button>
            `;
        }

        html += `
            </div>
        </div>
        `;

        return html;
    }

    function loadAvailableDays(fisioId, initialDateTime = '') {
        diasContainer.innerHTML = '<div class="text-sm text-gray-500 py-4 w-full text-center">Cargando días disponibles...</div>';
        slotsContainer.innerHTML = '<div class="col-span-full text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl text-center border border-gray-100">Selecciona un día primero para ver las horas.</div>';

        let initialDate = '';
        let initialTime = '';
        if (initialDateTime) {
            const parts = initialDateTime.split(' ');
            initialDate = parts[0];
            if (parts[1]) {
                initialTime = parts[1].substring(0, 5);
            }
        }

        const tipoCitaId = tipoCitaSelect ? tipoCitaSelect.value : '';
        fetch(`${root}/citas/dias-disponibles?fisio_id=${encodeURIComponent(fisioId)}&tipo_cita_id=${encodeURIComponent(tipoCitaId)}`)
            .then(response => response.json())
            .then(days => {
                diasContainer.innerHTML = '';

                const availableDaysMap = {};
                days.forEach(d => {
                    availableDaysMap[d.fecha] = d;
                });

                if (initialDate && !availableDaysMap[initialDate]) {
                    availableDaysMap[initialDate] = {
                        fecha: initialDate,
                        total_slots: 1
                    };
                }

                if (days.length === 0 && !initialDate) {
                    diasContainer.className = "flex w-full";
                    diasContainer.innerHTML = '<div class="text-sm text-red-500 py-4 w-full text-center font-bold">No hay días disponibles programados para este profesional en los próximos 60 días.</div>';
                    return;
                }

                const today = new Date();
                const currentYear = today.getFullYear();
                const currentMonth = today.getMonth();

                const nextMonthDate = new Date(currentYear, currentMonth + 1, 1);
                const nextYear = nextMonthDate.getFullYear();
                const nextMonth = nextMonthDate.getMonth();

                const currentCalHtml = generateCalendarHtml(currentYear, currentMonth, availableDaysMap, initialDate);
                const nextCalHtml = generateCalendarHtml(nextYear, nextMonth, availableDaysMap, initialDate);

                diasContainer.className = "grid grid-cols-1 md:grid-cols-2 gap-6 w-full";
                diasContainer.innerHTML = currentCalHtml + nextCalHtml;

                diasContainer.querySelectorAll('button[data-date]').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const dateStr = this.dataset.date;
                        selectDate(dateStr);
                    });
                });

                function selectDate(dateStr) {
                    diasContainer.querySelectorAll('button[data-date]').forEach(b => {
                        const d = b.dataset.date;
                        const isAv = !!availableDaysMap[d];
                        const isSel = d === dateStr;

                        if (isAv) {
                            if (isSel) {
                                b.className = "w-full aspect-square flex items-center justify-center text-xs rounded-xl focus:outline-none transition-all active:scale-95 bg-primary-600 text-white font-extrabold shadow-md hover:bg-primary-700";
                            } else {
                                b.className = "w-full aspect-square flex items-center justify-center text-xs rounded-xl focus:outline-none transition-all active:scale-95 border-2 border-primary-500/20 text-primary-700 bg-primary-50/20 font-bold hover:border-primary-500 hover:bg-primary-50 hover:text-primary-800 transition-all";
                            }
                        }
                    });

                    loadAvailableHours(fisioId, dateStr, dateStr === initialDate ? initialTime : '');
                }

                if (initialDate) {
                    selectDate(initialDate);
                }
            })
            .catch(err => {
                console.error(err);
                diasContainer.innerHTML = '<div class="text-sm text-red-500 py-4 w-full text-center">Error al cargar la agenda.</div>';
            });
    }

    function loadAvailableHours(fisioId, dateStr, initialTime = '') {
        slotsContainer.innerHTML = '<div class="col-span-full text-sm text-gray-500 py-4 text-center">Cargando horas disponibles...</div>';

        const tipoCitaId = tipoCitaSelect ? tipoCitaSelect.value : '';
        fetch(`${root}/citas/slots?fisio_id=${encodeURIComponent(fisioId)}&fecha=${encodeURIComponent(dateStr)}&tipo_cita_id=${encodeURIComponent(tipoCitaId)}`)
            .then(response => response.json())
            .then(slots => {
                slotsContainer.innerHTML = '';

                if (initialTime && !slots.includes(initialTime)) {
                    slots.push(initialTime);
                    slots.sort();
                }

                if (slots.length === 0) {
                    slotsContainer.innerHTML = '<div class="col-span-full text-sm text-red-500 py-4 text-center">No hay horas libres para este día.</div>';
                    return;
                }

                slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'slot-btn px-4 py-3 text-sm font-bold border-2 border-gray-100 rounded-xl hover:border-primary-500 hover:text-primary-600 transition-all text-gray-700 bg-white';
                    btn.textContent = slot;

                    const selectHour = () => {
                        document.querySelectorAll('.slot-btn').forEach(b => {
                            b.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-600');
                            b.classList.add('border-gray-100', 'text-gray-700');
                        });
                        btn.classList.remove('border-gray-100', 'text-gray-700');
                        btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-600');
                        if (fechaHoraHidden) fechaHoraHidden.value = `${dateStr} ${slot}:00`;

                        const fechaSesionInput = document.getElementById('fecha_sesion');
                        const horaInicioInput = document.getElementById('hora_inicio');
                        const horaFinInput = document.getElementById('hora_fin');

                        if (fechaSesionInput) fechaSesionInput.value = dateStr;
                        if (horaInicioInput) horaInicioInput.value = slot;

                        if (horaFinInput && tipoCitaSelect) {
                            const selectedOption = tipoCitaSelect.options[tipoCitaSelect.selectedIndex];
                            const duracion = selectedOption ? parseInt(selectedOption.dataset.duracion || 60, 10) : 60;
                            const [h, m] = slot.split(':').map(Number);
                            const endDate = new Date();
                            endDate.setHours(h, m + duracion, 0, 0);
                            const endH = String(endDate.getHours()).padStart(2, '0');
                            const endM = String(endDate.getMinutes()).padStart(2, '0');
                            horaFinInput.value = `${endH}:${endM}`;
                        }
                    };

                    btn.addEventListener('click', selectHour);
                    slotsContainer.appendChild(btn);

                    if (slot === initialTime) {
                        selectHour();
                    }
                });
            })
            .catch(err => {
                console.error(err);
                slotsContainer.innerHTML = '<div class="col-span-full text-sm text-red-500 py-4 text-center">Error al cargar las horas.</div>';
            });
    }

    if (tipoCitaSelect) {
        tipoCitaSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption) {
                const precioInput = document.getElementById('precio_sesion');
                if (precioInput && selectedOption.dataset.precio) {
                    precioInput.value = parseFloat(selectedOption.dataset.precio).toFixed(2);
                }
                const horaInicioInput = document.getElementById('hora_inicio');
                const horaFinInput = document.getElementById('hora_fin');
                if (horaInicioInput && horaInicioInput.value && horaFinInput) {
                    const duracion = parseInt(selectedOption.dataset.duracion || 60, 10);
                    const [h, m] = horaInicioInput.value.split(':').map(Number);
                    const endDate = new Date();
                    endDate.setHours(h, m + duracion, 0, 0);
                    const endH = String(endDate.getHours()).padStart(2, '0');
                    const endM = String(endDate.getMinutes()).padStart(2, '0');
                    horaFinInput.value = `${endH}:${endM}`;
                }
            }

            if (hiddenFisioInput && hiddenFisioInput.value) {
                loadAvailableDays(hiddenFisioInput.value);
            }
        });
    }

    function setupSearch(inputId, resultsId, hiddenId, rol, onSelect) {
        const input = document.getElementById(inputId);
        const results = document.getElementById(resultsId);
        const hidden = document.getElementById(hiddenId);
        if (!input || !results || !hidden) return;
        let debounceTimer;

        input.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                results.classList.add('hidden');
                return;
            }

            const searchUrl = rol === 'Paciente' ? `${root}/pacientes/buscar?q=${encodeURIComponent(query)}` : `${root}/trabajadores/buscar?q=${encodeURIComponent(query)}`;
            debounceTimer = setTimeout(() => {
                fetch(searchUrl)
                    .then(response => response.json())
                    .then(data => {
                        results.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(user => {
                                const div = document.createElement('div');
                                div.className = 'px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors flex items-center justify-between border-b border-gray-50 last:border-0';
                                div.innerHTML = `
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-900">${user.nombre} ${user.apellidos}</span>
                                    <span class="text-xs text-gray-500">ID: ${user.usuario_id}</span>
                                </div>
                                <i class="bi bi-plus-circle text-primary-500"></i>
                            `;
                                div.addEventListener('click', () => {
                                    input.value = `${user.nombre} ${user.apellidos}`;
                                    hidden.value = user.usuario_id;
                                    results.classList.add('hidden');
                                    input.classList.add('border-primary-500', 'ring-1', 'ring-primary-500');
                                    if (typeof onSelect === 'function') {
                                        onSelect(user.usuario_id);
                                    }
                                });
                                results.appendChild(div);
                            });
                            results.classList.remove('hidden');
                        } else {
                            results.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">No se encontraron resultados</div>';
                            results.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.classList.add('hidden');
            }
        });
    }

    setupSearch('paciente_search', 'paciente_results', 'paciente_id', 'Paciente');

    fisioCards.forEach(card => {
        card.addEventListener('click', function () {
            const fisioId = this.dataset.id;

            fisioCards.forEach(c => {
                c.classList.remove('border-primary-500', 'bg-primary-50/30', 'ring-2', 'ring-primary-500/20');
                c.classList.add('border-gray-100', 'bg-white');
            });

            this.classList.remove('border-gray-100', 'bg-white');
            this.classList.add('border-primary-500', 'bg-primary-50/30', 'ring-2', 'ring-primary-500/20');

            if (hiddenFisioInput) hiddenFisioInput.value = fisioId;
            loadAvailableDays(fisioId);
        });
    });

    if (hiddenFisioInput && hiddenFisioInput.value) {
        const initialVal = fechaHoraHidden ? fechaHoraHidden.value : '';
        loadAvailableDays(hiddenFisioInput.value, initialVal);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initAppointmentForm({ rootUrl: window.PROJECT_ROOT || '' }));
} else {
    initAppointmentForm({ rootUrl: window.PROJECT_ROOT || '' });
}
window.addEventListener('tervion:navigated', () => initAppointmentForm({ rootUrl: window.PROJECT_ROOT || '' }));
