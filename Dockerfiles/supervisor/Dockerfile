FROM php:7.4
RUN apt-get update && apt-get install -y \
    wget \
    curl \
    git \
    libpq-dev \
    librabbitmq-dev \
    openssh-server \
    supervisor \
    apache2 \
    && docker-php-ext-install pdo pdo_pgsql pgsql
RUN pecl install amqp \
       && docker-php-ext-enable amqp
RUN mkdir -p /var/lock/apache2 /var/run/apache2 /var/run/sshd /var/log/supervisor
COPY messenger-worker.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini "$PHP_INI_DIR/php.ini"
WORKDIR /app
CMD ["/usr/bin/supervisord"]
