<?php

namespace App\Controllers;

use App\Core\Controller;

class DocumentController extends Controller
{
    /**
     * Muestra el listado de plantillas de documentos.
     */
    public function list()
    {
        $documentModel = $this->model('Document');
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $documents = $documentModel->getAll($cuenta_id, $search);

        $data = [
            'documents' => $documents,
            'search' => $search
        ];

        $this->view('document/list', $data);
    }

    /**
     * Muestra y procesa el formulario para crear un nuevo documento.
     */
    public function create()
    {
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documentModel = $this->model('Document');

            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $contenido = $_POST['contenido'] ?? ''; // HTML procedente de TinyMCE

            if (empty($titulo) || empty($contenido)) {
                header('Location: ' . PROJECT_ROOT . '/documentos/crear?alert=danger&message=El título y el contenido son obligatorios');
                $this->exitApp();
            }

            $data = [
                'cuenta_id' => $cuenta_id,
                'titulo' => htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'),
                'descripcion' => htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'),
                'contenido' => $contenido,
                'creado_por' => $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? null
            ];

            $newId = $documentModel->create($data);

            if ($newId) {
                header('Location: ' . PROJECT_ROOT . '/documentos?alert=success&message=Documento creado correctamente');
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/documentos/crear?alert=danger&message=Error al guardar el documento');
                $this->exitApp();
            }
        } else {
            $data = [
                'document' => null,
                'isEdit' => false
            ];
            $this->view('document/form', $data);
        }
    }

    /**
     * Muestra y procesa el formulario para editar un documento existente.
     */
    public function edit()
    {
        $documentModel = $this->model('Document');
        $cuenta_id = $_SESSION['cuenta_id'] ?? 1;
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : $id;
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $contenido = $_POST['contenido'] ?? '';

            if ($id <= 0 || empty($titulo) || empty($contenido)) {
                header('Location: ' . PROJECT_ROOT . '/documentos/editar?id=' . $id . '&alert=danger&message=Datos incompletos para actualizar');
                $this->exitApp();
            }

            $data = [
                'titulo' => htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8'),
                'descripcion' => htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8'),
                'contenido' => $contenido,
                'modificado_por' => $_SESSION['usuario_id'] ?? $_SESSION['user_id'] ?? null
            ];

            if ($documentModel->update($id, $data, $cuenta_id)) {
                header('Location: ' . PROJECT_ROOT . '/documentos?alert=success&message=Documento actualizado correctamente');
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/documentos/editar?id=' . $id . '&alert=danger&message=Error al actualizar el documento');
                $this->exitApp();
            }
        } else {
            $document = $documentModel->getById($id, $cuenta_id);

            if (!$document) {
                header('Location: ' . PROJECT_ROOT . '/documentos?alert=danger&message=El documento solicitado no existe');
                $this->exitApp();
            }

            $data = [
                'document' => $document,
                'isEdit' => true
            ];
            $this->view('document/form', $data);
        }
    }

    /**
     * Procesa la eliminación de un documento.
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $documentModel = $this->model('Document');
            $cuenta_id = $_SESSION['cuenta_id'] ?? 1;
            $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

            if ($id > 0 && $documentModel->delete($id, $cuenta_id)) {
                header('Location: ' . PROJECT_ROOT . '/documentos?alert=success&message=Documento eliminado correctamente');
                $this->exitApp();
            } else {
                header('Location: ' . PROJECT_ROOT . '/documentos?alert=danger&message=Error al eliminar el documento');
                $this->exitApp();
            }
        } else {
            header('Location: ' . PROJECT_ROOT . '/documentos');
            $this->exitApp();
        }
    }
}
