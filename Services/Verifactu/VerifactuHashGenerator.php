<?php

namespace App\Services\Verifactu;

class VerifactuHashGenerator
{
    /**
     * Genera la huella SHA-256 encadenada según las especificaciones técnicas de la AEAT para RegistroAlta
     *
     * @param string $nifEmisor NIF del emisor
     * @param string $numSerieFactura Número o Serie+Número de la factura
     * @param string $fechaExpedicion Fecha de expedición (formato DD-MM-YYYY)
     * @param string $tipoFactura Tipo de factura (ej. F1, F2, R1)
     * @param float|string $cuotaTotal Cuota total del impuesto (formato 0.00)
     * @param float|string $importeTotal Importe total de la factura (formato 0.00)
     * @param string|null $huellaAnterior Huella del registro inmediatamente anterior (o vacía si es la primera)
     * @param string $fechaHoraHusoGenRegistro Fecha y hora con huso horario ISO8601 (ej. 2026-08-01T19:00:00+02:00)
     * @return string Hash SHA-256 en mayúsculas (64 caracteres)
     */
    public static function generateHash(
        string $nifEmisor,
        string $numSerieFactura,
        string $fechaExpedicion,
        string $tipoFactura,
        $cuotaTotal,
        $importeTotal,
        ?string $huellaAnterior,
        string $fechaHoraHusoGenRegistro
    ): string {
        $cuotaFormatted = number_format((float)$cuotaTotal, 2, '.', '');
        $importeFormatted = number_format((float)$importeTotal, 2, '.', '');
        $huellaAnt = $huellaAnterior ? trim($huellaAnterior) : '';

        // Formato estándar de la cadena a resumir según AEAT Verifactu
        $cadena = "IDEmisorFactura=" . trim($nifEmisor) .
                  "&NumSerieFactura=" . trim($numSerieFactura) .
                  "&FechaExpedicionFactura=" . trim($fechaExpedicion) .
                  "&TipoFactura=" . trim($tipoFactura) .
                  "&CuotaTotal=" . $cuotaFormatted .
                  "&ImporteTotal=" . $importeFormatted .
                  "&Huella=" . $huellaAnt .
                  "&FechaHoraHusoGenRegistro=" . trim($fechaHoraHusoGenRegistro);

        return strtoupper(hash('sha256', $cadena));
    }
}
