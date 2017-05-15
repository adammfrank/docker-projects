FROM php:7.0-cli
RUN apt-get update && \
    apt-get install -y curl nano git-all netcat && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
RUN docker-php-ext-install bcmath
RUN composer install

CMD while ! nc -z rabbitmq 5672; do sleep 3; done; php -f worker.php
