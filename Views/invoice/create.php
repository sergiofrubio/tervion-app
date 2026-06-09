<?php
$pageTitle = "Nueva Factura - Verifactu";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-4xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="<?= PROJECT_ROOT ?>/facturas" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
            <i class="bi bi-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Emitir Nueva Factura</h1>
            <p class="mt-1 text-sm text-gray-500">Los registros se encadenarán automáticamente siguiendo la normativa Verifactu.</p>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= PROJECT_ROOT ?>/facturas/create" method="POST" class="p-8" x-data="{ precio: 0, impuesto: 21, total: 0, 
            calcularTotal() { 
                this.total = (parseFloat(this.precio) + (parseFloat(this.precio) * (parseFloat(this.impuesto) / 100))).toFixed(2);
            } 
        }">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Serie y Tipo -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Serie de Facturación</label>
                    <select name="serie" class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        <option value="A">Serie Principal (A)</option>
                        <option value="B">Serie Secundaria (B)</option>
                        <option value="R">Serie Rectificativa (R)</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Tipo de Factura</label>
                    <select name="tipo_factura" class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        <option value="F1">F1 - Factura Ordinaria</option>
                        <option value="F2">F2 - Factura Simplificada</option>
                        <option value="R1">R1 - Rectificativa (Error)</option>
                    </select>
                </div>

                <!-- Paciente -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-person text-primary-500"></i> Paciente
                    </label>
                    <select name="paciente_id" required class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        <option value="">Selecciona un paciente</option>
                        <?php foreach ($pacientes as $paciente) : ?>
                            <option value="<?= $paciente['usuario_id'] ?>"><?= $paciente['nombre'] . ' ' . $paciente['apellidos'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Fecha -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-calendar-event text-primary-500"></i> Fecha de Emisión
                    </label>
                    <input type="date" name="fecha_emision" value="<?= date('Y-m-d') ?>" required
                        class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                </div>

                <!-- Descripción -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-card-text text-primary-500"></i> Descripción / Concepto
                    </label>
                    <input type="text" name="descripcion" placeholder="Ej: Sesión de fisioterapia deportiva" required
                        class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                </div>

                <!-- Precio Base -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-currency-euro text-primary-500"></i> Precio Base (€)
                    </label>
                    <input type="number" step="0.01" name="precio" x-model="precio" @input="calcularTotal()" required
                        class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all" placeholder="0.00">
                </div>

                <!-- Impuesto -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-percent text-primary-500"></i> Impuesto (%)
                    </label>
                    <input type="number" step="0.01" name="impuesto" x-model="impuesto" @input="calcularTotal()" required
                        class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all" placeholder="21">
                </div>

                <!-- Total -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-calculator text-primary-500"></i> Total Estimado
                    </label>
                    <input type="number" step="0.01" x-model="total" readonly
                        class="w-full rounded-2xl border-gray-50 bg-gray-50 text-sm font-bold text-primary-600 focus:outline-none transition-all cursor-not-allowed">
                </div>

                <!-- Estado -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="bi bi-info-circle text-primary-500"></i> Estado Inicial de Pago
                    </label>
                    <select name="estado" required class="w-full rounded-2xl border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 transition-all">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Pagada">Pagada</option>
                    </select>
                </div>
            </div>

            <div class="mt-10 flex justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/facturas" class="px-6 py-2.5 text-sm font-semibold text-gray-600 hover:text-gray-900 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    <i class="bi bi-shield-check"></i>
                    Generar y Firmar Factura
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.4s ease-out forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>
