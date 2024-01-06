FROM php:8.2-fpm as laravel-php

ARG port
ARG user
ARG uid
ARG APP_DEBUG


# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libmagickwand-dev \
    locales \
    locales-all \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install xdebug
RUN if [ "$APP_DEBUG" = "true" ] ; then pecl install xdebug ; docker-php-ext-enable xdebug ; fi


# Install PHP extensions
RUN docker-php-ext-install pdo pgsql pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql

ENV LC_ALL ru_RU.UTF-8
ENV LANG ru_RU.UTF-8
ENV LANGUAGE ru_RU.UTF-8

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Create system user to run Composer and Artisan Commands
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
RUN apt-get update && apt-get install -y postgresql-client

# Set working directory
WORKDIR /var/www

COPY . .

RUN composer install --no-dev

USER $user


EXPOSE 8080
ENTRYPOINT [ "php", "-S", "0.0.0.0:8080", "-t", "public" ]