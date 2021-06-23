FROM alpine:3

LABEL MAINTAINER="FAN VINGA<fanalcest@gmail.com> ZE3kr<ze3kr@icloud.com>"

ENV HOST_KEY=e9e4498f0584b7098692512db0c62b48 \
    HOST_MAIL=ze3kr@example.com               \
    TITLE=TlOxygen

COPY . /app
RUN apk --no-cache --virtual runtimes add curl            \
    nginx           \
    php7            \
    php7-fpm        \
    php7-cli        \
    php7-json       \
    php7-gettext    \
    php7-curl       \
    php7-apcu       \
    php7-phar       \
    php7-iconv      \
    php7-mbstring   \
    php7-openssl && \
    mkdir -p /run/nginx && ln -s /var/run/nginx.pid /run/nginx/nginx.pid && \
    cd app && curl -s https://getcomposer.org/installer | php            && \
    php composer.phar install --no-dev -o

COPY .docker/nginx.conf /etc/nginx/conf.d/cloudflare.conf
COPY .docker/php-fpm.conf /etc/php7/php-fpm.conf

WORKDIR /app
EXPOSE 80

CMD cp /app/config.example.php /app/config.php && nginx                                     && \
    sed -i "s|e9e4498f0584b7098692512db0c62b48|${HOST_KEY}|g" /app/config.php               && \
    sed -i "s|ze3kr@example.com|${HOST_MAIL}|g"               /app/config.php               && \
    sed -i "s|// \$page_title = \"TlOxygen\"|\$page_title = \"${TITLE}\"|g" /app/config.php && \
    sed -i "s|// \$tlo_path = \"/\"|\$tlo_path = \"/\"|g" /app/config.php                   && \
    php-fpm7 --nodaemonize --fpm-config /etc/php7/php-fpm.conf -c /etc/php7/php.ini
