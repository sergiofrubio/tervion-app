<?php
$pageTitle = "Solicitar Cita - Velion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-3xl mx-auto animate-fade-in pb-12">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Nueva Cita</h1>
            <p class="mt-1 text-gray-500">Completa los detalles para programar tu próxima sesión.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/paciente/citas" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-primary-600 transition-colors group">
            <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/paciente/citas/nueva" method="POST" class="p-8 md:p-12">
            <div class="space-y-10">
                
                <!-- Sección: Profesional y Servicio -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center">
                            <i class="bi bi-person-badge text-xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Profesional y Servicio</h2>
                    </div>                    <div class="space-y-4">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Fisioterapeuta</label>
                        <input type="hidden" name="fisioterapeuta_id" id="fisioterapeuta_id" required>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="fisios-cards-container">
                            <?php foreach ($fisioterapeutas as $f): ?>
                                <?php 
                                    $firstLetter = mb_substr($f['nombre'], 0, 1, 'UTF-8');
                                    $avatarId = (intval(preg_replace('/[^0-9]/', '', $f['usuario_id'])) % 70) + 1;
                                    $avatarUrl = (isset($f['genero']) && $f['genero'] === 'Mujer') 
                                        ? "https://randomuser.me/api/portraits/women/{$avatarId}.jpg" 
                                        : "https://randomuser.me/api/portraits/men/{$avatarId}.jpg";
                                ?>
                                <button type="button" data-id="<?= $f['usuario_id'] ?>" 
                                    class="fisio-card flex flex-col items-center p-5 bg-white border-2 border-gray-100 rounded-3xl hover:border-primary-400 hover:bg-primary-50/10 active:scale-95 transition-all text-center focus:outline-none group">
                                    <div class="w-16 h-16 rounded-full overflow-hidden mb-3 border-2 border-white shadow-md group-hover:scale-105 transition-transform duration-200">
                                        <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($f['nombre']) ?>" class="w-full h-full object-cover">
                                    </div>
                                    <span class="block text-sm font-extrabold text-gray-900 leading-tight"><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellidos']) ?></span>
                                    <span class="block text-[10px] text-gray-400 uppercase font-black tracking-wider mt-1">Fisioterapeuta</span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Sección: Fecha y Hora -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="bi bi-calendar-check text-xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Selección de Agenda</h2>
                    </div>

                    <input type="hidden" name="fecha_hora" id="fecha_hora" required>

                    <!-- Días disponibles -->
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">1. Días Disponibles</label>
                        <div id="dias-container" class="flex gap-3 overflow-x-auto pb-4 pt-1 px-1 scrollbar-thin scrollbar-thumb-gray-200">
                            <div class="text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl w-full text-center border border-gray-100">
                                Selecciona un profesional para consultar su agenda de días disponibles.
                            </div>
                        </div>
                    </div>

                    <!-- Horas disponibles -->
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">2. Horas Disponibles</label>
                        <div id="horas-container" class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                            <div class="col-span-full text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl text-center border border-gray-100">
                                Selecciona un día primero para ver las horas.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center justify-end gap-6">
                    <a href="<?= PROJECT_ROOT ?>/paciente/citas" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-2xl bg-primary-600 px-10 py-4 text-sm font-bold text-white shadow-xl shadow-primary-200/50 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all hover:scale-105 active:scale-95">
                        Confirmar Cita
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const diasContainer = document.getElementById('dias-container');
    const horasContainer = document.getElementById('horas-container');
    const fechaHoraInput = document.getElementById('fecha_hora');
    const fisioCards = document.querySelectorAll('.fisio-card');
    const hiddenFisioInput = document.getElementById('fisioterapeuta_id');

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

    function loadAvailableDays(fisioId) {
        diasContainer.innerHTML = '<div class="text-sm text-gray-500 py-4 w-full text-center">Cargando días disponibles...</div>';
        horasContainer.innerHTML = '<div class="col-span-full text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl text-center border border-gray-100">Selecciona un día primero para ver las horas.</div>';
        fechaHoraInput.value = '';

        fetch(`<?= PROJECT_ROOT ?>/citas/dias-disponibles?fisio_id=${encodeURIComponent(fisioId)}`)
            .then(response => response.json())
            .then(days => {
                diasContainer.innerHTML = '';
                
                const availableDaysMap = {};
                days.forEach(d => {
                    availableDaysMap[d.fecha] = d;
                });
                
                if (days.length === 0) {
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
                
                const currentCalHtml = generateCalendarHtml(currentYear, currentMonth, availableDaysMap, '');
                const nextCalHtml = generateCalendarHtml(nextYear, nextMonth, availableDaysMap, '');
                
                diasContainer.className = "grid grid-cols-1 md:grid-cols-2 gap-6 w-full";
                diasContainer.innerHTML = currentCalHtml + nextCalHtml;
                
                diasContainer.querySelectorAll('button[data-date]').forEach(btn => {
                    btn.addEventListener('click', function() {
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
                    
                    loadAvailableHours(fisioId, dateStr);
                }
            })
            .catch(err => {
                console.error(err);
                diasContainer.innerHTML = '<div class="text-sm text-red-500 py-4 w-full text-center">Error al cargar la agenda.</div>';
            });
    }

    function loadAvailableHours(fisioId, dateStr) {
        horasContainer.innerHTML = '<div class="col-span-full text-sm text-gray-500 py-4 text-center">Cargando horas disponibles...</div>';
        fechaHoraInput.value = '';

        fetch(`<?= PROJECT_ROOT ?>/citas/slots?fisio_id=${encodeURIComponent(fisioId)}&fecha=${encodeURIComponent(dateStr)}`)
            .then(response => response.json())
            .then(slots => {
                horasContainer.innerHTML = '';
                if (slots.length === 0) {
                    horasContainer.innerHTML = '<div class="col-span-full text-sm text-red-500 py-4 text-center">No hay horas libres para este día.</div>';
                    return;
                }

                slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'hour-btn px-4 py-3 text-sm font-bold border-2 border-gray-100 rounded-xl hover:border-primary-500 hover:text-primary-600 transition-all text-gray-700 bg-white';
                    btn.textContent = slot;

                    btn.addEventListener('click', () => {
                        document.querySelectorAll('.hour-btn').forEach(b => {
                            b.classList.remove('border-primary-500', 'bg-primary-50', 'text-primary-600');
                            b.classList.add('border-gray-100', 'text-gray-700');
                        });
                        btn.classList.remove('border-gray-100', 'text-gray-700');
                        btn.classList.add('border-primary-500', 'bg-primary-50', 'text-primary-600');
                        fechaHoraInput.value = `${dateStr} ${slot}:00`;
                    });

                    horasContainer.appendChild(btn);
                });
            })
            .catch(err => {
                console.error(err);
                horasContainer.innerHTML = '<div class="col-span-full text-sm text-red-500 py-4 text-center">Error al cargar las horas.</div>';
            });
    }

    fisioCards.forEach(card => {
        card.addEventListener('click', function() {
            const fisioId = this.dataset.id;
            
            fisioCards.forEach(c => {
                c.classList.remove('border-primary-500', 'bg-primary-50/30', 'ring-2', 'ring-primary-500/20');
                c.classList.add('border-gray-100', 'bg-white');
            });
            
            this.classList.remove('border-gray-100', 'bg-white');
            this.classList.add('border-primary-500', 'bg-primary-50/30', 'ring-2', 'ring-primary-500/20');
            
            hiddenFisioInput.value = fisioId;
            loadAvailableDays(fisioId);
        });
    });
});
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    /* Estilos para scrollbar */
    .scrollbar-thin::-webkit-scrollbar {
        height: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
