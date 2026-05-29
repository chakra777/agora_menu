# Dockerfile for Agora Menu project (Railway deployment)

# ---------- Builder stage ----------
# Use a lightweight Node image to compile Tailwind CSS assets
FROM node:20-alpine AS builder
WORKDIR /app

# Install Node dependencies (package.json should exist)
COPY package*.json ./
RUN npm ci

# Copy the rest of the source code
COPY . ./

# Build Tailwind CSS (adjust paths if needed)
RUN npx tailwindcss -i ./src/input.css -o ./public/css/tailwind.css --minify

# ---------- Runtime stage ----------
# Use the official PHP Apache image
FROM php:8.2-apache

# Install required PHP extensions and enable Apache rewrite module
RUN docker-php-ext-install mysqli pdo pdo_mysql && a2enmod rewrite

# Set the document root to the project directory
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Copy compiled assets and application code from the builder stage
COPY --from=builder /app /var/www/html

# Copy custom Apache vhost configuration to avoid AH00534
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Ensure proper permissions for the web root
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose the default web port
EXPOSE 80

# Default command to run Apache in foreground
CMD ["apache2-foreground"]
