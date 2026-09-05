# Etapa base: Instalación de extensiones comunes
FROM php:8.4-fpm AS base
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /var/www/html

# Etapa de desarrollo: Incluye Xdebug y Node.js/npm
FROM base AS development

# Copiar Node.js y npm desde la imagen oficial de Node
COPY --from=node:22-slim /usr/local/bin /usr/local/bin
COPY --from=node:22-slim /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN apt-get update && apt-get install -y \
    autoconf \
    g++ \
    make \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get purge -y --auto-remove autoconf g++ make \
    && rm -rf /var/lib/apt/lists/*

# Configuración de Xdebug para desarrollo
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Instala dependencias PHP al iniciar en desarrollo (útil con volumen bind mount)
CMD ["sh", "-c", "if [ -f /var/www/html/composer.json ]; then composer install --no-interaction --prefer-dist; fi && php-fpm"]

# Etapa de producción: Copia código y limpia
FROM base AS production

# Instalar cron
RUN apt-get update && apt-get install -y cron && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
RUN chown -R www-data:www-data /var/www/html/

# Configurar tareas programadas (cron jobs)
# send_reminders.php se ejecuta una vez al día (a las 00:00)
# generate_payroll.php se ejecuta el día 25 de cada mes (a las 00:00)
RUN echo "0 0 * * * php /var/www/html/scripts/send_reminders.php >> /var/log/cron.log 2>&1" > /etc/cron.d/app-cron \
    && echo "0 0 25 * * php /var/www/html/scripts/generate_payroll.php >> /var/log/cron.log 2>&1" >> /etc/cron.d/app-cron \
    && chmod 0644 /etc/cron.d/app-cron \
    && crontab /etc/cron.d/app-cron \
    && touch /var/log/cron.log

# Iniciar el demonio cron y PHP-FPM
CMD cron && php-fpm