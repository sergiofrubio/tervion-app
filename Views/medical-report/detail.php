<?php
$pageTitle = "Detalle de Historial Médico";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto animate-fade-in-up">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Detalle de Informe Clínico</h1>
            <p class="mt-1 text-sm text-gray-500">Consulta realizada el <?= date('d/m/Y \a \l\a\s H:i', strtotime($report['fecha_consulta'])) ?></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= PROJECT_ROOT ?>/historial/pdf?id=<?= $report['historial_id'] ?>" target="_blank"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-bold text-white shadow-md hover:bg-gray-800 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-file-earmark-pdf"></i>
                Exportar PDF
            </a>
            <a href="<?= PROJECT_ROOT ?>/usuarios/detail?id=<?= $report['paciente_id'] ?>" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors px-3">
                <i class="bi bi-arrow-left"></i>
                Cerrar
            </a>
        </div>
    </div>

    <!-- Header Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Paciente</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-sm">
                    <?= substr($report['paciente_nombre'], 0, 1) . substr($report['paciente_apellidos'], 0, 1) ?>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900"><?= $report['paciente_nombre'] . ' ' . $report['paciente_apellidos'] ?></h4>
                    <p class="text-sm text-gray-500">ID: <?= $report['paciente_id'] ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Facultativo</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900"><?= $report['fisioterapeuta_nombre'] . ' ' . $report['fisioterapeuta_apellidos'] ?></h4>
                    <p class="text-sm text-gray-500"><?= $report['especialidad'] ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-50">
        <!-- Motivo -->
        <div class="p-8">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Motivo de la Consulta</h3>
            <p class="text-gray-900 font-medium text-lg italic">"<?= $report['motivo_consulta'] ?>"</p>
        </div>

        <!-- Diagnóstico -->
        <div class="p-8">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Diagnóstico Médico</h3>
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                <?= nl2br($report['diagnostico']) ?>
            </div>
        </div>

        <!-- Tratamiento -->
        <div class="p-8">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Tratamiento Realizado</h3>
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                <?= nl2br($report['tratamiento']) ?>
            </div>
        </div>

        <!-- Observaciones -->
        <div class="p-8 bg-gray-50/30">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Observaciones Adicionales</h3>
            <div class="text-sm text-gray-600 italic">
                <?= $report['observaciones'] ? nl2br($report['observaciones']) : 'Sin observaciones adicionales.' ?>
            </div>
        </div>
    </div>

    <div class="mt-8 flex justify-center">
        <button onclick="window.print()" class="text-sm font-medium text-gray-400 hover:text-gray-600 flex items-center gap-2 transition-colors">
            <i class="bi bi-printer"></i>
            Imprimir copia de seguridad
        </button>
    </div>
</div>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
