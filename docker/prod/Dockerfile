FROM node:11 as assets

ADD package.json yarn.lock ./
RUN yarn install

ADD webpack.* server.js .babelrc src/js src/scss ./
RUN yarn build

FROM composer as app

ADD . .
RUN composer install

FROM phusion/baseimage

ARG DEBIAN_FRONTEND=noninteractive

# install common tools
RUN apt-get update && \
    apt-get install --no-install-recommends -y \
        software-properties-common \
        wget \
        zip \
        unzip \
        git \
        make \
        && \
    rm -r /var/lib/apt/lists/*

# install php
RUN LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y --no-install-recommends \
        php7.2 \
        php7.2-fpm \
        php7.2-cli \
        php7.2-intl \
        php7.2-pdo \
        php7.2-zip \
        php7.2-xml \
        php7.2-mbstring \
        php7.2-json \
        php7.2-curl \
        php7.2-pdo \
        php7.2-mysql \
        php7.2-xdebug \
        && \
    rm -r /var/lib/apt/lists/*

# install & setup nginx
RUN apt-get update && \
    apt-get install --no-install-recommends -y \
        nginx \
        && \
    rm -r /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN mkdir /var/www/.composer && chown www-data /var/www/.composer

# install nodejs
RUN curl -sL https://deb.nodesource.com/setup_11.x | bash - \
    && apt-get install --no-install-recommends -y nodejs \
    && rm -r /var/lib/apt/lists/*

# install yarn
RUN curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install yarn \
    && rm -r /var/lib/apt/lists/*

# setup php-fpm
RUN mkdir /etc/service/php-fpm
ADD php-fpm.sh /etc/service/php-fpm/run
RUN chmod +x /etc/service/php-fpm/run

RUN mkdir /run/php
ADD fpm-www.conf /etc/php/7.2/fpm/pool.d/www.conf

# setup nginx
RUN mkdir /etc/service/nginx
ADD nginx.sh /etc/service/nginx/run
RUN chmod +x /etc/service/nginx/run

ADD nginx.conf /etc/nginx/nginx.conf
ADD nginx.vhost /etc/nginx/vhosts.d/app.conf

# container settings
WORKDIR /srv/share

EXPOSE 80

CMD ["/sbin/my_init"]
