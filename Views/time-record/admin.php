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
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Administración de Control Horario</h1>
            <p class="mt-1 text-sm text-gray-500">Supervisa, filtra y corrige manualmente los registros horarios de la plantilla.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <a href="<?= PROJECT_ROOT ?>/fichajes"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition-all rounded-xl shadow-sm gap-2">
                <i class="bi bi-clock"></i>
                Mi Fichaje
            </a>
            <button @click="openCreate()"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-100 transition-all rounded-xl shadow-sm gap-2">
                <i class="bi bi-plus-lg"></i>
                Registro Manual
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

    <!-- Tarjeta Filtros -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="<?= PROJECT_ROOT ?>/fichajes/admin" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Trabajador</label>
                <select name="usuario_id" class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                    <option value="">Todos los empleados</option>
                    <?php foreach ($workers as $worker) : ?>
                        <option value="<?= $worker['usuario_id'] ?>" <?= $filters['usuario_id'] === $worker['usuario_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($worker['nombre'] . ' ' . $worker['apellidos'], ENT_QUOTES, 'UTF-8') ?> (<?= $worker['rol'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filters['fecha_inicio']) ?>"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha Fin</label>
                <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filters['fecha_fin']) ?>"
                    class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-gray-900 hover:bg-gray-800 transition-all rounded-xl shadow-sm gap-2">
                    <i class="bi bi-filter"></i>
                    Filtrar
                </button>
                <a href="<?= PROJECT_ROOT ?>/fichajes/admin"
                    class="inline-flex items-center justify-center p-2.5 text-sm font-semibold text-gray-500 bg-gray-50 hover:bg-gray-100 transition-all rounded-xl border border-gray-200">
                    <i class="bi bi-arrow-counterclockwise text-lg"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de Registros -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Entrada</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salida</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duración</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notas</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php if (empty($records)) : ?>
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-400">
                                <i class="bi bi-clock text-3xl mb-2 block"></i>
                                No se encontraron registros coincidentes con los filtros.
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
                                $duracion = '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100 animate-pulse">En curso</span>';
                            }

                            // Preparar datos JSON seguros para pasar al JS de Alpine
                            $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($row['nombre'] . ' ' . $row['apellidos'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $row['rol'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= date('d/m/Y', strtotime($row['fecha'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('H:i:s', strtotime($row['entrada'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $row['salida'] ? date('H:i:s', strtotime($row['salida'])) : '<span class="text-gray-400">—</span>' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    <?= $duracion ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    <?= htmlspecialchars($row['notas'] ?? '', ENT_QUOTES, 'UTF-8') ?: '<span class="text-gray-300">Ninguna</span>' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button @click="openEdit(<?= $rowJson ?>)"
                                        class="inline-flex items-center text-primary-600 hover:text-primary-900 gap-1 bg-primary-50 hover:bg-primary-100/80 px-2.5 py-1.5 rounded-lg transition-colors">
                                        <i class="bi bi-pencil"></i>
                                        Editar
                                    </button>
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