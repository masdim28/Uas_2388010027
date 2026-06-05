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
    echo 'APP_NAME="Ade Afwa Boutique"' >> .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_URL=http://127.0.0.1:8000" >> .env && \
    echo "DB_CONNECTION=mysql" >> .env && \
    echo "DB_HOST=mariadb" >> .env && \
    echo "DB_PORT=3306" >> .env && \
    echo "DB_DATABASE=adeafwa_boutique" >> .env && \
    echo "DB_USERNAME=uas_2388010027" >> .env && \
    echo "DB_PASSWORD=Masdimas2811" >> .env && \
    echo "SESSION_DRIVER=database" >> .env && \
    echo "QUEUE_CONNECTION=sync" >> .env && \
    echo "MAIL_MAILER=smtp" >> .env && \
    echo "MAIL_HOST=smtp.gmail.com" >> .env && \
    echo "MAIL_PORT=587" >> .env && \
    echo "MAIL_USERNAME=dimasadriansah28@gmail.com" >> .env && \
    echo "MAIL_PASSWORD=moxlndojxgjrkczc" >> .env && \
    echo "MAIL_ENCRYPTION=tls" >> .env && \
    echo "MAIL_FROM_ADDRESS=dimasadriansah28@gmail.com" >> .env && \
    echo 'MAIL_FROM_NAME="${APP_NAME}"' >> .env && \
    echo "MAIL_TIMEOUT=5" >> .env && \
    echo "KOMERCE_BASE_URL=https://rajaongkir.komerce.id/api/v1" >> .env && \
    echo "KOMERCE_API_KEY=EVPekbK6da78aeb81bb67c55Y5EfHSd6" >> .env && \
    echo "KOMERCE_ORIGIN=17117" >> .env && \
    php artisan key:generate

RUN npm install
RUN npm run build


RUN chmod -R 777 storage bootstrap/cache

RUN php artisan storage:link || true

EXPOSE 8000

CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]