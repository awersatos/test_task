ARG PHP_VERSION=7.4.2

FROM php:${PHP_VERSION}-fpm-alpine

ENV TERM xterm

RUN apk update && \
    apk add gcc g++ shadow make git zlib ssmtp xvfb autoconf composer imagemagick imagemagick-libs imagemagick-dev && \
    apk add libmemcached-libs libmemcached-dev libmcrypt ttf-freefont fontconfig freetype freetype-dev libpng libzip libzip-dev && \
    apk add krb5-dev imap-dev icu-dev postgresql-dev mysql-dev libjpeg-turbo libjpeg-turbo-dev libpng-dev cyrus-sasl-dev libmcrypt-dev

RUN docker-php-ext-install bcmath pdo_pgsql pdo_mysql opcache exif intl mysqli zip
RUN pecl install imagick mcrypt memcached memcache xhprof xdebug

RUN docker-php-ext-configure gd --with-freetype=/usr --with-jpeg=/usr \
    && docker-php-ext-install -j "$(nproc)" gd

RUN PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl && \
    docker-php-ext-install -j$(nproc) imap

# Redis
#RUN pecl install -o -f redis \
#&&  rm -rf /tmp/pear \
#&&  docker-php-ext-enable redis

# AMQP
#RUN apk add rabbitmq-c rabbitmq-c-dev
#RUN pecl install amqp \
#&&  rm -rf /tmp/pear \
#&& docker-php-ext-enable amqp

# APCu
RUN pecl install apcu \
&&  rm -rf /tmp/pear \
&& docker-php-ext-enable apcu

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Don't move into the registry, leave in Dockerfile:
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk add --no-cache bash bash-doc bash-completion

ARG UID=1000
ARG GUID=1000
RUN usermod -u $UID www-data && groupmod -g $GUID www-data

RUN echo $'export HISTTIMEFORMAT="%d/%m/%y %T "\n\
export PS1=\'\u@docker:\w$ \'\n\
alias l=\'ls -CF\'\n\
alias la=\'ls -A\'\n\
alias ll=\'ls -lahF --color=auto\'\n\
alias ls=\'ls --color=auto\'\n' | tee /etc/profile.d/custom.sh
RUN source /etc/profile.d/custom.sh

RUN echo $'source /etc/profile.d/custom.sh\n\
source /etc/profile.d/bash_completion.sh\n' | tee /home/www-data/.bashrc
RUN source /home/www-data/.bashrc #need fix root

#RUN composer global require bamarni/symfony-console-autocomplete
# ---

# Override with custom opcache settings
COPY ssmtp.conf /etc/ssmtp/ssmtp.conf
COPY conf.d/* $PHP_INI_DIR/conf.d/

ARG TZ=Europe/Moscow
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /var/www/backend
EXPOSE 9000
