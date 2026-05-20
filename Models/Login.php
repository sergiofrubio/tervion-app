<?php
namespace App\Models;
use App\Core\DataBase;
use PDO;

class Login
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    public function getByEmail($email)
    {
        $query = "SELECT u.*, 
                        CASE 
                            WHEN p.usuario_id IS NOT NULL THEN 'Paciente'
                            WHEN f.usuario_id IS NOT NULL THEN 'Fisioterapeuta'
                            ELSE 'Administrador' 
                        END as rol
                  FROM usuarios u 
                  LEFT JOIN pacientes p ON u.usuario_id = p.usuario_id 
                  LEFT JOIN fisioterapeutas f ON u.usuario_id = f.usuario_id 
                  WHERE u.email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByToken($token)
    {
        // Add if needed for reset
        $query = "SELECT * FROM password_resets WHERE token = :token AND created_at >= (NOW() - INTERVAL 1 HOUR)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}