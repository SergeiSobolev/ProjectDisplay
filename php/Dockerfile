FROM php:8.1.0-fpm-alpine

#Install packages
RUN apk add --no-cache \
    curl \
    git \
    build-base\
    zlib-dev\
    oniguruma-dev\
    autoconf\
    bash\
    libpng\
    libpng-dev \
    openssh-server \
    apache2 \
    --upgrade supervisor \
    rabbitmq-c-dev \
          && pecl install amqp \
          && docker-php-ext-enable amqp

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer


# Simfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql gd

# GIT
RUN git config --global user.email "gmsobolevs@gmail.com" \
     && git config --global user.name "Sergey"

#Supervisor
RUN mkdir -p /var/lock/apache2 /var/run/apache2 /var/run/sshd /var/log/supervisor


#Configure non-root user
ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data


COPY entrypoint/fpm.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]