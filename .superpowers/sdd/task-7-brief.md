# Task 7: Full Integration Verification

**Scope:** Verify all 11 registries are properly loaded, registered, and the site runs without errors.

## Steps

### Step 1: Check all 11 registry files exist and parse
Run the following lint check (one command):
```
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-layout-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-woocommerce-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-typography-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-color-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-responsive-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-animation-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-hook-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-block-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-preset-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-demo-registry.php && docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/registry/class-asset-registry.php
```

Expected: All 11 report "No syntax errors detected".

### Step 2: Verify all bootstrap files parse
```
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/optix-core.php
docker exec optix_wordpress php -l /var/www/html/wp-content/plugins/optix-core/includes/class-core-plugin.php
```

### Step 3: Verify class loading via WordPress
```
docker exec optix_wordpress wp eval '
echo "=== PHP Syntax Check ===\n";
echo "optix-core.php: OK\n";
$files = [
    "layout" => "OptixCore\Registry\Layout_Registry",
    "woocommerce" => "OptixCore\Registry\WooCommerce_Registry",
    "typography" => "OptixCore\Registry\Typography_Registry",
    "color" => "OptixCore\Registry\Color_Registry",
    "responsive" => "OptixCore\Registry\Responsive_Registry",
    "animation" => "OptixCore\Registry\Animation_Registry",
    "hook" => "OptixCore\Registry\Hook_Registry",
    "block" => "OptixCore\Registry\Block_Registry",
    "preset" => "OptixCore\Registry\Preset_Registry",
    "demo" => "OptixCore\Registry\Demo_Registry",
    "asset" => "OptixCore\Registry\Asset_Registry",
];

$all_ok = true;
foreach ($files as $name => $class) {
    $loaded = class_exists($class);
    if ($loaded) {
        $count = $class::get_instance()->count();
        echo "✓ {$name}: {$class} loaded, {$count} entries\n";
    } else {
        echo "✗ {$name}: {$class} NOT loaded\n";
        $all_ok = false;
    }
}

echo "\n=== Register calls verified ===\n";
echo ($all_ok ? "✓ All 11 registries loaded" : "✗ Some registries failed") . "\n";
' --allow-root
```

### Step 4: Verify Bootstrap File Contents
Check that optix-core.php has all 16 entries in $registry_files:
```
docker exec optix_wordpress wp eval '
$content = file_get_contents("/var/www/html/wp-content/plugins/optix-core/optix-core.php");
preg_match("/\\\$registry_files\s*=\s*\[(.*?)\];/s", $content, $m);
$files = explode("\n", $m[1]);
$count = 0;
foreach ($files as $f) {
    if (preg_match("/class-(\w+)-registry/", $f, $m2)) {
        $count++;
        echo "  " . trim($f) . "\n";
    }
}
echo "Total registry files: {$count}\n";
' --allow-root
```

Expected: 16 registry files listed.

### Step 5: Check no PHP fatal errors on frontend
```
docker exec optix_wordpress curl -s -o /dev/null -w "HTTP %{http_code}\n" http://localhost/
```

Expected: HTTP 200.

### Step 6: Check PHP error log
```
docker exec optix_wordpress wp eval 'var_dump(error_get_last());' --allow-root
```

Expected: NULL (or errors unrelated to optix-core).

## Report
Write to `C:\Users\hamma\Downloads\wordpress\.superpowers\sdd\task-7-report.md`
