<?php

namespace App\Services\Verifactu;

use DOMDocument;

class VerifactuXmlBuilder
{
    /**
     * Construye el documento XML SOAP completo para la remisión de facturas de alta a la AEAT
     *
     * @param VerifactuConfig $config
     * @param array $facturaData
     * @return string XML resultante
     */
    public static function buildAltaXml(VerifactuConfig $config, array $facturaData): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        // SOAP Envelope
        $soapEnv = $dom->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soapenv:Envelope');
        $soapEnv->setAttribute('xmlns:sfLR', 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroLR.xsd');
        $soapEnv->setAttribute('xmlns:sf', 'https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/SuministroInformacion.xsd');
        $dom->appendChild($soapEnv);

        $soapBody = $dom->createElement('soapenv:Body');
        $soapEnv->appendChild($soapBody);

        // Raíz RegFactuSistemaFacturacion
        $root = $dom->createElement('sfLR:RegFactuSistemaFacturacion');
        $soapBody->appendChild($root);

        // 1. Cabecera
        $cabecera = $dom->createElement('sfLR:Cabecera');
        $obligado = $dom->createElement('sf:ObligadoEmision');
        $obligado->appendChild($dom->createElement('sf:NombreRazon', htmlspecialchars($config->nombreRazonEmisor, ENT_XML1, 'UTF-8')));
        $obligado->appendChild($dom->createElement('sf:NIF', htmlspecialchars($config->nifEmisor, ENT_XML1, 'UTF-8')));
        $cabecera->appendChild($obligado);
        $root->appendChild($cabecera);

        // 2. RegistroFactura (RegistroAlta)
        $regFactura = $dom->createElement('sfLR:RegistroFactura');
        $regAlta = $dom->createElement('sf:RegistroAlta');

        $regAlta->appendChild($dom->createElement('sf:IDVersion', '1.0'));

        // IDFactura
        $idFactura = $dom->createElement('sf:IDFactura');
        $idFactura->appendChild($dom->createElement('sf:IDEmisorFactura', htmlspecialchars($config->nifEmisor, ENT_XML1, 'UTF-8')));
        $numSerie = ($facturaData['serie'] ?? 'A') . '-' . ($facturaData['numero'] ?? '1');
        $idFactura->appendChild($dom->createElement('sf:NumSerieFactura', htmlspecialchars($numSerie, ENT_XML1, 'UTF-8')));

        $fechaExpedicionDate = date('d-m-Y', strtotime($facturaData['fecha_emision']));
        $idFactura->appendChild($dom->createElement('sf:FechaExpedicionFactura', $fechaExpedicionDate));
        $regAlta->appendChild($idFactura);

        $regAlta->appendChild($dom->createElement('sf:NombreRazonEmisor', htmlspecialchars($config->nombreRazonEmisor, ENT_XML1, 'UTF-8')));
        $regAlta->appendChild($dom->createElement('sf:Subsanacion', !empty($facturaData['subsanacion']) ? 'S' : 'N'));
        $regAlta->appendChild($dom->createElement('sf:RechazoPrevio', 'N'));
        $regAlta->appendChild($dom->createElement('sf:TipoFactura', htmlspecialchars($facturaData['tipo_factura'] ?? 'F1', ENT_XML1, 'UTF-8')));

        $regAlta->appendChild($dom->createElement('sf:FechaOperacion', $fechaExpedicionDate));
        $regAlta->appendChild($dom->createElement('sf:DescripcionOperacion', htmlspecialchars($facturaData['descripcion'] ?? 'Servicios profesionales', ENT_XML1, 'UTF-8')));

        // Destinatarios
        if (!empty($facturaData['paciente_nombre'])) {
            $destinatarios = $dom->createElement('sf:Destinatarios');
            $idDestinatario = $dom->createElement('sf:IDDestinatario');
            $idDestinatario->appendChild($dom->createElement('sf:NombreRazon', htmlspecialchars($facturaData['paciente_nombre'], ENT_XML1, 'UTF-8')));
            if (!empty($facturaData['paciente_nif'])) {
                $idDestinatario->appendChild($dom->createElement('sf:NIF', htmlspecialchars($facturaData['paciente_nif'], ENT_XML1, 'UTF-8')));
            }
            $destinatarios->appendChild($idDestinatario);
            $regAlta->appendChild($destinatarios);
        }

        // Desglose
        $desglose = $dom->createElement('sf:Desglose');
        $detalle = $dom->createElement('sf:DetalleDesglose');
        $detalle->appendChild($dom->createElement('sf:ClaveRegimen', '01'));
        $detalle->appendChild($dom->createElement('sf:CalificacionOperacion', 'S1'));
        $detalle->appendChild($dom->createElement('sf:TipoImpositivo', number_format((float)($facturaData['impuesto'] ?? 21), 2, '.', '')));
        $detalle->appendChild($dom->createElement('sf:BaseImponibleOimporteNoSujeto', number_format((float)($facturaData['precio'] ?? 0), 2, '.', '')));
        $detalle->appendChild($dom->createElement('sf:CuotaRepercutida', number_format((float)($facturaData['cuota_iva'] ?? 0), 2, '.', '')));
        $desglose->appendChild($detalle);
        $regAlta->appendChild($desglose);

        $regAlta->appendChild($dom->createElement('sf:CuotaTotal', number_format((float)($facturaData['cuota_iva'] ?? 0), 2, '.', '')));
        $regAlta->appendChild($dom->createElement('sf:ImporteTotal', number_format((float)($facturaData['total'] ?? 0), 2, '.', '')));

        // Encadenamiento
        if (!empty($facturaData['huella_anterior'])) {
            $encadenamiento = $dom->createElement('sf:Encadenamiento');
            $regAnterior = $dom->createElement('sf:RegistroAnterior');
            $regAnterior->appendChild($dom->createElement('sf:IDEmisorFactura', htmlspecialchars($config->nifEmisor, ENT_XML1, 'UTF-8')));
            $numSerieAnt = ($facturaData['serie_anterior'] ?? $facturaData['serie'] ?? 'A') . '-' . ($facturaData['numero_anterior'] ?? ($facturaData['numero'] - 1));
            $regAnterior->appendChild($dom->createElement('sf:NumSerieFactura', htmlspecialchars($numSerieAnt, ENT_XML1, 'UTF-8')));
            $regAnterior->appendChild($dom->createElement('sf:FechaExpedicionFactura', $facturaData['fecha_anterior'] ?? $fechaExpedicionDate));
            $regAnterior->appendChild($dom->createElement('sf:Huella', htmlspecialchars($facturaData['huella_anterior'], ENT_XML1, 'UTF-8')));
            $encadenamiento->appendChild($regAnterior);
            $regAlta->appendChild($encadenamiento);
        }

        // Sistema Informático
        $sistema = $dom->createElement('sf:SistemaInformatico');
        $sistema->appendChild($dom->createElement('sf:NombreRazon', htmlspecialchars($config->nombreRazonEmisor, ENT_XML1, 'UTF-8')));
        $sistema->appendChild($dom->createElement('sf:NIF', htmlspecialchars($config->nifEmisor, ENT_XML1, 'UTF-8')));
        $sistema->appendChild($dom->createElement('sf:NombreSistemaInformatico', 'tervion ERP'));
        $sistema->appendChild($dom->createElement('sf:IdSistemaInformatico', '01'));
        $sistema->appendChild($dom->createElement('sf:Version', '1.0.0'));
        $sistema->appendChild($dom->createElement('sf:NumeroInstalacion', '01'));
        $sistema->appendChild($dom->createElement('sf:TipoUsoPosibleSoloVerifactu', 'S'));
        $sistema->appendChild($dom->createElement('sf:TipoUsoPosibleMultiOT', 'N'));
        $sistema->appendChild($dom->createElement('sf:IndicadorMultiplesOT', 'N'));
        $regAlta->appendChild($sistema);

        // FechaHoraHusoGenRegistro y Huella
        $regAlta->appendChild($dom->createElement('sf:FechaHoraHusoGenRegistro', $facturaData['fecha_hora_huso']));
        $regAlta->appendChild($dom->createElement('sf:TipoHuella', '01'));
        $regAlta->appendChild($dom->createElement('sf:Huella', htmlspecialchars($facturaData['huella'], ENT_XML1, 'UTF-8')));

        $regFactura->appendChild($regAlta);
        $root->appendChild($regFactura);

        return $dom->saveXML();
    }
}
