<?php
$isEdit = isset($isEdit) && $isEdit && !empty($document);
$pageTitle = $isEdit ? "Editar Plantilla de Documento" : "Nueva Plantilla de Documento";
include TEMPLATE_DIR . 'header.php';
?>

<div class="max-w-5xl mx-auto space-y-6 animate-fade-in-up">
    <!-- Header Title & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <a href="<?= PROJECT_ROOT ?>/documentos" class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-900 transition-colors mb-2 font-medium">
                <i class="bi bi-arrow-left"></i>
                <span>Volver a documentos</span>
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight"><?= $isEdit ? 'Editar Plantilla de Documento' : 'Nueva Plantilla de Documento' ?></h1>
            <p class="text-gray-500 text-sm mt-0.5"><?= $isEdit ? 'Modifica los campos y contenido HTML de la plantilla.' : 'Crea una nueva plantilla de documento personalizada para tu clínica.' ?></p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden p-6 sm:p-8">
        <form action="<?= PROJECT_ROOT ?>/documentos/<?= $isEdit ? 'editar' : 'crear' ?>" method="POST" class="space-y-6" id="form-documento">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($document['documento_id']) ?>">
            <?php endif; ?>

            <!-- Title & Description Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="titulo" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Título de la Plantilla <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="titulo" id="titulo" required value="<?= htmlspecialchars($document['titulo'] ?? '') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-gray-400" placeholder="Ej: Consentimiento Informado Fisioterapia General">
                </div>

                <div>
                    <label for="descripcion" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Descripción Corta / Categoría
                    </label>
                    <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($document['descripcion'] ?? '') ?>" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all placeholder:text-gray-400" placeholder="Ej: Documento de autorización para tratamiento manual">
                </div>
            </div>

            <!-- WYSIWYG TinyMCE Editor Field -->
            <div>
                <label for="contenido_editor" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Contenido del Documento <span class="text-rose-500">*</span>
                </label>
                <div class="rounded-xl overflow-hidden border border-gray-200">
                    <textarea name="contenido" id="contenido_editor" rows="15" class="w-full p-4 text-sm focus:outline-none"><?= htmlspecialchars($document['contenido'] ?? '') ?></textarea>
                </div>
                <p class="text-xs text-gray-400 mt-2">Puedes formatear el texto, incluir listas, tablas e imágenes mediante la barra de herramientas de TinyMCE.</p>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="<?= PROJECT_ROOT ?>/documentos" class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold shadow-md transition-all cursor-pointer flex items-center gap-2">
                    <i class="bi bi-check-lg text-sm"></i>
                    <span><?= $isEdit ? 'Guardar Cambios' : 'Crear Plantilla' ?></span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- TinyMCE Script & Initializer -->
<script src="<?= PROJECT_ROOT ?>/public/vendor/tinymce/tinymce.min.js"></script>
<script>
    (function() {
        function initTinyMCE() {
            const textarea = document.getElementById('contenido_editor');
            if (!textarea) return;

            if (typeof tinymce === 'undefined') {
                setTimeout(initTinyMCE, 50);
                return;
            }

            if (tinymce.get('contenido_editor')) {
                tinymce.remove('#contenido_editor');
            }

            tinymce.init({
                selector: '#contenido_editor',
                license_key: 'gpl',
                height: 500,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat code fullscreen',
                content_style: 'body { font-family: Inter, Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #334155; }',
                branding: false,
                promotion: false,
                setup: function(editor) {
                    editor.on('change keyup', function() {
                        editor.save();
                    });
                }
            });

            const form = document.getElementById('form-documento');
            if (form && !form.dataset.tinymceBound) {
                form.dataset.tinymceBound = 'true';
                form.addEventListener('submit', function() {
                    if (typeof tinymce !== 'undefined' && tinymce.get('contenido_editor')) {
                        tinymce.get('contenido_editor').save();
                    }
                });
            }
        }

        // Ejecutar inmediatamente si el script se evalúa en navegación dinámica SPA
        initTinyMCE();

        // Escuchar eventos de recarga tradicional y navegación SPA
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTinyMCE);
        }
        window.addEventListener('tervion:navigated', initTinyMCE);
    })();
</script>

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

<?php include TEMPLATE_DIR . 'footer.php'; ?>