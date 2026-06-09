<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class PaymentMethod
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getByUsuario($usuario_id)
    {
        $query = "SELECT * FROM metodos_pago WHERE usuario_id = :usuario_id ORDER BY es_predeterminado DESC, fecha_creacion DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($metodo_id)
    {
        $query = "SELECT * FROM metodos_pago WHERE metodo_id = :metodo_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':metodo_id', $metodo_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($data)
    {
        // Si se marca como predeterminado, desmarcar los anteriores
        if (isset($data['es_predeterminado']) && $data['es_predeterminado'] == 1) {
            $this->unsetPredeterminado($data['usuario_id']);
        }

        $query = "INSERT INTO metodos_pago (usuario_id, tipo, proveedor, last4, fecha_expiracion, token_externo, es_predeterminado, creado_por) 
                  VALUES (:usuario_id, :tipo, :proveedor, :last4, :fecha_expiracion, :token_externo, :es_predeterminado, :creado_por)";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':usuario_id', $data['usuario_id']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':proveedor', $data['proveedor']);
        $stmt->bindParam(':last4', $data['last4']);
        $stmt->bindParam(':fecha_expiracion', $data['fecha_expiracion']);
        $stmt->bindParam(':token_externo', $data['token_externo']);
        $stmt->bindParam(':es_predeterminado', $data['es_predeterminado'], PDO::PARAM_INT);
        $stmt->bindParam(':creado_por', $data['usuario_id']);
        
        return $stmt->execute();
    }

    public function delete($metodo_id, $usuario_id)
    {
        $query = "DELETE FROM metodos_pago WHERE metodo_id = :metodo_id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':metodo_id', $metodo_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    public function setPredeterminado($metodo_id, $usuario_id)
    {
        $this->unsetPredeterminado($usuario_id);
        
        $query = "UPDATE metodos_pago SET es_predeterminado = 1 WHERE metodo_id = :metodo_id AND usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':metodo_id', $metodo_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    private function unsetPredeterminado($usuario_id)
    {
        $query = "UPDATE metodos_pago SET es_predeterminado = 0 WHERE usuario_id = :usuario_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }
}
