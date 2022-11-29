FROM php:8.1-fpm as base

FROM base as wordpress

RUN docker-php-ext-install mysqli

USER www-data

WORKDIR /var/www/html/wordpress

FROM wordpress as wordpress-cli

USER root

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli-nightly.phar
RUN chmod +x wp-cli-nightly.phar
RUN mv wp-cli-nightly.phar /usr/local/bin/wp-cli

USER www-data

ENTRYPOINT [ "/usr/local/bin/wp-cli" ]
