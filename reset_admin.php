<?php
require_once 'config.php';
$hash = password_hash('admin123', PASSWORD_DEFAULT);
query("UPDATE users SET password = ? WHERE username = 'admin'", [$hash]);
echo "Password updated! Hash: " . $hash;
?>