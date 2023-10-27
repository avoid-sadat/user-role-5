<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    // Display the role management page content
    echo "Welcome, Admin!";
    // Implement role management features here
} else {
    // Redirect unauthorized users to the login page
    header('Location: login.php');
    exit();
}
?>
