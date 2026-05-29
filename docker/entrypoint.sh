#!/bin/bash
# entrypoint.sh - Ensure only one MPM is loaded before starting Apache

# Remove ALL MPM symlinks from mods-enabled
rm -f /etc/apache2/mods-enabled/mpm_event.load
rm -f /etc/apache2/mods-enabled/mpm_event.conf
rm -f /etc/apache2/mods-enabled/mpm_worker.load
rm -f /etc/apache2/mods-enabled/mpm_worker.conf

# Ensure mpm_prefork is the ONLY MPM enabled
ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

# Verify (will show in Railway logs)
echo "=== Enabled MPM modules ==="
ls -la /etc/apache2/mods-enabled/mpm_*
echo "==========================="

# Start Apache
exec apache2-foreground
