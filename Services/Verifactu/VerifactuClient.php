<?php

namespace App\Services\Verifactu;

use SimpleXMLElement;
use Exception;

class VerifactuClient
{
    private VerifactuConfig $config;

    public function __construct(VerifactuConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Envia el XML SOAP de la petición a la AEAT
     *
     * @param string $xmlPayload
     * @return array Resumen formateado del resultado
     */
    public function send(string $xmlPayload): array
    {
        $endpoint = $this->config->getEndpointUrl();

        $headers = [
            'Content-Type: text/xml; charset=utf-8',
            'SOAPAction: ""',
            'Content-Length: ' . strlen($xmlPayload)
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        // Si se dispone de certificado cliente
        if (!empty($this->config->certPath) && file_exists($this->config->certPath)) {
            curl_setopt($ch, CURLOPT_SSLCERT, $this->config->certPath);
            if (!empty($this->config->certPassword)) {
                curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $this->config->certPassword);
            }
        }

        $responseXml = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($curlErrno) {
            return [
                'success' => false,
                'estado' => 'ErrorConexion',
                'codigo_error' => 'CURL_' . $curlErrno,
                'mensaje' => 'Error de conexión cURL: ' . $curlError,
                'csv' => null,
                'xml_respuesta' => null
            ];
        }

        return $this->parseResponse($responseXml, $httpCode);
    }

    /**
     * Parsea la respuesta XML devuelta por la AEAT
     */
    public function parseResponse(string $xmlString, int $httpCode): array
    {
        if (empty($xmlString)) {
            return [
                'success' => false,
                'estado' => 'ErrorConexion',
                'codigo_error' => 'HTTP_' . $httpCode,
                'mensaje' => 'Respuesta vacía del servidor AEAT (HTTP ' . $httpCode . ')',
                'csv' => null,
                'xml_respuesta' => null
            ];
        }

        try {
            // Limpiar namespaces para facilitar XPath en SimpleXML
            $cleanXml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$3', $xmlString);
            $xml = new SimpleXMLElement($cleanXml);

            // Verificar si hay Fault de SOAP
            if (isset($xml->Body->Fault)) {
                $faultString = (string)$xml->Body->Fault->faultstring;
                return [
                    'success' => false,
                    'estado' => 'Rechazado',
                    'codigo_error' => (string)($xml->Body->Fault->faultcode ?? 'SOAP_FAULT'),
                    'mensaje' => 'SOAP Fault: ' . $faultString,
                    'csv' => null,
                    'xml_respuesta' => $xmlString
                ];
            }

            // Buscar elementos dentro de RespuestaRegFactuSistemaFacturacion
            $respBody = $xml->Body->RespuestaRegFactuSistemaFacturacion ?? null;
            if (!$respBody) {
                return [
                    'success' => false,
                    'estado' => 'ErrorConexion',
                    'codigo_error' => 'XML_INVALID',
                    'mensaje' => 'Estructura de respuesta XML no reconocida',
                    'csv' => null,
                    'xml_respuesta' => $xmlString
                ];
            }

            $estadoEnvio = (string)($respBody->EstadoEnvio ?? 'Incorrecto');
            $csv = (string)($respBody->CSV ?? '');

            // Registro específico
            $lineaResp = $respBody->RespostaLinea[0] ?? $respBody->RespuestaLinea[0] ?? null;
            $estadoRegistro = $lineaResp ? (string)($lineaResp->EstadoRegistro ?? $estadoEnvio) : $estadoEnvio;
            $codigoError = $lineaResp ? (string)($lineaResp->CodigoErrorRegistro ?? '') : '';
            $descripcionError = $lineaResp ? (string)($lineaResp->DescripcionErrorRegistro ?? '') : '';

            $isSuccess = in_array($estadoRegistro, ['Correcto', 'Aceptado', 'AceptadoConErrores']);

            return [
                'success' => $isSuccess,
                'estado' => $estadoRegistro === 'Correcto' ? 'Aceptado' : ($estadoRegistro === 'AceptadoConErrores' ? 'AceptadoConErrores' : 'Rechazado'),
                'codigo_error' => $codigoError,
                'mensaje' => $descripcionError ?: ('Estado remisión: ' . $estadoEnvio),
                'csv' => $csv,
                'xml_respuesta' => $xmlString
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'estado' => 'ErrorConexion',
                'codigo_error' => 'XML_PARSE_ERROR',
                'mensaje' => 'Error al analizar respuesta XML: ' . $e->getMessage(),
                'csv' => null,
                'xml_respuesta' => $xmlString
            ];
        }
    }
}
