# Guía de Contribución a Tervion (Open Source)

¡Gracias por tu interés en contribuir a **Tervion**! Tervion es un sistema ERP clínico libre y 100% de código abierto con una arquitectura modular desacoplada.

Este documento establece las directrices, estándares de código y flujos de trabajo necesarios para que cualquier desarrollador pueda colaborar eficazmente en el proyecto.

---

## 📜 Licencia y Cesión de Código

Al contribuir al repositorio de Tervion, aceptas que:

1. **Licencia LGPLv3:** Todo código enviado mediante Pull Request se publicará bajo los términos de la [GNU Lesser General Public License v3.0 (LGPLv3)](LICENSE).
2. **Autoría y Compatibilidad:** Declaras que eres el autor original del código o posees los derechos/licencias necesarias para aportarlo bajo LGPLv3.
3. **Ecosistema Modular:** Tervion Core es 100% libre. Gracias a la licencia LGPLv3, los usuarios pueden desarrollar módulos propios para conectarse al ERP sin alterar el núcleo.

---

## 🛠️ Configuración del Entorno de Desarrollo

Tervion utiliza Docker y Docker Compose para garantizar un entorno idéntico e inmutable para todos los colaboradores.

```bash
# 1. Clonar el repositorio y configurar ramas
git clone https://github.com/sergiofrubio/tervion-app.git
cd tervion-app
git checkout -b feature/nombre-de-tu-mejora

# 2. Levantar los contenedores
docker compose up -d --build
```

### 3. Servicios disponibles en local
- **Aplicación Web:** [http://localhost](http://localhost) (Admin: admin@example.com / Pass: 12345678)
- **PHPMyAdmin:** [http://localhost:8080](http://localhost:8080)
- **Mailpit (Web UI de correo simulado):** [http://localhost:8025](http://localhost:8025)

Para una explicación detallada, consulta la [Guía de Inicialización (docs/setup.md)](docs/setup.md).

---

## 📐 Estándares de Programación y Calidad

Para mantener una base de código limpia, mantenible y segura, sigue estos principios:

### Backend (PHP)
- **PHP 8.4+ y Modo Estricto:** Todos los archivos PHP deben comenzar con:
  ```php
  <?php

  declare(strict_types=1);
  ```
- **Autoloading y Estructura:** Cumplimiento estricto de **PSR-4** bajo el namespace App\ mapeado al directorio src/.
- **Estilo de Código:** Seguir las convenciones **PSR-12** (nombres de clases en PascalCase, métodos en camelCase, constantes en UPPER_SNAKE_CASE).
- **Seguridad en Base de Datos:** **Prohibido concatenar variables en consultas SQL**. Se debe usar siempre consultas preparadas vía PDO (prepare() y execute()).

### Frontend (CSS / JS)
- Se utiliza **Tailwind CSS** y **Sass**.
- Si realizas cambios en los estilos o scripts del frontend:

```bash
  # Instalar dependencias
  docker compose exec apache npm install

  # Compilar assets
  docker compose exec apache npm run build

  # O ejecutar el watcher durante desarrollo
  docker compose exec apache npm run dev
```
---

## 🧪 Pruebas Automatizadas (Testing)

**Ningún Pull Request será aceptado si rompe las pruebas existentes o añade funcionalidades complejas sin sus tests correspondientes.**

Ejecuta la suite de pruebas unitarias dentro del contenedor Docker:

``docker compose exec php vendor/bin/phpunit``

Las pruebas se organizan en la carpeta 	ests/. Si creas un nuevo servicio, modelo o helper, añade su clase correspondiente en 	ests/. Más detalles en la [Guía de Testing (docs/testing.md)](docs/testing.md).

---

## 🌿 Flujo de Trabajo con Git (Git Workflow)

1. **Crea una rama descriptiva a partir de main:**
   - Para nuevas características: feature/gestion-citas-avanzada
   - Para corrección de errores: fix/calculo-iva-factura
   - Para documentación: docs/actualizar-readme
2. **Mensajes de Commit Claros (Conventional Commits):**
   - feat: anadir exportador de facturas a formato JSON
   - fix: corregir validacion de DNI/NIE en pacientes
   - docs: ampliar guia de integracion con Redsys
   - 	est: anadir pruebas unitarias para VerifactuXML
3. **Pull Requests (PR):**
   - Abre el PR contra la rama main.
   - Describe con claridad el problema resuelto y el comportamiento introducido.
   - Adjunta capturas o logs si hay impacto visual o cambios estructurales.
   - Asegúrate de que las pruebas pasen antes de solicitar revisión.

---

## 🧩 ¿Quieres desarrollar Módulos Propietarios o Privados?

Si tu objetivo es crear módulos comerciales, integraciones empresariales o extensiones privadas sin necesidad de publicar el código:

- Diseña tu módulo para que consuma las interfaces públicas, eventos o hooks de Tervion Core.
- Mantén tu código propietario en un repositorio privado independiente, aprovechando las libertades que otorga la licencia LGPLv3 (enlace y trabajos combinados) sin afectar la naturaleza abierta del núcleo.

---

## 💬 Soporte y Preguntas

Si encuentras un bug o deseas proponer una mejora arquitectónica antes de programarla:
- Abre un **Issue** en GitHub detallando los pasos de reproducción o la propuesta técnica.
- Consulta la carpeta docs/ para detalles de arquitectura, base de datos y cron jobs.
