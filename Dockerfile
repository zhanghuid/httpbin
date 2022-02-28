FROM php:7.4-fpm
WORKDIR /work/apps

RUN apt-get update

RUN apt-get install vim -y && \
    apt-get install openssl -y && \
    apt-get install libssl-dev -y && \
    apt-get install wget -y && \
    apt-get install procps -y

# Install Swoole extension
RUN pecl install swoole
RUN docker-php-ext-enable swoole
RUN echo "swoole.use_shortname = 'Off'" >> /usr/local/etc/php/conf.d/docker-php-ext-swoole.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /work/apps
COPY ./ /work/apps/
RUN cd /work/apps && composer install

# generate open.yaml
RUN php swagger.php --url=127.0.0.1:9501

EXPOSE 9501
ENTRYPOINT ["/usr/local/bin/php", "/work/apps/index.php", "start"]