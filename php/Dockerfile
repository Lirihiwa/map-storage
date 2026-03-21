FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libfreetype6-dev \
	libjpeg-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip \
	libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html