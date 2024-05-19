FROM php:7.3-fpm-alpine

# Install dependencies for GD and install GD with support for jpeg, png webp and freetype
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
install-php-extensions gd xdebug

# Copy custom PHP configuration
COPY php.ini /usr/local/etc/php/