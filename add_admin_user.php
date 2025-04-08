<?php
// add_admin_user.php
require 'db.php';

$username = 'admin';
$password = password_hash('admin', PASSWORD_DEFAULT); // Change 'admin' to a secure password

// Check if the admin user already exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ?");
$stmt->execute([$username]);
$userExists = $stmt->fetchColumn();

if ($userExists) {
    echo "Admin user already exists.";
} else {
    // Insert new admin user
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    echo "Admin user added.";
}
?>
