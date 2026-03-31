FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
  git unzip libzip-dev zip curl \
  && docker-php-ext-install pdo_mysql bcmath \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 8000