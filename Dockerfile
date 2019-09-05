FROM php:7.3-fpm-alpine

LABEL maintainer="Alley M. Carvalho <alleycarvalho@gmail.com>"

#####################################################################
# RUN Setup:
#####################################################################

RUN apk update && apk upgrade \
    # Install dependencies, softwares, etc.
    && apk add --no-cache \
    bash \
    mysql-client \
    nodejs \
    npm \
    openssl \
    # PHP dependencies
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    # Remove cache
    && rm -rf /tmp/* /var/cache/apk/* \
    # Remove html
    && rm -rf /var/www/html

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dockerize
ENV DOCKERIZE_VERSION v0.6.1

RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz

#####################################################################
# Final:
#####################################################################

# Set default work directory
WORKDIR /var/www

# Create symbolic link
RUN ln -s public html

# Expose port 9000
EXPOSE 9000

ENTRYPOINT ["php-fpm"]
