<?php
$pageTitle = "Administración de Control Horario";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up" x-data="{ 
    showModal: false, 
    modalTitle: 'Añadir Registro Horario',
    registro_id: '',
    usuario_id: '',
    fecha: '',
    hora_entrada: '',
    hora_salida: '',
    notas: '',
    openCreate() {
        this.modalTitle = 'Añadir Registro Horario';
        this.registro_id = '';
        this.usuario_id = '';
        this.fecha = '<?= date('Y-m-d') ?>';
        this.hora_entrada = '09:00';
        this.hora_salida = '18:00';
        this.notas = '';
        this.showModal = true;
    },
    openEdit(record) {
        this.modalTitle = 'Editar Registro Horario';
        this.registro_id = record.registro_id;
        this.usuario_id = record.usuario_id;
        this.fecha = record.fecha;
        this.hora_entrada = record.entrada.split(' ')[1].substring(0, 5);
        this.hora_salida = record.salida ? record.salida.split(' ')[1].substring(0, 5) : '';
        this.notas = record.notas || '';
        this.showModal = true;
    }
}">
    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Administración de Control Horario</h1>
            <p class="text-gray-500 text-sm mt-0.5">Supervisa, filtra y corrige manualmente los registros horarios de la plantilla.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= PROJECT_ROOT ?>/fichajes/admin/exportar?<?= http_build_query($filters) ?>"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition-all rounded-2xl shadow-sm gap-2">
                <i class="bi bi-download"></i>
                <span>Exportar datos</span>
            </a>
            <button @click="openCreate()"
                class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-4 py-2.5 rounded-2xl font-semibold text-sm shadow-md transition-all cursor-pointer">
                <i class="bi bi-plus-lg"></i>
                <span>Registro Manual</span>
            </button>
        </div>
    </div>

    <!-- Mensajes de Estado -->
    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="rounded-2xl bg-green-50 p-4 border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
            <p class="text-sm font-medium text-green-800"><?= $_SESSION['success_message'];
                                                            unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="rounded-2xl bg-red-50 p-4 border border-red-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
            <p class="text-sm font-medium text-red-800"><?= $_SESSION['error_message'];
                                                        unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Table Card Header & Filters -->
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <form id="filterForm" method="GET" action="<?= PROJECT_ROOT ?>/fichajes/admin" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-center">
                <div>
                    <select name="usuario_id" onchange="document.getElementById('filterForm').submit()" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all">
                        <option value="">Todos los empleados</option>
                        <?php foreach ($workers as $worker) : ?>
                            <option value="<?= $worker['usuario_id'] ?>" <?= $filters['usuario_id'] === $worker['usuario_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($worker['nombre'] . ' ' . $worker['apellidos'], ENT_QUOTES, 'UTF-8') ?> (<?= $worker['rol'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filters['fecha_inicio']) ?>"
                        onchange="document.getElementById('filterForm').submit()"
                        class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                        placeholder="Fecha Inicio">
                </div>

                <div>
                    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filters['fecha_fin']) ?>"
                        onchange="document.getElementById('filterForm').submit()"
                        class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                        placeholder="Fecha Fin">
                </div>

                <div class="flex items-center justify-end gap-3">
                    <?php if (!empty($filters['usuario_id']) || !empty($filters['fecha_inicio']) || !empty($filters['fecha_fin'])) : ?>
                        <a href="<?= PROJECT_ROOT ?>/fichajes/admin"
                            class="p-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs transition-colors shrink-0" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                    <div class="text-xs text-gray-500 font-medium whitespace-nowrap">
                        Total registrado: <span class="font-bold text-gray-900"><?= count($records) ?></span> registros
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/70 text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100">
                        <th class="py-3 px-4 font-semibold">Empleado</th>
                        <th class="py-3 px-4 font-semibold">Rol</th>
                        <th class="py-3 px-4 font-semibold">Fecha</th>
                        <th class="py-3 px-4 font-semibold">Entrada</th>
                        <th class="py-3 px-4 font-semibold">Salida</th>
                        <th class="py-3 px-4 font-semibold">Duración</th>
                        <th class="py-3 px-4 font-semibold">Notas</th>
                        <th class="py-3 px-4 font-semibold text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($records)) : ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 text-gray-400">
                                        <i class="bi bi-clock text-xl"></i>
                                    </div>
                                    <h3 class="text-xs font-bold text-gray-900">No hay registros</h3>
                                    <p class="mt-0.5 text-xs text-gray-500">No se encontraron registros de control horario con los filtros aplicados.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($records as $row) : ?>
                            <?php
                            $duracion = '-';
                            if ($row['entrada'] && $row['salida']) {
                                $start = strtotime($row['entrada']);
                                $end = strtotime($row['salida']);
                                $diff = $end - $start;
                                $hours = floor($diff / 3600);
                                $minutes = floor(($diff % 3600) / 60);
                                $duracion = sprintf("%dh %02dm", $hours, $minutes);
                            } elseif ($row['entrada']) {
                                $duracion = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-green-50 text-green-700 border border-green-100 animate-pulse">En curso</span>';
                            }

                            // Preparar datos JSON seguros para pasar al JS de Alpine
                            $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-7 w-7 rounded-full bg-primary-50 flex items-center justify-center text-primary-600 font-bold text-xs">
                                            <?= htmlspecialchars(substr($row['nombre'], 0, 1), ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($row['nombre'] . ' ' . $row['apellidos'], ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="text-[11px] text-gray-400 font-medium">#<?= htmlspecialchars($row['usuario_id'], ENT_QUOTES, 'UTF-8') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-600">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700">
                                        <?= htmlspecialchars($row['rol'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-900">
                                    <?= date('d/m/Y', strtotime($row['fecha'])) ?>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-600">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="bi bi-box-arrow-in-right text-green-600 text-xs"></i>
                                        <?= date('H:i:s', strtotime($row['entrada'])) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 font-medium text-gray-600">
                                    <?php if ($row['salida']): ?>
                                        <span class="inline-flex items-center gap-1">
                                            <i class="bi bi-box-arrow-right text-rose-600 text-xs"></i>
                                            <?= date('H:i:s', strtotime($row['salida'])) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 font-bold text-gray-900">
                                    <?= $duracion ?>
                                </td>
                                <td class="py-4 px-4 text-gray-500 max-w-xs truncate">
                                    <?= htmlspecialchars($row['notas'] ?? '', ENT_QUOTES, 'UTF-8') ?: '<span class="text-gray-300">Ninguna</span>' ?>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button @click="openEdit(<?= $rowJson ?>)"
                                            class="p-1.5 text-gray-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all cursor-pointer" title="Editar Registro">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Alpine.js) -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <!-- Trick element to center modal contents -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal Panel -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-middle bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-gray-100 p-6">

                <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-6">
                    <h3 class="text-lg font-bold text-gray-900" x-text="modalTitle"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form action="<?= PROJECT_ROOT ?>/fichajes/admin/guardar" method="POST" class="space-y-4">
                    <input type="hidden" name="registro_id" x-model="registro_id">

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</label>
                        <select name="usuario_id" x-model="usuario_id" required class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                            <option value="">Seleccione un empleado...</option>
                            <?php foreach ($workers as $worker) : ?>
                                <option value="<?= $worker['usuario_id'] ?>">
                                    <?= htmlspecialchars($worker['nombre'] . ' ' . $worker['apellidos'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1 sm:col-span-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</label>
                            <input type="date" name="fecha" x-model="fecha" required class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora Entrada</label>
                            <input type="time" name="hora_entrada" x-model="hora_entrada" required class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Hora Salida</label>
                            <input type="time" name="hora_salida" x-model="hora_salida" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notas / Justificación</label>
                        <textarea name="notas" x-model="notas" rows="3" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all" placeholder="Escribe el motivo del registro manual o edición..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-50 hover:bg-gray-100 transition-all rounded-xl border border-gray-200">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 transition-all rounded-xl shadow-sm">
                            Guardar Registro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>