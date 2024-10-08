# Build base Drupal image.
FROM drupal:10-php8.2-apache-bookworm as base

# Install extra packages.
RUN	apt update; \
	apt install -y \
    zip \
    sqlite3 \
    git

# Remove stock Drupal codebase.
RUN rm -rf /opt/drupal && \
    mkdir -p /opt/drupal

RUN git config --global --add safe.directory /opt/drupal

# @todo Removing opcache shouldn't be necessary, but the current base image seems to ignore code changes. Fix this.
RUN rm /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini; \
    rm /usr/local/etc/php/conf.d/opcache-recommended.ini

VOLUME /opt/drupal

FROM base as dev

# Set development ENV variables.
ENV PHP_XDEBUG=${PHP_XDEBUG}
ENV PHP_XDEBUG_CLIENT_HOST=${PHP_XDEBUG_CLIENT_HOST}
ENV PHP_IDE_CONFIG=${PHP_IDE_CONFIG}
ENV PHP_XDEBUG_IDEKEY=${PHP_XDEBUG_IDEKEY}
ENV DRUSH_OPTIONS_URI=${DRUSH_OPTIONS_URI}

RUN pecl install xdebug
RUN echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20220829/xdebug.so' | tee /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.mode=\${PHP_XDEBUG}" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_port=9000" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.start_with_request=yes" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.idekey=\${PHP_XDEBUG_IDEKEY}" | tee -a /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.client_host=\${PHP_XDEBUG_CLIENT_HOST}" | tee -a /usr/local/etc/php/conf.d/xdebug.ini

EXPOSE 9000

