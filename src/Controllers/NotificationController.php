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

    /**
     * Envía un mensaje de texto o plantilla por WhatsApp utilizando la API Cloud de Meta (Graph API).
     *
     * @param string $to Número de teléfono del destinatario en formato internacional (E.164 sin +, ej: 34600112233).
     * @param string $message Texto del mensaje (si no es plantilla).
     * @param array|null $template Opcional. Estructura de plantilla ['name' => 'nombre_plantilla', 'language' => ['code' => 'es'], 'components' => [...]]
     * @return array ['success' => bool, 'response' => array|string|null, 'error' => string|null, 'http_code' => int]
     */
    public function sendWhatsAppMessage($to, $message = '', ?array $template = null)
    {
        $apiUrl = rtrim(getenv('WHATSAPP_API_URL') ?: 'https://graph.facebook.com/v20.0', '/');
        $phoneNumberId = getenv('WHATSAPP_PHONE_NUMBER_ID');
        $accessToken = getenv('WHATSAPP_ACCESS_TOKEN');

        if (empty($phoneNumberId) || empty($accessToken)) {
            $errorMsg = 'Configuración de WhatsApp incompleta: WHATSAPP_PHONE_NUMBER_ID o WHATSAPP_ACCESS_TOKEN no definidos.';
            error_log($errorMsg);
            return [
                'success' => false,
                'error' => $errorMsg,
                'response' => null,
                'http_code' => 0
            ];
        }

        // Limpiar el número de teléfono (dejar solo dígitos)
        $cleanTo = preg_replace('/\D+/', '', $to);

        $endpoint = "{$apiUrl}/{$phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanTo,
        ];

        if ($template !== null) {
            $payload['type'] = 'template';
            $payload['template'] = $template;
        } else {
            $payload['type'] = 'text';
            $payload['text'] = [
                'preview_url' => false,
                'body' => $message
            ];
        }

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false || !empty($curlError)) {
            $errorMsg = "Error cURL al conectar con Meta WhatsApp API: " . $curlError;
            error_log($errorMsg);
            return [
                'success' => false,
                'error' => $errorMsg,
                'response' => null,
                'http_code' => $httpCode
            ];
        }

        $decodedResponse = json_decode($response, true);

        // Los códigos de éxito de Meta suelen ser 200 OK o 201 Created
        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'response' => $decodedResponse ?? $response,
                'error' => null,
                'http_code' => $httpCode
            ];
        }

        $errorMessage = isset($decodedResponse['error']['message'])
            ? $decodedResponse['error']['message']
            : "HTTP {$httpCode}: " . $response;

        error_log("Error de Meta WhatsApp API ({$httpCode}): " . $errorMessage);

        return [
            'success' => false,
            'error' => $errorMessage,
            'response' => $decodedResponse ?? $response,
            'http_code' => $httpCode
        ];
    }
}
