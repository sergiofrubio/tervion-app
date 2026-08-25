<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error 404 - Página no encontrada - Tervion</title>
  <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion-sin-fondo.png" type="image/jpeg">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Compiled CSS (Tailwind + SCSS) -->
  <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/custom/css/app.css">
</head>

<?php
$systemAlertMessage = trim((string)($systemAlertMessage ?? ($GLOBALS['systemAlertMessage'] ?? '')));
$hasSystemAlert = $systemAlertMessage !== '';
?>

<body class="min-h-screen flex flex-col justify-between font-sans antialiased text-gray-900 bg-cover bg-center bg-no-repeat <?= $hasSystemAlert ? 'pt-7' : '' ?>" style="background-image: url('<?= PROJECT_ROOT ?>/public/custom/img/fondo.jpg');">

  <?php include TEMPLATE_DIR . 'system-alert.php'; ?>

  <!-- Top Navigation / Header -->
  <header class="border-b border-gray-200 bg-white/80 backdrop-blur-md sticky top-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <a href="<?= PROJECT_ROOT ?>/" class="flex items-center">
        <img src="<?= PROJECT_ROOT ?>/public/custom/img/logo-tervion-sin-fondo.png" alt="Tervion Logo" class="h-9 object-contain">
      </a>
      <div class="flex items-center gap-3">
        <a href="<?= PROJECT_ROOT ?>/" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 hover:text-gray-900 border border-gray-200/80 shadow-2xs transition-all">
          <i class="bi bi-house-door text-sm text-gray-500"></i>
          <span>Ir al Inicio</span>
        </a>
      </div>
    </div>
  </header>

  <!-- Main 404 Card Section -->
  <main class="flex-grow flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
    <div class="w-full max-w-md p-8 bg-white/85 backdrop-blur-md rounded-3xl shadow-2xl border border-white/40 text-center animate-fade-in-up">

      <!-- Dog Image (Friendly Touch) -->
      <div class="mx-auto mb-6 h-52 max-w-xs overflow-hidden rounded-2xl">
        <img src="<?= PROJECT_ROOT ?>/public/custom/img/dog.jpg" alt="Perro buscando la página" class="w-full object-contain hover:scale-105 transition-transform duration-300">
      </div>

      <!-- Error Message -->
      <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900">¡Guau! ¿Te has perdido?</h1>
      <p class="mt-3 text-sm text-gray-600 leading-relaxed">Parece que el perrito se ha comido esta página o la dirección a la que intentas acceder no existe.</p>

      <!-- Buttons -->
      <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="#" onclick="history.back();" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-gray-800 transition-all hover:scale-105 active:scale-95">
          <i class="bi bi-arrow-left"></i>
          Volver atrás
        </a>
        <a href="<?= PROJECT_ROOT ?>/" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm border border-gray-200 hover:bg-gray-50 transition-all hover:scale-105 active:scale-95">
          <i class="bi bi-house"></i>
          Ir al Inicio
        </a>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-900 text-gray-400 py-8 border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-6 sm:gap-4">
      <p class="w-full sm:w-1/3 text-center sm:text-left text-sm">© 2026 Tervion Ibérica SLU</p>

      <a href="<?= PROJECT_ROOT ?>/" class="w-full sm:w-1/3 flex items-center justify-center">
        <img src="<?= PROJECT_ROOT ?>/public/custom/img/icono-tervion-sin-fondo.png" alt="Tervion Logo" class="h-8 object-contain rounded-md p-1 shadow-sm">
      </a>

      <div class="w-full sm:w-1/3 flex items-center justify-center sm:justify-end gap-6 text-gray-400">
        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Twitter">
          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
          </svg>
        </a>
        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="LinkedIn">
          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75-1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" />
          </svg>
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" aria-label="Instagram">
          <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
          </svg>
        </a>
      </div>
    </div>
  </footer>

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