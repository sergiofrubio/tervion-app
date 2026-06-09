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

    public function saveResetToken($email, $token)
    {
        // Primero eliminamos tokens antiguos para este correo
        $queryDelete = "DELETE FROM password_resets WHERE email = :email";
        $stmtDelete = $this->db->prepare($queryDelete);
        $stmtDelete->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtDelete->execute();

        // Insertamos el nuevo token
        $queryInsert = "INSERT INTO password_resets (email, token, created_at) VALUES (:email, :token, NOW())";
        $stmtInsert = $this->db->prepare($queryInsert);
        $stmtInsert->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtInsert->bindParam(':token', $token, PDO::PARAM_STR);
        return $stmtInsert->execute();
    }

    public function deleteResetToken($token)
    {
        $query = "DELETE FROM password_resets WHERE token = :token";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function updateUserPassword($email, $hashedPassword)
    {
        $query = "UPDATE usuarios SET pass = :pass WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':pass', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        return $stmt->execute();
    }
}