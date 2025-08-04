# Utilise l'image officielle PHP avec Apache
FROM php:7.4-apache

# Installe les dépendances système (intl, zip, etc.)
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install intl pdo pdo_mysql zip

# Active le mod_rewrite d’Apache pour Symfony
RUN a2enmod rewrite

# Positionne le dossier de travail
WORKDIR /var/www/html

# Copie le code source dans le conteneur
COPY . .

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installe les dépendances PHP
RUN composer install --no-interaction

# IMPORTANT : Change le DocumentRoot vers le dossier public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Ajoute un bloc Directory pour autoriser l'accès à /public
RUN echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/apache2.conf

# Change les droits (optionnel, dépend des droits locaux Docker)
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor

# --- Installation de SonarScanner ---
RUN apt-get update && \
    apt-get install -y openjdk-11-jre wget && \
    wget -O /tmp/sonar-scanner.zip https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-5.0.1.3006-linux.zip && \
    unzip /tmp/sonar-scanner.zip -d /opt && \
    ln -s /opt/sonar-scanner-5.0.1.3006-linux/bin/sonar-scanner /usr/local/bin/sonar-scanner

# Expose le port Apache (ici 8001)
EXPOSE 8001

# Change le port d'écoute d’Apache à 8001 (au lieu de 80)
RUN sed -i 's/80/8001/g' /etc/apache2/ports.conf /etc/apache2/sites-enabled/000-default.conf

# Lancement d’Apache au démarrage
CMD ["apache2-foreground"]
