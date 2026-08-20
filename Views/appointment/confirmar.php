<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmación de Asistencia - Velion</title>
  <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion.jpg" type="image/jpeg">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Compiled CSS (Tailwind + SCSS) -->
  <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/custom/css/app.css">
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="h-full font-sans antialiased text-gray-900 flex items-center justify-center bg-cover bg-center bg-no-repeat <?= $hasSystemAlert ? 'pt-7' : '' ?>" style="background-image: url('<?= PROJECT_ROOT ?>/public/custom/img/fondo.jpg');">

  <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

  <div class="w-full max-w-md p-8 mx-4 bg-white/85 backdrop-blur-md rounded-3xl shadow-xl border border-white/20 text-center animate-fade-in-up">

    <?php if ($success): ?>
      <!-- Success Icon Area -->
      <div class="mx-auto w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
        <i class="bi bi-check-circle-fill text-3xl"></i>
      </div>

      <!-- Header -->
      <span class="text-xs font-extrabold uppercase tracking-widest text-green-600 bg-green-100/60 px-3 py-1 rounded-full">
        <?= isset($already) && $already ? 'Asistencia ya Confirmada' : '¡Asistencia Confirmada!' ?>
      </span>

      <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">¡Gracias por confirmar!</h1>
      <p class="mt-3 text-sm text-gray-600 leading-relaxed">Hemos registrado tu asistencia correctamente. Te esperamos en la clínica.</p>

      <?php if (isset($appointment)): ?>
        <!-- Appointment Summary Card -->
        <div class="mt-6 p-5 bg-slate-50/50 rounded-2xl border border-slate-100 text-left">
          <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Detalles de la cita</h3>

          <div class="space-y-2 text-sm text-slate-700">
            <div class="flex justify-between">
              <span class="text-slate-400">Paciente:</span>
              <span class="font-medium"><?= htmlspecialchars($appointment['paciente_nombre'] . ' ' . $appointment['paciente_apellidos']) ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Fecha y Hora:</span>
              <span class="font-semibold text-slate-900"><?= date('d/m/Y H:i', strtotime($appointment['fecha_hora'])) ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Especialista:</span>
              <span class="font-medium"><?= htmlspecialchars($appointment['fisioterapeuta_nombre'] . ' ' . $appointment['fisioterapeuta_apellidos']) ?></span>
            </div>
          </div>
        </div>
      <?php endif; ?>

    <?php else: ?>
      <!-- Error Icon Area -->
      <div class="mx-auto w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
        <i class="bi bi-x-circle-fill text-3xl"></i>
      </div>

      <!-- Header -->
      <span class="text-xs font-extrabold uppercase tracking-widest text-red-600 bg-red-100/60 px-3 py-1 rounded-full">Error</span>

      <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">No se pudo confirmar</h1>
      <p class="mt-3 text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Close/Home Action -->
    <div class="mt-8">
      <a href="<?= PROJECT_ROOT ?>/login" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-gray-800 transition-all hover:scale-[1.02] active:scale-95">
        Ir a Velion
      </a>
    </div>

  </div>

  <style>
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fade-in-up {
      animation: fadeInUp 0.4s ease-out forwards;
    }
  </style>

</body>

</html>