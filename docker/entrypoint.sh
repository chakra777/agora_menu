#!/bin/bash
# entrypoint.sh - Configure Apache for Railway and start

# ---- Fix MPM: ensure only mpm_prefork is loaded ----
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# ---- Configure port for Railway ----
# Railway provides the PORT env var; fallback to 80 if not set
PORT="${PORT:-80}"
echo "Configuring Apache to listen on port $PORT"

# Update Apache to listen on the correct port
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf

# Update VirtualHost to use the correct port
sed -i "s/*:80/*:${PORT}/" /etc/apache2/sites-available/000-default.conf

# Verify
echo "=== Port configuration ==="
cat /etc/apache2/ports.conf | grep Listen
echo "=== VirtualHost ==="
head -2 /etc/apache2/sites-available/000-default.conf
echo "==========================="

# Start Apache
exec apache2-foreground
