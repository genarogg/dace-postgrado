FROM php:8.3-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libicu-dev \
    zlib1g-dev \
    libzip-dev

# Instalar Node.js y npm desde NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && node --version \
    && npm --version

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Configurar PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar todos los archivos del proyecto primero
COPY . /var/www

# Instalar dependencias de Composer con opciones para asegurar instalación completa
RUN cd /var/www && composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Verificar instalación de Composer
RUN ls -la /var/www/vendor

# Instalar dependencias de Node y compilar assets con opciones para asegurar instalación completa
RUN cd /var/www && npm install --no-audit --no-fund --verbose
RUN ls -la /var/www/node_modules

# Compilar assets
RUN cd /var/www && npm run build

# Configurar permisos para directorios críticos
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/node_modules /var/www/vendor

# Configurar entorno
RUN if [ -f "/var/www/.env.docker" ]; then cp /var/www/.env.docker /var/www/.env; elif [ -f "/var/www/.env.example" ]; then cp /var/www/.env.example /var/www/.env; fi
RUN php artisan key:generate --force

# Configurar PHP-FPM
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Exponer puerto PHP-FPM
EXPOSE 9000

# Iniciar PHP-FPM
CMD ["php-fpm"]