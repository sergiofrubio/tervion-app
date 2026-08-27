<?php
$pageTitle = "Citas";
include TEMPLATE_DIR . 'header.php';

$filtro_fecha_hora = isset($_REQUEST['fecha_hora']) ? $_REQUEST['fecha_hora'] : '';
$filtro_estado = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : '';
$filtro_fisioterapeuta = isset($_REQUEST['fisioterapeuta_id']) ? $_REQUEST['fisioterapeuta_id'] : '';

if (empty($fisioterapeutas) && !empty($appointments)) {
    $fisioterapeutas = [];
    $seen = [];
    foreach ($appointments as $cita) {
        if (!empty($cita['fisioterapeuta_id']) && !isset($seen[$cita['fisioterapeuta_id']])) {
            $seen[$cita['fisioterapeuta_id']] = true;
            $fisioterapeutas[] = [
                'usuario_id' => $cita['fisioterapeuta_id'],
                'nombre' => $cita['fisioterapeuta_nombre'] ?? '',
                'apellidos' => $cita['fisioterapeuta_apellidos'] ?? ''
            ];
        }
    }
}

$citas_filtradas = [];
if (!empty($appointments)) {
    foreach ($appointments as $cita) {
        $match = true;
        if ($filtro_fecha_hora !== '' && strpos((string)$cita['fecha_hora'], $filtro_fecha_hora) === false) {
            $match = false;
        }
        if ($filtro_estado !== '' && isset($cita['estado']) && $cita['estado'] !== $filtro_estado) {
            $match = false;
        }
        if ($filtro_fisioterapeuta !== '' && isset($cita['fisioterapeuta_id']) && (string)$cita['fisioterapeuta_id'] !== (string)$filtro_fisioterapeuta) {
            $match = false;
        }
        if ($match) {
            $citas_filtradas[] = $cita;
        }
    }
}


$total_citas = count($citas_filtradas);

// Prepare events for FullCalendar
$calendarEvents = [];
if (!empty($citas_filtradas)) {
    foreach ($citas_filtradas as $cita) {
        $estado = $cita['estado'] ?? 'Programada';
        $bgColor = '#3b82f6'; // blue-500 default (Pendiente)
        if ($estado === 'Realizada') $bgColor = '#10b981'; // emerald-500
        elseif ($estado === 'Confirmada') $bgColor = '#14b8a6'; // teal-500
        elseif ($estado === 'Programada') $bgColor = '#f59e0b'; // amber-500
        elseif ($estado === 'Cancelada') $bgColor = '#f43f5e'; // rose-500
        
        if (!empty($cita['tipo_cita_color'])) {
            $bgColor = $cita['tipo_cita_color'];
        }

        $start = $cita['fecha_hora'];
        // Assume 1 hour duration by default
        $end = date('Y-m-d H:i:s', strtotime($start . ' +1 hour'));
        
        $calendarEvents[] = [
            'id' => $cita['cita_id'],
            'title' => trim(($cita['paciente_nombre'] ?? '') . ' ' . ($cita['paciente_apellidos'] ?? '')),
            'start' => $start,
            'end' => $end,
            'backgroundColor' => $bgColor,
            'borderColor' => $bgColor,
            'extendedProps' => [
                'fisioterapeuta' => trim(($cita['fisioterapeuta_nombre'] ?? '') . ' ' . ($cita['fisioterapeuta_apellidos'] ?? '')),
                'estado' => $estado,
                'paciente_id' => $cita['paciente_id']
            ]
        ];
    }
}
?>

