<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password_raw = $_POST['password'];

    $sql = "SELECT id, username, password, role FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password_raw, $user['password'])) {
            // Logowanie poprawne - ustaw sesję
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Przekierowanie w zależności od roli
            if ($user['role'] == 'organizer') {
                header("Location: organizer/dashboard.php");
            } else {
                header("Location: index_klient.php");
            }
            exit;
        } else {
            $error = "Niepoprawne hasło.";
        }
    } else {
        $error = "Nie znaleziono użytkownika o podanym emailu.";
    }
}
?>