<?php

namespace App\Services;

use App\Core\DataBase;
use PDO;
use Exception;

class InstallerService
{
    private static ?string $lockFilePath = null;

    public static function getLockFilePath(): string
    {
        if (self::$lockFilePath === null) {
            self::$lockFilePath = dirname(__DIR__, 2) . '/storage/installed.lock';
        }
        return self::$lockFilePath;
    }

    public static function setLockFilePath(string $path): void
    {
        self::$lockFilePath = $path;
    }

    /**
     * Determina si el sistema ya está completamente instalado y configurado.
     */
    public static function isInstalled(): bool
    {
        $lockFile = self::getLockFilePath();
        if (file_exists($lockFile)) {
            return true;
        }

        // Si no hay archivo lock, verificar si la BD ya tiene un Administrador y Clínica
        try {
            $db = (new DataBase())->connect();
            if (!$db) {
                return false;
            }

            // Comprobar si existe la tabla usuarios
            $stmt = $db->query("SHOW TABLES LIKE 'usuarios'");
            if (!$stmt->fetch()) {
                return false;
            }

            // Comprobar si existe al menos un Administrador
            $stmtAdmin = $db->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'Administrador'");
            $adminCount = (int)$stmtAdmin->fetchColumn();

            // Comprobar si existe al menos una Clínica
            $stmtClinica = $db->query("SHOW TABLES LIKE 'clinicas'");
            $hasClinicaTable = (bool)$stmtClinica->fetch();
            $clinicaCount = 0;
            if ($hasClinicaTable) {
                $stmtC = $db->query("SELECT COUNT(*) FROM clinicas");
                $clinicaCount = (int)$stmtC->fetchColumn();
            }

            if ($adminCount > 0 && $clinicaCount > 0) {
                @file_put_contents($lockFile, "Installed on " . date('Y-m-d H:i:s') . "\n");
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Comprueba los requisitos del entorno del servidor PHP.
     */
    public static function checkRequirements(): array
    {
        $phpVersion = PHP_VERSION;
        $phpOk = version_compare($phpVersion, '8.1.0', '>=');

        $requiredExtensions = [
            'pdo' => 'PDO',
            'pdo_mysql' => 'PDO MySQL Driver',
            'mbstring' => 'Multibyte String (mbstring)',
            'openssl' => 'OpenSSL',
            'curl' => 'cURL',
            'json' => 'JSON'
        ];

        $extensionResults = [];
        $allExtensionsOk = true;
        foreach ($requiredExtensions as $ext => $label) {
            $loaded = extension_loaded($ext);
            $extensionResults[$ext] = [
                'label' => $label,
                'loaded' => $loaded
            ];
            if (!$loaded) {
                $allExtensionsOk = false;
            }
        }

        $baseDir = dirname(__DIR__, 2);
        $writableDirectories = [
            'storage' => $baseDir . '/storage',
            'storage/certificates' => $baseDir . '/storage/certificates',
            'storage/logs' => $baseDir . '/storage/logs'
        ];

        $permissionResults = [];
        $allPermissionsOk = true;
        foreach ($writableDirectories as $key => $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0777, true);
            }
            $isWritable = is_dir($path) && is_writable($path);
            $permissionResults[$key] = [
                'path' => $key,
                'writable' => $isWritable
            ];
            if (!$isWritable) {
                $allPermissionsOk = false;
            }
        }

        $dbStatus = self::checkDatabaseConnection();

        return [
            'php' => [
                'version' => $phpVersion,
                'required' => '8.1.0',
                'ok' => $phpOk
            ],
            'extensions' => [
                'list' => $extensionResults,
                'ok' => $allExtensionsOk
            ],
            'permissions' => [
                'list' => $permissionResults,
                'ok' => $allPermissionsOk
            ],
            'database' => $dbStatus,
            'allOk' => ($phpOk && $allExtensionsOk && $allPermissionsOk && $dbStatus['ok'])
        ];
    }

    /**
     * Prueba la conexión a la base de datos configurada.
     */
    public static function checkDatabaseConnection(): array
    {
        try {
            $db = (new DataBase())->connect();
            if ($db) {
                $version = $db->query('SELECT VERSION()')->fetchColumn();
                return [
                    'ok' => true,
                    'message' => 'Conexión a MySQL establecida correctamente.',
                    'version' => $version
                ];
            }
            return [
                'ok' => false,
                'message' => 'No se pudo conectar a la base de datos.'
            ];
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => 'Error de conexión: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Ejecuta el proceso de inicialización (Guardar clínica, crear superadministrador y generar lockfile).
     */
    public static function install(array $clinicaData, array $adminData): array
    {
        $db = (new DataBase())->connect();
        if (!$db) {
            throw new Exception('No hay conexión disponible con la base de datos.');
        }

        $db->beginTransaction();

        try {
            // 1. Configurar o Actualizar Clínica (ID = 1)
            $stmtCheckClinica = $db->prepare("SELECT clinica_id FROM clinicas WHERE clinica_id = 1 LIMIT 1");
            $stmtCheckClinica->execute();
            $hasClinica = (bool)$stmtCheckClinica->fetch();

            if ($hasClinica) {
                $stmtClinica = $db->prepare("UPDATE clinicas SET 
                    nombre_comercial = :nombre_comercial,
                    razon_social = :razon_social,
                    nif_cif = :nif_cif,
                    direccion = :direccion,
                    ciudad = :ciudad,
                    provincia_estado = :provincia_estado,
                    codigo_postal = :codigo_postal,
                    pais = :pais,
                    telefono_contacto = :telefono_contacto,
                    email_contacto = :email_contacto,
                    sitio_web = :sitio_web
                    WHERE clinica_id = 1");
            } else {
                $stmtClinica = $db->prepare("INSERT INTO clinicas (
                    clinica_id, cuenta_id, nombre_comercial, razon_social, nif_cif, 
                    direccion, ciudad, provincia_estado, codigo_postal, pais, 
                    telefono_contacto, email_contacto, sitio_web, verifactu_env, verifactu_activo, activo
                ) VALUES (
                    1, 1, :nombre_comercial, :razon_social, :nif_cif, 
                    :direccion, :ciudad, :provincia_estado, :codigo_postal, :pais, 
                    :telefono_contacto, :email_contacto, :sitio_web, 'pruebas', 1, 1
                )");
            }

            $stmtClinica->execute([
                ':nombre_comercial' => $clinicaData['nombre_comercial'],
                ':razon_social' => $clinicaData['razon_social'] ?? $clinicaData['nombre_comercial'],
                ':nif_cif' => $clinicaData['nif_cif'] ?? 'B00000000',
                ':direccion' => $clinicaData['direccion'] ?? 'S/N',
                ':ciudad' => $clinicaData['ciudad'] ?? 'Madrid',
                ':provincia_estado' => $clinicaData['provincia_estado'] ?? 'Madrid',
                ':codigo_postal' => $clinicaData['codigo_postal'] ?? '28001',
                ':pais' => $clinicaData['pais'] ?? 'España',
                ':telefono_contacto' => $clinicaData['telefono_contacto'] ?? '',
                ':email_contacto' => $clinicaData['email_contacto'] ?? '',
                ':sitio_web' => $clinicaData['sitio_web'] ?? ''
            ]);

            // 2. Comprobar / Crear cuenta cliente principal
            $stmtCheckCuenta = $db->prepare("SELECT cuenta_id FROM cuentas_clientes WHERE cuenta_id = 1 LIMIT 1");
            $stmtCheckCuenta->execute();
            if (!$stmtCheckCuenta->fetch()) {
                $stmtCuenta = $db->prepare("INSERT INTO cuentas_clientes (
                    cuenta_id, nombre_empresa, nif_cif, slug, plan_suscripcion, email_admin, estado_cuenta
                ) VALUES (
                    1, :nombre_empresa, :nif_cif, :slug, 'Community', :email_admin, 'Activo'
                )");
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $clinicaData['nombre_comercial']));
                $stmtCuenta->execute([
                    ':nombre_empresa' => $clinicaData['nombre_comercial'],
                    ':nif_cif' => $clinicaData['nif_cif'] ?? 'B00000000',
                    ':slug' => trim($slug, '-') ?: 'clinica',
                    ':email_admin' => $adminData['email']
                ]);
            } else {
                $stmtUpdateCuenta = $db->prepare("UPDATE cuentas_clientes SET 
                    nombre_empresa = :nombre_empresa,
                    email_admin = :email_admin
                    WHERE cuenta_id = 1");
                $stmtUpdateCuenta->execute([
                    ':nombre_empresa' => $clinicaData['nombre_comercial'],
                    ':email_admin' => $adminData['email']
                ]);
            }

            // 3. Crear o actualizar Administrador Inicial
            $dni = !empty($adminData['dni']) ? trim($adminData['dni']) : 'ADM' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            $hashedPass = password_hash($adminData['pass'], PASSWORD_DEFAULT);

            $stmtCheckUser = $db->prepare("SELECT usuario_id FROM usuarios WHERE email = :email LIMIT 1");
            $stmtCheckUser->execute([':email' => $adminData['email']]);
            $existingUser = $stmtCheckUser->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                $stmtUser = $db->prepare("UPDATE usuarios SET 
                    nombre = :nombre,
                    apellidos = :apellidos,
                    pass = :pass,
                    rol = 'Administrador',
                    activo = 1
                    WHERE usuario_id = :id");
                $stmtUser->execute([
                    ':nombre' => $adminData['nombre'],
                    ':apellidos' => $adminData['apellidos'] ?? '',
                    ':pass' => $hashedPass,
                    ':id' => $existingUser['usuario_id']
                ]);
            } else {
                $stmtUser = $db->prepare("INSERT INTO usuarios (
                    dni, cuenta_id, nombre, apellidos, telefono, fecha_nacimiento, 
                    email, pass, genero, rol, activo
                ) VALUES (
                    :dni, 1, :nombre, :apellidos, :telefono, '1990-01-01', 
                    :email, :pass, 'Otro', 'Administrador', 1
                )");
                $stmtUser->execute([
                    ':dni' => $dni,
                    ':nombre' => $adminData['nombre'],
                    ':apellidos' => $adminData['apellidos'] ?? '',
                    ':telefono' => $adminData['telefono'] ?? null,
                    ':email' => $adminData['email'],
                    ':pass' => $hashedPass
                ]);
            }

            $db->commit();

            // 4. Escribir archivo de bloqueo
            $lockContent = sprintf(
                "Tervion Community Edition Installed\nDate: %s\nAdmin: %s\nClinic: %s\n",
                date('c'),
                $adminData['email'],
                $clinicaData['nombre_comercial']
            );
            file_put_contents(self::getLockFilePath(), $lockContent);

            return [
                'success' => true,
                'message' => 'Instalación y configuración inicial completada con éxito.'
            ];
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw new Exception('Error durante el proceso de instalación: ' . $e->getMessage());
        }
    }
}