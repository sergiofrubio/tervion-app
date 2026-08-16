<?php
$pageTitle = "Control Horario";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Control Horario</h1>
            <p class="mt-1 text-sm text-gray-500">Registra tus horas de entrada y salida diarias de forma segura.</p>
        </div>
        
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Administrador') : ?>
            <div class="mt-4 sm:mt-0">
                <a href="<?= PROJECT_ROOT ?>/control-horario/admin" 
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 transition-all rounded-xl shadow-sm gap-2">
                    <i class="bi bi-person-gear text-lg"></i>
                    Panel de Administración
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Mensajes de Estado -->
    <?php if (isset($_SESSION['success_message'])) : ?>
        <div class="rounded-2xl bg-green-50 p-4 border border-green-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-check-circle-fill text-green-500 text-lg"></i>
            <p class="text-sm font-medium text-green-800"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])) : ?>
        <div class="rounded-2xl bg-red-50 p-4 border border-red-100 flex items-center gap-3 animate-fade-in">
            <i class="bi bi-exclamation-circle-fill text-red-500 text-lg"></i>
            <p class="text-sm font-medium text-red-800"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tarjeta de Fichaje Activo -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Fichaje Diario</h3>
                
                <?php if ($activeRecord) : ?>
                    <!-- Trabajando actualmente -->
                    <div class="rounded-2xl bg-green-50 border border-green-100 p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <span class="text-sm font-semibold text-green-800">Jornada en curso</span>
                        </div>
                        <p class="mt-2 text-xs text-green-700">
                            Entrada registrada hoy a las: 
                            <strong class="text-sm font-bold block mt-1"><?= date('H:i:s', strtotime($activeRecord['entrada'])) ?></strong>
                        </p>
                    </div>
                <?php else : ?>
                    <!-- No fichado -->
                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="h-3 w-3 rounded-full bg-gray-400"></span>
                            <span class="text-sm font-semibold text-gray-600">No iniciado</span>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Aún no has registrado tu entrada para hoy.</p>
                    </div>
                <?php endif; ?>
            </div>

            <form action="<?= PROJECT_ROOT ?>/control-horario/fichar" method="POST" class="space-y-4">
                <?php if (!$activeRecord) : ?>
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Notas u observaciones (Opcional)</label>
                        <input type="text" name="notas" 
                               class="w-full rounded-xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all"
                               placeholder="Ej: Teletrabajo, visita médica...">
                    </div>
                <?php endif; ?>

                <?php if ($activeRecord) : ?>
                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center px-4 py-3.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 hover:shadow-lg hover:shadow-red-100 transition-all rounded-xl shadow-sm gap-2">
                        <i class="bi bi-box-arrow-right text-lg"></i>
                        Registrar Salida
                    </button>
                <?php else : ?>
                    <button type="submit" 
                            class="w-full inline-flex items-center justify-center px-4 py-3.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-100 transition-all rounded-xl shadow-sm gap-2">
                        <i class="bi bi-box-arrow-in-right text-lg"></i>
                        Registrar Entrada
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <!-- Historial de Fichajes Propios -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Tus registros recientes</h3>
                <span class="text-xs text-gray-500 font-medium">Últimos 30 días</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Entrada</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Salida</th>
                            <th scope="col" class="px-6 py-3.5 class text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duración</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (empty($history)) : ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400">
                                    <i class="bi bi-clock-history text-3xl mb-2 block"></i>
                                    No se encontraron fichajes registrados.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($history as $row) : ?>
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
                                    $duracion = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100 animate-pulse">En curso</span>';
                                }
                                ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
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
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
