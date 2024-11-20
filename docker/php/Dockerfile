# 使用 PHP-FPM 基礎映像
FROM php:8.2-fpm

# 安裝必要擴展
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# 安裝 Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 設定工作目錄
WORKDIR /var/www/html

CMD ["php-fpm"]
