FROM composer:1.9
RUN apk update
RUN apk add curl \
    wget \
    curl \
    git \
    libpq-dev \
    librabbitmq-dev \
    libssh-dev \
    openssh-server \
    supervisor \
    apache2 \
    && docker-php-ext-install pdo pdo_pgsql pgsql bcmath sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp
RUN mkdir -p /var/lock/apache2 /var/run/apache2 /var/run/sshd /var/log/supervisor
WORKDIR /app
CMD ['composer intall']