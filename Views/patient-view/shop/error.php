<?php
$pageTitle = "Error en el Pago - Tervion";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-2xl mx-auto text-center space-y-10 animate-fade-in pb-16">
    <!-- Animated failure icon -->
    <div class="relative w-32 h-32 mx-auto flex items-center justify-center bg-red-50 rounded-full border border-red-100 shadow-xl shadow-red-100/50">
        <i class="bi bi-x-circle-fill text-6xl text-red-500 animate-scale-up"></i>
    </div>

    <!-- Error Header -->
    <div class="space-y-4">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">El Pago ha Fallado o ha sido Cancelado ⚠️</h1>
        <p class="text-gray-500 text-lg max-w-lg mx-auto">
            No se ha podido completar la transacción con la pasarela de pagos Redsys. No se ha realizado ningún cargo en tu tarjeta.
        </p>
    </div>

    <!-- Options Card -->
    <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-2xl shadow-gray-100/50 text-left relative overflow-hidden">
        <div class="relative z-10 space-y-6">
            <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4">¿Qué ha podido pasar?</h3>

            <ul class="space-y-3 text-sm text-gray-600 list-disc list-inside">
                <li>Los datos de la tarjeta (número, fecha de caducidad o CVV) podrían ser incorrectos.</li>
                <li>La tarjeta no tiene habilitados los pagos de seguridad 3D Secure / Comercio Electrónico Seguro (CES).</li>
                <li>Límite de crédito o saldo insuficiente en la cuenta.</li>
                <li>Se ha cancelado manualmente la operación o se ha agotado el tiempo de espera.</li>
            </ul>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <a href="<?= PROJECT_ROOT ?>/paciente/tienda"
                    class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-bold px-6 py-4 rounded-xl text-sm transition-all hover:scale-[1.01]">
                    <i class="bi bi-arrow-left"></i>
                    Volver a Intentarlo
                </a>
                <a href="tel:+34900000000"
                    class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-700 font-bold px-6 py-4 rounded-xl text-sm transition-all hover:scale-[1.01]">
                    <i class="bi bi-telephone-fill"></i>
                    Soporte / Ayuda
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes scaleUp {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-scale-up {
        animation: scaleUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }
</style>

<?php include TEMPLATE_DIR . 'footer.php'; ?>