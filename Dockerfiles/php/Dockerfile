FROM php:7.4
RUN apt-get update && apt-get install -y \
    wget \
    curl \
    git \
    libpq-dev \
    librabbitmq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql
RUN pecl install amqp \
       && docker-php-ext-enable amqp
COPY php.ini "$PHP_INI_DIR/php.ini"
WORKDIR /var/www/boto
