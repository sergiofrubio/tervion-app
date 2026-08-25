<?php

namespace App\Services\Verifactu;

use App\Models\Invoice;
use App\Models\Setting;
use Exception;

class VerifactuService
{
    private Invoice $invoiceModel;
    private Setting $settingModel;

    public function __construct(?Invoice $invoiceModel = null, ?Setting $settingModel = null)
    {
        $this->invoiceModel = $invoiceModel ?: new Invoice();
        $this->settingModel = $settingModel ?: new Setting();
    }

    /**
     * Procesa y envía una factura a Verifactu (AEAT)
     *
     * @param int $facturaId
     * @return array Resultado de la operación
     */
    public function procesarFactura(int $facturaId): array
    {
        $factura = $this->invoiceModel->getById($facturaId);
        if (!$factura) {
            return [
                'success' => false,
                'message' => 'La factura especificada no existe.'
            ];
        }

        $clinica = $this->settingModel->getClinica();
        $config = new VerifactuConfig($clinica ?: []);

        // Si Verifactu está desactivado por configuración
        if (!$config->activo) {
            return [
                'success' => false,
                'message' => 'Verifactu está desactivado en la configuración de la clínica.'
            ];
        }

        // Formatear huso horario e identificadores
        $fechaHoraHuso = date('c', strtotime($factura['fecha_hora_emision']));
        $fechaExpedicionDate = date('d-m-Y', strtotime($factura['fecha_emision']));
        $numSerie = ($factura['serie'] ?? 'A') . '-' . ($factura['numero'] ?? '1');

        // Generar huella si aún no existe
        $huella = $factura['huella'];
        if (empty($huella)) {
            $huella = VerifactuHashGenerator::generateHash(
                $config->nifEmisor,
                $numSerie,
                $fechaExpedicionDate,
                $factura['tipo_factura'] ?? 'F1',
                $factura['cuota_iva'],
                $factura['total'],
                $factura['huella_anterior'],
                $fechaHoraHuso
            );
        }

        // Generar URL del Código QR
        $qrUrl = VerifactuQRGenerator::generateUrl(
            $config,
            $numSerie,
            $fechaExpedicionDate,
            $factura['total']
        );

        $facturaData = array_merge($factura, [
            'nif_emisor' => $config->nifEmisor,
            'fecha_hora_huso' => $fechaHoraHuso,
            'huella' => $huella,
            'qr_url' => $qrUrl,
            'paciente_nombre' => trim(($factura['nombre'] ?? '') . ' ' . ($factura['apellidos'] ?? '')),
            'paciente_nif' => $factura['paciente_id'] ?? ''
        ]);

        // Construir XML SOAP
        $xmlPayload = VerifactuXmlBuilder::buildAltaXml($config, $facturaData);

        // Enviar a la AEAT a través del cliente
        $client = new VerifactuClient($config);
        $result = $client->send($xmlPayload);

        // Actualizar datos de Verifactu en la BD
        $updateData = [
            'factura_id' => $facturaId,
            'nif_emisor' => $config->nifEmisor,
            'fecha_hora_huso' => $fechaHoraHuso,
            'huella' => $huella,
            'qr_url' => $qrUrl,
            'estado_verifactu' => $result['estado'],
            'csv_verifactu' => $result['csv'],
            'codigo_error_verifactu' => $result['codigo_error'],
            'mensaje_verifactu' => $result['mensaje'],
            'fecha_envio_verifactu' => date('Y-m-d H:i:s'),
            'xml_peticion' => $xmlPayload,
            'xml_respuesta' => $result['xml_respuesta']
        ];

        $this->invoiceModel->updateVerifactuData($updateData);

        return array_merge($result, [
            'huella' => $huella,
            'qr_url' => $qrUrl
        ]);
    }
}
