FROM php:8.2-fpm

# ────────────────────────────────────────────────────────
# 1. Dependencias del sistema
# ────────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ────────────────────────────────────────────────────────
# 2. Node.js 20 (para Vite / npm)
# ────────────────────────────────────────────────────────
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ────────────────────────────────────────────────────────
# 3. Extensiones PHP que Laravel necesita
# ────────────────────────────────────────────────────────
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# ────────────────────────────────────────────────────────
# 4. Composer (desde la imagen oficial)
# ────────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ────────────────────────────────────────────────────────
# 5. Usuario no-root (para evitar problemas de permisos)
# ────────────────────────────────────────────────────────
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www

# ────────────────────────────────────────────────────────
# 6. Instalar dependencias ANTES de copiar todo el código
#    (así Docker cachea esta capa y los builds son rápidos)
# ────────────────────────────────────────────────────────
COPY --chown=www:www composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-interaction --prefer-dist

COPY --chown=www:www package.json package-lock.json ./
RUN npm install

# ────────────────────────────────────────────────────────
# 7. Ahora sí, copiar el resto del proyecto
# ────────────────────────────────────────────────────────
COPY --chown=www:www . /var/www

# ────────────────────────────────────────────────────────
# 8. Finalizar instalación de Composer y dar permisos
# ────────────────────────────────────────────────────────
RUN composer dump-autoload --optimize \
    && chown -R www:www /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# ────────────────────────────────────────────────────────
# 9. Copiar el script de arranque
# ────────────────────────────────────────────────────────
COPY --chown=www:www docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
