<?php
$pageTitle = "Libro Registro de Gastos";
include TEMPLATE_DIR . 'header.php';
?>

<div class="space-y-6 animate-fade-in-up">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Libro Registro de Facturas Recibidas y Gastos</h1>
            <p class="mt-1 text-sm text-gray-500">Obligación legal: Registra las compras y gastos deducibles para deducir el IVA y aminorar tu IRPF.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-2">
            <button onclick="toggleModal(true)" class="inline-flex items-center gap-2 rounded-xl bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-all hover:scale-105 active:scale-95">
                <i class="bi bi-plus-lg"></i>
                Registrar Gasto
            </button>
            <a href="<?= PROJECT_ROOT ?>/contabilidad" class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                <i class="bi bi-arrow-left"></i> Volver a Contabilidad
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <form action="<?= PROJECT_ROOT ?>/contabilidad/gastos" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="space-y-2">
                <label for="categoria" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Categoría</label>
                <select name="categoria" id="categoria" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white">
                    <option value="">Todas las categorías</option>
                    <option value="Alquileres" <?= ($filters['categoria'] ?? '') === 'Alquileres' ? 'selected' : '' ?>>Alquileres</option>
                    <option value="Suministros" <?= ($filters['categoria'] ?? '') === 'Suministros' ? 'selected' : '' ?>>Suministros</option>
                    <option value="Personal" <?= ($filters['categoria'] ?? '') === 'Personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="Servicios profesionales" <?= ($filters['categoria'] ?? '') === 'Servicios profesionales' ? 'selected' : '' ?>>Servicios profesionales</option>
                    <option value="Bienes de inversión" <?= ($filters['categoria'] ?? '') === 'Bienes de inversión' ? 'selected' : '' ?>>Bienes de inversión</option>
                    <option value="Otros" <?= ($filters['categoria'] ?? '') === 'Otros' ? 'selected' : '' ?>>Otros</option>
                </select>
            </div>

            <div class="space-y-2">
                <label for="fecha_desde" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Fecha Desde</label>
                <input type="date" name="fecha_desde" id="fecha_desde" value="<?= htmlspecialchars($filters['fecha_desde'] ?? '') ?>"
                       class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
            </div>

            <div class="space-y-2">
                <label for="fecha_hasta" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Fecha Hasta</label>
                <input type="date" name="fecha_hasta" id="fecha_hasta" value="<?= htmlspecialchars($filters['fecha_hasta'] ?? '') ?>"
                       class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-primary-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary-200 hover:scale-[1.02] active:scale-95 transition-all">
                    Filtrar
                </button>
                <?php if (!empty($filters['categoria']) || !empty($filters['fecha_desde']) || !empty($filters['fecha_hasta'])) : ?>
                    <a href="<?= PROJECT_ROOT ?>/contabilidad/gastos" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 transition-all" title="Limpiar filtros">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table of Expenses -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha / Factura</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Proveedor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Categoría / Concepto</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Base (€)</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">IVA (%)</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">IRPF (%)</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total (€)</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (empty($gastos)) : ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500 italic">
                                <i class="bi bi-inbox text-4xl block mb-2 opacity-20"></i>
                                No hay facturas recibidas registradas en este período.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($gastos as $g) : ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-bold text-gray-900"><?= date('d/m/Y', strtotime($g['fecha_emision'])) ?></span>
                                    <div class="text-xs text-gray-400">Fact: <?= htmlspecialchars($g['numero_factura']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="font-semibold text-gray-800"><?= htmlspecialchars($g['nombre_proveedor']) ?></span>
                                    <div class="text-xs text-gray-400">NIF: <?= htmlspecialchars($g['nif_proveedor']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-700">
                                        <?= htmlspecialchars($g['categoria']) ?>
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($g['concepto']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-700">
                                    <?= number_format($g['base_imponible'], 2, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                    <?= number_format($g['tipo_iva'], 0) ?>%
                                    <div class="text-xs text-gray-400">(+<?= number_format($g['cuota_iva'], 2, ',', '.') ?>)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                    <?= number_format($g['retencion_irpf'], 0) ?>%
                                    <?php if ($g['retencion_irpf'] > 0) : ?>
                                        <div class="text-xs text-red-500">(-<?= number_format($g['cuota_irpf'], 2, ',', '.') ?>)</div>
                                    <?php else : ?>
                                        <div class="text-xs text-gray-300">-</div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-black text-gray-900">
                                    <?= number_format($g['total'], 2, ',', '.') ?> €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <form action="<?= PROJECT_ROOT ?>/contabilidad/gastos/delete" method="POST" class="inline" onsubmit="return confirm('¿Desea borrar este gasto? Esta acción modificará los cálculos fiscales.');">
                                        <input type="hidden" name="gasto_id" value="<?= $g['gasto_id'] ?>">
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div id="expenseModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-50 transition-opacity">
    <div class="bg-white rounded-3xl p-8 max-w-xl w-full mx-4 shadow-xl border border-gray-100 max-h-[90vh] overflow-y-auto relative animate-fade-in-up">
        <button onclick="toggleModal(false)" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-50 transition-all">
            <i class="bi bi-x-lg text-lg"></i>
        </button>

        <h3 class="text-xl font-bold text-gray-900 mb-6">Registrar Factura Recibida / Gasto</h3>
        
        <form action="<?= PROJECT_ROOT ?>/contabilidad/gastos" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="nif_proveedor" class="text-xs font-bold text-gray-400 uppercase tracking-widest">NIF/CIF Proveedor</label>
                    <input type="text" name="nif_proveedor" id="nif_proveedor" placeholder="B12345678" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                </div>

                <div class="space-y-1">
                    <label for="nombre_proveedor" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nombre Proveedor</label>
                    <input type="text" name="nombre_proveedor" id="nombre_proveedor" placeholder="Proveedor de material S.L." required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="numero_factura" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nº Factura del Proveedor</label>
                    <input type="text" name="numero_factura" id="numero_factura" placeholder="FV-2026/001" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                </div>

                <div class="space-y-1">
                    <label for="fecha_emision" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Fecha Factura</label>
                    <input type="date" name="fecha_emision" id="fecha_emision" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                </div>
            </div>

            <div class="space-y-1">
                <label for="concepto" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Concepto / Descripción</label>
                <input type="text" name="concepto" id="concepto" placeholder="Compra de camilla médica y cremas" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="categoria" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Categoría del Gasto</label>
                    <select name="categoria" id="categoria" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white">
                        <option value="Alquileres">Alquileres</option>
                        <option value="Suministros">Suministros (Luz, Internet, etc.)</option>
                        <option value="Personal">Personal</option>
                        <option value="Servicios profesionales">Servicios profesionales (Gestoría, etc.)</option>
                        <option value="Bienes de inversión">Bienes de inversión (Maquinaria/Camillas)</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label for="base_imponible" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Base Imponible (€)</label>
                    <input type="number" step="0.01" min="0" name="base_imponible" id="base_imponible" placeholder="100.00" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="tipo_iva" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tipo de IVA aplicable</label>
                    <select name="tipo_iva" id="tipo_iva" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white">
                        <option value="21">21% (General)</option>
                        <option value="10">10% (Reducido)</option>
                        <option value="4">4% (Superreducido)</option>
                        <option value="0">Exento / 0%</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label for="retencion_irpf" class="text-xs font-bold text-gray-400 uppercase tracking-widest">Retención IRPF (%)</label>
                    <select name="retencion_irpf" id="retencion_irpf" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all outline-none text-sm bg-white">
                        <option value="0">Sin retención (0%)</option>
                        <option value="15">15% (Profesional autónomo)</option>
                        <option value="7">7% (Nuevos autónomos)</option>
                        <option value="19">19% (Retención sobre alquileres)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full mt-6 bg-primary-600 text-white px-4 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary-200 hover:scale-[1.01] active:scale-95 transition-all">
                Guardar Factura y Calcular Impuestos
            </button>
        </form>
    </div>
</div>

<script>
function toggleModal(show) {
    const modal = document.getElementById('expenseModal');
    if (show) {
        modal.classList.remove('hidden');
    } else {
        modal.classList.add('hidden');
    }
}
</script>

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
