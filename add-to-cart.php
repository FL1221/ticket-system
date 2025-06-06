<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = intval($_POST['event_id']);
    $quantity = max(1, intval($_POST['quantity']));

    // Dodaj do koszyka w sesji
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Jeśli już dodane – zwiększ ilość
    if (isset($_SESSION['cart'][$event_id])) {
        $_SESSION['cart'][$event_id] += $quantity;
    } else {
        $_SESSION['cart'][$event_id] = $quantity;
    }

    header("Location: cart.php");
    exit;
} else {
    echo "Nieprawidłowe żądanie.";
}
