# Pruebas Unitarias (Testing) y Depuración con Xdebug

Este documento explica cómo ejecutar las pruebas unitarias en el sistema y cómo configurar el entorno para depurar el código en tiempo de ejecución.

---

## 🧪 Pruebas Unitarias con PHPUnit

El proyecto cuenta con **PHPUnit** como dependencia de desarrollo integrada. Las pruebas se ubican en el directorio `Tests/`.

### Ejecutar Pruebas en el Contenedor
Dado que la aplicación corre dentro de Docker, las pruebas deben ser ejecutadas en el runtime del contenedor de Apache para que dispongan de las extensiones y la conexión a la base de datos de test correctas.

Para ejecutar la suite completa de pruebas:
```bash
docker compose exec apache vendor/bin/phpunit
```

O si utilizas comandos directos del contenedor:
```bash
docker exec -it <nombre-contenedor-apache> ./vendor/bin/phpunit
```

> [!TIP]
> Puedes verificar el nombre exacto de tus contenedores levantados ejecutando `docker compose ps`.

---

## 🔍 Depuración Interactiva con Xdebug

El contenedor de desarrollo de Apache tiene **Xdebug** preinstalado y configurado automáticamente para permitir depuración por breakpoints paso a paso y análisis de cobertura de pruebas (`coverage`).

### Configuración del Servidor PHP (en Docker)
Como se detalla en el [Dockerfile](file:///c:/Users/sergi/Documents/velion-app/Dockerfile), Xdebug está parametrizado con los siguientes valores para desarrollo:
- `xdebug.mode=coverage,debug` (habilita depuración activa y reportes de cobertura)
- `xdebug.start_with_request=yes` (inicia el handshake de depuración en cada petición entrante)
- `xdebug.client_host=host.docker.internal` (redirige el puerto al host que aloja los contenedores)
- `xdebug.client_port=9003` (puerto por defecto del protocolo de Xdebug 3)

### Integración con Visual Studio Code (VS Code)
Para conectar tu IDE a la sesión de Xdebug del contenedor:

1. Instala la extensión **PHP Debug** de VS Code (creada por *Felix Becker*).
2. Crea o edita el archivo `.vscode/launch.json` en la raíz de tu workspace con la siguiente configuración:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for Xdebug (Docker)",
      "type": "php",
      "request": "launch",
      "port": 9003,
      "pathMappings": {
        "/var/www/html": "${workspaceRoot}"
      }
    }
  ]
}
```

3. Coloca un *Breakpoint* (punto de interrupción) en cualquier línea de un controlador.
4. Presiona **F5** en VS Code ("Listen for Xdebug").
5. Carga la página correspondiente en el navegador. El IDE pausará la ejecución en el punto exacto permitiendo inspeccionar variables de sesión, globales y locales.
