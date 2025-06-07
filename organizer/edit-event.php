<?php
session_start();
require_once '../includes/db.php';

// Tylko organizator ma dostęp
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: ../login.html");
    exit;
}

$organizer_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "Brak ID wydarzenia.";
    exit;
}

$event_id = intval($_GET['id']);

// Pobierz dane wydarzenia
$sql = "SELECT * FROM events WHERE id = $event_id AND organizer_id = $organizer_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "Nie znaleziono wydarzenia lub brak dostępu.";
    exit;
}

$event = $result->fetch_assoc();

// Jeśli formularz został wysłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['event_date']);
    $location = $conn->real_escape_string($_POST['location']);
    $description = $conn->real_escape_string($_POST['description']);
    $ticket_price = floatval($_POST['ticket_price']);
    $tickets_available = intval($_POST['tickets_available']);

    $update = "UPDATE events 
               SET title='$title', event_date='$date', location='$location',
                   description='$description', ticket_price=$ticket_price,
                   tickets_available=$tickets_available 
               WHERE id=$event_id AND organizer_id=$organizer_id";

    if ($conn->query($update) === TRUE) {
        echo "Wydarzenie zaktualizowane. <a href='dashboard.php'>Wróć do panelu</a>";
    } else {
        echo "Błąd podczas aktualizacji: " . $conn->error;
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edytuj wydarzenie</title>
</head>
<body>
    <h1>Edytuj wydarzenie</h1>
    <form method="POST">
        <label>Nazwa wydarzenia:
            <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
        </label><br><br>

        <label>Data wydarzenia:
            <input type="date" name="event_date" value="<?= htmlspecialchars($event['event_date']) ?>" required>
        </label><br><br>

        <label>Lokacja:
            <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
        </label><br><br>

        <label>Opis:
            <textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea>
        </label><br><br>

        <label>Cena biletu:
            <input type="number" step="0.01" name="ticket_price" value="<?= htmlspecialchars($event['ticket_price']) ?>" required>
        </label><br><br>

        <label>Liczba dostępnych biletów:
            <input type="number" name="tickets_available" value="<?= htmlspecialchars($event['tickets_available']) ?>" required>
        </label><br><br>

        <button type="submit">Zapisz zmiany</button>
    </form>
</body>
</html>
