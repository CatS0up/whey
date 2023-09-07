FROM php:8.2-fpm

# Copy composer.lock and composer.json
COPY composer.json composer.lock /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Install dependecies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpq-dev \
    libonig-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN pecl install redis
RUN docker-php-ext-enable redis
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for Laravel app
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory content
COPY . /var/www/html/

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html/

# Change current user to www
USER www

# Expose port 9000
EXPOSE 9000

# Start php-fpm server
CMD ["php-fpm"]
