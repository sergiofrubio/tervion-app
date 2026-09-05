# Tareas Programadas (Cron Jobs) y Scripts de Consola (CLI)

Este documento detalla los procesos asíncronos y tareas en segundo plano que se ejecutan de forma automatizada mediante el demonio de cron del sistema.

---

## ⚙️ Configuración del Demonio Cron en Producción

Como se define en el [Dockerfile](file:///c:/Users/sergi/Documents/tervion-app/Dockerfile) del entorno de producción, las tareas del sistema se orquestan bajo el archivo `/etc/cron.d/app-cron` dentro del contenedor Apache.

La definición exacta de las tareas en crontab es:

```text
# Envío diario de recordatorios a las 00:00
0 0 * * * php /var/www/html/scripts/send_reminders.php >> /var/log/cron.log 2>&1

# Generación mensual de nóminas el día 25 a las 00:00
0 0 25 * * php /var/www/html/scripts/generate_payroll.php >> /var/log/cron.log 2>&1
```

> [!NOTE]
> Las redirecciones de salida (`>> /var/log/cron.log 2>&1`) permiten monitorizar la ejecución consultando el archivo de log del contenedor.

---

## 📁 Detalle de los Scripts

Los scripts CLI se encuentran en la carpeta [`Scripts/`](file:///c:/Users/sergi/Documents/tervion-app/Scripts) y cargan las clases necesarias utilizando el autoloader de Composer, lo que les permite reutilizar toda la lógica de los controladores y modelos del ERP.

### 1. [`send_reminders.php`](file:///c:/Users/sergi/Documents/tervion-app/Scripts/send_reminders.php)
Este script se ejecuta diariamente. Su objetivo es notificar a los pacientes sobre sus próximas citas médicas para reducir la tasa de inasistencia.
- **Funcionamiento:** Instancia [`AppointmentController`](file:///c:/Users/sergi/Documents/tervion-app/Controllers/AppointmentController.php) y ejecuta el método `enviarRecordatorios()`.
- **Lógica de Negocio:** Busca citas agendadas para el día siguiente, construye una plantilla de correo electrónico utilizando PHPMailer y la encola o transmite al servidor SMTP.

### 2. [`generate_payroll.php`](file:///c:/Users/sergi/Documents/tervion-app/Scripts/generate_payroll.php)
Este script se ejecuta el día 25 de cada mes.
- **Funcionamiento:** Instancia [`PayrollController`](file:///c:/Users/sergi/Documents/tervion-app/Controllers/PayrollController.php) y llama a `processAllMonthlyPayrolls(date('m'), date('Y'))`.
- **Lógica de Negocio:** Lee los contratos activos (`Contract` model), calcula el salario base e incentivos mensuales basándose en el registro del trabajador, y genera los registros de nómina correspondientes en la base de datos para su posterior firma o exportación a PDF.

---

## 💻 Ejecución Manual en Entornos de Desarrollo

Si necesitas probar los scripts en tu entorno local sin esperar a que el cron del sistema se active:

1. **Ejecutar Recordatorio de Citas:**
   ```bash
   docker compose exec php php /var/www/html/Scripts/send_reminders.php
   ```

2. **Ejecutar Generador de Nóminas:**
   ```bash
   docker compose exec php php /var/www/html/Scripts/generate_payroll.php
   ```
