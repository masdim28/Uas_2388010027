FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    default-mysql-client

# Install NodeJS 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

RUN docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN cp .env.example .env && \
    echo "DB_CONNECTION=mysql" >> .env && \
    echo "DB_HOST=mariadb" >> .env && \
    echo "DB_PORT=3306" >> .env && \
    echo "DB_DATABASE=adeafwa_boutique" >> .env && \
    echo "DB_USERNAME=uas_2388010027" >> .env && \
    echo "DB_PASSWORD=Masdimas2811" >> .env && \
    php artisan key:generate

RUN npm install
RUN npm run build


RUN chmod -R 777 storage bootstrap/cache

RUN php artisan storage:link || true

EXPOSE 8000

CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]