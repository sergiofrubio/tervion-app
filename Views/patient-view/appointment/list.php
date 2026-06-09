<?php
$pageTitle = "Mis Citas - Velion";
include TEMPLATE_DIR . 'header.php';

// Separar citas próximas de pasadas
$hoy = date('Y-m-d H:i:s');
$proximas = [];
$pasadas = [];

if (!empty($appointments)) {
    foreach ($appointments as $cita) {
        // Consideramos próximas las que son futuras y no están canceladas
        if ($cita['fecha_hora'] >= $hoy && $cita['estado'] !== 'Cancelada') {
            $proximas[] = $cita;
        } else {
            $pasadas[] = $cita;
        }
    }
}
?>

<div class="space-y-8 animate-fade-in pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Mis Citas</h1>
            <p class="text-gray-500">Gestiona tus sesiones programadas y consulta tu historial de visitas.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/vista-pacientes/citas/nueva" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-primary-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-primary-200/50 hover:bg-primary-700 hover:scale-105 transition-all active:scale-95 group">
            <i class="bi bi-calendar-plus transition-transform group-hover:rotate-12"></i>
            Nueva Cita
        </a>
    </div>

    <?php if (isset($_GET['alert'])): ?>
        <div class="rounded-2xl border p-4 <?= $_GET['alert'] === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200' ?> flex items-center gap-3 animate-fade-in">
            <i class="bi <?= $_GET['alert'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' ?> text-lg"></i>
            <span class="text-sm font-bold"><?= htmlspecialchars($_GET['message'] ?? '') ?></span>
        </div>
    <?php endif; ?>

    <!-- Upcoming Appointments Section -->
    <section class="space-y-4">
        <div class="flex items-center gap-2 px-1">
            <div class="w-2 h-2 rounded-full bg-primary-500 shadow-[0_0_8px_rgba(59,130,246,0.5)] animate-pulse"></div>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Próximas Sesiones</h2>
        </div>

        <?php if (!empty($proximas)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($proximas as $cita): ?>
                    <div class="group bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl hover:border-primary-100 transition-all duration-300 relative overflow-hidden">
                        <!-- Background glow effect -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary-50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-150 transition-all duration-700"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-6">
                                <div class="p-3 bg-primary-50 text-primary-600 rounded-2xl group-hover:bg-primary-600 group-hover:text-white transition-colors duration-300">
                                    <i class="bi bi-calendar-event text-xl"></i>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-[10px] font-black uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                    <?= $cita['estado'] ?>
                                </span>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-2xl font-black text-gray-900 group-hover:text-primary-700 transition-colors"><?= date('d M, Y', strtotime($cita['fecha_hora'])) ?></p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <i class="bi bi-clock text-primary-500"></i>
                                        <p class="text-lg font-bold text-gray-600"><?= date('H:i', strtotime($cita['fecha_hora'])) ?>h</p>
                                    </div>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-colors">
                                            <i class="bi bi-person-badge"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Profesional</p>
                                            <p class="text-sm font-bold text-gray-700"><?= $cita['fisioterapeuta_nombre'] . " " . $cita['fisioterapeuta_apellidos'] ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary-50 group-hover:text-primary-500 transition-colors">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Especialidad</p>
                                            <p class="text-sm font-bold text-gray-700"><?= $cita['descripcion'] ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8">
                                <form action="<?= PROJECT_ROOT ?>/vista-pacientes/citas/delete" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita?');">
                                    <input type="hidden" name="id" value="<?= $cita['cita_id'] ?>">
                                    <button type="submit" class="w-full py-3 px-4 rounded-xl border border-gray-100 text-gray-400 text-[11px] font-black uppercase tracking-widest hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all flex items-center justify-center gap-2">
                                        <i class="bi bi-x-circle"></i>
                                        Cancelar Cita
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-[2rem] p-16 border border-dashed border-gray-200 text-center shadow-inner">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <i class="bi bi-calendar-x text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Sin citas pendientes</h3>
                <p class="text-gray-500 mt-2 max-w-xs mx-auto text-sm leading-relaxed">No tienes ninguna sesión programada por ahora. ¡Es un buen momento para tu revisión!</p>
                <a href="<?= PROJECT_ROOT ?>/vista-pacientes/citas/nueva" class="inline-flex mt-8 px-8 py-3 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-primary-600 transition-all hover:scale-105 active:scale-95 shadow-lg">Reservar ahora</a>
            </div>
        <?php endif; ?>
    </section>

    <!-- Visit History Section -->
    <section class="space-y-4 pt-6">
        <div class="flex items-center gap-2 px-1">
            <div class="w-2 h-2 rounded-full bg-gray-300"></div>
            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Historial de Visitas</h2>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-50">
                            <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Fecha y Hora</th>
                            <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Servicio / Especialidad</th>
                            <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest">Profesional</th>
                            <th class="px-8 py-5 text-[11px] font-bold text-gray-400 uppercase tracking-widest text-right">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (!empty($pasadas)): ?>
                            <?php foreach ($pasadas as $cita): ?>
                                <tr class="hover:bg-primary-50/30 transition-colors group">
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-primary-700"><?= date('d/m/Y', strtotime($cita['fecha_hora'])) ?></p>
                                        <p class="text-xs text-gray-400 font-medium"><?= date('H:i', strtotime($cita['fecha_hora'])) ?>h</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-sm text-gray-600 font-bold group-hover:text-gray-900"><?= $cita['descripcion'] ?></span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-7 h-7 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center text-[10px] font-black group-hover:bg-primary-100 group-hover:text-primary-600 transition-colors">
                                                <?= substr($cita['fisioterapeuta_nombre'], 0, 1) ?>
                                            </div>
                                            <span class="text-sm text-gray-600 font-medium group-hover:text-gray-900"><?= $cita['fisioterapeuta_nombre'] . " " . $cita['fisioterapeuta_apellidos'] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <?php
                                        $estado = $cita['estado'];
                                        $style = 'text-gray-400 bg-gray-50 border-gray-100';
                                        if ($estado === 'Realizada') $style = 'text-emerald-600 bg-emerald-50 border-emerald-100';
                                        if ($estado === 'Cancelada') $style = 'text-rose-500 bg-rose-50 border-rose-100';
                                        ?>
                                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border <?= $style ?>">
                                            <?= $estado ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-8 py-16 text-center">
                                    <p class="text-gray-400 italic text-sm">Aún no tienes historial de citas registradas.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
