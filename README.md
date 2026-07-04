# Velion — Sistema de Gestión de Clínicas

---

## 📑 Índice
- [Introducción](#introducción)
- [Requisitos](#requisitos)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Instalación y Configuración](#instalación-y-configuración)
- [Arquitectura y Tecnologías](#arquitectura-y-tecnologías)
- [Autenticación](#autenticación)
- [Testing y Calidad de Código](#testing-y-calidad-de-código)

---

## 🚀 Introducción
Este proyecto nace con el objetivo de digitalizar la operativa diaria de una clínica médica. No se trata solo de una agenda de citas, sino de una plataforma robusta que permite la gestión centralizada de:
- **Pacientes y Sanitarios**: Perfiles detallados con historial vinculado.
- **Citas Médicas**: Sistema de programación inteligente con estados.
- **Reportes médicos**: Registro seguro de la evolución del paciente.
- **Facturación**: Gestión de facturas con normativa Verifactu.
- **Recordatorios**: Y confirmación de citas por correo electrónico.
- **Informes**: Generación automatizada de documentos PDF.

---

## 📋 Requisitos
Para garantizar el correcto funcionamiento del entorno de desarrollo y producción, se requieren las siguientes herramientas:
- **Docker & Docker Compose**: Imprescindible para la orquestación de contenedores.
- **PHP 8.1+**: (Opcional si se usa Docker) Para ejecución local.
- **Composer**: Para la gestión de dependencias de backend.

---

## 📂 Estructura del Proyecto
El proyecto sigue una estructura organizada que separa las responsabilidades de forma clara:

```text
├── Controllers/    # Lógica de negocio y manejo de peticiones.
├── Models/         # Interacción con la base de datos y entidades.
├── Views/          # Interfaces de usuario finales.
├── Templates/      # Componentes reutilizables de UI (Layouts, Modales).
├── Core/           # Motor del framework (Base de Datos, Configuración).
├── public/         # Punto de entrada (index.php) y recursos estáticos.
├── Router/         # Definición de rutas y lógica del Router.
├── Scripts/        # Scripts para automatizar tareas con cron jobs.
├── vendor/         # Dependencias de terceros (Composer).
└── compose.yml     # Orquestación de infraestructura.
```

---

## 🛠️ Instalación y Configuración

El proyecto está totalmente contenedorizado para facilitar su despliegue inmediato.

### 1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/velion-php.git
cd velion-php
```

### 2. Levantar la infraestructura
Usa Docker Compose para levantar el servidor Apache y la base de datos MySQL:
```bash
docker compose up -d
```
*Nota: La base de datos se inicializa automáticamente importando el esquema ubicado en `Core/velion.sql`.*

### 3. Acceso al proyecto
Una vez que los contenedores estén en marcha, abre tu navegador en:
[http://localhost](http://localhost)

---

## 🏗️ Arquitectura y Tecnologías

Como desarrollador, he priorizado el uso de estándares modernos para asegurar la robustez del sistema:
- **PHP Moderno**: Uso de namespaces y autoloader compatible con **PSR-4**.
- **Frontend Premium**: Integración de **Tailwind CSS** para un diseño responsivo y profesional.
- **Persistencia**: **MySQL 8.0** con abstracción mediante PDO para prevenir inyecciones SQL.
- **Generación de Documentos**: Integración con **FPDF** para reportes médicos dinámicos.
- **Notificaciones**: **PHPMailer** para el envío de confirmaciones de citas por correo electrónico.
- **Entorno de Desarrollo**: Configuración de **Xdebug** preinstalada en el contenedor para facilitar la depuración.

---

## 🔐 Autenticación

El sistema cuenta con un control de acceso basado en roles. Para las pruebas iniciales, puede utilizar las siguientes credenciales de administrador:

- **Usuario**: `admin@example.com`
- **Contraseña**: `12345678`

---

## 🧪 Testing y Calidad de Código

Aunque el proyecto se encuentra en una fase activa de desarrollo, la arquitectura ha sido diseñada pensando en la **Testabilidad**.

### Ejecución de Pruebas Unitarias
El proyecto ya incluye **PHPUnit** como dependencia de desarrollo. Para ejecutar las pruebas (una vez implementadas en la carpeta `/tests`):

```bash
docker exec -it velion-php-apache-1 ./vendor/bin/phpunit
```

---