#!/usr/bin/env bash
set -euo pipefail

echo "=== Starting Optix WordPress Environment ==="

docker compose up -d

echo "=== Waiting for WordPress to become ready ==="
for i in $(seq 1 60); do
  if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/wp-admin/install.php 2>/dev/null | grep -q "200"; then
    echo "WordPress is ready!"
    break
  fi
  if [ "$i" -eq 60 ]; then
    echo "Timed out waiting for WordPress."
    exit 1
  fi
  sleep 2
done

sleep 5

echo "=== Running WP-CLI setup ==="
docker exec optix_wordpress wp core install \
  --url="http://localhost:8080" \
  --title="Optix Framework" \
  --admin_user="admin" \
  --admin_password="admin" \
  --admin_email="admin@example.com" \
  --skip-email \
  --allow-root

echo "=== Activating plugin ==="
docker exec optix_wordpress wp plugin activate optix-core --allow-root

echo "=== Switching theme ==="
docker exec optix_wordpress wp theme activate optix-main --allow-root

echo "=== Setting permalinks ==="
docker exec optix_wordpress wp rewrite structure '/%postname%/' --allow-root
docker exec optix_wordpress wp rewrite flush --allow-root

echo ""
echo "=== Optix Environment Ready ==="
echo "  WordPress: http://localhost:8080"
echo "  Admin:     http://localhost:8080/wp-admin (admin / admin)"
echo "  phpMyAdmin: http://localhost:8081"
echo ""
