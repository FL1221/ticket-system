<?php
session_start();
require_once '../includes/db.php';  // popraw ścieżkę do db.php

// Sprawdzenie uprawnień
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'organizer' && $_SESSION['role'] != 'admin')) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['event-name']);
    $event_date = $conn->real_escape_string($_POST['event-date']);
    $location = $conn->real_escape_string($_POST['event-location']);
    $description = $conn->real_escape_string($_POST['event-description']);
    $ticket_price = floatval($_POST['ticket-price']);
    $tickets_available = intval($_POST['ticket-quantity']);
    $organizer_id = $_SESSION['user_id'];

    // Dodaj wydarzenie
    $sql = "INSERT INTO events (title, description, event_date, organizer_id, location, ticket_price, tickets_available)
            VALUES ('$title', '$description', '$event_date', $organizer_id, '$location', $ticket_price, $tickets_available)";

    if ($conn->query($sql) === TRUE) {
        $event_id = $conn->insert_id;

        // Dodaj bilety - user_id i purchase_date NULL bo bilety nie sprzedane
        for ($i = 0; $i < $tickets_available; $i++) {
            $sql_ticket = "INSERT INTO tickets (event_id, user_id, purchase_date) VALUES ($event_id, NULL, NULL)";
            $conn->query($sql_ticket);
        }

        echo "Wydarzenie i bilety zostały utworzone! <a href='dashboard.php'>Powrót do panelu</a>";
    } else {
        echo "Błąd: " . $conn->error;
    }
}
?>
