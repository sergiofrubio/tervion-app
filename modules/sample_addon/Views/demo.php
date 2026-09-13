<?php require_once dirname(__DIR__, 3) . '/src/Templates/header.php'; ?>

<main class=" p-6 max-w-7xl mx-auto\>
 <div class=\bg-white rounded-2xl border border-slate-200/80 shadow-sm p-8\>
 <div class=\flex items-center gap-4 mb-6\>
 <div class=\w-12 h-12 rounded-xl bg-primary-50 border border-primary-100 flex items-center justify-center text-primary-600 text-2xl\>
 <i class=\bi bi-puzzle\></i>
 </div>
 <div>
 <h1 class=\text-xl font-bold text-slate-900\><?= htmlspecialchars($pageTitle ?? 'Módulo Demo') ?></h1>
 <p class=\text-xs text-slate-500\>Demostración de módulo desacoplado e independiente de Tervion Core</p>
 </div>
 </div>

 <div class=\prose prose-slate max-w-none text-sm text-slate-600 space-y-4\>
 <p>
 Este módulo reside en <code class=\bg-slate-100 text-slate-800 px-2 py-1 rounded text-xs\>modules/sample_addon/</code>
 y se ha cargado de manera 100% dinámica mediante el <code class=\bg-slate-100 text-slate-800 px-2 py-1 rounded text-xs\>ModuleManager</code>.
 </p>
 <div class=\grid grid-cols-1 md:grid-cols-3 gap-4 pt-4\>
 <div class=\p-4 rounded-xl bg-slate-50 border border-slate-100\>
 <h3 class=\font-semibold text-slate-800 text-xs uppercase tracking-wider mb-2\>Ruta Desacoplada</h3>
 <p class=\text-xs text-slate-500\>El controlador y la ruta no tocan el archivo <code class=\text-[11px]\>src/Router/routes.php</code> principal.</p>
 </div>
 <div class=\p-4 rounded-xl bg-slate-50 border border-slate-100\>
 <h3 class=\font-semibold text-slate-800 text-xs uppercase tracking-wider mb-2\>Licencia Independiente</h3>
 <p class=\text-xs text-slate-500\>Los desarrolladores pueden licenciar este código como código cerrado o comercial según LGPLv3.</p>
 </div>
 <div class=\p-4 rounded-xl bg-slate-50 border border-slate-100\>
 <h3 class=\font-semibold text-slate-800 text-xs uppercase tracking-wider mb-2\>Menú Inyectado</h3>
 <p class=\text-xs text-slate-500\>El acceso directo en la barra lateral izquierda se registró vía <code class=\text-[11px]\>module.json</code>.</p>
 </div>
 </div>
 </div>
 </div>
</main>

<?php require_once dirname(__DIR__, 3) . '/src/Templates/footer.php'; ?>
