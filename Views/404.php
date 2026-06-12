<!DOCTYPE html>
<html lang="es" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error 404 - Página no encontrada</title>
  <link rel="icon" href="<?= PROJECT_ROOT ?>/public/custom/img/VELION Logo Rounded.png" type="image/png">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
                colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                    }
                }
            }
        }
    }
  </script>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="h-full font-sans antialiased text-gray-900 flex items-center justify-center bg-cover bg-center bg-no-repeat" style="background-image: url('<?= PROJECT_ROOT ?>/public/custom/img/fondo.jpg');">

  <div class="w-full max-w-md p-8 mx-4 bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/20 text-center animate-fade-in-up">
    <!-- Icon or Logo Area -->
    <div class="mx-auto w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
      <i class="bi bi-exclamation-triangle-fill text-3xl"></i>
    </div>

    <!-- Error Code -->
    <span class="text-xs font-extrabold uppercase tracking-widest text-red-500 bg-red-100/60 px-3 py-1 rounded-full">Error 404</span>

    <!-- Error Message -->
    <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">¡Oops! Página no encontrada</h1>
    <p class="mt-3 text-sm text-gray-600 leading-relaxed">Lo sentimos, la página que estás buscando no existe, ha sido eliminada o no tienes permisos para acceder a ella.</p>

    <!-- Buttons -->
    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
      <a href="#" onclick="history.back();" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-950 px-5 py-3 text-sm font-semibold text-white shadow-md hover:bg-gray-800 transition-all hover:scale-105 active:scale-95">
        <i class="bi bi-arrow-left"></i>
        Volver atrás
      </a>
      <!-- <a href="<?= PROJECT_ROOT ?>/inicio" class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm border border-gray-200 hover:bg-gray-50 transition-all hover:scale-105 active:scale-95">
        <i class="bi bi-house"></i>
        Ir al Inicio
      </a> -->
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

</body>

</html>