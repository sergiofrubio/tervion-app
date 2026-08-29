<?php

namespace App\Core;

class Recaptcha
{
    private static $client = null;

    /**
     * Permite inyectar un cliente o handler simulado para tests.
     */
    public static function setClient($client): void
    {
        self::$client = $client;
    }

    /**
     * Obtiene la clave de sitio (site key) configurada.
     */
    public static function getSiteKey(): string
    {
        return getenv('RECAPTCHA_SITE_KEY') ?: ($_ENV['RECAPTCHA_SITE_KEY'] ?? '');
    }

    /**
     * Obtiene la clave secreta (secret key) configurada.
     */
    public static function getSecretKey(): string
    {
        return getenv('RECAPTCHA_SECRET_KEY') ?: ($_ENV['RECAPTCHA_SECRET_KEY'] ?? '');
    }

    /**
     * Verifica la respuesta enviada por Google reCAPTCHA.
     *
     * @param string|null $responseToken El token enviado en $_POST['g-recaptcha-response']
     * @param string|null $remoteIp Dirección IP del cliente
     * @return bool
     */
    public static function verify(?string $responseToken, ?string $remoteIp = null): bool
    {
        if (self::$client !== null) {
            return (bool) call_user_func(self::$client, $responseToken, $remoteIp);
        }

        $secretKey = self::getSecretKey();

        // Si no está configurada la secret key en entorno de desarrollo/test local, se omite para no bloquear
        if (empty($secretKey)) {
            $env = getenv('APP_ENV') ?: ($_ENV['APP_ENV'] ?? 'local');
            if (in_array($env, ['local', 'testing', 'dev'])) {
                return true;
            }
            return false;
        }

        if (empty($responseToken)) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $responseToken,
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data),
                'timeout' => 5,
            ]
        ];

        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            return false;
        }

        $response = json_decode($result, true);
        return isset($response['success']) && $response['success'] === true;
    }
}
