# Etapa base: Instalación de extensiones comunes
FROM php:apache AS base
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
WORKDIR /var/www/html

# Etapa de desarrollo: Incluye Xdebug
FROM base AS development
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

# Etapa de producción: Copia código y limpia
FROM base AS production

# Instalar cron
RUN apt-get update && apt-get install -y cron && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/

# Configurar tareas programadas (cron jobs)
# send_reminders.php se ejecuta una vez al día (a las 00:00)
# generate_payroll.php se ejecuta el día 25 de cada mes (a las 00:00)
RUN echo "0 0 * * * php /var/www/html/scripts/send_reminders.php >> /var/log/cron.log 2>&1" > /etc/cron.d/app-cron \
    && echo "0 0 25 * * php /var/www/html/scripts/generate_payroll.php >> /var/log/cron.log 2>&1" >> /etc/cron.d/app-cron \
    && chmod 0644 /etc/cron.d/app-cron \
    && crontab /etc/cron.d/app-cron \
    && touch /var/log/cron.log

# Iniciar el demonio cron y Apache
CMD cron && apache2-foreground