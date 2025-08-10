FROM php:8.2-fpm-alpine

# 安裝系統依賴
RUN apk add --no-cache     nginx     build-base     autoconf     libzip-dev     sqlite-dev     oniguruma-dev     libpng-dev     libjpeg-turbo-dev     libwebp-dev     libxml2-dev     postgresql-dev     openssl     git     curl     mysql-client     zip # 確保 zip 擴展可用

# 安裝 PHP 擴展
RUN docker-php-ext-install     pdo_mysql     zip     exif     pcntl     gd     opcache     bcmath     sockets

# 安裝 Composer (如果不需要 Composer 作為基礎映像的一部分，可以省略)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www/html

# 複製應用程式檔案 (在開發環境中，通常會使用 volume 掛載)
COPY . .

# 設定權限 (重要：確保 Laravel 可以在 storage 和 bootstrap/cache 中寫入)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
