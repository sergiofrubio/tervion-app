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
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/
# En producción no instalamos Xdebug