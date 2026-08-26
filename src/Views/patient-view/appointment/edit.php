<?php
$pageTitle = "Modificar Cita - Tervion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-3xl mx-auto animate-fade-in pb-12">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Modificar Cita</h1>
            <p class="mt-1 text-gray-500">Cambia la fecha o el profesional de tu sesión programada.</p>
        </div>
        <a href="<?= PROJECT_ROOT ?>/paciente/citas" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-primary-600 transition-colors group">
            <i class="bi bi-arrow-left transition-transform group-hover:-translate-x-1"></i>
            Volver
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/paciente/citas/editar" method="POST" class="p-8 md:p-12">
            <input type="hidden" name="cita_id" value="<?= $appointment['cita_id'] ?>">
            <input type="hidden" name="estado" value="<?= $appointment['estado'] ?>"> <!-- Mantenemos el estado actual -->

            <div class="space-y-10">

                <!-- Sección: Profesional y Servicio -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-600 flex items-center justify-center">
                            <i class="bi bi-person-badge text-xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Profesional y Servicio</h2>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Fisioterapeuta</label>
                        <input type="hidden" name="fisioterapeuta_id" id="fisioterapeuta_id" value="<?= $appointment['fisioterapeuta_id'] ?>" required>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="fisios-cards-container">
                            <?php foreach ($fisioterapeutas as $f): ?>
                                <?php
                                $firstLetter = mb_substr($f['nombre'], 0, 1, 'UTF-8');
                                $isSelected = ($f['usuario_id'] == $appointment['fisioterapeuta_id']);
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
                </div>

                <!-- Sección: Fecha y Hora -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="bi bi-calendar-check text-xl"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Selección de Agenda</h2>
                    </div>

                    <input type="hidden" name="fecha_hora" id="fecha_hora" value="<?= $appointment['fecha_hora'] ?>" required>

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
                        Descartar cambios
                    </a>
                    <button type="submit" class="inline-flex justify-center items-center gap-2 rounded-2xl bg-primary-600 px-10 py-4 text-sm font-bold text-white shadow-xl shadow-primary-200/50 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all hover:scale-105 active:scale-95">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="module" src="<?= PROJECT_ROOT ?>/public/js/modules/appointment/appointment-form.js"></script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

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