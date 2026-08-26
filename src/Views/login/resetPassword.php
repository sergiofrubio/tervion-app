<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Establecer Nueva Contraseña - Tervion</title>
    <link rel="icon" href="<?= PROJECT_ROOT ?>/public/img/icono-tervion-sin-fondo.png" type="image/jpeg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Compiled CSS (Tailwind + SCSS) -->
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/app.css">

</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="bg-gray-50 font-sans antialiased min-h-screen flex items-center justify-center relative <?= $hasSystemAlert ? 'pt-7' : '' ?>">

    <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

    <!-- Container -->
    <div class="relative z-10 w-full max-w-md px-6">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 p-8 sm:p-10 transition-all">

            <div class="text-center mb-8">
                <img src="<?= PROJECT_ROOT ?>/public/img/logo-tervion-sin-fondo.png" alt="Tervion Logo"
                    class="w-24 mx-auto rounded-2xl shadow-sm mb-4">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Nueva Contraseña</h2>
                <p class="text-sm text-gray-500 mt-1">Establece tu nueva contraseña de acceso</p>
            </div>

            <?php
            // Verificar si hay una alerta de usuario
            if (isset($_GET['alert']) && isset($_GET['message'])) {
                $alert_type = $_GET['alert'] === 'danger' ? 'bg-red-50 text-red-800 border-red-200' : ($_GET['alert'] === 'success' ? 'bg-green-50 text-green-800 border-green-200' :
                    'bg-blue-50 text-blue-800 border-blue-200');

                echo '<div class="rounded-xl border p-4 mb-6 ' . $alert_type . '" role="alert">
                    <div class="flex justify-between items-start">
                        <div class="text-sm font-medium">' . htmlspecialchars($_GET['message']) . '</div>
                    </div>
                </div>';
            }
            ?>

            <form action="<?= PROJECT_ROOT . '/login/update-password' ?>" method="post" class="space-y-5">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                <div>
                    <label for="pass" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="pass" id="pass" minlength="8" required
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-colors shadow-sm"
                            placeholder="Mínimo 8 caracteres">
                    </div>
                </div>

                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirmar
                        Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="bi bi-shield-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="confirmPassword" id="confirmPassword" minlength="8" required
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white/50 focus:bg-white text-sm transition-colors shadow-sm"
                            placeholder="Repite la contraseña">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                        <span>Restablecer Contraseña</span>
                        <i class="bi bi-check2-circle ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>