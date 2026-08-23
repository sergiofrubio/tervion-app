<?php

namespace App\Controllers;

use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class NotificationController extends Controller
{
    /**
     * Envía un correo electrónico utilizando PHPMailer configurado para Mailpit.
     *
     * @param string $to Dirección de correo electrónico del destinatario.
     * @param string $subject Asunto del correo.
     * @param string $body Cuerpo del correo.
     * @param bool $isHtml Indica si el cuerpo del correo es HTML.
     * @return bool True si el correo se envió con éxito, false en caso contrario.
     */
    public function sendEmail($to, $subject, $body, $isHtml = true)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor local Mailpit
            $mail->isSMTP();
            $mail->Host       = 'mailpit'; // Host del servicio dentro de la red Docker
            $mail->Port       = 1025;      // Puerto SMTP de Mailpit
            $mail->SMTPAuth   = false;     // Sin autenticación obligatoria para local
            $mail->SMTPAutoTLS = false;

            // Destinatarios
            $mail->setFrom('noreply@tervion.local', 'Tervion');
            $mail->addAddress($to);

            // Contenido del correo
            $mail->isHTML($isHtml);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Se puede registrar el error $mail->ErrorInfo si es necesario
            error_log("Error al enviar email a $to: " . $mail->ErrorInfo);
            return false;
        }
    }
}
