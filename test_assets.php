<?php
header('Content-Type: text/plain');
echo "Testing asset paths:\n";
echo "SITE_URL: " . SITE_URL . "\n";
echo "ASSETS_URL: " . ASSETS_URL . "\n";
echo "\nFiles in assets/css:\n";
print_r(glob(__DIR__ . '/assets/css/*'));
echo "\nFiles in assets/js:\n";
print_r(glob(__DIR__ . '/assets/js/*'));
?>