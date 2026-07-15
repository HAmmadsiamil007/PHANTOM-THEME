FROM wordpress:6.4-apache

ENV PHP_MEMORY_LIMIT=256M \
    PHP_UPLOAD_LIMIT=64M

RUN set -ex; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        less \
        unzip \
        libzip-dev \
        mariadb-client; \
    docker-php-ext-install zip; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

RUN curl -O https://raw.githubusercontent.com/wp-cli/wp-cli/main/phar/wp-cli.phar; \
    chmod +x wp-cli.phar; \
    mv wp-cli.phar /usr/local/bin/wp

COPY optix-core /var/www/html/wp-content/plugins/optix-core
COPY optix-main/optix-main /var/www/html/wp-content/themes/optix-main

RUN chown -R www-data:www-data /var/www/html/wp-content/plugins/optix-core; \
    chown -R www-data:www-data /var/www/html/wp-content/themes/optix-main

RUN cp /usr/local/bin/wp /var/www/html/wp-cli.phar
