<?php
// Bootstrap the application for CLI execution
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Core/config.php';

use App\Core\DataBase;
use App\Models\Gasto;
use Redsys\Merchant;
use Redsys\Parameters;
use Redsys\Rest;

echo "--- INICIANDO PROCESADO DE SUSCRIPCIONES Y COBROS MENSUALES ---\n";
echo "Fecha y hora actual: " . date('Y-m-d H:i:s') . "\n\n";

try {
    $db = (new DataBase())->connect();

    // 1. Obtener todas las cuentas de clientes activas
    $queryCuentas = "SELECT * FROM cuentas_clientes WHERE estado_cuenta = 'Activo'";
    $stmtCuentas = $db->prepare($queryCuentas);
    $stmtCuentas->execute();
    $cuentas = $stmtCuentas->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cuentas)) {
        echo "No hay cuentas activas registradas.\n";
        exit(0);
    }

    $gastoModel = new Gasto($db);
    $merchant = Merchant::initWithApiKey(REDSYS_API_KEY);

    foreach ($cuentas as $cuenta) {
        echo "Procesando cuenta ID {$cuenta['cuenta_id']} ({$cuenta['nombre_empresa']})...\n";

        // 2. Determinar el importe según el plan contratado
        $plan = $cuenta['plan_suscripcion'] ?? 'Basico';
        $precio = 29.99; // Básico por defecto
        if ($plan === 'Profesional') {
            $precio = 59.99;
        } elseif ($plan === 'Premium') {
            $precio = 99.99;
        }

        // 3. Obtener el método de pago por tarjeta guardado para el administrador de la cuenta
        $queryUser = "SELECT usuario_id FROM usuarios WHERE email = :email LIMIT 1";
        $stmtUser = $db->prepare($queryUser);
        $stmtUser->execute([':email' => $cuenta['email_admin']]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo " [ERROR] No se pudo encontrar el usuario administrador ({$cuenta['email_admin']}) para esta cuenta.\n";
            continue;
        }

        $queryCard = "SELECT * FROM metodos_pago WHERE usuario_id = :usuario_id AND tipo = 'Tarjeta' ORDER BY es_predeterminado DESC LIMIT 1";
        $stmtCard = $db->prepare($queryCard);
        $stmtCard->execute([':usuario_id' => $user['usuario_id']]);
        $card = $stmtCard->fetch(PDO::FETCH_ASSOC);

        if (!$card || empty($card['numero_completo'])) {
            echo " [ERROR] No hay ningún método de pago por tarjeta registrado para esta cuenta.\n";
            continue;
        }

        // 4. Intentar realizar el cobro recurrente tokenizado con Redsys
        $orderNumber = date('ymdHis') . $cuenta['cuenta_id'];
        $orderNumber = substr($orderNumber, 0, 12); // Asegurar longitud requerida por Redsys (máx 12)

        $params = new Parameters();
        $params->amount = intval(round($precio * 100)); // En céntimos
        $params->order = $orderNumber;
        $params->tokenizedCard = $card['token_externo'] ?? 'tok_simulated';
        $params->merchantData = 'Suscripcion Mensual Tervion - Plan ' . $plan;

        $cobroExitoso = false;
        try {
            // Llamada oficial a la API REST de Redsys
            $response = Rest::authorisation($merchant, $params);

            // Si devuelve respuesta satisfactoria del banco (0000 a 0099)
            if ($response && isset($response->data) && (int)$response->data->codResponse >= 0 && (int)$response->data->codResponse <= 99) {
                $cobroExitoso = true;
            } else {
                // En pruebas locales, si no hay internet o el servidor Redsys falla, simulamos éxito
                if (strpos(REDSYS_API_KEY, 'TEST_') === 0) {
                    $cobroExitoso = true;
                    echo " [SIMULACIÓN] Cobro simulado con éxito en entorno de pruebas.\n";
                } else {
                    echo " [DENEGADO] Cargo rechazado por la pasarela de pagos Redsys.\n";
                }
            }
        } catch (\Exception $e) {
            // Fallback para pruebas de desarrollo local sin conexión
            if (strpos(REDSYS_API_KEY, 'TEST_') === 0) {
                $cobroExitoso = true;
                echo " [SIMULACIÓN/FALLBACK] Simulación de cobro activada por error de conexión: " . $e->getMessage() . "\n";
            } else {
                echo " [ERROR REDSYS] Fallo en la comunicación con Redsys: " . $e->getMessage() . "\n";
            }
        }

        // 5. Si el cobro es exitoso, registrar el gasto de la suscripción en el sistema contable
        if ($cobroExitoso) {
            $baseImponible = $precio / 1.21;
            $iva = $precio - $baseImponible;

            $gastoData = [
                'nif_proveedor' => 'B99999999', // NIF de Tervion
                'nombre_proveedor' => 'Tervion S.L.',
                'numero_factura' => 'VELSUB-' . date('Ymd') . '-' . $cuenta['cuenta_id'],
                'fecha_emision' => date('Y-m-d'),
                'concepto' => 'Mensualidad suscripción Tervion - Plan ' . $plan,
                'base_imponible' => $baseImponible,
                'tipo_iva' => 21.00,
                'categoria' => 'Software'
            ];

            if ($gastoModel->save($gastoData)) {
                echo " [ÉXITO] Cobro de {$precio}€ realizado correctamente y registrado en gastos.\n";
            } else {
                echo " [ADVERTENCIA] Cobro realizado pero no se pudo guardar el registro de gasto.\n";
            }

            // 6. Aplicar downgrade diferido si existía un plan próximo programado y avanzar fecha_renovacion
            $nuevoPlanEfectivo = !empty($cuenta['plan_proximo']) ? $cuenta['plan_proximo'] : $cuenta['plan_suscripcion'];
            $proximaRenovacion = date('Y-m-d', strtotime('+1 month'));

            $updateCuentaStmt = $db->prepare("UPDATE cuentas_clientes 
                                              SET plan_suscripcion = :plan, 
                                                  plan_proximo = NULL, 
                                                  fecha_renovacion = :fecha_renovacion 
                                              WHERE cuenta_id = :cuenta_id");
            $updateCuentaStmt->execute([
                ':plan' => $nuevoPlanEfectivo,
                ':fecha_renovacion' => $proximaRenovacion,
                ':cuenta_id' => $cuenta['cuenta_id']
            ]);

            if (!empty($cuenta['plan_proximo'])) {
                echo " [PLAN ACTUALIZADO] Se aplicó el cambio diferido al plan {$nuevoPlanEfectivo} para el nuevo ciclo.\n";
            }
        }
        echo "\n";
    }
} catch (\Exception $e) {
    echo "[ERROR GENERAL] Ocurrió un error en el cron de suscripciones: " . $e->getMessage() . "\n";
}

echo "--- FINALIZADO PROCESADO DE SUSCRIPCIONES ---\n";
