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
            // Configuración del servidor SMTP mediante variables de entorno
            $mail->isSMTP();
            $mail->Host       = getenv('MAIL_HOST') ?: 'mailpit';
            $mail->Port       = (int)(getenv('MAIL_PORT') ?: 1025);
            
            $smtpAuth = getenv('MAIL_SMTP_AUTH');
            $mail->SMTPAuth = $smtpAuth !== false ? filter_var($smtpAuth, FILTER_VALIDATE_BOOLEAN) : false;

            $username = getenv('MAIL_USERNAME');
            if ($username !== false && $username !== '') {
                $mail->Username = $username;
            }

            $password = getenv('MAIL_PASSWORD');
            if ($password !== false && $password !== '') {
                $mail->Password = $password;
            }

            $encryption = getenv('MAIL_ENCRYPTION');
            if ($encryption !== false && $encryption !== '') {
                $mail->SMTPSecure = $encryption;
            }

            $autoTls = getenv('MAIL_AUTO_TLS');
            $mail->SMTPAutoTLS = $autoTls !== false ? filter_var($autoTls, FILTER_VALIDATE_BOOLEAN) : false;

            // Destinatarios
            $fromAddress = getenv('MAIL_FROM_ADDRESS') ?: 'noreply@tervion.local';
            $fromName    = getenv('MAIL_FROM_NAME') ?: 'Tervion';
            $mail->setFrom($fromAddress, $fromName);
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
