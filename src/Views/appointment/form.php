<?php
$a = $appointment ?? [];
$isEdit = !empty($a);
$pageTitle = $isEdit ? "Editar Cita" : "Nueva Cita";
include TEMPLATE_DIR . 'header.php';
?>

<div class="w-full animate-fade-in-up">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight"><?= $isEdit ? "Editar Cita" : "Nueva Cita" ?></h1>
            <p class="mt-1 text-sm text-gray-500"><?= $isEdit ? "Modifica los detalles de la sesión programada." : "Programa una nueva sesión para un paciente." ?></p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/citas" onclick="if (history.length > 1) { history.back(); return false; }" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-primary-600 transition-colors">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/citas/<?= $isEdit ? 'edit' : 'create' ?>" method="POST" class="p-8" id="appointment-form">
            <?php if ($isEdit) : ?>
                <input type="hidden" name="cita_id" value="<?= $a['cita_id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Paciente y Fisio -->
                <div class="space-y-6 md:col-span-2 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-person-check text-primary-600"></i>
                        1. Asignación de Paciente y Especialista
                    </h2>
                </div>

                <div class="space-y-2 relative group">
                    <label for="paciente_search" class="block text-sm font-medium text-gray-700">Paciente</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" id="paciente_search" placeholder="Buscar paciente por nombre o ID..."
                            value="<?= $isEdit ? htmlspecialchars($a['paciente_nombre'] . ' ' . $a['paciente_apellidos']) : '' ?>"
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 pl-10 transition-all bg-white" autocomplete="off">
                        <input type="hidden" name="paciente_id" id="paciente_id" value="<?= $a['paciente_id'] ?? '' ?>" required>
                        <div id="paciente_results" class="absolute z-20 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 hidden max-h-64 overflow-y-auto py-2 animate-in fade-in slide-in-from-top-2 duration-200">
                        </div>
                    </div>
                </div>

                <!-- Tipo de Cita -->
                <div class="space-y-2">
                    <label for="tipo_cita_id" class="block text-sm font-medium text-gray-700">Tipo de Cita</label>
                    <div class="relative">
                        <select name="tipo_cita_id" id="tipo_cita_id" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm border p-3 bg-white appearance-none transition-all">
                            <option value="">-- Seleccionar Tipo de Cita --</option>
                            <?php if (!empty($tiposCitas)): ?>
                                <?php foreach ($tiposCitas as $tc): ?>
                                    <?php $selected = ($isEdit && isset($a['tipo_cita_id']) && $a['tipo_cita_id'] == $tc['tipo_cita_id']) ? 'selected' : ''; ?>
                                    <option value="<?= $tc['tipo_cita_id'] ?>" <?= $selected ?>>
                                        <?= htmlspecialchars($tc['nombre']) ?> (<?= $tc['duracion_minutos'] ?> min - <?= number_format($tc['precio'], 2) ?>€)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </div>
                </div>

                <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Fisioterapeuta</label>
                    <input type="hidden" name="fisioterapeuta_id" id="fisioterapeuta_id" value="<?= $isEdit ? $a['fisioterapeuta_id'] : '' ?>" required>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="fisios-cards-container">
                        <?php foreach ($fisioterapeutas as $f): ?>
                            <?php
                            $firstLetter = mb_substr($f['nombre'], 0, 1, 'UTF-8');
                            $isSelected = ($isEdit && $f['usuario_id'] == $a['fisioterapeuta_id']);
                            $avatarId = (intval(preg_replace('/[^0-9]/', '', $f['usuario_id'])) % 70) + 1;
                            $avatarUrl = (isset($f['genero']) && $f['genero'] === 'Mujer')
                                ? "https://randomuser.me/api/portraits/women/{$avatarId}.jpg"
                                : "https://randomuser.me/api/portraits/men/{$avatarId}.jpg";
                            ?>
                            <button type="button" data-id="<?= $f['usuario_id'] ?>"
                                class="fisio-card flex flex-col items-center p-5 bg-white border-2 rounded-3xl hover:border-primary-400 hover:bg-primary-50/10 active:scale-95 transition-all text-center focus:outline-none group <?= $isSelected ? 'border-primary-500 bg-primary-50/30 ring-2 ring-primary-500/20' : 'border-gray-100' ?>">
                                <div class="w-16 h-16 rounded-full overflow-hidden mb-3 border-2 border-white shadow-md group-hover:scale-105 transition-transform duration-200">
                                    <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($f['nombre']) ?>" class="w-full h-full object-cover">
                                </div>
                                <span class="block text-sm font-extrabold text-gray-900 leading-tight"><?= htmlspecialchars($f['nombre'] . ' ' . $f['apellidos']) ?></span>
                                <span class="block text-[10px] text-gray-400 uppercase font-black tracking-wider mt-1">Fisioterapeuta</span>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Selección de Fecha y Hora -->
                <div class="space-y-6 md:col-span-2 pt-4 pb-4 border-b border-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-primary-600"></i>
                        2. Fecha y Horas Disponibles
                    </h2>
                </div>

                <input type="hidden" name="fecha_hora" id="fecha_hora_hidden" value="<?= $isEdit ? $a['fecha_hora'] : '' ?>" required>

                <!-- Días disponibles -->
                <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">1. Días Disponibles</label>
                    <div id="dias-container" class="flex gap-3 overflow-x-auto pb-4 pt-1 px-1 scrollbar-thin scrollbar-thumb-gray-200">
                        <div class="text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl w-full text-center border border-gray-100">
                            Selecciona un profesional para consultar su agenda de días disponibles.
                        </div>
                    </div>
                </div>

                <!-- Horas disponibles -->
                <div class="space-y-3 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">2. Horas Disponibles</label>
                    <div id="slots-container" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3">
                        <div class="col-span-full text-sm text-gray-400 italic p-6 bg-gray-50/50 rounded-2xl text-center border border-gray-100">
                            Selecciona un día primero para ver las horas.
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/citas" class="px-6 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                    Cancelar
                </a>
                <button type="submit" id="submit-btn" class="inline-flex justify-center items-center gap-2 rounded-xl bg-primary-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <?= $isEdit ? "Guardar Cambios" : "Programar Cita" ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/appointment/appointment-form.js"></script>

<style>
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