FROM php:8.4-fpm

WORKDIR /var/www

# Timezone
ENV TZ=Europe/Moscow
RUN apt-get update && apt-get install -yy tzdata
RUN cp /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Установка docker-php-extension-installer
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
            nano \
            procps \
            cron \
            supervisor \
            curl \
            libpq-dev \
            libz-dev \
            libpq-dev \
            libjpeg-dev \
            libpng-dev \
            libfreetype6-dev \
            libssl-dev \
            libwebp-dev \
            libxpm-dev \
            libmcrypt-dev \
            libonig-dev; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    docker-php-ext-install pgsql; \
    docker-php-ext-install pdo_pgsql; \
    docker-php-ext-configure gd \
            --prefix=/usr \
            --with-jpeg \
            --with-webp \
            --with-xpm \
            --with-freetype; \
    docker-php-ext-install gd; \
    install-php-extensions redis; \
    install-php-extensions @composer; \
    install-php-extensions xdebug; \
    install-php-extensions sockets; \
    php -r 'var_dump(gd_info());'

# Установка nodejs
RUN curl -fsSL https://deb.nodesource.com/setup_current.x | bash - \
  && apt-get install -y nodejs

# Добавление кастомных .ini файлов для php
#COPY /.docker/app/conf.d /usr/local/etc/php/conf.d/

#COPY . .

RUN composer install \
      --no-interaction \
      --no-plugins \
      --no-scripts \
      --no-autoloader \
      --prefer-dist

RUN npm install; \
    npm run build; \
    rm -fr node_modules

RUN composer dump-autoload  --no-scripts --optimize && \
    chown -R root:www-data /var/www && \
    chmod -R 755 /var/www && \
    chmod -R 775 /var/www/storage && \
    chmod -R 775 /var/www/bootstrap/cache
