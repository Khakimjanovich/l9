FROM php:8.0-fpm

ARG user
ARG uid

RUN mkdir /var/www/html/app
# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/html/app/

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html/app

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

#installs xdebug and enables it
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user


# Copy existing application directory contents
COPY . /var/www/html/app

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html/app

# Change current user to www
USER $user

# Expose port 9001 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
