FROM php:7.3
RUN apt-get update && apt-get install -y \
    wget \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && bash -c "wget http://getcomposer.org/composer.phar && mv composer.phar /usr/local/bin/composer" \
    && bash -c "chmod +x /usr/local/bin/composer"
WORKDIR /var/www/boto
CMD ["php","bin/console","server:run", "0.0.0.0:8585"]