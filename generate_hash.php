<?php
$password = '123456';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Password Hash: " . $hash . "\n";
?>
