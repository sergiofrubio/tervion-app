<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Verifactu\VerifactuConfig;
use App\Services\Verifactu\VerifactuHashGenerator;
use App\Services\Verifactu\VerifactuQRGenerator;
use App\Services\Verifactu\VerifactuXmlBuilder;
use App\Services\Verifactu\VerifactuClient;

class VerifactuTest extends TestCase
{
    public function testHashGenerationReturnsHexSha256Upper()
    {
        $hash = VerifactuHashGenerator::generateHash(
            'B12345678',
            'A-1',
            '01-08-2026',
            'F1',
            21.00,
            121.00,
            null,
            '2026-08-01T19:00:00+02:00'
        );

        $this->assertEquals(64, strlen($hash));
        $this->assertEquals(strtoupper($hash), $hash);
        $this->assertMatchesRegularExpression('/^[A-F0-9]{64}$/', $hash);
    }

    public function testHashChainingChangesWhenPreviousHashProvided()
    {
        $hash1 = VerifactuHashGenerator::generateHash(
            'B12345678', 'A-2', '01-08-2026', 'F1', 10.00, 110.00, null, '2026-08-01T19:00:00+02:00'
        );

        $hash2 = VerifactuHashGenerator::generateHash(
            'B12345678', 'A-2', '01-08-2026', 'F1', 10.00, 110.00, $hash1, '2026-08-01T19:00:00+02:00'
        );

        $this->assertNotEquals($hash1, $hash2);
    }

    public function testQrUrlGeneratorContainsCorrectParams()
    {
        $config = new VerifactuConfig([
            'verifactu_env' => 'pruebas',
            'nif_cif' => 'B12345678'
        ]);

        $url = VerifactuQRGenerator::generateUrl($config, 'A-10', '01-08-2026', 150.50);

        $this->assertStringStartsWith(VerifactuConfig::QR_BASE_PRUEBAS, $url);
        $this->assertStringContainsString('nif=B12345678', $url);
        $this->assertStringContainsString('serie=A-10', $url);
        $this->assertStringContainsString('importe=150.50', $url);
    }

    public function testXmlBuilderConstructsValidVerifactuSoapStructure()
    {
        $config = new VerifactuConfig([
            'verifactu_env' => 'pruebas',
            'nif_cif' => 'B12345678',
            'razon_social' => 'CLINICA TEST S.L.'
        ]);

        $facturaData = [
            'serie' => 'A',
            'numero' => 1,
            'tipo_factura' => 'F1',
            'fecha_emision' => '2026-08-01',
            'fecha_hora_huso' => '2026-08-01T19:00:00+02:00',
            'precio' => 100.00,
            'impuesto' => 21.00,
            'cuota_iva' => 21.00,
            'total' => 121.00,
            'descripcion' => 'Sesión de Fisioterapia',
            'paciente_nombre' => 'Juan Pérez',
            'paciente_nif' => '12345678Z',
            'huella' => 'E3B0C44298FC1C149AFBF4C8996FB92427AE41E4649B934CA495991B7852B855'
        ];

        $xml = VerifactuXmlBuilder::buildAltaXml($config, $facturaData);

        $this->assertStringContainsString('<sfLR:RegFactuSistemaFacturacion', $xml);
        $this->assertStringContainsString('<sf:RegistroAlta>', $xml);
        $this->assertStringContainsString('<sf:IDEmisorFactura>B12345678</sf:IDEmisorFactura>', $xml);
        $this->assertStringContainsString('<sf:NumSerieFactura>A-1</sf:NumSerieFactura>', $xml);
        $this->assertStringContainsString('<sf:CuotaTotal>21.00</sf:CuotaTotal>', $xml);
        $this->assertStringContainsString('<sf:ImporteTotal>121.00</sf:ImporteTotal>', $xml);
    }

    public function testClientResponseParsingSuccess()
    {
        $config = new VerifactuConfig();
        $client = new VerifactuClient($config);

        $mockXml = '<?xml version="1.0" encoding="UTF-8"?>
        <env:Envelope xmlns:env="http://schemas.xmlsoap.org/soap/envelope/">
            <env:Body>
                <sfR:RespuestaRegFactuSistemaFacturacion xmlns:sfR="https://www2.agenciatributaria.gob.es/static_files/common/internet/dep/aplicaciones/es/aeat/tike/cont/ws/RespuestaSuministro.xsd">
                    <sfR:EstadoEnvio>Correcto</sfR:EstadoEnvio>
                    <sfR:CSV>CSVTEST123456789</sfR:CSV>
                    <sfR:RespuestaLinea>
                        <sfR:EstadoRegistro>Correcto</sfR:EstadoRegistro>
                    </sfR:RespuestaLinea>
                </sfR:RespuestaRegFactuSistemaFacturacion>
            </env:Body>
        </env:Envelope>';

        $res = $client->parseResponse($mockXml, 200);

        $this->assertTrue($res['success']);
        $this->assertEquals('Aceptado', $res['estado']);
        $this->assertEquals('CSVTEST123456789', $res['csv']);
    }
}
