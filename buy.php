<?php
session_start();
require_once 'includes/db.php';

$payment_method = $_POST['payment_method'] ?? 'nieokreślona';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Koszyk jest pusty. <a href='events.php'>Wróć do wydarzeń</a>";
    exit;
}

$conn->begin_transaction();

try {
    $purchased_ticket_ids = []; // tablica na zakupione bilety

    foreach ($cart as $event_id => $quantity) {
        // Pobierz dostępne bilety dla wydarzenia (user_id IS NULL)
        $stmt = $conn->prepare("SELECT id FROM tickets WHERE event_id = ? AND user_id IS NULL LIMIT ?");
        $stmt->bind_param("ii", $event_id, $quantity);
        $stmt->execute();
        $result = $stmt->get_result();

        $ticket_ids = [];
        while ($row = $result->fetch_assoc()) {
            $ticket_ids[] = $row['id'];
        }

        if (count($ticket_ids) < $quantity) {
            throw new Exception("Nie ma wystarczającej liczby dostępnych biletów dla wydarzenia ID $event_id.");
        }

        // Aktualizuj bilety, przypisując je użytkownikowi i ustawiając datę zakupu
        $purchase_date = date("Y-m-d H:i:s");

        // Aktualizacja pojedynczych ticketów
        $ids_placeholders = implode(',', array_fill(0, count($ticket_ids), '?'));
        $types = str_repeat('i', count($ticket_ids));
        $sql = "UPDATE tickets SET user_id = ?, purchase_date = ? WHERE id IN ($ids_placeholders)";
        $stmt_update = $conn->prepare($sql);

        // Przygotuj parametry do bind_param
        $params = array_merge([$user_id, $purchase_date], $ticket_ids);
        $bind_names = [];
        $bind_names[] = 'is' . $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }

        call_user_func_array([$stmt_update, 'bind_param'], $bind_names);
        $stmt_update->execute();

        if ($stmt_update->affected_rows != $quantity) {
            throw new Exception("Wystąpił błąd podczas przypisywania biletów dla wydarzenia ID $event_id.");
        }

        // Dodaj zakupione bilety do głównej tablicy
        $purchased_ticket_ids = array_merge($purchased_ticket_ids, $ticket_ids);
    }

    $conn->commit();

    // Czyścimy koszyk
    unset($_SESSION['cart']);

    echo "Zakup zakończony sukcesem!<br><br>";

    if (!empty($purchased_ticket_ids)) {
        // Link do pierwszego biletu (możesz rozszerzyć do pobierania wielu)
        $first_ticket_id = $purchased_ticket_ids[0];
        echo "<a href='download-ticket.php?ticket_id=$first_ticket_id' target='_blank'><button>Pobierz bilet</button></a><br><br>";
    }

    echo "<a href='events.php'>Wróć do wydarzeń</a>";

} catch (Exception $e) {
    $conn->rollback();
    echo "Błąd podczas zakupu: " . $e->getMessage() . " <a href='cart.php'>Wróć do koszyka</a>";
}
