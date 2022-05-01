FROM php:8.0-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt-get update
# Install extensions
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql
RUN pecl install redis && docker-php-ext-enable redis
RUN docker-php-ext-configure gd
RUN docker-php-ext-install gd
RUN docker-php-ext-install sockets

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory with permissions
COPY --chown=www:www . /var/www


# Delete defaul nginx config
RUN rm -f /etc/nginx/conf.d/default.conf
COPY --chown=www:www ./nginx/conf.d/app.conf /etc/nginx/conf.d

RUN composer update && composer install

# chown to folders
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN chown -R www:www ./ && chmod -R 755 ./
RUN chown -R www:www /var/log/nginx/ && chmod -R 755 /var/log/nginx/
RUN chown -R www:www /var/lib/nginx/ && chmod -R 755 /var/lib/nginx/
RUN chown -R www:www /run/ && chmod -R 755 /run/



# Expose port 9000 and start php-fpm server
EXPOSE 9000
EXPOSE 8080

# Change current user to www
USER www

#CMD php-fpm ; nginx
RUN chmod +x ./entrypoint.sh

CMD ["./entrypoint.sh"]

