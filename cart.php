<?php
session_start();
$role = $_SESSION['role'] ?? null;  // null jeśli nie zalogowany
require_once 'includes/db.php';

// Sprawdzenie logowania
if (!isset($_SESSION['user_id'])) {
    // Użytkownik niezalogowany — przekieruj do logowania
    header("Location: login.html");  // lub login.php, zależnie co masz
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$eventDetails = [];

if (!empty($cart)) {
    $ids = implode(',', array_map('intval', array_keys($cart)));

    $sql = "SELECT * FROM events WHERE id IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $eventDetails[$row['id']] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Koszyk – EventPass</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #eef2f7;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #0056b3;
      color: white;
      padding: 20px;
      text-align: center;
    }

    nav a {
      color: white;
      margin: 0 10px;
      text-decoration: none;
    }

    main {
      max-width: 800px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f0f0f0;
      text-align: left;
    }

    .btn {
      background-color: #0056b3;
      color: white;
      padding: 10px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
    }

    .btn:hover {
      background-color: #003d80;
    }

    footer {
      text-align: center;
      padding: 20px;
      margin-top: 40px;
      background: #f5f5f5;
    }
  </style>
</head>
<body>

<header>
  <h1>Twój koszyk</h1>
  <?php include 'navbar.php'; ?>

</header>

<main>
  <?php if (!empty($cart) && !empty($eventDetails)): ?>
    <form action="payment-method.php" method="post">
      <table>
        <thead>
          <tr>
            <th>Wydarzenie</th>
            <th>Data</th>
            <th>Cena za bilet</th>
            <th>Ilość</th>
            <th>Łącznie</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $total = 0;
          foreach ($cart as $event_id => $quantity):
            $event = $eventDetails[$event_id];
            $subtotal = $event['ticket_price'] * $quantity;
            $total += $subtotal;
        ?>
          <tr>
            <td><?= htmlspecialchars($event['title']) ?></td>
            <td><?= date("d.m.Y", strtotime($event['event_date'])) ?></td>
            <td><?= number_format($event['ticket_price'], 2) ?> zł</td>
            <td><?= $quantity ?></td>
            <td><?= number_format($subtotal, 2) ?> zł</td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>

      <p><strong>Razem do zapłaty: <?= number_format($total, 2) ?> zł</strong></p>

      <button type="submit" class="btn">Kup teraz</button>
    </form>
  <?php else: ?>
    <p>Twój koszyk jest pusty.</p>
  <?php endif; ?>
</main>

<footer>
  <p>&copy; 2025 EventPass</p>
</footer>

</body>
</html>
