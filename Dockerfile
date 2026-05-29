# Dockerfile for Agora Menu project (Railway deployment)

# ---------- Builder stage ----------
FROM node:20-alpine AS builder
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . ./

# Build Tailwind CSS
RUN npx tailwindcss -i ./src/input.css -o ./public/css/tailwind.css --minify

# ---------- Runtime stage ----------
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable rewrite module
RUN a2enmod rewrite

# Copy application code from builder
COPY --from=builder /app /var/www/html

# Copy Apache vhost configuration
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copy and set up the entrypoint script (handles MPM cleanup at runtime)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