<div class="space-y-6 animate-fade-in-up" x-data="appointmentCalendar()">
    <!-- Title & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Citas</h1>
            <p class="text-gray-500 text-sm mt-0.5">Gestiona las citas programadas de los pacientes en formato semanal.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white border border-gray-200 px-3 py-1.5 rounded-xl shadow-sm">
                <label for="slot_duration" class="text-xs font-semibold text-gray-600">Intervalo:</label>
                <select id="slot_duration" x-model="slotDuration" @change="updateCalendarConfig()" class="text-xs bg-transparent focus:outline-none font-bold text-gray-900 cursor-pointer">
                    <template x-for="interval in [10, 15, 20, 30, 40, 45, 50, 60]">
                        <option :value="interval + ':00'" x-text="interval + ' min'"></option>
                    </template>
                </select>
            </div>
            <a href="<?= PROJECT_ROOT ?>/citas/crear" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Asignar Cita</span>
            </a>
        </div>
    </div>

    <?php
    $alert = null;
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
    } elseif (isset($_GET['alert']) && isset($_GET['message'])) {
        $alert = [
            'type' => $_GET['alert'],
            'message' => $_GET['message']
        ];
    }

    if ($alert):
        $alert_type = $alert['type'];
        $alert_message = $alert['message'];
        $bg_color = $alert_type === 'danger' ? 'bg-rose-50 text-rose-800 border-rose-200' : ($alert_type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-blue-50 text-blue-800 border-blue-200');
    ?>
        <div class="p-4 rounded-2xl border flex items-center justify-between text-sm font-medium <?= $bg_color ?>" role="alert" x-data="{ show: true }" x-show="show">
            <div class="flex items-center gap-2">
                <i class="bi <?= $alert_type === 'success' ? 'bi-check-circle-fill text-emerald-500' : ($alert_type === 'danger' ? 'bi-exclamation-triangle-fill text-rose-500' : 'bi-info-circle-fill text-blue-500') ?> text-lg"></i>
                <span><?= htmlspecialchars($alert_message) ?></span>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- Calendar Container & Filters -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden flex flex-col min-h-[600px]">
        <!-- Top Toolbar / Filters -->
        <div class="p-4 sm:p-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="<?= PROJECT_ROOT ?>/citas" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <select name="fisioterapeuta_id" onchange="this.form.submit()" class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all cursor-pointer min-w-[200px] shadow-sm">
                    <option value="" <?= $filtro_fisioterapeuta === '' ? 'selected' : '' ?>>Todas las agendas</option>
                    <?php if (!empty($fisioterapeutas)): ?>
                        <?php foreach ($fisioterapeutas as $fisio): ?>
                            <option value="<?= htmlspecialchars($fisio['usuario_id']) ?>" <?= (string)$filtro_fisioterapeuta === (string)$fisio['usuario_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars(($fisio['nombre'] ?? '') . ' ' . ($fisio['apellidos'] ?? '')) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                    Total: <span class="font-bold text-gray-900"><?= $total_citas ?></span> citas
                </div>
            </form>
            
            <div class="flex items-center gap-2 text-xs">
                <!-- Copied indicator -->
                <div x-show="copiedEvent" x-cloak class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg flex items-center gap-2 font-medium animate-pulse">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Cita en portapapeles</span>
                    <button @click="copiedEvent = null; cutMode = false" class="hover:text-blue-900 ml-1"><i class="bi bi-x"></i></button>
                </div>
            </div>
        </div>
        
        <!-- FullCalendar Element -->
        <div class="p-4 sm:p-6 flex-1">
            <div id="calendar" class="h-full min-h-[600px] text-sm" data-events="<?= htmlspecialchars(json_encode($calendarEvents), ENT_QUOTES, 'UTF-8') ?>"></div>
        </div>
    </div>

    <!-- Modal Detalles Cita -->
    <div x-show="selectedEvent" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="selectedEvent = null" x-show="selectedEvent" x-transition.opacity></div>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md relative z-[101] overflow-hidden transform transition-all" x-show="selectedEvent" x-transition.scale.origin.bottom>
            <div class="p-5 border-b border-gray-100 flex items-start justify-between bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" x-text="selectedEvent ? selectedEvent.title : ''"></h3>
                    <p class="text-xs text-gray-500 font-medium mt-1 flex items-center gap-1.5">
                        <i class="bi bi-calendar-event"></i>
                        <span x-text="selectedEvent ? formatDateTime(selectedEvent.start) : ''"></span>
                    </p>
                </div>
                <button @click="selectedEvent = null" class="text-gray-400 hover:text-gray-700 bg-white shadow-sm p-1.5 rounded-lg border border-gray-200"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <div class="p-5 space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-500 w-24">Estado:</span>
                    <form action="<?= PROJECT_ROOT ?>/citas/estado" method="POST" class="flex-1 m-0">
                        <input type="hidden" name="cita_id" :value="selectedEvent ? selectedEvent.id : ''">
                        <select name="estado" onchange="this.form.submit()" class="w-full text-xs font-bold rounded-lg px-2.5 py-1.5 border border-gray-200 focus:ring-2 focus:ring-primary-500/20 outline-none cursor-pointer">
                            <option value="Programada" :selected="selectedEvent?.extendedProps.estado === 'Programada'">Programada</option>
                            <option value="Confirmada" :selected="selectedEvent?.extendedProps.estado === 'Confirmada'">Confirmada</option>
                            <option value="Pendiente" :selected="selectedEvent?.extendedProps.estado === 'Pendiente'">Pendiente</option>
                            <option value="Realizada" :selected="selectedEvent?.extendedProps.estado === 'Realizada'">Realizada</option>
                            <option value="Cancelada" :selected="selectedEvent?.extendedProps.estado === 'Cancelada'">Cancelada</option>
                        </select>
                    </form>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-500 w-24">Terapeuta:</span>
                    <span class="text-sm font-medium text-gray-900" x-text="selectedEvent?.extendedProps.fisioterapeuta || 'N/D'"></span>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <form action="<?= PROJECT_ROOT ?>/citas/eliminar" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                    <input type="hidden" name="id" :value="selectedEvent ? selectedEvent.id : ''">
                    <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline flex items-center gap-1.5 cursor-pointer">
                        <i class="bi bi-trash3"></i> Eliminar
                    </button>
                </form>
                <div class="flex items-center gap-2">
                    <a :href="selectedEvent ? '<?= PROJECT_ROOT ?>/historial?usuario_id=' + selectedEvent.extendedProps.paciente_id : '#'" 
                       class="px-3 py-1.5 rounded-lg bg-white border border-gray-200 text-gray-700 text-xs font-semibold shadow-sm hover:bg-gray-50">
                        Historial
                    </a>
                    <a :href="selectedEvent ? '<?= PROJECT_ROOT ?>/citas/editar?id=' + selectedEvent.id : '#'" 
                       class="px-3 py-1.5 rounded-lg bg-primary-600 text-white text-xs font-semibold shadow-sm hover:bg-primary-700">
                        Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar Dependencies -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/appointment/appointment-list.js"></script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }

    /* Customize FullCalendar aesthetics to match Tailwind theme */
    .fc {
        --fc-border-color: #f3f4f6; /* gray-100 */
        --fc-button-text-color: #4b5563; /* gray-600 */
        --fc-button-bg-color: #ffffff;
        --fc-button-border-color: #e5e7eb; /* gray-200 */
        --fc-button-hover-bg-color: #f9fafb; /* gray-50 */
        --fc-button-hover-border-color: #d1d5db;
        --fc-button-active-bg-color: #f3f4f6;
        --fc-event-border-color: rgba(0,0,0,0.1);
        --fc-today-bg-color: rgba(59, 130, 246, 0.05); /* primary-50 */
    }
    .fc .fc-button-primary {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        border-radius: 0.5rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827; /* gray-900 */
        text-transform: capitalize;
    }
    .fc-theme-standard th {
        border-color: #f3f4f6;
        padding: 0.5rem 0;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.65rem;
        letter-spacing: 0.05em;
        color: #6b7280;
    }
    .fc-event {
        border-radius: 6px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        padding: 2px 4px;
        font-size: 0.75rem;
        cursor: pointer;
        transition: transform 0.1s ease;
    }
    .fc-event:hover {
        transform: scale(1.02);
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>