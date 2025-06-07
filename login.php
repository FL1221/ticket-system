<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // jeśli ktoś wszedł na login.php bez POST — przekieruj na login.html
    header("Location: login.html");
    exit;
}

$email = $conn->real_escape_string($_POST['email']);
$password_raw = $_POST['password'];

$sql = "SELECT id, username, password, role FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result && $result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password_raw, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin-dashboard.php");
        } elseif ($user['role'] === 'organizer') {
            header("Location: organizer/dashboard.php");
        } else {
            header("Location: index_klient.php");
        }
        exit;
    } else {
        // Niepoprawne hasło - przekieruj z błędem
        header("Location: login.html?error=wrong_password");
        exit;
    }
} else {
    // Nie znaleziono użytkownika - przekieruj z błędem
    header("Location: login.html?error=no_user");
    exit;
}
?>
