FROM php:7.4
RUN apt-get update && apt-get install -y \
    wget \
    curl \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql
COPY php.ini "$PHP_INI_DIR/php.ini"
WORKDIR /var/www/boto
