FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    git \
    curl \
    libpq \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    postgresql-dev \
    mysql-client \
  && docker-php-ext-install pdo pdo_mysql zip exif pcntl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install gd \
  && docker-php-ext-install intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN rm -rf /var/cache/apk/*

RUN mkdir -p /var/www/html/storage \
    && mkdir -p /var/www/html/bootstrap/cache

# As permissões ainda são importantes para o Laravel escrever logs/cache
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Exponha a porta 8000 para o servidor Artisan
EXPOSE 8000

# Altere o comando padrão para iniciar o servidor Artisan
# --host=0.0.0.0 é crucial para que o servidor seja acessível de fora do contêiner
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]