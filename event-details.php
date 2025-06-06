<?php
session_start();
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    echo "Nie podano ID wydarzenia.";
    exit;
}

$event_id = intval($_GET['id']);
$sql = "SELECT * FROM events WHERE id = $event_id";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "Nie znaleziono wydarzenia.";
    exit;
}

$event = $result->fetch_assoc();

// Pobierz liczbę dostępnych biletów z tabeli tickets
$sql_tickets = "SELECT COUNT(*) AS available_tickets FROM tickets WHERE event_id = $event_id AND user_id IS NULL";
$res_tickets = $conn->query($sql_tickets);
$available_tickets = 0;
if ($res_tickets && $row = $res_tickets->fetch_assoc()) {
    $available_tickets = (int)$row['available_tickets'];
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($event['title']) ?> – EventPass</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f0f4f8;
      color: #333;
    }

    header {
      background-color: #0056b3;
      color: white;
      padding: 20px 30px;
      text-align: center;
    }

    nav a {
      color: white;
      text-decoration: none;
      margin: 0 12px;
      font-weight: bold;
    }

    nav a:hover {
      text-decoration: underline;
    }

    main {
      max-width: 800px;
      margin: 30px auto;
      padding: 20px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    h1 {
      margin-top: 0;
    }

    .event-info p {
      margin: 10px 0;
    }

    form {
      margin-top: 20px;
    }

    label {
      display: block;
      margin-bottom: 6px;
    }

    input[type="number"] {
      width: 80px;
      padding: 6px;
      margin-bottom: 12px;
    }

    button {
      background-color: #0056b3;
      color: white;
      padding: 10px 18px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #003d80;
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #f0f0f0;
      margin-top: 40px;
    }
  </style>
</head>
<body>
  <header>
    <h1>EventPass</h1>
    <?php include 'navbar.php'; ?>

  </header>

  <main>
    <h2><?= htmlspecialchars($event['title']) ?></h2>
    <div class="event-info">
      <p><strong>Data:</strong> <?= date("d.m.Y", strtotime($event['event_date'])) ?></p>
      <p><strong>Miejsce:</strong> <?= htmlspecialchars($event['location']) ?></p>
      <p><strong>Opis:</strong> <?= nl2br(htmlspecialchars($event['description'])) ?></p>
      <p><strong>Cena biletu:</strong> <?= number_format($event['ticket_price'], 2) ?> zł</p>
      <p><strong>Dostępnych biletów:</strong> <?= $available_tickets ?></p>

    </div>

    <?php if ($event['tickets_available'] > 0): ?>
    <form method="POST" action="add-to-cart.php">
      <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
      <label for="quantity">Liczba biletów:</label>
      <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= (int)$event['tickets_available'] ?>" required>
      <br>
      <button type="submit">Dodaj do koszyka</button>
    </form>
    <?php else: ?>
      <p style="color: red;"><strong>Brak dostępnych biletów!</strong></p>
    <?php endif; ?>
  </main>

  <footer>
    <p>&copy; 2025 EventPass</p>
  </footer>
</body>
</html>
