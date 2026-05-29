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

# 1. Disable ALL MPMs first (ignore errors if module is not loaded)
RUN a2dismod mpm_event 2>/dev/null; \
    a2dismod mpm_worker 2>/dev/null; \
    a2dismod mpm_prefork 2>/dev/null; \
    true

# 2. Enable ONLY mpm_prefork (required by mod_php)
RUN a2enmod mpm_prefork

# 3. Enable rewrite module
RUN a2enmod rewrite

# 4. Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 5. Copy application code from builder
COPY --from=builder /app /var/www/html

# 6. Copy Apache vhost configuration
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# 7. Set permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
