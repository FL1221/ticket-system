<?php
require_once 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_raw = $_POST['password'];

    // Bezpieczne przypisanie roli - pozwól na client i organizer, ale nie admin (możesz zmienić wedle potrzeb)
    $role = 'client'; 
    if (isset($_POST['role']) && in_array($_POST['role'], ['client', 'organizer', 'admin'])) {
        $role = $_POST['role'];
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    $sql_check = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql_check);

    if ($result->num_rows > 0) {
        echo "Email jest już zajęty.";
        exit;
    }

    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        header("Location: login.html");
        exit;
    } else {
        echo "Błąd podczas rejestracji: " . $conn->error;
    }
} else {
    echo "Niepoprawne żądanie.";
}
?>
