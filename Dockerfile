FROM php:8.5-apache

WORKDIR /var/www/html

COPY deploy/apache.conf /etc/apache2/sites-available/000-default.conf
COPY deploy/php.ini /usr/local/etc/php/conf.d/dashboard360.ini
RUN printf 'Listen 10000\n' > /etc/apache2/ports.conf

# Inclui apenas a aplicação pública; configurações locais e uploads não entram.
COPY index.php health.php ./
COPY pages/ ./pages/
COPY shared/ ./shared/

ENV PORT=10000
EXPOSE 10000
CMD ["sh", "-c", "mkdir -p /tmp/dashboard360-sessions && chown www-data:www-data /tmp/dashboard360-sessions && chmod 700 /tmp/dashboard360-sessions && exec apache2-foreground"]
