# Utilise l'image officielle PHP avec Apache
FROM php:7.4-apache

# Installe les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    wget \
    default-jre \
    && docker-php-ext-install intl pdo pdo_mysql zip

# ✅ Installation de Xdebug 3.1.6 (compatible PHP 7.4)
RUN pecl install xdebug-3.1.6 \
    && docker-php-ext-enable xdebug \
    && echo "zend_extension=xdebug.so" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Active mod_rewrite d’Apache
RUN a2enmod rewrite

# Définit le dossier de travail
WORKDIR /var/www/html

# Copie le code source dans le conteneur
COPY . .

# Installe Composer depuis l'image officielle composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installe les dépendances PHP
RUN composer install --no-interaction

# Met à jour le DocumentRoot vers le dossier /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Autorise l'accès à /public via Apache
RUN echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# Change les permissions
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor

# --- Installation de SonarScanner ---
RUN wget -O /tmp/sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip && \
    unzip /tmp/sonar-scanner.zip -d /opt && \
    ln -s /opt/sonar-scanner-5.0.1.3006-linux/bin/sonar-scanner /usr/local/bin/sonar-scanner

# Expose le port Apache 8001
EXPOSE 8001

# Configure Apache pour écouter sur le port 8001
RUN sed -i 's/80/8001/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Lance Apache au démarrage
CMD ["apache2-foreground"]
