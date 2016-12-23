FROM silintl/php-web:latest
MAINTAINER Phillip Shipley <phillip_shipley@sil.org>

ENV REFRESHED_AT 2016-01-20

# Make sure /data is available
RUN mkdir -p /data

# Copy in vhost configuration
COPY build/addressbook.conf /etc/apache2/sites-enabled/

# Copy in syslog config
RUN rm -f /etc/rsyslog.d/*
COPY build/rsyslog.conf /etc/rsyslog.conf

# Copy in updated php.ini
COPY build/php.ini /etc/php5/apache2/
COPY build/php.ini /etc/php5/cli/

# get s3-expand
RUN curl https://raw.githubusercontent.com/silinternational/s3-expand/1.5/s3-expand -o /usr/local/bin/s3-expand \
    && chmod a+x /usr/local/bin/s3-expand

# It is expected that /data is = application/ in project folder
COPY application/ /data/

# Copy SAML cert files into appropriate place
#COPY build/saml.* /data/simplesamlphp/cert/

WORKDIR /data

# Fix folder permissions
RUN chown -R www-data:www-data \
    protected/runtime/ \
    public/assets/

# Install/cleanup composer dependencies
RUN composer install --prefer-dist --no-interaction --no-dev --optimize-autoloader

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/s3-expand"]
CMD ["/data/run.sh"]
