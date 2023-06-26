FROM php:8.1-fpm as builder

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libpng16-16 \
    libonig-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    libjpeg62-turbo \
    libzip-dev \
    libmcrypt-dev \
    unzip \
    curl \
    git

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo "post_max_size = 200M" > /usr/local/etc/php/conf.d/upload_large_dumps.ini && \
    echo "upload_max_filesize = 200M" >> /usr/local/etc/php/conf.d/upload_large_dumps.ini


FROM php:8.1-fpm

COPY --from=builder /usr/local/bin/composer /usr/local/bin/composer
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# Set working directory
WORKDIR /var/www

# Remove the default Nginx index page
RUN rm -rf /var/www/html

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
