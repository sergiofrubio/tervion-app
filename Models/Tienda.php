<?php
namespace App\Models;

use App\Core\DataBase;
use PDO;

class Tienda
{
    private $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: (new DataBase())->connect();
    }

    /**
     * Registra la compra de un bono por parte de un paciente
     */
    public function registrarCompraBono($usuario_id, $bono_id)
    {
        // En una implementación real, aquí añadiríamos el bono a la tabla bonos_usuarios
        // y registraríamos la factura/pago.
        return true; 
    }
}
