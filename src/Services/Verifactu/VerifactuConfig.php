<?php

namespace App\Services\Verifactu;

class VerifactuConfig
{
    public const ENV_PRUEBAS = 'pruebas';
    public const ENV_PRODUCCION = 'produccion';

    public const URL_PRUEBAS = 'https://prewww1.aeat.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';
    public const URL_PRODUCCION = 'https://www1.agenciatributaria.gob.es/wlpl/TIKE-CONT/ws/SistemaFacturacion/VerifactuSOAP';

    public const QR_BASE_PRUEBAS = 'https://prewww1.aeat.es/vl/factura/qr';
    public const QR_BASE_PRODUCCION = 'https://www.agenciatributaria.gob.es/vl/factura/qr';

    public string $env;
    public string $nifEmisor;
    public string $nombreRazonEmisor;
    public ?string $certPath;
    public ?string $certPassword;
    public bool $activo;

    public function __construct(array $params = [])
    {
        $this->env = $params['verifactu_env'] ?? self::ENV_PRUEBAS;
        $this->nifEmisor = $params['nif_cif'] ?? 'B12345678';
        $this->nombreRazonEmisor = $params['razon_social'] ?? $params['nombre_comercial'] ?? 'tervion ERP DEMO S.L.';
        $this->certPath = $params['verifactu_cert_path'] ?? null;
        $this->certPassword = $params['verifactu_cert_password'] ?? null;
        $this->activo = isset($params['verifactu_activo']) ? (bool)$params['verifactu_activo'] : true;
    }

    public function getEndpointUrl(): string
    {
        return ($this->env === self::ENV_PRODUCCION) ? self::URL_PRODUCCION : self::URL_PRUEBAS;
    }

    public function getQrBaseUrl(): string
    {
        return ($this->env === self::ENV_PRODUCCION) ? self::QR_BASE_PRODUCCION : self::QR_BASE_PRUEBAS;
    }
}
