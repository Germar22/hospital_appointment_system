<?php
// Replace 'admin_password' with your desired password
$plain_password = 'admin_password';
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

echo "Plain Password: $plain_password<br>";
echo "Hashed Password: $hashed_password";
