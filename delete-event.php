<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}

if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']);

    // Najpierw usuń bilety powiązane z wydarzeniem
    $conn->query("DELETE FROM tickets WHERE event_id = $eventId");

    // Następnie usuń wydarzenie
    $conn->query("DELETE FROM events WHERE id = $eventId");

    header("Location: admin-dashboard.php?deleted=1");
    exit;
}
