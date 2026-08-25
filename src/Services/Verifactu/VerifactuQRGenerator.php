<?php

namespace App\Services\Verifactu;

class VerifactuQRGenerator
{
    /**
     * Genera la URL normalizada para la lectura del código QR de la factura conforme a la AEAT
     *
     * @param VerifactuConfig $config
     * @param string $numSerieFactura
     * @param string $fechaExpedicion (DD-MM-YYYY)
     * @param float|string $importeTotal
     * @return string
     */
    public static function generateUrl(
        VerifactuConfig $config,
        string $numSerieFactura,
        string $fechaExpedicion,
        $importeTotal
    ): string {
        $baseUrl = $config->getQrBaseUrl();
        $params = [
            'nif' => $config->nifEmisor,
            'serie' => $numSerieFactura,
            'fecha' => $fechaExpedicion,
            'importe' => number_format((float)$importeTotal, 2, '.', '')
        ];

        return $baseUrl . '?' . http_build_query($params);
    }
}
