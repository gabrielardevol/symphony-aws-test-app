FROM php:8.2-cli

# Instal·la les extensions necessàries per a PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql

# Instal·la Composer (si no tens Composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
